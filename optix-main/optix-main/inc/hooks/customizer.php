<?php
/**
 * Theme Customizer — Comprehensive Panels
 *
 * Mirrors all ACF admin options in the Customizer with live preview.
 * Follows naming: option keys save to wp_options for optix_get_option()
 * fallback chain compatibility.
 *
 * @package optix
 */

namespace Optix;

/**
 * Register ALL Customizer panels, sections, and controls.
 */
add_action( 'customize_register', __NAMESPACE__ . '\register_customizer_panels' );
function register_customizer_panels( $wp_customize ) {

  // ── 1. GENERAL PANEL ─────────────────────────────────────────────
  $wp_customize->add_panel( 'optix_general', [
    'title'    => __( 'General', 'optix' ),
    'priority' => 20,
  ] );

  $wp_customize->add_section( 'optix_general_main', [
    'title'    => __( 'General Settings', 'optix' ),
    'panel'    => 'optix_general',
  ] );

  register_text( $wp_customize, 'optix_general_main', 'general_site_logo', [
    'label'    => __( 'Site Logo Path', 'optix' ),
    'default'  => 'logo.png',
  ] );
  register_text( $wp_customize, 'optix_general_main', 'general_kc_img_base', [
    'label'    => __( 'Image Base Path', 'optix' ),
    'default'  => '/assets/kids-collection/images',
  ] );
  register_toggle( $wp_customize, 'optix_general_main', 'general_preloader_enable', [
    'label'    => __( 'Enable Preloader', 'optix' ),
    'default'  => 1,
  ] );
  $wp_customize->add_setting( 'custom_css', [
    'type'              => 'option',
    'default'           => '',
    'transport'         => 'refresh',
  ] );
  $wp_customize->add_control( 'custom_css', [
    'label'    => __( 'Custom CSS', 'optix' ),
    'section'  => 'optix_general_main',
    'type'     => 'textarea',
    'settings' => 'custom_css',
  ] );
  $wp_customize->add_setting( 'custom_js', [
    'type'              => 'option',
    'default'           => '',
    'transport'         => 'refresh',
  ] );
  $wp_customize->add_control( 'custom_js', [
    'label'    => __( 'Custom JavaScript', 'optix' ),
    'section'  => 'optix_general_main',
    'type'     => 'textarea',
    'settings' => 'custom_js',
  ] );

  // ── 2. KC COLORS PANEL ───────────────────────────────────────────
  $wp_customize->add_panel( 'optix_colors', [
    'title'       => __( 'Colors', 'optix' ),
    'description' => __( 'Theme color settings.', 'optix' ),
    'priority'    => 30,
  ] );

  $wp_customize->add_section( 'optix_colors_main', [
    'title'    => __( 'Main Colors', 'optix' ),
    'panel'    => 'optix_colors',
  ] );

  $color_fields = [
    'color_primary'    => [ 'label' => 'Primary Color',    'default' => '#705b53' ],
    'color_secondary'  => [ 'label' => 'Secondary Color',  'default' => '#c19a6b' ],
    'color_accent'     => [ 'label' => 'Accent Color',     'default' => '#d4a373' ],
    'color_text'       => [ 'label' => 'Text Color',       'default' => '#666666' ],
    'color_heading'    => [ 'label' => 'Heading Color',    'default' => '#222222' ],
    'color_background' => [ 'label' => 'Background Color', 'default' => '#ffffff' ],
    'color_header_bg'  => [ 'label' => 'Header Background','default' => '#ffffff' ],
    'color_footer_bg'  => [ 'label' => 'Footer Background','default' => '#222222' ],
  ];
  foreach ( $color_fields as $key => $cfg ) {
    register_color( $wp_customize, 'optix_colors_main', $key, $cfg );
  }

  $wp_customize->add_section( 'optix_colors_additional', [
    'title'    => __( 'Additional Colors', 'optix' ),
    'panel'    => 'optix_colors',
  ] );

  register_color( $wp_customize, 'optix_colors_additional', 'color_link', [
    'label'   => __( 'Link Color', 'optix' ),
    'default' => '#705b53',
  ] );
  register_color( $wp_customize, 'optix_colors_additional', 'color_link_hover', [
    'label'   => __( 'Link Hover Color', 'optix' ),
    'default' => '#c19a6b',
  ] );
  register_color( $wp_customize, 'optix_colors_additional', 'color_border', [
    'label'   => __( 'Border Color', 'optix' ),
    'default' => '#e5e5e5',
  ] );
  register_color( $wp_customize, 'optix_colors_additional', 'color_sale', [
    'label'   => __( 'Sale / Discount Color', 'optix' ),
    'default' => '#e74c3c',
  ] );

  // ── 3. TYPOGRAPHY PANEL ──────────────────────────────────────────
  $wp_customize->add_panel( 'optix_typography', [
    'title'    => __( 'Typography', 'optix' ),
    'priority' => 31,
  ] );

  $wp_customize->add_section( 'optix_typo_main', [
    'title'    => __( 'Font Settings', 'optix' ),
    'panel'    => 'optix_typography',
  ] );

  register_text( $wp_customize, 'optix_typo_main', 'typography_heading_font', [
    'label'   => __( 'Heading Font Family', 'optix' ),
    'default' => 'Archivo',
  ] );
  register_text( $wp_customize, 'optix_typo_main', 'typography_body_font', [
    'label'   => __( 'Body Font Family', 'optix' ),
    'default' => 'Jost',
  ] );
  register_number( $wp_customize, 'optix_typo_main', 'typography_base_size', [
    'label'   => __( 'Base Font Size (px)', 'optix' ),
    'default' => 16,
  ] );
  register_select( $wp_customize, 'optix_typo_main', 'typography_heading_weight', [
    'label'   => __( 'Heading Font Weight', 'optix' ),
    'default' => '700',
    'choices' => [
      '300' => 'Light (300)',
      '400' => 'Regular (400)',
      '500' => 'Medium (500)',
      '600' => 'Semi Bold (600)',
      '700' => 'Bold (700)',
      '800' => 'Extra Bold (800)',
      '900' => 'Black (900)',
    ],
  ] );
  register_select( $wp_customize, 'optix_typo_main', 'typography_body_weight', [
    'label'   => __( 'Body Font Weight', 'optix' ),
    'default' => '400',
    'choices' => [
      '300' => 'Light (300)',
      '400' => 'Regular (400)',
      '500' => 'Medium (500)',
      '600' => 'Semi Bold (600)',
      '700' => 'Bold (700)',
    ],
  ] );

  $wp_customize->add_section( 'optix_typo_advanced', [
    'title'    => __( 'Advanced Typography', 'optix' ),
    'panel'    => 'optix_typography',
  ] );

  register_number( $wp_customize, 'optix_typo_advanced', 'typography_line_height', [
    'label'   => __( 'Body Line Height', 'optix' ),
    'default' => 1.6,
    'attrs'   => [ 'step' => 0.1, 'min' => 1, 'max' => 3 ],
  ] );
  register_number( $wp_customize, 'optix_typo_advanced', 'typography_letter_spacing', [
    'label'   => __( 'Body Letter Spacing (px)', 'optix' ),
    'default' => 0,
    'attrs'   => [ 'step' => 0.5, 'min' => -5, 'max' => 10 ],
  ] );

  // ── 4. BUTTONS PANEL ─────────────────────────────────────────────
  $wp_customize->add_panel( 'optix_buttons', [
    'title'    => __( 'Buttons', 'optix' ),
    'priority' => 32,
  ] );

  $wp_customize->add_section( 'optix_buttons_main', [
    'title'    => __( 'Button Styles', 'optix' ),
    'panel'    => 'optix_buttons',
  ] );

  register_color( $wp_customize, 'optix_buttons_main', 'button_bg', [
    'label'   => __( 'Button Background', 'optix' ),
    'default' => '#705b53',
  ] );
  register_color( $wp_customize, 'optix_buttons_main', 'button_text', [
    'label'   => __( 'Button Text Color', 'optix' ),
    'default' => '#ffffff',
  ] );
  register_color( $wp_customize, 'optix_buttons_main', 'button_bg_hover', [
    'label'   => __( 'Button Hover Background', 'optix' ),
    'default' => '#c19a6b',
  ] );
  register_color( $wp_customize, 'optix_buttons_main', 'button_text_hover', [
    'label'   => __( 'Button Hover Text', 'optix' ),
    'default' => '#ffffff',
  ] );
  register_number( $wp_customize, 'optix_buttons_main', 'button_radius', [
    'label'   => __( 'Button Border Radius (px)', 'optix' ),
    'default' => 4,
  ] );
  register_number( $wp_customize, 'optix_buttons_main', 'button_padding_y', [
    'label'   => __( 'Button Vertical Padding (px)', 'optix' ),
    'default' => 12,
  ] );
  register_number( $wp_customize, 'optix_buttons_main', 'button_padding_x', [
    'label'   => __( 'Button Horizontal Padding (px)', 'optix' ),
    'default' => 24,
  ] );

  // ── 5. HEADER PANEL ──────────────────────────────────────────────
  $wp_customize->add_panel( 'optix_header', [
    'title'    => __( 'Header', 'optix' ),
    'priority' => 33,
  ] );

  $wp_customize->add_section( 'optix_header_main', [
    'title'    => __( 'Header Layout', 'optix' ),
    'panel'    => 'optix_header',
  ] );

  register_text( $wp_customize, 'optix_header_main', 'header_logo', [
    'label'   => __( 'Header Logo Path', 'optix' ),
    'default' => '/logo.png',
  ] );
  register_number( $wp_customize, 'optix_header_main', 'header_logo_width', [
    'label'   => __( 'Logo Width (px)', 'optix' ),
    'default' => 150,
  ] );
  register_text( $wp_customize, 'optix_header_main', 'header_search_icon', [
    'label'   => __( 'Search Icon Path', 'optix' ),
    'default' => '/header-search.png',
  ] );
  register_text( $wp_customize, 'optix_header_main', 'header_cart_icon', [
    'label'   => __( 'Cart Icon Path', 'optix' ),
    'default' => '/header-cart.png',
  ] );
  register_text( $wp_customize, 'optix_header_main', 'header_login_icon', [
    'label'   => __( 'Login Icon Path', 'optix' ),
    'default' => '/header-admin.png',
  ] );

  $wp_customize->add_section( 'optix_header_styling', [
    'title'    => __( 'Header Styling', 'optix' ),
    'panel'    => 'optix_header',
  ] );

  register_toggle( $wp_customize, 'optix_header_styling', 'header_sticky', [
    'label'   => __( 'Sticky Header', 'optix' ),
    'default' => 1,
  ] );
  register_number( $wp_customize, 'optix_header_styling', 'header_height', [
    'label'   => __( 'Header Height (px)', 'optix' ),
    'default' => 80,
  ] );

  $wp_customize->add_section( 'optix_topbar', [
    'title'    => __( 'Top Bar', 'optix' ),
    'panel'    => 'optix_header',
  ] );

  register_toggle( $wp_customize, 'optix_topbar', 'topbar_enable', [
    'label'   => __( 'Enable Top Bar', 'optix' ),
    'default' => 1,
  ] );
  register_text( $wp_customize, 'optix_topbar', 'topbar_sale_text', [
    'label'   => __( 'Sale Notification Text', 'optix' ),
    'default' => 'Summer sale discount off <span class="d-inline-block">60%</span> on all of your orders!',
  ] );
  register_color( $wp_customize, 'optix_topbar', 'topbar_bg', [
    'label'   => __( 'Top Bar Background', 'optix' ),
    'default' => '#222222',
  ] );
  register_color( $wp_customize, 'optix_topbar', 'topbar_text', [
    'label'   => __( 'Top Bar Text Color', 'optix' ),
    'default' => '#ffffff',
  ] );

  // ── 6. HOME PAGE PANEL ───────────────────────────────────────────
  $wp_customize->add_panel( 'optix_home_page', [
    'title'    => __( 'Home Page', 'optix' ),
    'priority' => 34,
  ] );

  // Hero / Banner
  $wp_customize->add_section( 'optix_home_banner', [
    'title'    => __( 'Hero Banner', 'optix' ),
    'panel'    => 'optix_home_page',
  ] );

  register_toggle( $wp_customize, 'optix_home_banner', 'home_banner_enable', [
    'label'   => __( 'Enable Banner Section', 'optix' ),
    'default' => 1,
  ] );
  register_text( $wp_customize, 'optix_home_banner', 'home_banner_heading', [
    'label'   => __( 'Banner Heading', 'optix' ),
    'default' => 'Claudia Kids Collection',
  ] );
  register_textarea( $wp_customize, 'optix_home_banner', 'home_banner_title', [
    'label'   => __( 'Banner Title', 'optix' ),
    'default' => 'Little Treasures, <br>Big Smiles!',
  ] );
  register_textarea( $wp_customize, 'optix_home_banner', 'home_banner_description', [
    'label'   => __( 'Banner Description', 'optix' ),
    'default' => 'Discover a world of fun and joy with our toys, clothes, and essentials that bring smiles.',
  ] );
  register_text( $wp_customize, 'optix_home_banner', 'home_banner_btn_text', [
    'label'   => __( 'Banner Button Text', 'optix' ),
    'default' => 'Shop Now',
  ] );
  register_text( $wp_customize, 'optix_home_banner', 'home_banner_btn_url', [
    'label'   => __( 'Banner Button URL', 'optix' ),
    'default' => '/shop/',
  ] );
  register_text( $wp_customize, 'optix_home_banner', 'home_banner_img1', [
    'label'   => __( 'Banner Image 1 Path', 'optix' ),
    'default' => '/banner-img1.png',
  ] );
  register_text( $wp_customize, 'optix_home_banner', 'home_banner_img2', [
    'label'   => __( 'Banner Image 2 Path', 'optix' ),
    'default' => '/banner-img2.png',
  ] );

  // Promotion
  $wp_customize->add_section( 'optix_home_promotion', [
    'title'    => __( 'Promotion', 'optix' ),
    'panel'    => 'optix_home_page',
  ] );

  register_toggle( $wp_customize, 'optix_home_promotion', 'home_promotion_enable', [
    'label'   => __( 'Enable Promotion Section', 'optix' ),
    'default' => 1,
  ] );

  // Products
  $wp_customize->add_section( 'optix_home_products', [
    'title'    => __( 'Products', 'optix' ),
    'panel'    => 'optix_home_page',
  ] );

  register_toggle( $wp_customize, 'optix_home_products', 'home_products_enable', [
    'label'   => __( 'Enable Products Section', 'optix' ),
    'default' => 1,
  ] );
  register_text( $wp_customize, 'optix_home_products', 'home_products_heading', [
    'label'   => __( 'Section Heading', 'optix' ),
    'default' => 'Our Collection',
  ] );
  register_text( $wp_customize, 'optix_home_products', 'home_products_title', [
    'label'   => __( 'Section Title', 'optix' ),
    'default' => 'Popular Products',
  ] );
  register_number( $wp_customize, 'optix_home_products', 'home_products_count', [
    'label'   => __( 'Number of Products', 'optix' ),
    'default' => 6,
  ] );
  register_text( $wp_customize, 'optix_home_products', 'home_products_btn_text', [
    'label'   => __( 'View All Button Text', 'optix' ),
    'default' => 'View All',
  ] );
  register_text( $wp_customize, 'optix_home_products', 'home_products_fallback_img', [
    'label'   => __( 'Fallback Product Image', 'optix' ),
    'default' => '/product-img1.png',
  ] );
  register_number( $wp_customize, 'optix_home_products', 'home_products_price_multiplier', [
    'label'   => __( 'Price Multiplier', 'optix' ),
    'default' => 1.3,
    'attrs'   => [ 'step' => 0.1, 'min' => 0.5, 'max' => 10 ],
  ] );

  // CTA Sale
  $wp_customize->add_section( 'optix_home_cta', [
    'title'    => __( 'CTA Sale', 'optix' ),
    'panel'    => 'optix_home_page',
  ] );

  register_toggle( $wp_customize, 'optix_home_cta', 'home_cta_enable', [
    'label'   => __( 'Enable CTA Sale Section', 'optix' ),
    'default' => 1,
  ] );
  register_text( $wp_customize, 'optix_home_cta', 'home_cta_title', [
    'label'   => __( 'CTA Title', 'optix' ),
    'default' => 'Mid Season Sale!',
  ] );
  register_text( $wp_customize, 'optix_home_cta', 'home_cta_subtitle', [
    'label'   => __( 'CTA Subtitle', 'optix' ),
    'default' => 'Get 20% Off on All New Arrivals!',
  ] );
  register_text( $wp_customize, 'optix_home_cta', 'home_cta_btn_text', [
    'label'   => __( 'CTA Button Text', 'optix' ),
    'default' => 'Get this Deal',
  ] );
  register_text( $wp_customize, 'optix_home_cta', 'home_cta_btn_url', [
    'label'   => __( 'CTA Button URL', 'optix' ),
    'default' => '/shop/',
  ] );

  // Categories
  $wp_customize->add_section( 'optix_home_categories', [
    'title'    => __( 'Categories', 'optix' ),
    'panel'    => 'optix_home_page',
  ] );

  register_toggle( $wp_customize, 'optix_home_categories', 'home_categories_enable', [
    'label'   => __( 'Enable Categories Section', 'optix' ),
    'default' => 1,
  ] );
  register_text( $wp_customize, 'optix_home_categories', 'home_categories_heading', [
    'label'   => __( 'Section Heading', 'optix' ),
    'default' => 'magna aliqua',
  ] );
  register_text( $wp_customize, 'optix_home_categories', 'home_categories_title', [
    'label'   => __( 'Section Title', 'optix' ),
    'default' => 'Product Categories',
  ] );

  // Top Selling
  $wp_customize->add_section( 'optix_home_top_selling', [
    'title'    => __( 'Top Selling', 'optix' ),
    'panel'    => 'optix_home_page',
  ] );

  register_toggle( $wp_customize, 'optix_home_top_selling', 'home_top_selling_enable', [
    'label'   => __( 'Enable Top Selling Section', 'optix' ),
    'default' => 1,
  ] );
  register_text( $wp_customize, 'optix_home_top_selling', 'home_top_selling_title', [
    'label'   => __( 'Section Title', 'optix' ),
    'default' => 'Top Selling Products',
  ] );
  register_number( $wp_customize, 'optix_home_top_selling', 'home_top_selling_count', [
    'label'   => __( 'Number of Products', 'optix' ),
    'default' => 4,
  ] );

  // Testimonials
  $wp_customize->add_section( 'optix_home_testimonials', [
    'title'    => __( 'Testimonials', 'optix' ),
    'panel'    => 'optix_home_page',
  ] );

  register_toggle( $wp_customize, 'optix_home_testimonials', 'home_testimonials_enable', [
    'label'   => __( 'Enable Testimonials Section', 'optix' ),
    'default' => 1,
  ] );
  register_text( $wp_customize, 'optix_home_testimonials', 'home_testimonials_heading', [
    'label'   => __( 'Section Heading', 'optix' ),
    'default' => 'Testimonials',
  ] );
  register_text( $wp_customize, 'optix_home_testimonials', 'home_testimonials_title', [
    'label'   => __( 'Section Title', 'optix' ),
    'default' => 'Our Client Reviews',
  ] );

  // Instagram
  $wp_customize->add_section( 'optix_home_instagram', [
    'title'    => __( 'Instagram', 'optix' ),
    'panel'    => 'optix_home_page',
  ] );

  register_toggle( $wp_customize, 'optix_home_instagram', 'home_instagram_enable', [
    'label'   => __( 'Enable Instagram Section', 'optix' ),
    'default' => 1,
  ] );
  register_text( $wp_customize, 'optix_home_instagram', 'home_instagram_heading', [
    'label'   => __( 'Section Heading', 'optix' ),
    'default' => '@claudia instagram',
  ] );
  register_text( $wp_customize, 'optix_home_instagram', 'home_instagram_title', [
    'label'   => __( 'Section Title', 'optix' ),
    'default' => 'Find us On Instagram',
  ] );

  // Benefits
  $wp_customize->add_section( 'optix_home_benefits', [
    'title'    => __( 'Benefits', 'optix' ),
    'panel'    => 'optix_home_page',
  ] );

  register_toggle( $wp_customize, 'optix_home_benefits', 'home_benefits_enable', [
    'label'   => __( 'Enable Benefits Section', 'optix' ),
    'default' => 1,
  ] );

  // ── 7. FOOTER PANEL ──────────────────────────────────────────────
  $wp_customize->add_panel( 'optix_footer', [
    'title'    => __( 'Footer', 'optix' ),
    'priority' => 35,
  ] );

  $wp_customize->add_section( 'optix_footer_main', [
    'title'    => __( 'Footer Layout', 'optix' ),
    'panel'    => 'optix_footer',
  ] );

  register_text( $wp_customize, 'optix_footer_main', 'footer_logo', [
    'label'   => __( 'Footer Logo Path', 'optix' ),
    'default' => '/footer-logo.png',
  ] );
  register_textarea( $wp_customize, 'optix_footer_main', 'footer_about_text', [
    'label'   => __( 'About Text', 'optix' ),
    'default' => 'Duis aute irure dolor in reprehenderit in voluptate velit cillum dolore eu fugiat nulla pariatur ccaecat cupidata proident, sunt in culpa officia deserunt mollit.',
  ] );
  register_text( $wp_customize, 'optix_footer_main', 'footer_copyright', [
    'label'   => __( 'Copyright Text', 'optix' ),
    'default' => 'Copyright (c) %d claudia.com All rights reserved.',
  ] );
  register_text( $wp_customize, 'optix_footer_main', 'footer_payment_cards', [
    'label'   => __( 'Payment Cards Image Path', 'optix' ),
    'default' => '/payment-cards.png',
  ] );
  register_text( $wp_customize, 'optix_footer_main', 'footer_phone', [
    'label'   => __( 'Phone Number', 'optix' ),
    'default' => '+1235 211 5236',
  ] );
  register_text( $wp_customize, 'optix_footer_main', 'footer_email', [
    'label'   => __( 'Email Address', 'optix' ),
    'default' => 'hello@claudia.com',
  ] );
  register_textarea( $wp_customize, 'optix_footer_main', 'footer_address', [
    'label'   => __( 'Physical Address', 'optix' ),
    'default' => '121 King Street Melbourne, <br>3000, Australia',
  ] );

  $wp_customize->add_section( 'optix_footer_styling', [
    'title'    => __( 'Footer Styling', 'optix' ),
    'panel'    => 'optix_footer',
  ] );

  register_color( $wp_customize, 'optix_footer_styling', 'footer_text', [
    'label'   => __( 'Footer Text Color', 'optix' ),
    'default' => '#999999',
  ] );
  register_color( $wp_customize, 'optix_footer_styling', 'footer_heading_text', [
    'label'   => __( 'Footer Heading Color', 'optix' ),
    'default' => '#ffffff',
  ] );
  register_color( $wp_customize, 'optix_footer_styling', 'footer_link', [
    'label'   => __( 'Footer Link Color', 'optix' ),
    'default' => '#cccccc',
  ] );

  // ── 8. BLOG PANEL ────────────────────────────────────────────────
  $wp_customize->add_panel( 'optix_blog', [
    'title'    => __( 'Blog', 'optix' ),
    'priority' => 36,
  ] );

  $wp_customize->add_section( 'optix_blog_main', [
    'title'    => __( 'Blog Settings', 'optix' ),
    'panel'    => 'optix_blog',
  ] );

  register_toggle( $wp_customize, 'optix_blog_main', 'blog_enable', [
    'label'   => __( 'Enable Blog Section', 'optix' ),
    'default' => 1,
  ] );
  register_text( $wp_customize, 'optix_blog_main', 'blog_title', [
    'label'   => __( 'Blog Page Title', 'optix' ),
    'default' => 'Blog',
  ] );
  register_number( $wp_customize, 'optix_blog_main', 'blog_posts_per_page', [
    'label'   => __( 'Posts Per Page', 'optix' ),
    'default' => 6,
  ] );

  // ── 9. SHOP PANEL (WooCommerce) ──────────────────────────────────
  $wp_customize->add_panel( 'optix_shop', [
    'title'    => __( 'Shop', 'optix' ),
    'priority' => 37,
  ] );

  $wp_customize->add_section( 'optix_shop_main', [
    'title'    => __( 'Shop / Catalog', 'optix' ),
    'panel'    => 'optix_shop',
  ] );

  register_text( $wp_customize, 'optix_shop_main', 'shop_title', [
    'label'   => __( 'Shop Page Title', 'optix' ),
    'default' => 'Shop',
  ] );
  register_number( $wp_customize, 'optix_shop_main', 'shop_products_per_page', [
    'label'   => __( 'Products Per Page', 'optix' ),
    'default' => 12,
  ] );
  register_number( $wp_customize, 'optix_shop_main', 'shop_columns', [
    'label'   => __( 'Grid Columns', 'optix' ),
    'default' => 3,
    'attrs'   => [ 'min' => 2, 'max' => 6 ],
  ] );
  register_toggle( $wp_customize, 'optix_shop_main', 'shop_enable_sidebar', [
    'label'   => __( 'Enable Shop Sidebar', 'optix' ),
    'default' => 1,
  ] );

  $wp_customize->add_section( 'optix_product_detail', [
    'title'    => __( 'Single Product', 'optix' ),
    'panel'    => 'optix_shop',
  ] );

  register_text( $wp_customize, 'optix_product_detail', 'product_related_title', [
    'label'   => __( 'Related Products Title', 'optix' ),
    'default' => 'Related Products',
  ] );
  register_number( $wp_customize, 'optix_product_detail', 'product_related_count', [
    'label'   => __( 'Number of Related Products', 'optix' ),
    'default' => 12,
  ] );

  $wp_customize->add_section( 'optix_cart_checkout', [
    'title'    => __( 'Cart & Checkout', 'optix' ),
    'panel'    => 'optix_shop',
  ] );

  register_text( $wp_customize, 'optix_cart_checkout', 'cart_title', [
    'label'   => __( 'Cart Page Title', 'optix' ),
    'default' => 'Cart',
  ] );
  register_text( $wp_customize, 'optix_cart_checkout', 'checkout_title', [
    'label'   => __( 'Checkout Page Title', 'optix' ),
    'default' => 'Checkout',
  ] );

  // ── 10. PAGES PANEL ──────────────────────────────────────────────
  $wp_customize->add_panel( 'optix_pages', [
    'title'    => __( 'Pages', 'optix' ),
    'priority' => 38,
  ] );

  $wp_customize->add_section( 'optix_about_page', [
    'title'    => __( 'About Page', 'optix' ),
    'panel'    => 'optix_pages',
  ] );

  register_toggle( $wp_customize, 'optix_about_page', 'about_about_enable', [
    'label'   => __( 'Enable About Us Section', 'optix' ),
    'default' => 1,
  ] );
  register_text( $wp_customize, 'optix_about_page', 'about_about_heading', [
    'label'   => __( 'About Us Heading', 'optix' ),
    'default' => 'About Us',
  ] );
  register_text( $wp_customize, 'optix_about_page', 'about_about_title', [
    'label'   => __( 'About Us Title', 'optix' ),
    'default' => 'Unqiue clothes & Toys For Kids',
  ] );
  register_textarea( $wp_customize, 'optix_about_page', 'about_about_text_1', [
    'label'   => __( 'About Text (Paragraph 1)', 'optix' ),
    'default' => 'At Claudia Kids, we believe every child\'s world is full of wonder and imagination.',
  ] );
  register_textarea( $wp_customize, 'optix_about_page', 'about_about_text_2', [
    'label'   => __( 'About Text (Paragraph 2)', 'optix' ),
    'default' => 'Our passion lies in designing and curating playful, high-quality pieces.',
  ] );
  register_text( $wp_customize, 'optix_about_page', 'about_about_image', [
    'label'   => __( 'About Us Image Path', 'optix' ),
    'default' => '/about-us-img.jpg',
  ] );
  register_text( $wp_customize, 'optix_about_page', 'about_about_btn_text', [
    'label'   => __( 'About Button Text', 'optix' ),
    'default' => 'Read More',
  ] );
  register_text( $wp_customize, 'optix_about_page', 'about_about_btn_url', [
    'label'   => __( 'About Button URL', 'optix' ),
    'default' => '/shop/',
  ] );
  register_toggle( $wp_customize, 'optix_about_page', 'about_mission_enable', [
    'label'   => __( 'Enable Mission Section', 'optix' ),
    'default' => 1,
  ] );
  register_toggle( $wp_customize, 'optix_about_page', 'about_team_enable', [
    'label'   => __( 'Enable Team Section', 'optix' ),
    'default' => 1,
  ] );

  $wp_customize->add_section( 'optix_contact_page', [
    'title'    => __( 'Contact Page', 'optix' ),
    'panel'    => 'optix_pages',
  ] );

  register_textarea( $wp_customize, 'optix_contact_page', 'contact_map_embed', [
    'label'   => __( 'Google Maps Embed URL', 'optix' ),
    'default' => '',
  ] );
  register_text( $wp_customize, 'optix_contact_page', 'contact_form_heading', [
    'label'   => __( 'Form Section Heading', 'optix' ),
    'default' => 'Get in Touch',
  ] );
  register_text( $wp_customize, 'optix_contact_page', 'contact_form_title', [
    'label'   => __( 'Form Section Title', 'optix' ),
    'default' => 'Send Us a Message',
  ] );
  register_text( $wp_customize, 'optix_contact_page', 'contact_form_btn_text', [
    'label'   => __( 'Submit Button Text', 'optix' ),
    'default' => 'Send Now',
  ] );
  register_text( $wp_customize, 'optix_contact_page', 'contact_info_heading', [
    'label'   => __( 'Info Section Heading', 'optix' ),
    'default' => 'Contact Info',
  ] );
  register_text( $wp_customize, 'optix_contact_page', 'contact_info_title', [
    'label'   => __( 'Info Section Title', 'optix' ),
    'default' => 'Our Information',
  ] );

  $wp_customize->add_section( 'optix_pages_other', [
    'title'    => __( 'Other Pages', 'optix' ),
    'panel'    => 'optix_pages',
  ] );

  register_toggle( $wp_customize, 'optix_pages_other', 'faq_enable', [
    'label'   => __( 'Enable FAQ Page', 'optix' ),
    'default' => 1,
  ] );

  // ── 11. 404 PAGE PANEL ───────────────────────────────────────────
  $wp_customize->add_panel( 'optix_404_page', [
    'title'    => __( '404 Page', 'optix' ),
    'priority' => 39,
  ] );

  $wp_customize->add_section( 'optix_404_main', [
    'title'    => __( '404 Settings', 'optix' ),
    'panel'    => 'optix_404_page',
  ] );

  register_text( $wp_customize, 'optix_404_main', '404_title', [
    'label'   => __( '404 Title', 'optix' ),
    'default' => 'We Could Not Find The Page You\'re Looking For',
  ] );
  register_textarea( $wp_customize, 'optix_404_main', '404_description', [
    'label'   => __( '404 Description', 'optix' ),
    'default' => 'The link you\'re trying to access is probably broken, or the page has been removed.',
  ] );
  register_text( $wp_customize, 'optix_404_main', '404_btn_text', [
    'label'   => __( 'Button Text', 'optix' ),
    'default' => 'Back to Homepage',
  ] );

  // ── 12. COMING SOON PANEL ────────────────────────────────────────
  $wp_customize->add_panel( 'optix_coming_soon', [
    'title'    => __( 'Coming Soon', 'optix' ),
    'priority' => 40,
  ] );

  $wp_customize->add_section( 'optix_coming_soon_main', [
    'title'    => __( 'Coming Soon Settings', 'optix' ),
    'panel'    => 'optix_coming_soon',
  ] );

  register_text( $wp_customize, 'optix_coming_soon_main', 'coming_soon_logo', [
    'label'   => __( 'Logo Path', 'optix' ),
    'default' => '/large-logo.png',
  ] );
  register_text( $wp_customize, 'optix_coming_soon_main', 'coming_soon_subtitle', [
    'label'   => __( 'Subtitle', 'optix' ),
    'default' => 'Our Website is under construction',
  ] );
  register_text( $wp_customize, 'optix_coming_soon_main', 'coming_soon_title', [
    'label'   => __( 'Title', 'optix' ),
    'default' => 'Coming Soon',
  ] );
  register_text( $wp_customize, 'optix_coming_soon_main', 'coming_soon_date', [
    'label'   => __( 'Launch Date', 'optix' ),
    'default' => '',
    'type'    => 'date',
  ] );

  // ── 13. NEWSLETTER PANEL ─────────────────────────────────────────
  $wp_customize->add_panel( 'optix_newsletter', [
    'title'    => __( 'Newsletter', 'optix' ),
    'priority' => 41,
  ] );

  $wp_customize->add_section( 'optix_newsletter_main', [
    'title'    => __( 'Newsletter Settings', 'optix' ),
    'panel'    => 'optix_newsletter',
  ] );

  register_text( $wp_customize, 'optix_newsletter_main', 'newsletter_heading', [
    'label'   => __( 'Heading Text', 'optix' ),
    'default' => 'Subscribe to Our Newsletter :',
  ] );
  register_text( $wp_customize, 'optix_newsletter_main', 'newsletter_placeholder', [
    'label'   => __( 'Input Placeholder', 'optix' ),
    'default' => 'Enter Your Email Address:',
  ] );
  register_text( $wp_customize, 'optix_newsletter_main', 'newsletter_btn_text', [
    'label'   => __( 'Button Text', 'optix' ),
    'default' => 'Subscribe',
  ] );

  // ── 14. SOCIAL MEDIA PANEL ───────────────────────────────────────
  $wp_customize->add_panel( 'optix_social', [
    'title'    => __( 'Social Media', 'optix' ),
    'priority' => 42,
  ] );

  $wp_customize->add_section( 'optix_social_main', [
    'title'    => __( 'Social Links', 'optix' ),
    'panel'    => 'optix_social',
  ] );

  register_text( $wp_customize, 'optix_social_main', 'social_facebook', [
    'label'   => __( 'Facebook URL', 'optix' ),
    'default' => 'https://www.facebook.com/',
  ] );
  register_text( $wp_customize, 'optix_social_main', 'social_instagram', [
    'label'   => __( 'Instagram URL', 'optix' ),
    'default' => 'https://www.instagram.com/',
  ] );
  register_text( $wp_customize, 'optix_social_main', 'social_youtube', [
    'label'   => __( 'YouTube URL', 'optix' ),
    'default' => 'https://www.youtube.com/',
  ] );
  register_text( $wp_customize, 'optix_social_main', 'social_twitter', [
    'label'   => __( 'Twitter / X URL', 'optix' ),
    'default' => 'https://twitter.com/',
  ] );
  register_text( $wp_customize, 'optix_social_main', 'social_pinterest', [
    'label'   => __( 'Pinterest URL', 'optix' ),
    'default' => '',
  ] );
  register_text( $wp_customize, 'optix_social_main', 'social_tiktok', [
    'label'   => __( 'TikTok URL', 'optix' ),
    'default' => '',
  ] );

  // ── 15. ANIMATIONS PANEL ─────────────────────────────────────────
  $wp_customize->add_panel( 'optix_animations', [
    'title'    => __( 'Animations', 'optix' ),
    'priority' => 43,
  ] );

  $wp_customize->add_section( 'optix_animations_main', [
    'title'    => __( 'Animation Settings', 'optix' ),
    'panel'    => 'optix_animations',
  ] );

  register_toggle( $wp_customize, 'optix_animations_main', 'animations_enable', [
    'label'   => __( 'Enable Animations', 'optix' ),
    'default' => 1,
  ] );
  register_select( $wp_customize, 'optix_animations_main', 'animations_duration', [
    'label'   => __( 'Animation Duration', 'optix' ),
    'default' => '2s',
    'choices' => [
      '0.5s' => '0.5s (Fast)',
      '1s'   => '1s (Normal)',
      '2s'   => '2s (Slow)',
      '3s'   => '3s (Very Slow)',
    ],
  ] );
  register_text( $wp_customize, 'optix_animations_main', 'animations_delay', [
    'label'   => __( 'Animation Delay', 'optix' ),
    'default' => '0.05s',
  ] );

  // ── 16. PERFORMANCE PANEL ────────────────────────────────────────
  $wp_customize->add_panel( 'optix_performance', [
    'title'    => __( 'Performance', 'optix' ),
    'priority' => 44,
  ] );

  $wp_customize->add_section( 'optix_performance_main', [
    'title'    => __( 'Performance Settings', 'optix' ),
    'panel'    => 'optix_performance',
  ] );

  register_toggle( $wp_customize, 'optix_performance_main', 'performance_lazy_load', [
    'label'   => __( 'Lazy Load Images', 'optix' ),
    'default' => 1,
  ] );
  register_toggle( $wp_customize, 'optix_performance_main', 'performance_minify_css', [
    'label'   => __( 'Minify CSS', 'optix' ),
    'default' => 0,
  ] );
  register_toggle( $wp_customize, 'optix_performance_main', 'performance_minify_js', [
    'label'   => __( 'Minify JavaScript', 'optix' ),
    'default' => 0,
  ] );

  // ── 17. GDPR / COOKIE CONSENT PANEL ──────────────────────────────
  $wp_customize->add_panel( 'optix_cookie', [
    'title'    => __( 'Cookie Consent', 'optix' ),
    'priority' => 45,
  ] );

  $wp_customize->add_section( 'optix_cookie_main', [
    'title'    => __( 'Cookie / GDPR Settings', 'optix' ),
    'panel'    => 'optix_cookie',
  ] );

  register_toggle( $wp_customize, 'optix_cookie_main', 'cookie_enable', [
    'label'   => __( 'Enable Cookie Consent Bar', 'optix' ),
    'default' => 0,
  ] );
  register_text( $wp_customize, 'optix_cookie_main', 'cookie_message', [
    'label'   => __( 'Cookie Message', 'optix' ),
    'default' => 'This website uses cookies to improve your experience.',
  ] );
  register_text( $wp_customize, 'optix_cookie_main', 'cookie_accept_text', [
    'label'   => __( 'Accept Button Text', 'optix' ),
    'default' => 'Accept',
  ] );
  register_text( $wp_customize, 'optix_cookie_main', 'cookie_decline_text', [
    'label'   => __( 'Decline Button Text', 'optix' ),
    'default' => 'Decline',
  ] );
  register_text( $wp_customize, 'optix_cookie_main', 'cookie_policy_link', [
    'label'   => __( 'Privacy Policy URL', 'optix' ),
    'default' => '/privacy-policy/',
  ] );

  // ── 18. IMPORT / EXPORT PANEL ────────────────────────────────────
  // This is best kept as an admin page, but we add a section to reference it.
  $wp_customize->add_panel( 'optix_import_export', [
    'title'    => __( 'Import / Export', 'optix' ),
    'priority' => 46,
  ] );

  $wp_customize->add_section( 'optix_import_export_main', [
    'title'    => __( 'Import / Export Settings', 'optix' ),
    'panel'    => 'optix_import_export',
  ] );

  // ── 19. BRANDING PANEL ─────────────────────────────────────────────
  $wp_customize->add_panel( 'optix_branding', [
    'title'    => __( 'Branding', 'optix' ),
    'priority' => 47,
  ] );

  $wp_customize->add_section( 'optix_branding_main', [
    'title'    => __( 'Logo Settings', 'optix' ),
    'panel'    => 'optix_branding',
  ] );

  register_text( $wp_customize, 'optix_branding_main', 'site_logo', [
    'label'   => __( 'Site Logo Path', 'optix' ),
    'default' => '',
  ] );
  register_text( $wp_customize, 'optix_branding_main', 'favicon', [
    'label'   => __( 'Favicon Path', 'optix' ),
    'default' => '',
  ] );
  register_text( $wp_customize, 'optix_branding_main', 'site_icon', [
    'label'   => __( 'Site Icon Path', 'optix' ),
    'default' => '',
  ] );

  // ── 20. SEARCH PANEL ───────────────────────────────────────────────
  $wp_customize->add_panel( 'optix_search', [
    'title'    => __( 'Search', 'optix' ),
    'priority' => 48,
  ] );

  $wp_customize->add_section( 'optix_search_main', [
    'title'    => __( 'Search Settings', 'optix' ),
    'panel'    => 'optix_search',
  ] );

  register_text( $wp_customize, 'optix_search_main', 'search_placeholder', [
    'label'   => __( 'Search Placeholder', 'optix' ),
    'default' => 'Search products...',
  ] );
  register_text( $wp_customize, 'optix_search_main', 'search_results_label', [
    'label'   => __( 'Search Results Label', 'optix' ),
    'default' => 'Results',
  ] );
  register_text( $wp_customize, 'optix_search_main', 'search_btn_text', [
    'label'   => __( 'Search Button Text', 'optix' ),
    'default' => 'Search',
  ] );

  // ── 21. PRODUCT CARDS PANEL ────────────────────────────────────────
  $wp_customize->add_panel( 'optix_product_cards', [
    'title'    => __( 'Product Cards', 'optix' ),
    'priority' => 49,
  ] );

  $wp_customize->add_section( 'optix_product_cards_main', [
    'title'    => __( 'Card Styling', 'optix' ),
    'panel'    => 'optix_product_cards',
  ] );

  register_number( $wp_customize, 'optix_product_cards_main', 'card_border_radius', [
    'label'   => __( 'Card Border Radius (px)', 'optix' ),
    'default' => 0,
  ] );
  register_toggle( $wp_customize, 'optix_product_cards_main', 'card_shadow_enable', [
    'label'   => __( 'Enable Card Shadow', 'optix' ),
    'default' => 0,
  ] );
  register_select( $wp_customize, 'optix_product_cards_main', 'card_hover_effect', [
    'label'   => __( 'Card Hover Effect', 'optix' ),
    'default' => 'none',
    'choices' => [
      'none'   => __( 'None', 'optix' ),
      'lift'   => __( 'Lift', 'optix' ),
      'shadow' => __( 'Shadow', 'optix' ),
      'border' => __( 'Border', 'optix' ),
    ],
  ] );
  register_select( $wp_customize, 'optix_product_cards_main', 'card_image_ratio', [
    'label'   => __( 'Card Image Ratio', 'optix' ),
    'default' => '1:1',
    'choices' => [
      '1:1'  => '1:1',
      '4:3'  => '4:3',
      '3:2'  => '3:2',
      '16:9' => '16:9',
    ],
  ] );
  register_text( $wp_customize, 'optix_product_cards_main', 'card_sale_badge_text', [
    'label'   => __( 'Sale Badge Text', 'optix' ),
    'default' => 'Sale!',
  ] );
  register_text( $wp_customize, 'optix_product_cards_main', 'card_new_badge_text', [
    'label'   => __( 'New Badge Text', 'optix' ),
    'default' => 'New',
  ] );
  register_toggle( $wp_customize, 'optix_product_cards_main', 'card_quick_view_enable', [
    'label'   => __( 'Enable Quick View Button', 'optix' ),
    'default' => 0,
  ] );
  register_toggle( $wp_customize, 'optix_product_cards_main', 'card_wishlist_enable', [
    'label'   => __( 'Enable Wishlist Button', 'optix' ),
    'default' => 0,
  ] );

  // ── 22. FORMS PANEL ────────────────────────────────────────────────
  $wp_customize->add_panel( 'optix_forms', [
    'title'    => __( 'Forms', 'optix' ),
    'priority' => 50,
  ] );

  $wp_customize->add_section( 'optix_forms_main', [
    'title'    => __( 'Form Styling', 'optix' ),
    'panel'    => 'optix_forms',
  ] );

  register_number( $wp_customize, 'optix_forms_main', 'form_input_height', [
    'label'   => __( 'Input Height (px)', 'optix' ),
    'default' => 50,
  ] );
  register_number( $wp_customize, 'optix_forms_main', 'form_input_radius', [
    'label'   => __( 'Input Border Radius (px)', 'optix' ),
    'default' => 4,
  ] );
  register_color( $wp_customize, 'optix_forms_main', 'form_input_border_color', [
    'label'   => __( 'Input Border Color', 'optix' ),
    'default' => '#e5e5e5',
  ] );
  register_color( $wp_customize, 'optix_forms_main', 'form_input_focus_color', [
    'label'   => __( 'Input Focus Border Color', 'optix' ),
    'default' => '#705b53',
  ] );
  register_select( $wp_customize, 'optix_forms_main', 'form_label_weight', [
    'label'   => __( 'Label Font Weight', 'optix' ),
    'default' => '500',
    'choices' => [
      '400' => 'Regular (400)',
      '500' => 'Medium (500)',
      '600' => 'Semi Bold (600)',
      '700' => 'Bold (700)',
    ],
  ] );

  // ── 23. LAYOUT PANEL ───────────────────────────────────────────────
  $wp_customize->add_panel( 'optix_layout', [
    'title'    => __( 'Layout', 'optix' ),
    'priority' => 51,
  ] );

  $wp_customize->add_section( 'optix_layout_container', [
    'title'    => __( 'Container', 'optix' ),
    'panel'    => 'optix_layout',
  ] );

  register_number( $wp_customize, 'optix_layout_container', 'container_width', [
    'label'   => __( 'Container Width (px)', 'optix' ),
    'default' => 1200,
  ] );
  register_number( $wp_customize, 'optix_layout_container', 'container_gutter', [
    'label'   => __( 'Container Gutter (px)', 'optix' ),
    'default' => 15,
  ] );

  $wp_customize->add_section( 'optix_layout_sidebar', [
    'title'    => __( 'Sidebar', 'optix' ),
    'panel'    => 'optix_layout',
  ] );

  register_number( $wp_customize, 'optix_layout_sidebar', 'sidebar_width', [
    'label'   => __( 'Sidebar Width (px)', 'optix' ),
    'default' => 330,
  ] );
  register_select( $wp_customize, 'optix_layout_sidebar', 'sidebar_position', [
    'label'   => __( 'Sidebar Position', 'optix' ),
    'default' => 'right',
    'choices' => [
      'left'  => __( 'Left', 'optix' ),
      'right' => __( 'Right', 'optix' ),
      'none'  => __( 'None', 'optix' ),
    ],
  ] );

  // ── 24. RESPONSIVE PANEL ───────────────────────────────────────────
  $wp_customize->add_panel( 'optix_responsive', [
    'title'    => __( 'Responsive', 'optix' ),
    'priority' => 52,
  ] );

  $wp_customize->add_section( 'optix_responsive_desktop', [
    'title'    => __( 'Desktop (min 1200px)', 'optix' ),
    'panel'    => 'optix_responsive',
  ] );

  register_number( $wp_customize, 'optix_responsive_desktop', 'desktop_columns', [
    'label'   => __( 'Desktop Columns', 'optix' ),
    'default' => 4,
  ] );

  $wp_customize->add_section( 'optix_responsive_tablet', [
    'title'    => __( 'Tablet (768-1199px)', 'optix' ),
    'panel'    => 'optix_responsive',
  ] );

  register_number( $wp_customize, 'optix_responsive_tablet', 'tablet_columns', [
    'label'   => __( 'Tablet Columns', 'optix' ),
    'default' => 3,
  ] );
  register_number( $wp_customize, 'optix_responsive_tablet', 'tablet_breakpoint', [
    'label'   => __( 'Tablet Breakpoint (px)', 'optix' ),
    'default' => 992,
  ] );

  $wp_customize->add_section( 'optix_responsive_mobile', [
    'title'    => __( 'Mobile (<768px)', 'optix' ),
    'panel'    => 'optix_responsive',
  ] );

  register_number( $wp_customize, 'optix_responsive_mobile', 'mobile_columns', [
    'label'   => __( 'Mobile Columns', 'optix' ),
    'default' => 2,
  ] );
  register_number( $wp_customize, 'optix_responsive_mobile', 'mobile_breakpoint', [
    'label'   => __( 'Mobile Breakpoint (px)', 'optix' ),
    'default' => 768,
  ] );

  // ── 25. SEO PANEL ──────────────────────────────────────────────────
  $wp_customize->add_panel( 'optix_seo', [
    'title'    => __( 'SEO', 'optix' ),
    'priority' => 53,
  ] );

  $wp_customize->add_section( 'optix_seo_meta', [
    'title'    => __( 'Meta Tags', 'optix' ),
    'panel'    => 'optix_seo',
  ] );

  register_textarea( $wp_customize, 'optix_seo_meta', 'seo_meta_description', [
    'label'   => __( 'Meta Description', 'optix' ),
    'default' => '',
  ] );
  register_text( $wp_customize, 'optix_seo_meta', 'seo_meta_keywords', [
    'label'   => __( 'Meta Keywords', 'optix' ),
    'default' => '',
  ] );
  register_text( $wp_customize, 'optix_seo_meta', 'seo_og_title', [
    'label'   => __( 'Open Graph Title', 'optix' ),
    'default' => '',
  ] );
  register_textarea( $wp_customize, 'optix_seo_meta', 'seo_og_description', [
    'label'   => __( 'Open Graph Description', 'optix' ),
    'default' => '',
  ] );
  register_text( $wp_customize, 'optix_seo_meta', 'seo_og_image', [
    'label'   => __( 'Open Graph Image Path', 'optix' ),
    'default' => '',
  ] );

  $wp_customize->add_section( 'optix_seo_schema', [
    'title'    => __( 'Schema', 'optix' ),
    'panel'    => 'optix_seo',
  ] );

  register_select( $wp_customize, 'optix_seo_schema', 'seo_schema_type', [
    'label'   => __( 'Schema.org Type', 'optix' ),
    'default' => 'Organization',
    'choices' => [
      'Organization' => __( 'Organization', 'optix' ),
      'WebSite'      => __( 'WebSite', 'optix' ),
      'Product'      => __( 'Product', 'optix' ),
      'LocalBusiness' => __( 'Local Business', 'optix' ),
    ],
  ] );
  register_text( $wp_customize, 'optix_seo_schema', 'seo_schema_name', [
    'label'   => __( 'Schema Name', 'optix' ),
    'default' => '',
  ] );

  // ── 26. INTEGRATIONS PANEL ─────────────────────────────────────────
  $wp_customize->add_panel( 'optix_integrations', [
    'title'    => __( 'Integrations', 'optix' ),
    'priority' => 54,
  ] );

  $wp_customize->add_section( 'optix_integrations_google', [
    'title'    => __( 'Google', 'optix' ),
    'panel'    => 'optix_integrations',
  ] );

  register_text( $wp_customize, 'optix_integrations_google', 'google_analytics_id', [
    'label'   => __( 'Google Analytics ID', 'optix' ),
    'default' => '',
  ] );
  register_text( $wp_customize, 'optix_integrations_google', 'google_tag_manager', [
    'label'   => __( 'Google Tag Manager ID', 'optix' ),
    'default' => '',
  ] );
  register_text( $wp_customize, 'optix_integrations_google', 'google_maps_api', [
    'label'   => __( 'Google Maps API Key', 'optix' ),
    'default' => '',
  ] );

  $wp_customize->add_section( 'optix_integrations_social', [
    'title'    => __( 'Social', 'optix' ),
    'panel'    => 'optix_integrations',
  ] );

  register_text( $wp_customize, 'optix_integrations_social', 'facebook_pixel', [
    'label'   => __( 'Facebook Pixel ID', 'optix' ),
    'default' => '',
  ] );
  register_text( $wp_customize, 'optix_integrations_social', 'facebook_app_id', [
    'label'   => __( 'Facebook App ID', 'optix' ),
    'default' => '',
  ] );
  register_text( $wp_customize, 'optix_integrations_social', 'instagram_token', [
    'label'   => __( 'Instagram Access Token', 'optix' ),
    'default' => '',
  ] );

  // ── 27. BACKUP / RESTORE PANEL ─────────────────────────────────────
  $wp_customize->add_panel( 'optix_backup', [
    'title'    => __( 'Backup / Restore', 'optix' ),
    'priority' => 55,
  ] );

  $wp_customize->add_section( 'optix_backup_main', [
    'title'    => __( 'Settings Backup', 'optix' ),
    'panel'    => 'optix_backup',
  ] );

  $wp_customize->add_setting( 'backup_export_info', [
    'type'              => 'option',
    'default'           => '',
    'transport'         => 'refresh',
    'sanitize_callback' => 'wp_kses_post',
  ] );
  $wp_customize->add_control( 'backup_export_info', [
    'label'    => __( 'Backup Info', 'optix' ),
    'section'  => 'optix_backup_main',
    'type'     => 'textarea',
    'settings' => 'backup_export_info',
    'description' => __( 'Use the Import / Export panel to manage settings backup.', 'optix' ),
  ] );
  register_toggle( $wp_customize, 'optix_backup_main', 'backup_auto', [
    'label'   => __( 'Enable Automatic Backup', 'optix' ),
    'default' => 0,
  ] );

  // ── Restore Defaults Section ───────────────────────────────────────
  $wp_customize->add_section( 'optix_restore_section', [
    'title'    => __( 'Restore Defaults', 'optix' ),
    'panel'    => 'optix_backup',
  ] );

  $wp_customize->add_setting( 'optix_restore_trigger', [
    'type'              => 'option',
    'default'           => '',
    'transport'         => 'refresh',
    'sanitize_callback' => 'wp_kses_post',
  ] );

  class Optix_Restore_Control extends \WP_Customize_Control {
    public $type = 'button';

    public function render_content() {
      $nonce = wp_create_nonce( 'optix_restore_nonce' );
      ?>
      <label>
        <span class="customize-control-title"><?php esc_html_e( 'Reset Theme Options', 'optix' ); ?></span>
        <span class="description customize-control-description"><?php esc_html_e( 'Reset all theme options to their default values. This cannot be undone.', 'optix' ); ?></span>
      </label>
      <button type="button" class="button button-secondary" id="optix-restore-btn" style="margin-top:8px;background:#dc3232;color:#fff;border-color:#dc3232;">
        <?php esc_html_e( 'Restore Defaults', 'optix' ); ?>
      </button>
      <span id="optix-restore-msg" style="display:block;margin-top:6px;font-size:12px;"></span>
      <script>
      (function(){
        var btn=document.getElementById('optix-restore-btn');
        if(!btn)return;
        btn.onclick=function(){
          if(!confirm('<?php echo esc_js( __( 'Are you sure? This will reset all theme options.', 'optix' ) ); ?>'))return;
          btn.disabled=true;btn.textContent='<?php echo esc_js( __( 'Restoring...', 'optix' ) ); ?>';
          var d=new FormData();
          d.append('action','optix_restore_defaults');
          d.append('optix_restore_nonce','<?php echo esc_js( $nonce ); ?>');
          fetch(ajaxurl,{method:'POST',body:d}).then(function(r){return r.json();}).then(function(j){
            var msg=document.getElementById('optix-restore-msg');
            msg.style.color=j.status==='Success'?'#46b450':'#dc3232';
            msg.textContent=j.msg;
            btn.disabled=false;btn.textContent='<?php echo esc_js( __( 'Restore Defaults', 'optix' ) ); ?>';
            if(j.status==='Success'){setTimeout(function(){location.reload();},1500);}
          });
        };
      })();
      </script>
      <?php
    }
  }

  $wp_customize->add_control( new Optix_Restore_Control( $wp_customize, 'optix_restore_trigger', [
    'section'  => 'optix_restore_section',
    'settings' => 'optix_restore_trigger',
  ] ) );

  // ── 28. 3D EFFECTS PANEL ───────────────────────────────────────────
  $wp_customize->add_panel( 'optix_3d', [
    'title'    => __( '3D Effects', 'optix' ),
    'priority' => 56,
  ] );

  $wp_customize->add_section( 'optix_3d_main', [
    'title'    => __( '3D Settings', 'optix' ),
    'panel'    => 'optix_3d',
  ] );

  register_toggle( $wp_customize, 'optix_3d_main', 'enable_3d', [
    'label'   => __( 'Enable 3D Effects', 'optix' ),
    'default' => 0,
  ] );
  register_number( $wp_customize, 'optix_3d_main', '3d_intensity', [
    'label'   => __( '3D Intensity (%)', 'optix' ),
    'default' => 10,
    'attrs'   => [ 'step' => 1, 'min' => 1, 'max' => 100 ],
  ] );
  register_number( $wp_customize, 'optix_3d_main', '3d_perspective', [
    'label'   => __( '3D Perspective (px)', 'optix' ),
    'default' => 1000,
  ] );
  register_toggle( $wp_customize, 'optix_3d_main', '3d_easing_enable', [
    'label'   => __( 'Enable Smooth Easing', 'optix' ),
    'default' => 0,
  ] );
}

