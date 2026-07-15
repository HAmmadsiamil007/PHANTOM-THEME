<?php
/**
 * Plugin Name:       Optix Core Framework
 * Plugin URI:        https://optix.test
 * Description:       Core engine for Optix theme — controls, settings, CPTs, and features. Required by Optix theme.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      8.1
 * Author:            Optix
 * Text Domain:       optix-core
 * Domain Path:       /languages
 *
 * @package OptixCore
 */

declare(strict_types=1);

namespace OptixCore;

defined( 'ABSPATH' ) || exit;

define( 'OPTIX_CORE_VERSION', '1.0.0' );
define( 'OPTIX_CORE_FILE', __FILE__ );
define( 'OPTIX_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'OPTIX_CORE_URL', plugin_dir_url( __FILE__ ) );

$includes = array(
	'class-core-plugin.php',
	'class-head-manager.php',
	'class-options-manager.php',
	'class-cpt-manager.php',
	'class-taxonomy-manager.php',
	'class-maintenance-mode.php',
	'class-dynamic-css-generator.php',
	'class-mega-menu.php',
	'class-3d-effects.php',
	'class-acf-blocks.php',
	'class-profile-router.php',
	'class-theme-api.php',
	'class-section-manifest-validator.php',
);

$registry_files = array(
	'registry/class-base-registry.php',
	'registry/class-settings-registry.php',
	'registry/class-section-registry.php',
	'registry/class-component-registry.php',
	'registry/class-animation-registry.php',
	'registry/class-block-registry.php',
	'registry/class-color-registry.php',
	'registry/class-demo-registry.php',
	'registry/class-hook-registry.php',
	'registry/class-layout-registry.php',
	'registry/class-preset-registry.php',
	'registry/class-responsive-registry.php',
	'registry/class-typography-registry.php',
	'registry/class-woocommerce-registry.php',
	'registry/class-asset-registry.php',
	'registry/class-template-registry.php',
);

foreach ( $registry_files as $file ) {
	$path = OPTIX_CORE_PATH . 'includes/' . $file;
	if ( file_exists( $path ) ) {
		require_once $path;
	}
}

foreach ( $includes as $file ) {
	$path = OPTIX_CORE_PATH . 'includes/' . $file;
	if ( file_exists( $path ) ) {
		require_once $path;
	}
}

require_once OPTIX_CORE_PATH . 'admin/class-settings-page.php';

$acf_sync_path = OPTIX_CORE_PATH . 'includes/acf/class-acf-sync.php';
if ( file_exists( $acf_sync_path ) ) {
	require_once $acf_sync_path;
	\OptixCore\Acf\Acf_Sync::get_instance()->init();
}

$rest_path = OPTIX_CORE_PATH . 'includes/api/class-rest-controller.php';
if ( file_exists( $rest_path ) ) {
	require_once $rest_path;
	\OptixCore\Api\Rest_Controller::get_instance()->init();
}

$wizard_path = OPTIX_CORE_PATH . 'admin/class-setup-wizard.php';
if ( file_exists( $wizard_path ) ) {
	require_once $wizard_path;
	\OptixCore\Setup_Wizard::get_instance()->init();
}

$cookie_path = OPTIX_CORE_PATH . 'includes/class-cookie-consent.php';
if ( file_exists( $cookie_path ) ) {
	require_once $cookie_path;
	\OptixCore\Cookie_Consent::get_instance()->init();
}

$ajax_path = OPTIX_CORE_PATH . 'includes/class-ajax-handlers.php';
if ( file_exists( $ajax_path ) ) {
	require_once $ajax_path;
}

$demo_import_path = OPTIX_CORE_PATH . 'includes/class-demo-importer.php';
if ( file_exists( $demo_import_path ) ) {
	require_once $demo_import_path;
}

define( 'OPTIX_CORE_SCHEMA_VERSION', 1 );

$engine_files = array(
	'engine/class-cache.php',
	'engine/class-schema-migrator.php',
	'engine/class-typography-engine.php',
	'engine/class-color-engine.php',
	'engine/class-layout-engine.php',
	'engine/class-responsive-engine.php',
	'engine/class-header-builder.php',
	'engine/class-footer-builder.php',
	'engine/class-blog-engine.php',
	'engine/class-search-engine.php',
	'engine/class-product-engine.php',
	'engine/class-accessibility-engine.php',
);
foreach ( $engine_files as $file ) {
	$path = OPTIX_CORE_PATH . 'includes/' . $file;
	if ( file_exists( $path ) ) {
		require_once $path;
	}
}
\OptixCore\Engine\Cache::get_instance()->init();
\OptixCore\Engine\Schema_Migrator::get_instance()->init();

$customizer_path = OPTIX_CORE_PATH . 'includes/engine/class-customizer.php';
if ( file_exists( $customizer_path ) ) {
	require_once $customizer_path;
	\OptixCore\Engine\Customizer::get_instance()->init();
}

$mig_path = OPTIX_CORE_PATH . 'includes/migrations/class-migration-1.php';
if ( file_exists( $mig_path ) ) {
	require_once $mig_path;
	\OptixCore\Engine\Schema_Migrator::get_instance()->add_migration(
		1,
		'Migrate from theme options to plugin options',
		array( \OptixCore\Migrations\Migration_1::class, 'up' )
	);
}

/**
 * Load plugin textdomain early.
 */
add_action(
	'plugins_loaded',
	function () {
		load_plugin_textdomain(
			'optix-core',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
		);
	},
	1
);

/**
 * Initialize the plugin.
 *
 * @return void
 */
function init(): void {
	$plugin = new Plugin();
	$plugin->init();
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\\init', 5 );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	$cli_path = OPTIX_CORE_PATH . 'includes/cli/class-commands.php';
	if ( file_exists( $cli_path ) ) {
		require_once $cli_path;
		\WP_CLI::add_command( 'optix', \OptixCore\Cli\Commands::class );
	}
}

register_activation_hook(
	__FILE__,
	function () {
		if ( method_exists( __NAMESPACE__ . '\\Options_Manager', 'migrate_from_theme' ) ) {
			call_user_func( array( __NAMESPACE__ . '\\Options_Manager', 'migrate_from_theme' ) );
		}
		flush_rewrite_rules();
	}
);

register_deactivation_hook(
	__FILE__,
	function () {
		flush_rewrite_rules();
	}
);
