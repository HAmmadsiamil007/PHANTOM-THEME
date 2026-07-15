<?php
/**
 * Theme functions and definitions.
 *
 * @package optix
 */

namespace Optix;

/**
 * The current version of the theme.
 */
define( 'OPTIX_VERSION', '1.0.0' );

/**
 * Load textdomain early to avoid _load_textdomain_just_in_time warning in WP 6.7+.
 */
add_action( 'after_setup_theme', function() {
  load_theme_textdomain( 'optix', get_template_directory() . '/languages' );
}, 5 );

// Emergency Fallback: Load when optix-core plugin is not active
if ( ! class_exists( '\OptixCore\Profile_Router' ) ) {
  require get_theme_file_path( '/inc/class-fallback-router.php' );
  require get_theme_file_path( '/inc/fallback-functions.php' );
  \Optix\Fallback_Router::get_instance()->init();
}

add_action( 'after_setup_theme', function() {
  $theme_settings = [
    'textdomain'              => 'optix',
    'content_width'           => 800,
    'default_featured_image'  => null,
    'logo'                    => null,

    'custom_settings' => [],
    'social_media_accounts'  => [],
    'external_link_domains_exclude' => [
      'localhost:3000',
      'optix.test',
      'localhost',
    ],

    /**
     * Menu locations
     */
    'menu_locations' => [
      'primary' => __( 'Primary Menu', 'optix' ),
      'footer'  => __( 'Footer Menu', 'optix' ),
    ],

    'taxonomies' => [
      'Portfolio_Category' => [ 'portfolio' ],
    ],
    'post_types' => [
      'Portfolio',
    ],
    // Register custom ACF Blocks
    'acf_blocks' => [
      [
        'name'           => 'portfolio-grid',
        'title'          => 'Portfolio Grid',
        'prevent_cache'  => false,
        'icon'           => 'portfolio',
      ],
      [
        'name'           => 'project-highlight',
        'title'          => 'Project Highlight',
        'prevent_cache'  => false,
        'icon'           => 'star-filled',
      ],
    ],

    // Custom ACF block default settings
    'acf_block_defaults' => [
      'category'          => 'optix',
      'mode'              => 'preview',
      'align'             => 'full',
      'post_types'        => [
        'page',
        'portfolio',
      ],
      'supports'  => [
        'align'           => false,
        'anchor'          => true,
        'customClassName' => false,
      ],
      'validate'          => true,
      'render_callback'   => __NAMESPACE__ . '\render_acf_block',
    ],

    // Restrict to only selected blocks
    //
    // Options: 'none', 'all', 'all-core-blocks', 'all-acf-blocks',
    // or any specific block or a combination of these
    // Accepts both string (all*/none-options only) and array (options + specific blocks)
    'allowed_blocks' => [
      'post' => [
        'core/column',
        'core/columns',
        'core/coverImage',
        'core/embed',
        'core/freeform',
        'core/gallery',
        'core/heading',
        'core/html',
        'core/image',
        'core/list',
        'core/list-item',
        'core/paragraph',
        'core/quote',
        'core/block',
        'core/table',
        'core/textColumns',
      ],
      'portfolio' => [
        'all-core-blocks',
        'acf/portfolio-grid',
        'acf/project-highlight',
      ],
      'page' => [
        'all',
      ],
    ],

    'use_classic_editor' => [ 'portfolio' ],
    'my_custom_setting'  => true,
  ];

  $theme_settings = apply_filters( 'optix_theme_settings', $theme_settings );

  define( 'THEME_SETTINGS', $theme_settings );
} );
/**
 * Required files
 */
require get_theme_file_path( '/inc/hooks.php' );
require get_theme_file_path( '/inc/includes.php' );
require get_theme_file_path( '/inc/template-tags.php' );

// Initialize Asset_Registry from plugin (if available)
if ( class_exists( '\OptixCore\Registry\Asset_Registry' ) ) {
	\OptixCore\Registry\Asset_Registry::get_instance()->init();
}

// Register theme assets via Asset_Registry
add_action( 'init', function () {
	if ( ! class_exists( '\OptixCore\Registry\Asset_Registry' ) ) {
		return;
	}
	$registry = \OptixCore\Registry\Asset_Registry::get_instance();

	$registry->register_style(
		'optix-main',
		get_template_directory_uri() . '/assets/css/style.css',
		[],
		OPTIX_VERSION
	);

	$registry->register_script(
		'optix-main',
		get_template_directory_uri() . '/assets/js/main.js',
		[ 'jquery' ],
		OPTIX_VERSION,
		true
	);

	if ( class_exists( 'WooCommerce' ) ) {
		$registry->register_style(
			'optix-woocommerce',
			get_template_directory_uri() . '/assets/css/woocommerce.css',
			[ 'optix-main' ],
			OPTIX_VERSION
		);
	}
}, 5 );

// Web Vitals optimization
require get_theme_file_path( '/inc/class-web-vitals.php' );
\Optix\Web_Vitals::get_instance()->init();

// Run theme setup
add_action( 'after_setup_theme', __NAMESPACE__ . '\theme_setup' );
add_action( 'after_setup_theme', __NAMESPACE__ . '\build_theme_support' );

/*
 * Register taxonomies and post types after setup theme.
 * air-helper may re-register with translations if available.
 */
add_action( 'after_setup_theme', __NAMESPACE__ . '\build_taxonomies' );
add_action( 'after_setup_theme', __NAMESPACE__ . '\build_post_types' );


