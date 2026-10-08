"""
Design parity harness: the Naya demo against the child theme, section by section.

Renders the demo (Egstore-theme/demo-home/index.html) and the implementation at
the same width, saves a side-by-side PNG per mapped section (demo left,
implementation right) and prints a measured-style diff for the elements the
contract measures (NAYA-HOME-DESIGN-CONTRACT.md §9).

The implementation is either a URL, or "customizer": the child rendered in the
Customizer's live preview of egystore.test (nothing is saved; never publish).
Customizer mode needs admin cookies (contract §8) in a JSON file:
{"auth": [name, value], "logged": [name, value]}.

Usage:
  python tests/design-parity.py --impl customizer --cookies cookies.json --out ../../../artifacts/naya-design/parity
  python tests/design-parity.py --impl http://example.test/ --widths 1366

Afterwards check that egystore.test's active theme did not change:
  php D:/wp-cli/wp-cli.phar option get stylesheet   ->  Egstore-theme
"""

from __future__ import annotations

import argparse
import json
import pathlib
import subprocess
import sys

from PIL import Image
from playwright.sync_api import Frame, Page, sync_playwright

DEMO = pathlib.Path(__file__).resolve().parents[2] / 'Egstore-theme' / 'demo-home' / 'index.html'

# Demo section selector -> implementation section selector. Extend as sections
# are built (contract §6).
SECTIONS: list[tuple[str, str, str]] = [
    ('hero', '#hero', '#hero'),
    ('pillars', '.pillars-section', '#pillars'),
    ('categories', '#categories', '#categories'),
    ('deals', '.deals-section', '#deals'),
    ('new-arrivals', '#new-arrivals', '#new-arrivals'),
    ('skincare', '#skincare-grid', '#skincare'),
    ('bodycare', '#bodycare-grid', '#bodycare'),
    ('bundles', '#bundles', '#bundles'),
    ('bestsellers', '#bestsellers', '#bestsellers'),
]

# Elements measured inside each section: (label, demo selector, implementation selector).
MEASURED: list[tuple[str, str, str]] = [
    ('eyebrow', '.section-tag, .hero-badge-pill', '[class$="__eyebrow"], .eg-hero__eyebrow'),
    ('title', '.section-title, .hero-title', 'h2, h1'),
    ('subtitle', '.section-subtitle, .hero-description', '[class$="__heading"] p, .eg-hero__description'),
    ('button', '.btn', '.eg-hero__action, [class$="__action"], [class$="__toggle"]'),
    ('card', '.category-card, .pillar-card, .product-card', '.eg-category-showcase__card, .eg-features__item, .eg-product-card'),
]

PROPS = ['fontSize', 'fontWeight', 'lineHeight', 'color', 'backgroundColor', 'borderRadius',
         'boxShadow', 'padding', 'paddingTop', 'paddingBottom']

MEASURE_JS = """([section, selector, props]) => {
  const root = document.querySelector(section);
  if (!root) return null;
  const el = selector ? root.querySelector(selector) : root;
  if (!el) return null;
  const s = getComputedStyle(el), b = el.getBoundingClientRect();
  const out = {w: Math.round(b.width), h: Math.round(b.height)};
  props.forEach(p => out[p] = s[p]);
  return out;
}"""


def store_defaults() -> dict:
    """The child's first-activation defaults, read from inc/import-settings.php."""
    child = pathlib.Path(__file__).resolve().parents[1]
    code = ("require 'wp-load.php'; require '" + str(child / 'inc' / 'import-settings.php').replace('\\', '/')
            + "'; echo json_encode(misr_child_naya_store_defaults());")
    try:
        result = subprocess.run(['php', '-r', code], cwd=str(child.parents[2]), capture_output=True, text=True, timeout=60)
        return json.loads(result.stdout.strip().splitlines()[-1])
    except Exception:  # noqa: BLE001 - the harness still runs, with theme defaults
        print('warning: could not read the child defaults; preview shows theme defaults')
        return {}


