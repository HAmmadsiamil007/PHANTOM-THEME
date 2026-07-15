<?php
/**
 * Standalone fallback functions for when optix-core plugin is deactivated.
 *
 * These functions read options directly via get_option() with no plugin
 * class dependencies.
 *
 * @package optix
 */

namespace Optix;

function fallback_option( string $key, $default = null ) {
  $value = get_option( 'optix_' . $key, null );
  return $value ?? $default;
}

function fallback_asset_url( string $relative_path = '' ): string {
  $profile = get_option( 'optix_active_profile', 'default' );
  $base    = get_template_directory_uri() . '/profiles/' . $profile . '/assets';
  return $relative_path ? $base . '/' . ltrim( $relative_path, '/' ) : $base;
}

function fallback_img( string $path, string $fallback = '' ): string {
  $url = fallback_asset_url( $path );
  return file_exists( get_template_directory() . str_replace( get_template_directory_uri(), '', $url ) ) ? $url : $fallback;
}

function fallback_string( string $key, string $default = '' ): string {
  $value = fallback_option( $key, $default );
  return is_string( $value ) ? $value : $default;
}

function fallback_int( string $key, int $default = 0 ): int {
  return intval( fallback_option( $key, $default ) );
}

function fallback_bool( string $key, bool $default = false ): bool {
	return (bool) fallback_option( $key, $default );
}

/**
 * Public alias for fallback_asset_url — ensures callers linking to
 * profile assets (CSS, JS, images) keep working even when optix-core
 * plugin is deactivated.
 */
function optix_asset_url( string $relative_path = '' ): string {
	return fallback_asset_url( $relative_path );
}

/**
 * Public alias for fallback_img — returns a profile image URL or
 * fallback string when the plugin is inactive.
 */
function optix_img( string $path = '', string $fallback = '' ): string {
	return fallback_img( $path, $fallback );
}

/**
 * Read a theme option with full fallback chain, working without the
 * optix-core plugin.  Tries get_option -> defaults.php -> '' .
 */
function optix_get_option( string $key, $default = null ) {
	$direct = get_option( 'optix_' . $key, '__not_set__' );
	if ( '__not_set__' !== $direct && '' !== $direct && false !== $direct ) {
		return $direct;
	}

	$opt_value = get_option( 'optix_' . $key, $default );
	if ( null !== $opt_value && false !== $opt_value ) {
		return $opt_value;
	}

	if ( $default !== null ) {
		return $default;
	}

	return '';
}
