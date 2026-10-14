<?php
/**
 * First activation: carry the store's Customizer settings over to this theme.
 *
 * WordPress keeps Customizer settings per theme (theme_mods_{slug}), so a
 * child theme starts empty. The first time it is active, its settings are
 * built from, in order of precedence:
 *   1. anything it already has (a re-activation never overwrites);
 *   2. the parent Egstore theme's settings on this site;
 *   3. Naya's defaults below (brand colours, header, announcement bar), only
 *      where nothing above sets them.
 * The parent's Additional CSS is not carried over. Earlier settings, if any,
 * are kept as a backup option, and a marker makes this run once.
 *
 * @package MisrChildNaya
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

const MISR_CHILD_NAYA_IMPORT_MARKER = 'misr_child_naya_settings_imported';
const MISR_CHILD_NAYA_IMPORT_BACKUP = 'misr_child_naya_settings_before_import';

/** @return array<string, mixed> Naya's settings (see README.md). */
function misr_child_naya_store_defaults(): array
{
	return [
		'egstore_header_show_menu'       => true,
		'egstore_header_show_drawer'     => true,
		'egstore_header_show_account'    => true,
		// The store's offers (confirmed 2026-10-14); the bar stays off until
		// the store turns it on.
		'egstore_announcement_enabled'   => false,
		'egstore_announcement_placement' => 'all',
		'egstore_announcement_1'         => 'شحن مجاني للطلبات فوق 1000 ج.م',
		'egstore_announcement_2'         => 'خصم 20% على أول طلب بكود NAYA20',
		'egstore_announcement_3'         => 'توصيل لكل المحافظات خلال 5 إلى 7 أيام عمل',
		'egstore_announcement_4'         => '',
		// Naya's palette.
		'egstore_color_primary'          => '#662979',
		'egstore_color_primary_hover'    => '#9f3ebd',
		'egstore_color_text'             => '#102136',
		'egstore_color_muted'            => '#4d5d6d',
		'egstore_color_border'           => '#e7ebee',
		'egstore_color_surface'          => '#f3f5f7',
		'egstore_color_canvas'           => '#fbfbfc',
		'egstore_color_inverse'          => '#ffffff',
	];
}

/**
 * The settings this theme gets on its first activation.
 *
 * @param array<string, mixed> $child  This theme's current settings.
 * @param array<string, mixed> $parent The parent theme's settings.
 * @return array<string, mixed>
 */
function misr_child_naya_merge_settings(array $child, array $parent): array
{
	unset($parent['custom_css_post_id']);

	return array_replace(misr_child_naya_store_defaults(), $parent, $child);
}

function misr_child_naya_import_settings(): void
{
	if (get_option(MISR_CHILD_NAYA_IMPORT_MARKER)) {
		return;
	}

	$stylesheet = get_stylesheet();
	$child = get_option('theme_mods_' . $stylesheet);
	$child = is_array($child) ? $child : [];
	$parent = get_option('theme_mods_' . get_template());
	$parent = is_array($parent) ? $parent : [];

	if ($child !== []) {
		update_option(MISR_CHILD_NAYA_IMPORT_BACKUP, $child, false);
	}

	update_option('theme_mods_' . $stylesheet, misr_child_naya_merge_settings($child, $parent));
	update_option(MISR_CHILD_NAYA_IMPORT_MARKER, gmdate('c'), false);
}

// After WordPress's own after_switch_theme handlers: _wp_menus_changed()
// writes the new theme's settings from a copy it read earlier, and would
// overwrite an import made before it.
add_action('after_switch_theme', 'misr_child_naya_import_settings', 100);
