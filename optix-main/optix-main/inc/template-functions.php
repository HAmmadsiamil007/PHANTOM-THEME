<?php
/**
 * Template helper functions for Optix Kids Collection.
 *
 * Provides optix_get_option() with a fallback chain:
 * ACF field → defaults.php → caller $default → ''
 *
 * @package optix
 */

namespace Optix {

  require_once get_theme_file_path( '/inc/defaults.php' );

  /**
   * Get an option value with full fallback chain.
   *
   * 1. ACF field (if ACF Pro is active and field has a non-empty value)
   * 2. defaults.php (if key exists and value is non-empty)
   * 3. Caller-supplied $default parameter
   * 4. Empty string
   *
   * @param string $key     The option key / ACF field name.
   * @param mixed  $default Fallback value if nothing is stored.
   * @return mixed
   */
  if ( ! function_exists( __NAMESPACE__ . '\optix_get_option' ) ) {
  function optix_get_option( string $key, $default = null ) {
    static $cache = [];
    static $acf_cache = null;

    if ( array_key_exists( $key, $cache ) ) {
      return $cache[ $key ];
    }

    $direct = get_option( 'optix_' . $key, '__not_set__' );
    if ( '__not_set__' !== $direct && '' !== $direct && false !== $direct ) {
      $cache[ $key ] = $direct;
      return $direct;
    }

    $sections = [ 'general', 'header', 'footer', 'home-page', 'about-page', 'blog', 'contact', 'shop', 'product-detail', 'cart-checkout', 'pages', 'animations', 'advanced', 'import-export', 'topbar', 'colors', 'typography' ];
    foreach ( $sections as $section ) {
      $opt_key = 'optix_' . $section . '_' . $key;
      $value = get_option( $opt_key, '__not_set__' );
      if ( '__not_set__' !== $value && '' !== $value && false !== $value ) {
        $cache[ $key ] = $value;
        return $value;
      }
    }

    if ( function_exists( 'get_field' ) && did_action( 'acf/init' ) ) {
      if ( null === $acf_cache ) {
        $acf_cache = [];
      }
      if ( ! array_key_exists( $key, $acf_cache ) ) {
        $acf_cache[ $key ] = get_field( $key, 'option' );
      }
      $acf_value = $acf_cache[ $key ];
      if ( null !== $acf_value && '' !== $acf_value && false !== $acf_value ) {
        $cache[ $key ] = $acf_value;
        return $acf_value;
      }
    }

    $defaults = get_default( $key );
    if ( null !== $defaults && '' !== $defaults && false !== $defaults ) {
      $cache[ $key ] = $defaults;
      return $defaults;
    }

    if ( null !== $default ) {
      $cache[ $key ] = $default;
      return $default;
    }

    $cache[ $key ] = '';
    return '';
  }
  }

  /**
   * Get the Kids Collection image base URL.
   *
   * Filterable via 'optix/kc_image_base' so profiles can override.
   *
   * @return string
   */
  function kc_img_base(): string {
    $base = get_template_directory_uri() . '/assets/kids-collection/images';
    return apply_filters( 'optix/kc_image_base', $base );
  }

  /**
   * Get a URL for a Kids Collection image asset.
   *
   * Prepends the theme directory URI and the kc_img base path.
   * Base path is filterable via 'optix/kc_image_base'.
   *
   * @param string $path Relative image path (e.g. '/logo.png').
   * @return string
   */
  function optix_img( string $path = '', string $fallback = '' ): string {
    if ( '' === $path || '0' === $path ) {
      return $fallback;
    }
    return kc_img_base() . '/' . ltrim( $path, '/' );
  }

  /**
   * Redirect non-admins to maintenance page when maintenance mode is on.
   */
  function optix_maintenance_mode_check() {
    if ( optix_get_option( 'maintenance_mode_enable' ) && ! current_user_can( 'manage_options' ) ) {
      $maintenance_template = get_theme_file_path( 'maintenance.php' );
      if ( file_exists( $maintenance_template ) ) {
        include $maintenance_template;
        exit;
      }
    }
  }

  /**
   * Get portfolio option helper.
   */
  function optix_portfolio_option( string $key, $default = null ) {
    return optix_get_option( 'portfolio_' . $key, $default );
  }

  add_action( 'template_redirect', __NAMESPACE__ . '\optix_maintenance_mode_check', 1 );
}

// ── Global namespace wrappers ─────────────────────────────────
// The templates/kids-collection/*.php files do NOT have namespace
// Optix, so global functions are needed for easy access.
namespace {
  if ( ! function_exists( 'optix_get_option' ) ) {
    function optix_get_option( string $key, $default = null ) {
      return \Optix\optix_get_option( $key, $default );
    }
  }

  if ( ! function_exists( 'optix_img' ) ) {
    function optix_img( string $path = '', string $fallback = '' ): string {
      return \Optix\optix_img( $path, $fallback );
    }
  }

  if ( ! function_exists( 'kc_img_base' ) ) {
    function kc_img_base(): string {
      return \Optix\kc_img_base();
    }
  }
}