def open_impl(context, impl: str, width: int) -> Page | Frame:
    page = context.new_page()
    if impl != 'customizer':
        page.goto(impl, wait_until='networkidle')
        return page

    page.goto('http://egystore.test/wp-admin/customize.php?theme=misr-child-naya', wait_until='load', timeout=120000)
    page.wait_for_selector('#customize-preview iframe', timeout=60000)
    page.wait_for_function('window.wp && wp.customize && wp.customize.previewer', timeout=60000)
    # The child's first-activation defaults (Naya's palette...) are not saved
    # for a theme that was never activated; set them in the preview only
    # (unsaved, never published) so it renders as the store will.
    defaults = store_defaults()
    if defaults:
        page.evaluate("""(values) => { Object.entries(values).forEach(([id, value]) => {
            const setting = wp.customize(id); if (setting) { setting.set(value); } }); }""", defaults)
        page.wait_for_timeout(4000)
    # Full-width preview: hide the controls panel (desktop) or switch to the
    # preview (narrow screens).
    if width >= 1024:
        page.click('.collapse-sidebar')
    else:
        page.click('.customize-controls-preview-toggle')
    page.wait_for_timeout(1500)
    frame = page.frame_locator('#customize-preview iframe')
    frame.locator('main').wait_for(timeout=60000)
    return page.frames[-1]


def shot(target, selector: str, path: pathlib.Path) -> bool:
    locator = target.locator(selector).first
    if locator.count() == 0:
        return False
    locator.scroll_into_view_if_needed()
    target.wait_for_timeout(700)
    locator.screenshot(path=str(path))
    return True


def side_by_side(left: pathlib.Path, right: pathlib.Path | None, out: pathlib.Path) -> None:
    a = Image.open(left)
    b = Image.open(right) if right and right.exists() else Image.new('RGB', (a.width, 120), (255, 230, 230))
    canvas = Image.new('RGB', (a.width + b.width + 24, max(a.height, b.height)), (220, 40, 40))
    canvas.paste(a, (0, 0))
    canvas.paste(b, (a.width + 24, 0))
    canvas.save(out)


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument('--impl', required=True, help='URL, or "customizer"')
    parser.add_argument('--cookies', help='admin cookies JSON (customizer mode)')
    parser.add_argument('--widths', default='1366,390')
    parser.add_argument('--out', default='parity')
    args = parser.parse_args()

    out = pathlib.Path(args.out)
    out.mkdir(parents=True, exist_ok=True)
    report: dict = {}

    with sync_playwright() as p:
        browser = p.chromium.launch(channel='chrome')
        for width in (int(w) for w in args.widths.split(',')):
            mobile = width < 700
            demo_ctx = browser.new_context(viewport={'width': width, 'height': 900}, is_mobile=mobile, has_touch=mobile)
            demo = demo_ctx.new_page()
            demo.goto(DEMO.as_uri(), wait_until='networkidle')

            impl_ctx = browser.new_context(viewport={'width': width, 'height': 900}, is_mobile=mobile and args.impl != 'customizer', has_touch=mobile)
            if args.cookies:
                ck = json.loads(pathlib.Path(args.cookies).read_text(encoding='utf-8'))
                impl_ctx.add_cookies([
                    {'name': ck['auth'][0], 'value': ck['auth'][1], 'domain': 'egystore.test', 'path': '/wp-admin'},
                    {'name': ck['logged'][0], 'value': ck['logged'][1], 'domain': 'egystore.test', 'path': '/'},
                ])
            impl = open_impl(impl_ctx, args.impl, width)

            for name, demo_sel, impl_sel in SECTIONS:
                d = out / f'{width}-{name}-demo.png'
                i = out / f'{width}-{name}-impl.png'
                has_demo = shot(demo, demo_sel, d)
                has_impl = shot(impl, impl_sel, i)
                if has_demo:
                    side_by_side(d, i if has_impl else None, out / f'{width}-{name}.png')
                rows = {}
                for label, dsel, isel in MEASURED:
                    rows[label] = {
                        'demo': demo.evaluate(MEASURE_JS, [demo_sel, dsel, PROPS]),
                        'impl': impl.evaluate(MEASURE_JS, [impl_sel, isel, PROPS]) if has_impl else None,
                    }
                rows['section'] = {
                    'demo': demo.evaluate(MEASURE_JS, [demo_sel, '', PROPS]),
                    'impl': impl.evaluate(MEASURE_JS, [impl_sel, '', PROPS]) if has_impl else None,
                }
                report.setdefault(str(width), {})[name] = rows
                print(f'{width} {name}: demo={"ok" if has_demo else "missing"} impl={"ok" if has_impl else "MISSING"}')
            demo_ctx.close()
            impl_ctx.close()
        browser.close()

    (out / 'measured.json').write_text(json.dumps(report, ensure_ascii=False, indent=1), encoding='utf-8')
    print(f'side-by-side images and measured.json in {out}')
    return 0


if __name__ == '__main__':
    sys.exit(main())
