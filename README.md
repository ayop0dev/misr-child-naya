# Misr Child (Naya): store layer (child theme of Egstore) for نايا للجمال النقي

This theme holds only what is specific to Naya (naya.dokanelbanat.com).
Everything else comes from the Egstore theme and the egstore-commerce plugin,
which update without touching this folder. Built for Egstore theme 1.3.4 and
egstore-commerce 1.3.3.

It shows as "Misr Child" in the dashboard; its folder and update slug are
`misr-child-naya`, separate from عطور.ستور's `misr-child`, so each store gets
only its own updates.

| File | Purpose |
|---|---|
| `config/pages/home.php` | Homepage: which theme sections, in which order, with this store's copy (from the design in `Egstore-theme/demo-home`). Replaces the theme's `config/pages/home.php`. |
| `assets/store.css` | Brand touches the Customizer cannot set (discount badges in the secondary orchid). |
| `inc/import-settings.php` | First activation only: builds this theme's Customizer settings from the parent theme's settings on that site plus Naya's defaults (palette, header, announcement texts). |
| `functions.php` | Loads `assets/store.css` and the first-activation import. Nothing else belongs here. |

## Colours (Customizer, set on first activation)

| Setting | Value |
|---|---|
| Primary (buttons, highlights) | `#662979` |
| Primary hover | `#9f3ebd` (also the discount badges, in `store.css`) |
| Text / headings | `#102136` |
| Muted text | `#4d5d6d` |
| Borders | `#e7ebee` |
| Surfaces | `#f3f5f7` |
| Page background | `#fbfbfc` |
| Text on dark backgrounds | `#ffffff` |

## To confirm before production

The design promises things only the store can confirm. Until then:

- The announcement bar is **off**; its texts are the design's (free shipping
  over 600 ج.م, 20% off with `NAYA20`, 30-day returns, 24-48h delivery).
- The features band (cold shipping times, 30-day guarantee, WhatsApp
  consultation, payment methods) and the deals/bundles headings ("حتى 40%",
  "وفري حتى 35%") are the design's copy.

## Homepage: design sections and their state

| Design section | State |
|---|---|
| Hero (split, highlight, two actions, figures, badges), features (inline), categories (circle, counts) | Theme sections (Egstore 1.3.4 options) |
| Deals (on sale, violet band), new arrivals, body care, best sellers | `product-collection`, carousel |
| Skin care, bundles (kits) | `product-collection`, grid |
| Centred headings with an eyebrow | `heading_align: center`, `eyebrow` |
| Hair care | Its category is empty for now |
| Under 200 ج.م | Needs a price filter in the plugin's collections |
| Flash deals countdown and stock bar, routine finder, shop by concern tabs, bestseller tabs, bundle builder, clinical proof, ingredients, reviews, UGC, blog, FAQ, newsletter | New theme capabilities, one at a time |

## Updates and releases

Updates come from WordPress → Updates through the Egstore update gateway
(needs egstore-commerce 1.3.1 or newer and Egstore theme 1.3.4 or newer).
`style.css` declares `Update URI` and `Egstore Update Key`, the Ed25519
public key in `release-config.json`.

To release a version:

1. Set the same version in `VERSION` and `style.css`, add it to
   `CHANGELOG.md`, commit, and tag `v{version}`.
2. Build signed: `php scripts/release/build.php --output <dir>
   --signing-key-file <key>` (the private key is kept outside the repo).
3. Publish a GitHub Release for the tag with the zip, manifest, checksums,
   audit and changelog.
4. On the gateway: `php import-release.php misr-child-naya v{version}`, then
   `php promote-release.php misr-child-naya {version}`.
