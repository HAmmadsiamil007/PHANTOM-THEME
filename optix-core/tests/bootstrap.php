<?php
/**
 * Optix Core Tests Bootstrap
 *
 * Loads the plugin files for unit testing.
 */

define( 'ABSPATH', dirname( __DIR__, 4 ) . '/' );
define( 'WP_CONTENT_DIR', dirname( __DIR__, 3 ) );
$template_path = WP_CONTENT_DIR . '/themes/optix-main';
if ( ! is_dir( $template_path . '/profiles' ) ) {
	$template_path = dirname( __DIR__, 2 ) . '/optix-main/optix-main';
}
define( 'TEMPLATE_DIR', $template_path );

// Load WordPress function stubs for testing without WordPress
require_once __DIR__ . '/wp-stubs.php';

$autoload = __DIR__ . '/../vendor/autoload.php';
if ( file_exists( $autoload ) ) {
	require_once $autoload;
}

$plugin_dir = dirname( __DIR__ );
$includes   = [
	'class-options-manager.php',
	'class-profile-router.php',
	'class-theme-api.php',
	'class-core-plugin.php',
	'class-cpt-manager.php',
	'class-taxonomy-manager.php',
	'class-dynamic-css-generator.php',
	'class-maintenance-mode.php',
	'class-mega-menu.php',
	'class-3d-effects.php',
	'class-acf-blocks.php',
	'class-cookie-consent.php',
	'class-ajax-handlers.php',
	'class-demo-importer.php',
	'class-head-manager.php',
	'class-section-manifest-validator.php',
];

// Admin classes
$admin_dir = $plugin_dir . '/admin/';
if ( is_dir( $admin_dir ) ) {
	foreach ( glob( $admin_dir . 'class-*.php' ) as $file ) {
		require_once $file;
	}
}

// Engine classes
$engine_dir = $plugin_dir . '/includes/engine/';
if ( is_dir( $engine_dir ) ) {
	foreach ( glob( $engine_dir . 'class-*.php' ) as $file ) {
		require_once $file;
	}
}

foreach ( $includes as $file ) {
	$path = $plugin_dir . '/includes/' . $file;
	if ( file_exists( $path ) ) {
		require_once $path;
	}
}

// Load registry files (Base_Registry first, then rest)
$registry_dir = $plugin_dir . '/includes/registry/';
if ( is_dir( $registry_dir ) ) {
	$base = $registry_dir . 'class-base-registry.php';
	if ( file_exists( $base ) ) {
		require_once $base;
	}
	foreach ( glob( $registry_dir . 'class-*.php' ) as $file ) {
		if ( basename( $file ) !== 'class-base-registry.php' ) {
			require_once $file;
		}
	}
}

$wp_tests = getenv( 'WP_TESTS_DIR' ) ?: '/tmp/wordpress-tests-lib/bootstrap.php';
if ( file_exists( $wp_tests ) ) {
	require_once $wp_tests;
}