// ── HELPER: Register a color control ───────────────────────────────
function register_color( $wp_customize, string $section, string $key, array $cfg ) {
  $wp_customize->add_setting( $key, [
    'type'              => 'option',
    'default'           => $cfg['default'],
    'transport'         => 'postMessage',
    'sanitize_callback' => 'sanitize_hex_color',
  ] );
  $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, $key, [
    'label'    => $cfg['label'],
    'section'  => $section,
    'settings' => $key,
  ] ) );
}

// ── HELPER: Register a text control ────────────────────────────────
function register_text( $wp_customize, string $section, string $key, array $cfg ) {
  $wp_customize->add_setting( $key, [
    'type'              => 'option',
    'default'           => $cfg['default'],
    'transport'         => $cfg['transport'] ?? 'refresh',
    'sanitize_callback' => 'sanitize_text_field',
  ] );
  $wp_customize->add_control( $key, [
    'label'    => $cfg['label'],
    'section'  => $section,
    'type'     => $cfg['type'] ?? 'text',
    'settings' => $key,
  ] );
}

// ── HELPER: Register a number control ──────────────────────────────
function register_number( $wp_customize, string $section, string $key, array $cfg ) {
  $attrs = $cfg['attrs'] ?? [];
  $input_attrs = [];
  if ( isset( $attrs['min'] ) )  $input_attrs['min']  = $attrs['min'];
  if ( isset( $attrs['max'] ) )  $input_attrs['max']  = $attrs['max'];
  if ( isset( $attrs['step'] ) ) $input_attrs['step'] = $attrs['step'];

  $wp_customize->add_setting( $key, [
    'type'              => 'option',
    'default'           => $cfg['default'],
    'transport'         => 'postMessage',
    'sanitize_callback' => 'floatval',
  ] );
  $wp_customize->add_control( $key, [
    'label'       => $cfg['label'],
    'section'     => $section,
    'type'        => 'number',
    'settings'    => $key,
    'input_attrs' => $input_attrs,
  ] );
}

