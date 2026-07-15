<?php
/**
 * Theme API — registry-first bridge between registries and templates.
 *
 * @package optix-core
 */

declare(strict_types=1);

namespace OptixCore { // phpcs:ignore Universal.Classes.RequireAnonClassParenthesis

	use OptixCore\Registry\Settings_Registry;

	defined( 'ABSPATH' ) || exit;

	/**
	 * Bridge class exposing registry-backed getters to dumb templates.
	 */
	class Theme_API {

		/**
		 * Singleton instance.
		 *
		 * @var ?Theme_API
		 */
		private static ?Theme_API $instance = null;

		/**
		 * Get singleton instance.
		 *
		 * @return self
		 */
		public static function get_instance(): self {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Initialize the API. Hook registration placeholder.
		 *
		 * @return void
		 */
		public function init(): void {
		}

		/**
		 * Resolve profile asset URL.
		 *
		 * @param  string $relative_path Relative path within the profile assets dir.
		 * @return string
		 */
		public static function asset_url( string $relative_path = '' ): string {
			$base = get_template_directory_uri() . '/profiles/' . Profile_Router::get_instance()->get_active_profile() . '/assets';
			if ( $relative_path ) {
				return $base . '/' . ltrim( $relative_path, '/' );
			}
			return $base;
		}

		/**
		 * Resolve profile-aware image path.
		 *
		 * @param  string $path     Image path.
		 * @param  string $fallback Fallback if image not found.
		 * @return string
		 */
		public static function img( string $path = '', string $fallback = '' ): string {
			if ( empty( $path ) || '0' === $path ) {
				return $fallback;
			}

			$profile = Profile_Router::get_instance();
			$profile_image_dir = $profile->get_profile_path() . '/assets/images';

			if ( file_exists( $profile_image_dir . $path ) ) {
				$uri = get_template_directory_uri() . '/profiles/' . $profile->get_active_profile() . '/assets/images';
				return $uri . $path;
			}

			return $fallback;
		}

		/**
		 * Registry-first option getter — reads Settings_Registry first, falls back to Options_Manager.
		 *
		 * @param  string $key     Option key.
		 * @param  mixed  $default Fallback value.
		 * @return mixed
		 */
		public static function option( string $key, mixed $default = null ): mixed {
			$registry = Settings_Registry::get_instance();
			if ( $registry->has( $key ) ) {
				$value = $registry->get( $key );
				if ( null !== $value ) {
					return apply_filters( "optix/settings/get/{$key}", $value );
				}
			}
			return apply_filters( "optix/settings/get/{$key}", Options_Manager::get( $key, $default ) );
		}

		/**
		 * Get an option as string.
		 *
		 * @param  string $key     Option key.
		 * @param  string $default Fallback value.
		 * @return string
		 */
		public static function string( string $key, string $default = '' ): string {
			$val = self::option( $key, $default );
			if ( null === $val ) {
				return $default;
			}
			return is_string( $val ) ? $val : (string) $val;
		}

		/**
		 * Get an option as integer.
		 *
		 * @param  string $key     Option key.
		 * @param  int    $default Fallback value.
		 * @return int
		 */
		public static function int( string $key, int $default = 0 ): int {
			return (int) self::option( $key, $default );
		}

		/**
		 * Get an option as boolean.
		 *
		 * @param  string $key     Option key.
		 * @param  bool   $default Fallback value.
		 * @return bool
		 */
		public static function bool( string $key, bool $default = false ): bool {
			return (bool) self::option( $key, $default );
		}

		/**
		 * Get an option as sanitized hex color.
		 *
		 * @param  string $key     Option key.
		 * @param  string $default Fallback hex color.
		 * @return string
		 */
		public static function color( string $key, string $default = '#000000' ): string {
			$val = self::option( $key, $default );
			if ( ! is_string( $val ) ) {
				return $default;
			}
			$val       = self::expand_hex_shorthand( $val );
			$sanitized = sanitize_hex_color( $val );
			return $sanitized ? $sanitized : $default;
		}

		/**
		 * Expand 3-digit hex shorthand to full 6-digit.
		 *
		 * @param  string $color Hex color string.
		 * @return string
		 */
		private static function expand_hex_shorthand( string $color ): string {
			if ( 1 === preg_match( '/^#([0-9a-f])([0-9a-f])([0-9a-f])$/i', $color, $m ) ) {
				return '#' . $m[1] . $m[1] . $m[2] . $m[2] . $m[3] . $m[3];
			}
			return $color;
		}

		/**
		 * Get an option as image URL.
		 *
		 * @param  string $key  Option key.
		 * @param  string $size Image size (for attachment IDs).
		 * @return string
		 */
		public static function image( string $key, string $size = 'full' ): string {
			$val = self::option( $key, '' );
			if ( empty( $val ) ) {
				return '';
			}
			if ( is_numeric( $val ) ) {
				$src = wp_get_attachment_image_url( (int) $val, $size );
				return false !== $src ? $src : '';
			}
			return self::img( (string) $val );
		}
	}

}

namespace { // phpcs:ignore Universal.Classes.RequireAnonClassParenthesis

