<?php
/**
 * Misr Child (Naya): the store layer for نايا للجمال النقي
 * (naya.dokanelbanat.com).
 *
 * Only this store's identity lives here: the homepage composition
 * (config/pages/home.php replaces the parent's) and brand styling through the
 * parent theme's tokens. Behaviour belongs in the Egstore theme or the
 * egstore-commerce plugin; settings (colours, menus, announcement bar) live
 * in the Customizer.
 *
 * @package MisrChildNaya
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

// First activation: the store's Customizer settings move over to this theme.
require_once __DIR__ . '/inc/import-settings.php';

add_action(
	'wp_enqueue_scripts',
	static function (): void {
		$path = get_stylesheet_directory() . '/assets/store.css';

		wp_enqueue_style(
			'misr-child-naya',
			get_stylesheet_directory_uri() . '/assets/store.css',
			['egstore-core'],
			(string) filemtime($path)
		);
	},
	20
);