// ── HELPER: Register a select/dropdown control ─────────────────────
function register_select( $wp_customize, string $section, string $key, array $cfg ) {
  $wp_customize->add_setting( $key, [
    'type'              => 'option',
    'default'           => $cfg['default'],
    'transport'         => $cfg['transport'] ?? 'refresh',
    'sanitize_callback' => 'sanitize_text_field',
  ] );
  $wp_customize->add_control( $key, [
    'label'    => $cfg['label'],
    'section'  => $section,
    'type'     => 'select',
    'choices'  => $cfg['choices'],
    'settings' => $key,
  ] );
}

// ── HELPER: Register a textarea control ────────────────────────────
function register_textarea( $wp_customize, string $section, string $key, array $cfg ) {
  $wp_customize->add_setting( $key, [
    'type'              => 'option',
    'default'           => $cfg['default'],
    'transport'         => $cfg['transport'] ?? 'refresh',
    'sanitize_callback' => 'wp_kses_post',
  ] );
  $wp_customize->add_control( $key, [
    'label'    => $cfg['label'],
    'section'  => $section,
    'type'     => 'textarea',
    'settings' => $key,
  ] );
}

// ── HELPER: Register a checkbox/toggle control ─────────────────────
function register_toggle( $wp_customize, string $section, string $key, array $cfg ) {
  $wp_customize->add_setting( $key, [
    'type'              => 'option',
    'default'           => $cfg['default'],
    'transport'         => $cfg['transport'] ?? 'refresh',
    'sanitize_callback' => 'absint',
  ] );
  $wp_customize->add_control( $key, [
    'label'    => $cfg['label'],
    'section'  => $section,
    'type'     => 'checkbox',
    'settings' => $key,
  ] );
}

/**
 * Enqueue live preview JavaScript.
 */
add_action( 'customize_preview_init', __NAMESPACE__ . '\enqueue_customizer_preview' );
function enqueue_customizer_preview() {
  $preview_js = get_theme_file_path( 'assets/kids-collection/js/customizer-preview.js' );
  if ( ! file_exists( $preview_js ) ) {
    return;
  }

  wp_enqueue_script(
    'optix-customizer-preview',
    get_theme_file_uri( 'assets/kids-collection/js/customizer-preview.js' ),
    [ 'jquery', 'customize-preview' ],
    filemtime( $preview_js ),
    true
  );
}