	if ( ! function_exists( 'optix_asset_url' ) ) {
		/**
		 * Get profile asset URL.
		 *
		 * @param  string $relative_path Relative path.
		 * @return string
		 */
		function optix_asset_url( string $relative_path = '' ): string {
			return \OptixCore\Theme_API::asset_url( $relative_path );
		}
	}

	if ( ! function_exists( 'optix_img' ) ) {
		/**
		 * Resolve profile-aware image path.
		 *
		 * @param  string $path     Image path.
		 * @param  string $fallback Fallback URL.
		 * @return string
		 */
		function optix_img( string $path = '', string $fallback = '' ): string {
			return \OptixCore\Theme_API::img( $path, $fallback );
		}
	}

	if ( ! function_exists( 'optix_option' ) ) {
		/**
		 * Get option value.
		 *
		 * @param  string $key     Option key.
		 * @param  mixed  $default Fallback value.
		 * @return mixed
		 */
		function optix_option( string $key, mixed $default = null ): mixed {
			return \OptixCore\Theme_API::option( $key, $default );
		}
	}

	if ( ! function_exists( 'optix_string' ) ) {
		/**
		 * Get option as string.
		 *
		 * @param  string $key     Option key.
		 * @param  string $default Fallback value.
		 * @return string
		 */
		function optix_string( string $key, string $default = '' ): string {
			return \OptixCore\Theme_API::string( $key, $default );
		}
	}

	if ( ! function_exists( 'optix_int' ) ) {
		/**
		 * Get option as integer.
		 *
		 * @param  string $key     Option key.
		 * @param  int    $default Fallback value.
		 * @return int
		 */
		function optix_int( string $key, int $default = 0 ): int {
			return \OptixCore\Theme_API::int( $key, $default );
		}
	}

	if ( ! function_exists( 'optix_bool' ) ) {
		/**
		 * Get option as boolean.
		 *
		 * @param  string $key     Option key.
		 * @param  bool   $default Fallback value.
		 * @return bool
		 */
		function optix_bool( string $key, bool $default = false ): bool {
			return \OptixCore\Theme_API::bool( $key, $default );
		}
	}

	if ( ! function_exists( 'optix_color' ) ) {
		/**
		 * Get option as sanitized hex color.
		 *
		 * @param  string $key     Option key.
		 * @param  string $default Fallback hex color.
		 * @return string
		 */
		function optix_color( string $key, string $default = '#000000' ): string {
			return \OptixCore\Theme_API::color( $key, $default );
		}
	}

	if ( ! function_exists( 'optix_image' ) ) {
		/**
		 * Get option as image URL.
		 *
		 * @param  string $key  Option key.
		 * @param  string $size Image size.
		 * @return string
		 */
		function optix_image( string $key, string $size = 'full' ): string {
			return \OptixCore\Theme_API::image( $key, $size );
		}
	}

	if ( ! function_exists( 'optix_get_option' ) ) {
		/**
		 * Legacy alias for optix_option().
		 *
		 * @param  string $key     Option key.
		 * @param  mixed  $default Fallback value.
		 * @return mixed
		 */
		function optix_get_option( string $key, mixed $default = null ): mixed {
			return \OptixCore\Theme_API::option( $key, $default );
		}
	}
}
