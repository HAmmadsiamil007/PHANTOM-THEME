<?php
/**
 * Backward compatibility shim for optix-core AJAX handlers.
 *
 * Previously located in the theme, now migrated to the plugin.
 *
 * @package optix
 */

_deprecated_file( __FILE__, '1.0.0', 'OptixCore\Ajax_Handlers' );

// Load plugin class if not already loaded
$ajax_handlers_file = WP_PLUGIN_DIR . '/optix-core/includes/class-ajax-handlers.php';
if ( file_exists( $ajax_handlers_file ) ) {
    require_once $ajax_handlers_file;
}

