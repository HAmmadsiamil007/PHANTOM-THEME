<?php
/**
 * Optix Theme Options — ACF-powered admin panel.
 *
 * Registers 1 parent + 17 sub-pages for all Kids Collection settings.
 * Runs only when ACF Pro is active.
 *
 * @package optix
 */

namespace Optix;

/**
 * Theme Options class.
 */
class Theme_Options {
  const PARENT_SLUG = 'optix-theme-options';

  /**
   * Hook into ACF for field groups, use WordPress admin_menu for pages.
   */
  public function __construct() {
    add_action( 'acf/init', [ $this, 'register_field_groups' ] );
    add_action( 'admin_menu', [ $this, 'register_admin_pages' ] );
    add_action( 'admin_menu', [ $this, 'register_tools_page' ] );
    add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_styles' ] );
    add_action( 'admin_post_optix_export', [ $this, 'handle_export' ] );
    add_action( 'admin_post_optix_import', [ $this, 'handle_import' ] );
  }

  /**
   * Sub-page definitions used by both WordPress admin and ACF Pro.
   */
  public static function get_sub_pages(): array {
    return [
      'general'        => 'General',
      'header'         => 'Header',
      'topbar'         => 'Top Bar',
      'footer'         => 'Footer',
      'typography'     => 'Typography',
      'colors'         => 'Colors',
      'home-page'      => 'Home Page',
      'about-page'     => 'About Page',
      'blog'           => 'Blog',
      'contact'        => 'Contact',
      'shop'           => 'Shop',
      'product-detail' => 'Product Detail',
      'cart-checkout'  => 'Cart & Checkout',
      'pages'          => 'Other Pages',
      'animations'     => 'Animations',
      'advanced'       => 'Advanced',
      'import-export'  => 'Import / Export',
    ];
  }

  /**
   * Register admin pages using WordPress core (works without ACF Pro).
   */
  public function register_admin_pages() {
    if ( function_exists( 'acf_add_options_page' ) ) {
      return;
    }

    $icon = 'dashicons-admin-customizer';

    add_menu_page(
      'Optix Theme Options',
      'Optix Options',
      'manage_options',
      self::PARENT_SLUG,
      [ $this, 'render_admin_page' ],
      $icon,
      3
    );

    $sub_pages = self::get_sub_pages();
    foreach ( $sub_pages as $slug => $title ) {
      add_submenu_page(
        self::PARENT_SLUG,
        $title,
        $title,
        'manage_options',
        self::PARENT_SLUG . '-' . $slug,
        [ $this, 'render_admin_page' ]
      );
    }
  }

  /**
   * Enqueue admin styles for options pages.
   */
  public function enqueue_admin_styles( $hook ) {
    if ( strpos( $hook, 'optix-theme-options' ) === false ) {
      return;
    }
    wp_add_inline_style( 'forms', '
      .optix-options-wrap { max-width: 900px; }
      .optix-options-wrap h2 { margin: 2em 0 0.5em; padding-bottom: 0.5em; border-bottom: 1px solid #c3c4c7; }
      .optix-options-wrap .form-table th { width: 200px; }
      .optix-options-wrap input[type="text"],
      .optix-options-wrap input[type="url"],
      .optix-options-wrap input[type="number"],
      .optix-options-wrap textarea { width: 100%; max-width: 600px; }
      .optix-options-wrap textarea { min-height: 100px; }
      .optix-options-wrap .optix-field-row { margin-bottom: 1.5em; }
      .optix-options-wrap .optix-field-label { font-weight: 600; display: block; margin-bottom: 4px; }
      .optix-options-wrap .optix-field-desc { color: #666; font-style: italic; margin-top: 4px; font-size: 13px; }
      .optix-options-wrap .optix-image-preview { max-width: 150px; max-height: 150px; display: block; margin: 8px 0; }
      .optix-options-wrap .optix-saved { background: #d4edda; color: #155724; padding: 12px 16px; border-radius: 4px; margin: 12px 0; border: 1px solid #c3e6cb; }
    ' );
  }

  /**
   * Render a WordPress-native admin page for each section.
   */
  public function render_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
      wp_die( esc_html__( 'Unauthorized.', 'optix' ) );
    }

    $screen    = get_current_screen();
    $page_slug = str_replace( 'toplevel_page_optix-theme-options_page_', '', $screen->id );
    $page_slug = str_replace( 'admin_page_', '', $page_slug );

    $sub_pages = self::get_sub_pages();
    $slug      = 'general';
    $title     = 'General';
    foreach ( $sub_pages as $s => $t ) {
      if ( strpos( $page_slug, $s ) !== false ) {
        $slug  = $s;
        $title = $t;
        break;
      }
    }

    $prefix   = 'optix_' . $slug . '_';
    $saved    = false;

    if ( ! empty( $_POST['optix_save'] ) && check_admin_referer( 'optix_options_' . $slug ) ) {
      $this->save_options( $slug, $prefix );
      $saved = true;
    }

    $options = $this->get_options( $slug, $prefix );
    ?>
    <div class="wrap optix-options-wrap">
      <h1><?php echo esc_html( $title ); ?> Settings</h1>
      <?php if ( $saved ) : ?>
        <div class="optix-saved"><?php esc_html_e( 'Settings saved.', 'optix' ); ?></div>
      <?php endif; ?>
      <form method="post">
        <?php wp_nonce_field( 'optix_options_' . $slug ); ?>
        <table class="form-table">
          <?php $this->render_fields( $slug, $options, $prefix ); ?>
        </table>
        <p class="submit">
          <button type="submit" name="optix_save" class="button button-primary"><?php esc_html_e( 'Save Settings', 'optix' ); ?></button>
        </p>
      </form>
    </div>
    <?php
  }

  /**
   * Get saved options for a section.
   */
  private function get_options( string $slug, string $prefix ): array {
    $field_map = $this->get_field_map( $slug );
    $options   = [];
    foreach ( $field_map as $key => $field ) {
      $db_key         = $prefix . $key;
      $options[$key]  = get_option( $db_key, $field['default'] ?? '' );
    }
    return $options;
  }

  /**
   * Save options for a section.
   */
  private function save_options( string $slug, string $prefix ) {
    $field_map = $this->get_field_map( $slug );
    foreach ( $field_map as $key => $field ) {
      $db_key = $prefix . $key;
      if ( ( $field['type'] ?? '' ) === 'true_false' ) {
        $value = isset( $_POST[ $db_key ] ) ? 1 : 0;
      } else {
        $value = sanitize_text_field( wp_unslash( $_POST[ $db_key ] ?? '' ) );
      }
      update_option( $db_key, $value );
    }
  }

  /**
   * Render form fields for a section.
   */
  private function render_fields( string $slug, array $options, string $prefix ) {
    $field_map = $this->get_field_map( $slug );
    foreach ( $field_map as $key => $field ) {
      $db_key  = $prefix . $key;
      $value   = $options[ $key ] ?? $field['default'] ?? '';
      $label   = $field['label'] ?? ucfirst( str_replace( '_', ' ', $key ) );
      $desc    = $field['instructions'] ?? '';
      $type    = $field['type'] ?? 'text';
      ?>
      <tr class="optix-field-row">
        <th scope="row">
          <label for="<?php echo esc_attr( $db_key ); ?>" class="optix-field-label"><?php echo esc_html( $label ); ?></label>
        </th>
        <td>
          <?php if ( $type === 'textarea' ) : ?>
            <textarea name="<?php echo esc_attr( $db_key ); ?>" id="<?php echo esc_attr( $db_key ); ?>" class="large-text"><?php echo esc_textarea( $value ); ?></textarea>
          <?php elseif ( $type === 'true_false' ) : ?>
            <input type="checkbox" name="<?php echo esc_attr( $db_key ); ?>" id="<?php echo esc_attr( $db_key ); ?>" value="1" <?php checked( $value, 1 ); ?>>
          <?php elseif ( $type === 'number' ) : ?>
            <input type="number" name="<?php echo esc_attr( $db_key ); ?>" id="<?php echo esc_attr( $db_key ); ?>" value="<?php echo esc_attr( $value ); ?>" class="small-text" <?php echo isset( $field['attrs'] ) ? 'step="' . esc_attr( $field['attrs']['step'] ?? '1' ) . '"' : ''; ?>>
          <?php elseif ( $type === 'image' || $type === 'url' ) : ?>
            <input type="url" name="<?php echo esc_attr( $db_key ); ?>" id="<?php echo esc_attr( $db_key ); ?>" value="<?php echo esc_url( $value ); ?>" class="large-text">
          <?php elseif ( $type === 'select' && ! empty( $field['choices'] ) ) : ?>
            <select name="<?php echo esc_attr( $db_key ); ?>" id="<?php echo esc_attr( $db_key ); ?>">
              <?php foreach ( $field['choices'] as $opt_val => $opt_label ) : ?>
                <option value="<?php echo esc_attr( $opt_val ); ?>" <?php selected( $value, $opt_val ); ?>><?php echo esc_html( $opt_label ); ?></option>
              <?php endforeach; ?>
            </select>
          <?php else : ?>
            <input type="text" name="<?php echo esc_attr( $db_key ); ?>" id="<?php echo esc_attr( $db_key ); ?>" value="<?php echo esc_attr( $value ); ?>" class="large-text">
          <?php endif; ?>
          <?php if ( $desc ) : ?>
            <p class="optix-field-desc"><?php echo esc_html( $desc ); ?></p>
          <?php endif; ?>
        </td>
      </tr>
      <?php
    }
  }

  /**
   * Get simplified field map for a section.
   *
   * Used by the WordPress-native admin fallback when ACF Pro is not active.
   */
  private function get_field_map( string $slug ): array {
    $all = [

      /* ── General ──────────────────────────────────────────────────── */
      'general' => [
        'site_logo'         => [ 'label' => 'Site Logo',               'type' => 'image',      'default' => '' ],
        'favicon'           => [ 'label' => 'Favicon',                 'type' => 'image',      'default' => '' ],
        'kc_img_base'       => [ 'label' => 'Image Base Path',        'type' => 'text',       'default' => '/wp-content/themes/optix-main/assets/kids-collection/images' ],
        'preloader_enable'  => [ 'label' => 'Enable Preloader',       'type' => 'true_false',  'default' => 1 ],
        'custom_css'        => [ 'label' => 'Custom CSS',             'type' => 'textarea',   'default' => '' ],
        'custom_js'         => [ 'label' => 'Custom JavaScript',      'type' => 'textarea',   'default' => '' ],
      ],

      /* ── Header ───────────────────────────────────────────────────── */
      'header' => [
        'header_logo'         => [ 'label' => 'Header Logo',           'type' => 'image',      'default' => '/logo.png' ],
        'header_logo_width'   => [ 'label' => 'Logo Width (px)',      'type' => 'number',     'default' => 150 ],
        'header_search_icon'  => [ 'label' => 'Search Icon',           'type' => 'image',      'default' => '/header-search.png' ],
        'header_cart_icon'    => [ 'label' => 'Cart Icon',             'type' => 'image',      'default' => '/header-cart.png' ],
        'header_login_icon'   => [ 'label' => 'Login Icon',            'type' => 'image',      'default' => '/header-admin.png' ],
        'header_sticky'       => [ 'label' => 'Sticky Header',         'type' => 'true_false',  'default' => 1 ],
        'header_height'       => [ 'label' => 'Header Height (px)',   'type' => 'number',     'default' => 80 ],
        'menu_font_size'      => [ 'label' => 'Menu Font Size (px)',  'type' => 'number',     'default' => 14 ],
        'search_placeholder'  => [ 'label' => 'Search Placeholder',    'type' => 'text',       'default' => 'Search products…' ],
      ],

      /* ── Top Bar ──────────────────────────────────────────────────── */
      'topbar' => [
        'topbar_enable'     => [ 'label' => 'Enable Top Bar',          'type' => 'true_false',  'default' => 1 ],
        'topbar_sale_text'  => [ 'label' => 'Sale Notification Text',  'type' => 'text',       'default' => 'Summer sale discount off 60% on all of your orders!' ],
        'topbar_bg'         => [ 'label' => 'Top Bar Background',      'type' => 'text',       'default' => '#222222' ],
        'topbar_text' => [ 'label' => 'Top Bar Text Color',      'type' => 'text',       'default' => '#ffffff' ],
      ],

      /* ── Footer ───────────────────────────────────────────────────── */
      'footer' => [
        'footer_logo'       => [ 'label' => 'Footer Logo',             'type' => 'image',      'default' => '/footer-logo.png' ],
        'footer_about_text' => [ 'label' => 'About Text',             'type' => 'textarea',   'default' => '' ],
        'footer_phone'      => [ 'label' => 'Phone Number',           'type' => 'text',       'default' => '' ],
        'footer_email'      => [ 'label' => 'Email Address',          'type' => 'text',       'default' => '' ],
        'footer_address'    => [ 'label' => 'Physical Address',       'type' => 'textarea',   'default' => '' ],
        'footer_copyright'  => [ 'label' => 'Copyright Text',         'type' => 'text',       'default' => '© 2025 Claudia Kids Collection. All Rights Reserved.' ],
        'payment_icons'     => [ 'label' => 'Payment Icons Image',     'type' => 'image',      'default' => '' ],
      ],

      /* ── Typography ────────────────────────────────────────────────── */
      'typography' => [
        'typography_heading_font'    => [ 'label' => 'Heading Font Family',    'type' => 'text',   'default' => 'Archivo' ],
        'typography_body_font'       => [ 'label' => 'Body Font Family',       'type' => 'text',   'default' => 'Jost' ],
        'typography_base_size'       => [ 'label' => 'Base Font Size (px)',   'type' => 'number', 'default' => 16 ],
        'typography_heading_weight'  => [ 'label' => 'Heading Font Weight',    'type' => 'text',   'default' => '700' ],
        'typography_body_weight'     => [ 'label' => 'Body Font Weight',       'type' => 'text',   'default' => '400' ],
        'typography_line_height'     => [ 'label' => 'Body Line Height',       'type' => 'number', 'default' => 1.6 ],
        'typography_letter_spacing'  => [ 'label' => 'Body Letter Spacing (px)', 'type' => 'number', 'default' => 0 ],
      ],

      /* ── Colors ────────────────────────────────────────────────────── */
      'colors' => [
        'color_primary'     => [ 'label' => 'Primary Color',         'type' => 'text', 'default' => '#705b53' ],
        'color_secondary'   => [ 'label' => 'Secondary Color',       'type' => 'text', 'default' => '#c19a6b' ],
        'color_text'        => [ 'label' => 'Text Color',            'type' => 'text', 'default' => '#666666' ],
        'color_heading'     => [ 'label' => 'Heading Color',         'type' => 'text', 'default' => '#222222' ],
        'color_background'  => [ 'label' => 'Background Color',      'type' => 'text', 'default' => '#ffffff' ],
        'color_header_bg'   => [ 'label' => 'Header Background',     'type' => 'text', 'default' => '#ffffff' ],
        'color_footer_bg'   => [ 'label' => 'Footer Background',     'type' => 'text', 'default' => '#222222' ],
        'color_accent'      => [ 'label' => 'Accent Color',          'type' => 'text', 'default' => '#d4a373' ],
        'color_link'        => [ 'label' => 'Link Color',            'type' => 'text', 'default' => '#705b53' ],
        'color_link_hover'  => [ 'label' => 'Link Hover Color',      'type' => 'text', 'default' => '#c19a6b' ],
        'color_border'      => [ 'label' => 'Border Color',          'type' => 'text', 'default' => '#e5e5e5' ],
        'color_sale'        => [ 'label' => 'Sale / Discount Color', 'type' => 'text', 'default' => '#e74c3c' ],
      ],

      /* ── Home Page ─────────────────────────────────────────────────── */
      'home-page' => [
        'home_banner_enable'           => [ 'label' => 'Enable Banner Section',       'type' => 'true_false', 'default' => 1 ],
        'home_banner_heading'          => [ 'label' => 'Banner Heading',             'type' => 'text',       'default' => 'Claudia Kids Collection' ],
        'home_banner_title'            => [ 'label' => 'Banner Title',               'type' => 'textarea',   'default' => 'Little Treasures, Big Smiles!' ],
        'home_banner_description'      => [ 'label' => 'Banner Description',         'type' => 'textarea',   'default' => '' ],
        'home_banner_btn_text'         => [ 'label' => 'Banner Button Text',          'type' => 'text',       'default' => 'Shop Now' ],
        'home_banner_btn_url'          => [ 'label' => 'Banner Button URL',           'type' => 'text',       'default' => '/shop/' ],
        'home_banner_img1'             => [ 'label' => 'Banner Image 1',             'type' => 'image',      'default' => '/banner-img1.png' ],
        'home_banner_img2'             => [ 'label' => 'Banner Image 2',             'type' => 'image',      'default' => '/banner-img2.png' ],
        'home_promotion_enable'        => [ 'label' => 'Enable Promotion Section',    'type' => 'true_false', 'default' => 1 ],
        'home_products_enable'         => [ 'label' => 'Enable Products Section',     'type' => 'true_false', 'default' => 1 ],
        'home_products_heading'        => [ 'label' => 'Products Section Heading',    'type' => 'text',       'default' => 'Our Collection' ],
        'home_products_title'          => [ 'label' => 'Products Section Title',      'type' => 'text',       'default' => 'Popular Products' ],
        'home_products_count'          => [ 'label' => 'Number of Products',          'type' => 'number',     'default' => 6 ],
        'home_products_btn_text'       => [ 'label' => 'View All Button Text',        'type' => 'text',       'default' => 'View All' ],
        'home_products_fallback_img'   => [ 'label' => 'Fallback Product Image',     'type' => 'text',       'default' => '/product-img1.png' ],
        'home_products_price_multiplier' => [ 'label' => 'Price Multiplier',         'type' => 'number',     'default' => 1.3 ],
        'home_cta_enable'              => [ 'label' => 'Enable CTA Sale Section',     'type' => 'true_false', 'default' => 1 ],
        'home_cta_title'               => [ 'label' => 'CTA Title',                  'type' => 'text',       'default' => 'Mid Season Sale!' ],
        'home_cta_subtitle'            => [ 'label' => 'CTA Subtitle',               'type' => 'text',       'default' => 'Get 20% Off on All New Arrivals!' ],
        'home_cta_btn_text'            => [ 'label' => 'CTA Button Text',             'type' => 'text',       'default' => 'Get this Deal' ],
        'home_cta_btn_url'             => [ 'label' => 'CTA Button URL',              'type' => 'text',       'default' => '/shop/' ],
        'home_categories_enable'       => [ 'label' => 'Enable Categories Section',   'type' => 'true_false', 'default' => 1 ],
        'home_categories_heading'      => [ 'label' => 'Categories Heading',          'type' => 'text',       'default' => 'magna aliqua' ],
        'home_categories_title'        => [ 'label' => 'Categories Title',            'type' => 'text',       'default' => 'Product Categories' ],
        'home_top_selling_enable'      => [ 'label' => 'Enable Top Selling Section',  'type' => 'true_false', 'default' => 1 ],
        'home_top_selling_title'       => [ 'label' => 'Top Selling Title',           'type' => 'text',       'default' => 'Top Selling Products' ],
        'home_top_selling_count'       => [ 'label' => 'Top Selling Count',           'type' => 'number',     'default' => 4 ],
        'home_testimonials_enable'     => [ 'label' => 'Enable Testimonials Section', 'type' => 'true_false', 'default' => 1 ],
        'home_testimonials_heading'    => [ 'label' => 'Testimonials Heading',        'type' => 'text',       'default' => 'Testimonials' ],
        'home_testimonials_title'      => [ 'label' => 'Testimonials Title',          'type' => 'text',       'default' => 'Our Client Reviews' ],
        'home_instagram_enable'        => [ 'label' => 'Enable Instagram Section',    'type' => 'true_false', 'default' => 1 ],
        'home_instagram_heading'       => [ 'label' => 'Instagram Heading',           'type' => 'text',       'default' => '@claudia instagram' ],
        'home_instagram_title'         => [ 'label' => 'Instagram Title',             'type' => 'text',       'default' => 'Find us On Instagram' ],
        'home_benefits_enable'         => [ 'label' => 'Enable Benefits Section',     'type' => 'true_false', 'default' => 1 ],
      ],

      /* ── About Page ────────────────────────────────────────────────── */
      'about-page' => [
        'about_about_enable'     => [ 'label' => 'Enable About Us Section',    'type' => 'true_false', 'default' => 1 ],
        'about_about_heading'    => [ 'label' => 'About Us Heading',          'type' => 'text',       'default' => 'About Us' ],
        'about_about_title'      => [ 'label' => 'About Us Title',            'type' => 'text',       'default' => 'Unique Clothes & Toys For Kids' ],
        'about_about_text_1'     => [ 'label' => 'About Text (Paragraph 1)',  'type' => 'textarea',   'default' => '' ],
        'about_about_text_2'     => [ 'label' => 'About Text (Paragraph 2)',  'type' => 'textarea',   'default' => '' ],
        'about_about_image'      => [ 'label' => 'About Us Image',            'type' => 'image',      'default' => '/about-us-img.jpg' ],
        'about_about_btn_text'   => [ 'label' => 'About Button Text',         'type' => 'text',       'default' => 'Read More' ],
        'about_about_btn_url'    => [ 'label' => 'About Button URL',          'type' => 'text',       'default' => '/shop/' ],
        'about_mission_enable'   => [ 'label' => 'Enable Mission Section',    'type' => 'true_false', 'default' => 1 ],
        'about_mission_heading'  => [ 'label' => 'Mission Heading',           'type' => 'text',       'default' => 'Our Mission' ],
        'about_mission_title'    => [ 'label' => 'Mission Title',             'type' => 'text',       'default' => 'Start of Countless Collection.' ],
        'about_mission_text_1'   => [ 'label' => 'Mission Text (1)',          'type' => 'textarea',   'default' => '' ],
        'about_mission_text_2'   => [ 'label' => 'Mission Text (2)',          'type' => 'textarea',   'default' => '' ],
        'about_team_enable'      => [ 'label' => 'Enable Team Section',       'type' => 'true_false', 'default' => 1 ],
        'about_team_heading'     => [ 'label' => 'Team Heading',              'type' => 'text',       'default' => 'Experts Team' ],
        'about_team_title'       => [ 'label' => 'Team Title',                'type' => 'text',       'default' => 'Our Team Members' ],
        'about_categories_enable' => [ 'label' => 'Enable Categories Section', 'type' => 'true_false', 'default' => 1 ],
        'about_instagram_enable'  => [ 'label' => 'Enable Instagram Section', 'type' => 'true_false', 'default' => 1 ],
        'about_benefits_enable'   => [ 'label' => 'Enable Benefits Section',  'type' => 'true_false', 'default' => 1 ],
      ],

      /* ── Blog ──────────────────────────────────────────────────────── */
      'blog' => [
        'blog_enable'        => [ 'label' => 'Enable Blog Section',     'type' => 'true_false', 'default' => 1 ],
        'blog_title'         => [ 'label' => 'Blog Page Title',         'type' => 'text',       'default' => 'Blog' ],
        'blog_posts_per_page' => [ 'label' => 'Posts Per Page',         'type' => 'number',     'default' => 6 ],
      ],

      /* ── Contact ───────────────────────────────────────────────────── */
      'contact' => [
        'contact_info_heading'   => [ 'label' => 'Info Section Heading',    'type' => 'text',     'default' => 'Contact Info' ],
        'contact_info_title'     => [ 'label' => 'Info Section Title',      'type' => 'text',     'default' => 'Our Information' ],
        'contact_location_icon'  => [ 'label' => 'Location Icon',           'type' => 'image',    'default' => '/loc-img.png' ],
        'contact_location_title' => [ 'label' => 'Location Card Title',     'type' => 'text',     'default' => 'Our Location' ],
        'contact_location_text'  => [ 'label' => 'Location Address',        'type' => 'textarea', 'default' => '' ],
        'contact_phone_icon'     => [ 'label' => 'Phone Icon',              'type' => 'image',    'default' => '/contact-img.png' ],
        'contact_phone_title'    => [ 'label' => 'Phone Card Title',        'type' => 'text',     'default' => 'Phone Number' ],
        'contact_email_icon'     => [ 'label' => 'Email Icon',              'type' => 'image',    'default' => '/email-img.png' ],
        'contact_email_title'    => [ 'label' => 'Email Card Title',        'type' => 'text',     'default' => 'Email Us:' ],
        'contact_form_heading'   => [ 'label' => 'Form Section Heading',    'type' => 'text',     'default' => 'Get in Touch' ],
        'contact_form_title'     => [ 'label' => 'Form Section Title',      'type' => 'text',     'default' => 'Send Us a Message' ],
        'contact_form_btn_text'  => [ 'label' => 'Form Submit Button Text', 'type' => 'text',     'default' => 'Send Now' ],
        'contact_map_embed'      => [ 'label' => 'Google Maps Embed URL',   'type' => 'textarea', 'default' => '' ],
      ],

      /* ── Shop ──────────────────────────────────────────────────────── */
      'shop' => [
        'shop_enable'            => [ 'label' => 'Enable Shop Section',     'type' => 'true_false', 'default' => 1 ],
        'shop_title'             => [ 'label' => 'Shop Page Title',         'type' => 'text',       'default' => 'Shop' ],
        'shop_products_per_page'  => [ 'label' => 'Products Per Page',      'type' => 'number',     'default' => 12 ],
        'shop_columns'           => [ 'label' => 'Product Grid Columns',    'type' => 'number',     'default' => 3 ],
        'shop_enable_sidebar'    => [ 'label' => 'Enable Shop Sidebar',     'type' => 'true_false', 'default' => 1 ],
      ],

      /* ── Product Detail ─────────────────────────────────────────────── */
      'product-detail' => [
        'product_detail_title'    => [ 'label' => 'Page Title',              'type' => 'text',   'default' => 'Product Details' ],
        'product_detail_related_title' => [ 'label' => 'Related Products Title', 'type' => 'text', 'default' => 'Related Products' ],
        'product_detail_related_count'  => [ 'label' => 'Related Products Count', 'type' => 'number', 'default' => 12 ],
        'product_detail_tabs_enable'    => [ 'label' => 'Enable Tabs',       'type' => 'true_false', 'default' => 1 ],
        'product_detail_sale_tag' => [ 'label' => 'Sale Tag Text',           'type' => 'text',   'default' => 'Sale' ],
        'product_detail_more_title' => [ 'label' => 'More Products Title',    'type' => 'text',   'default' => 'More Products' ],
      ],

      /* ── Cart & Checkout ────────────────────────────────────────────── */
      'cart-checkout' => [
        'cart_title'              => [ 'label' => 'Cart Page Title',         'type' => 'text',       'default' => 'Cart' ],
        'checkout_title'          => [ 'label' => 'Checkout Page Title',     'type' => 'text',       'default' => 'Checkout' ],
        'cart_coupon_enable'      => [ 'label' => 'Enable Coupon Field',     'type' => 'true_false',  'default' => 1 ],
        'cart_cross_sell_enable'  => [ 'label' => 'Enable Cross-Sells',     'type' => 'true_false',  'default' => 1 ],
      ],

      /* ── Other Pages ─────────────────────────────────────────────────── */
      'pages' => [
        'coming_soon_heading'  => [ 'label' => 'Coming Soon Heading',     'type' => 'text',     'default' => 'We are Coming Soon' ],
        'coming_soon_subtitle' => [ 'label' => 'Coming Soon Subtitle',    'type' => 'text',     'default' => 'Our Website is under construction' ],
        'coming_soon_date'     => [ 'label' => 'Coming Soon Target Date',  'type' => 'text',     'default' => '' ],
        'coming_soon_logo'     => [ 'label' => 'Coming Soon Logo',        'type' => 'image',    'default' => '/large-logo.png' ],
        'pages_404_title'      => [ 'label' => '404 Page Title',          'type' => 'text',     'default' => '404' ],
        'pages_404_text'       => [ 'label' => '404 Page Message',        'type' => 'textarea', 'default' => 'Oops! This page could not be found.' ],
        'pages_404_btn_text'   => [ 'label' => '404 Button Text',         'type' => 'text',     'default' => 'Back to Home' ],
      ],

      /* ── Animations ─────────────────────────────────────────────────── */
      'animations' => [
        'animations_enable'       => [ 'label' => 'Enable WOW.js Animations',    'type' => 'true_false', 'default' => 1 ],
        'animation_duration'      => [ 'label' => 'Default Animation Duration',  'type' => 'text',       'default' => '2s' ],
        'animation_delay'         => [ 'label' => 'Default Animation Delay',     'type' => 'text',       'default' => '0.05s' ],
        'animations_mobile'       => [ 'label' => 'Enable Animations on Mobile', 'type' => 'true_false', 'default' => 1 ],
        'effect_3d_enable'        => [ 'label' => 'Enable 3D Tilt Effects',      'type' => 'true_false', 'default' => 0 ],
        'effect_3d_perspective'   => [ 'label' => '3D Perspective (px)',          'type' => 'number',    'default' => 1000 ],
        'effect_3d_rotate_x'      => [ 'label' => 'Max X Rotation (deg)',        'type' => 'number',    'default' => 5 ],
        'effect_3d_rotate_y'      => [ 'label' => 'Max Y Rotation (deg)',        'type' => 'number',    'default' => 5 ],
      ],

      /* ── Advanced ───────────────────────────────────────────────────── */
      'advanced' => [
        'advanced_wc_enable'    => [ 'label' => 'Enable WooCommerce Features', 'type' => 'true_false', 'default' => 1 ],
        'advanced_preloader'    => [ 'label' => 'Enable Preloader',           'type' => 'true_false', 'default' => 1 ],
        'advanced_breadcrumbs'  => [ 'label' => 'Enable Breadcrumbs',         'type' => 'true_false', 'default' => 1 ],
        'advanced_back_to_top'  => [ 'label' => 'Enable Back to Top Button',  'type' => 'true_false', 'default' => 1 ],
        'maintenance_mode_enable' => [ 'label' => 'Enable Maintenance Mode',  'type' => 'true_false', 'default' => 0 ],
      ],

      /* ── Import / Export ─────────────────────────────────────────────── */
      'import-export' => [],
    ];

    return $all[ $slug ] ?? [];
  }

  /**
   * Register the Tools > Import/Export sub-menu page.
   */
  public function register_tools_page() {
    add_submenu_page(
      'tools.php',
      'Optix Import / Export',
      'Optix Import/Export',
      'manage_options',
      'optix-import-export',
      [ $this, 'render_import_export_page' ]
    );
  }

  /**
   * Render the Import / Export admin page.
   */
  public function render_import_export_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
      wp_die( esc_html__( 'Unauthorized.', 'optix' ) );
    }
    ?>
    <div class="wrap optix-options-wrap">
      <h1><?php esc_html_e( 'Optix Theme Options — Import / Export', 'optix' ); ?></h1>
      <p><?php esc_html_e( 'Export your theme options as a JSON file, or import a previously exported JSON file to restore settings.', 'optix' ); ?></p>

      <hr>

      <h2><?php esc_html_e( 'Export', 'optix' ); ?></h2>
      <p><?php esc_html_e( 'Download all theme options as a JSON file.', 'optix' ); ?></p>
      <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <?php wp_nonce_field( 'optix_export_action', 'optix_export_nonce' ); ?>
        <input type="hidden" name="action" value="optix_export">
        <p class="submit">
          <button type="submit" class="button button-primary"><?php esc_html_e( 'Download Export File', 'optix' ); ?></button>
        </p>
      </form>

      <hr>

      <h2><?php esc_html_e( 'Import', 'optix' ); ?></h2>
      <p><?php esc_html_e( 'Upload a previously exported JSON file to restore theme settings.', 'optix' ); ?></p>
      <form method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <?php wp_nonce_field( 'optix_import_action', 'optix_import_nonce' ); ?>
        <input type="hidden" name="action" value="optix_import">
        <p>
          <input type="file" name="optix_import_file" accept=".json" required>
        </p>
        <p class="submit">
          <button type="submit" class="button button-primary"><?php esc_html_e( 'Import Settings', 'optix' ); ?></button>
        </p>
      </form>
    </div>
    <?php
  }

  /**
   * Handle export action — generate JSON download.
   */
  public function handle_export() {
    if ( ! current_user_can( 'manage_options' ) ) {
      wp_die( esc_html__( 'Unauthorized.', 'optix' ) );
    }
    check_admin_referer( 'optix_export_action', 'optix_export_nonce' );

    global $wpdb;
    $rows = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE %s",
        $wpdb->esc_like( 'optix_' ) . '%'
      )
    );

    $data = [];
    foreach ( $rows as $row ) {
      $data[ $row->option_name ] = maybe_unserialize( $row->option_value );
    }

    $json = wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );

    nocache_headers();
    header( 'Content-Type: application/json' );
    header( 'Content-Disposition: attachment; filename="optix-theme-options-' . gmdate( 'Y-m-d' ) . '.json"' );
    header( 'Content-Length: ' . strlen( $json ) );
    echo $json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    exit;
  }

  /**
   * Handle import action — read uploaded JSON and save options.
   */
  public function handle_import() {
    if ( ! current_user_can( 'manage_options' ) ) {
      wp_die( esc_html__( 'Unauthorized.', 'optix' ) );
    }
    check_admin_referer( 'optix_import_action', 'optix_import_nonce' );

    if ( empty( $_FILES['optix_import_file'] ) || UPLOAD_ERR_OK !== $_FILES['optix_import_file']['error'] ) {
      wp_die( esc_html__( 'File upload failed.', 'optix' ) );
    }

    $import_file = $_FILES['optix_import_file']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

    if ( ! is_uploaded_file( $import_file['tmp_name'] ) ) {
      wp_die( esc_html__( 'File upload failed.', 'optix' ) );
    }

    $file_ext = strtolower( pathinfo( $import_file['name'], PATHINFO_EXTENSION ) );
    if ( 'json' !== $file_ext ) {
      wp_die( esc_html__( 'Invalid file type. Only .json files are allowed.', 'optix' ) );
    }

    if ( $import_file['size'] > 5 * MB_IN_BYTES ) {
      wp_die( esc_html__( 'File too large. Maximum size is 5 MB.', 'optix' ) );
    }

    $contents = file_get_contents( $import_file['tmp_name'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
    $data     = json_decode( $contents, true );

    if ( ! is_array( $data ) ) {
      wp_die( esc_html__( 'Invalid JSON file.', 'optix' ) );
    }

    $count = 0;
    foreach ( $data as $key => $value ) {
      if ( strpos( $key, 'optix_' ) !== 0 ) {
        continue;
      }
      if ( is_array( $value ) ) {
        update_option( $key, $value );
      } else {
        update_option( $key, sanitize_text_field( wp_unslash( (string) $value ) ) );
      }
      $count++;
    }

    $redirect = add_query_arg( 'imported', $count, admin_url( 'tools.php?page=optix-import-export' ) );
    wp_safe_redirect( $redirect );
    exit;
  }

  /**
   * Register all ACF field groups.
   *
   * Field group JSON files can be synced via ACF's built-in sync,
   * or generated here with acf_add_local_field_group().
   *
   * For production, prefer exporting field groups as JSON under
   * /acf-json/ so they are version-controlled and syncable.
   */
  public function register_field_groups() {
    $this->register_general_fields();
    $this->register_header_fields();
    $this->register_topbar_fields();
    $this->register_footer_fields();
    $this->register_typography_fields();
    $this->register_colors_fields();
    $this->register_home_page_fields();
    $this->register_about_page_fields();
    $this->register_blog_fields();
    $this->register_contact_fields();
    $this->register_shop_fields();
    $this->register_product_detail_fields();
    $this->register_cart_checkout_fields();
    $this->register_other_pages_fields();
    $this->register_animations_fields();
    $this->register_advanced_fields();
    $this->register_portfolio_fields();
    $this->register_import_export_fields();
  }

  /**
   * General tab fields.
   */
  private function register_general_fields() {
    acf_add_local_field_group( [
      'key'      => 'group_optix_general',
      'title'    => 'General Settings',
      'fields'   => [
        [
          'key'           => 'field_optix_site_logo',
          'name'          => 'site_logo',
          'label'         => 'Site Logo',
          'type'          => 'image',
          'return_format' => 'url',
          'instructions'  => 'Upload the main site logo.',
        ],
        [
          'key'           => 'field_optix_favicon',
          'name'          => 'favicon',
          'label'         => 'Favicon',
          'type'          => 'image',
          'return_format' => 'url',
        ],
        [
          'key'           => 'field_optix_kc_img_base',
          'name'          => 'kc_img_base',
          'label'         => 'Image Base Path',
          'type'          => 'text',
          'default_value' => '/assets/kids-collection/images',
          'instructions'  => 'Base path for Kids Collection images (relative to theme directory).',
        ],
        [
          'key'   => 'field_optix_preloader_enable',
          'name'  => 'preloader_enable',
          'label' => 'Enable Preloader',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'  => 'field_optix_custom_css',
          'name' => 'custom_css',
          'label' => 'Custom CSS',
          'type' => 'textarea',
          'instructions' => 'Custom CSS injected into the site head.',
        ],
        [
          'key'  => 'field_optix_custom_js',
          'name' => 'custom_js',
          'label' => 'Custom JavaScript',
          'type' => 'textarea',
          'instructions' => 'Custom JavaScript injected into the site footer.',
        ],
      ],
      'location' => [
        [ [ 'param' => 'options_page', 'operator' => '==', 'value' => self::PARENT_SLUG . '-general' ] ],
      ],
    ] );
  }

  /**
   * Header tab fields.
   */
  private function register_header_fields() {
    acf_add_local_field_group( [
      'key'      => 'group_optix_header',
      'title'    => 'Header Settings',
      'fields'   => [
        [
          'key'           => 'field_optix_header_logo',
          'name'          => 'header_logo',
          'label'         => 'Header Logo',
          'type'          => 'image',
          'return_format' => 'url',
        ],
        [
          'key'   => 'field_optix_header_logo_width',
          'name'  => 'header_logo_width',
          'label' => 'Logo Width (px)',
          'type'  => 'number',
        ],
        [
          'key'           => 'field_optix_header_search_icon',
          'name'          => 'header_search_icon',
          'label'         => 'Search Icon',
          'type'          => 'image',
          'return_format' => 'url',
        ],
        [
          'key'           => 'field_optix_header_cart_icon',
          'name'          => 'header_cart_icon',
          'label'         => 'Cart Icon',
          'type'          => 'image',
          'return_format' => 'url',
        ],
        [
          'key'           => 'field_optix_header_login_icon',
          'name'          => 'header_login_icon',
          'label'         => 'Login Icon',
          'type'          => 'image',
          'return_format' => 'url',
        ],
        [
          'key'   => 'field_optix_header_sticky',
          'name'  => 'header_sticky',
          'label' => 'Sticky Header',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_header_height',
          'name'  => 'header_height',
          'label' => 'Header Height (px)',
          'type'  => 'number',
          'default_value' => 80,
        ],
        [
          'key'   => 'field_optix_menu_font_size',
          'name'  => 'menu_font_size',
          'label' => 'Menu Font Size (px)',
          'type'  => 'number',
          'default_value' => 14,
        ],
        [
          'key'   => 'field_optix_search_placeholder',
          'name'  => 'search_placeholder',
          'label' => 'Search Placeholder Text',
          'type'  => 'text',
          'default_value' => 'Search products…',
        ],
      ],
      'location' => [
        [ [ 'param' => 'options_page', 'operator' => '==', 'value' => self::PARENT_SLUG . '-header' ] ],
      ],
    ] );
  }

  /**
   * Top Bar tab fields.
   */
  private function register_topbar_fields() {
    acf_add_local_field_group( [
      'key'      => 'group_optix_topbar',
      'title'    => 'Top Bar Settings',
      'fields'   => [
        [
          'key'   => 'field_optix_topbar_enable',
          'name'  => 'topbar_enable',
          'label' => 'Enable Top Bar',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_topbar_bg',
          'name'  => 'topbar_bg',
          'label' => 'Top Bar Background Color',
          'type'  => 'color_picker',
          'default_value' => '#222222',
        ],
        [
          'key'   => 'field_optix_topbar_text',
          'name'  => 'topbar_text',
          'label' => 'Top Bar Text Color',
          'type'  => 'color_picker',
          'default_value' => '#ffffff',
        ],
        [
          'key'   => 'field_optix_topbar_sale_text',
          'name'  => 'topbar_sale_text',
          'label' => 'Sale Notification Text',
          'type'  => 'text',
          'default_value' => 'Summer sale discount off <span class="d-inline-block">60%</span> on all of your orders!',
        ],
        [
          'key'      => 'field_optix_topbar_languages',
          'name'     => 'topbar_languages',
          'label'    => 'Language Switcher',
          'type'     => 'repeater',
          'layout'   => 'table',
          'sub_fields' => [
            [
              'key'           => 'field_optix_topbar_lang_flag',
              'name'          => 'lang_flag',
              'label'         => 'Flag',
              'type'          => 'image',
              'return_format' => 'url',
            ],
            [
              'key'   => 'field_optix_topbar_lang_code',
              'name'  => 'lang_code',
              'label' => 'Language Code',
              'type'  => 'text',
            ],
            [
              'key'   => 'field_optix_topbar_lang_url',
              'name'  => 'lang_url',
              'label' => 'Language URL',
              'type'  => 'url',
            ],
          ],
        ],
        [
          'key'      => 'field_optix_topbar_currencies',
          'name'     => 'topbar_currencies',
          'label'    => 'Currency Switcher',
          'type'     => 'repeater',
          'layout'   => 'table',
          'sub_fields' => [
            [
              'key'   => 'field_optix_topbar_currency_code',
              'name'  => 'currency_code',
              'label' => 'Currency Code',
              'type'  => 'text',
            ],
            [
              'key'   => 'field_optix_topbar_currency_url',
              'name'  => 'currency_url',
              'label' => 'Currency URL',
              'type'  => 'url',
            ],
          ],
        ],
      ],
      'location' => [
        [ [ 'param' => 'options_page', 'operator' => '==', 'value' => self::PARENT_SLUG . '-topbar' ] ],
      ],
    ] );
  }

  /**
   * Footer tab fields.
   */
  private function register_footer_fields() {
    acf_add_local_field_group( [
      'key'      => 'group_optix_footer',
      'title'    => 'Footer Settings',
      'fields'   => [
        [
          'key'           => 'field_optix_footer_logo',
          'name'          => 'footer_logo',
          'label'         => 'Footer Logo',
          'type'          => 'image',
          'return_format' => 'url',
        ],
        [
          'key'   => 'field_optix_footer_about_text',
          'name'  => 'footer_about_text',
          'label' => 'About Text',
          'type'  => 'textarea',
        ],
        [
          'key'      => 'field_optix_footer_social',
          'name'     => 'footer_social_links',
          'label'    => 'Social Links',
          'type'     => 'repeater',
          'layout'   => 'table',
          'sub_fields' => [
            [
              'key'   => 'field_optix_footer_social_platform',
              'name'  => 'platform',
              'label' => 'Platform',
              'type'  => 'text',
            ],
            [
              'key'   => 'field_optix_footer_social_url',
              'name'  => 'url',
              'label' => 'URL',
              'type'  => 'url',
            ],
          ],
        ],
        [
          'key'   => 'field_optix_footer_phone',
          'name'  => 'footer_phone',
          'label' => 'Phone Number',
          'type'  => 'text',
        ],
        [
          'key'   => 'field_optix_footer_email',
          'name'  => 'footer_email',
          'label' => 'Email Address',
          'type'  => 'email',
        ],
        [
          'key'   => 'field_optix_footer_address',
          'name'  => 'footer_address',
          'label' => 'Physical Address',
          'type'  => 'textarea',
        ],
        [
          'key'   => 'field_optix_footer_copyright',
          'name'  => 'footer_copyright',
          'label' => 'Copyright Text',
          'type'  => 'text',
        ],
      ],
      'location' => [
        [ [ 'param' => 'options_page', 'operator' => '==', 'value' => self::PARENT_SLUG . '-footer' ] ],
      ],
    ] );
  }

  /**
   * Typography tab fields.
   */
  private function register_typography_fields() {
    acf_add_local_field_group( [
      'key'      => 'group_optix_typography',
      'title'    => 'Typography Settings',
      'fields'   => [
        [
          'key'   => 'field_optix_typo_heading_font',
          'name'  => 'typography_heading_font',
          'label' => 'Heading Font Family',
          'type'  => 'text',
          'default_value' => 'Archivo',
          'instructions'  => 'Google Font name for headings (e.g., Archivo, Poppins, Playfair Display).',
        ],
        [
          'key'   => 'field_optix_typo_body_font',
          'name'  => 'typography_body_font',
          'label' => 'Body Font Family',
          'type'  => 'text',
          'default_value' => 'Jost',
          'instructions'  => 'Google Font name for body text (e.g., Jost, Inter, Open Sans).',
        ],
        [
          'key'   => 'field_optix_typo_base_size',
          'name'  => 'typography_base_size',
          'label' => 'Base Font Size (px)',
          'type'  => 'number',
          'default_value' => 16,
        ],
        [
          'key'   => 'field_optix_typo_heading_weight',
          'name'  => 'typography_heading_weight',
          'label' => 'Heading Font Weight',
          'type'  => 'text',
          'default_value' => '700',
        ],
        [
          'key'   => 'field_optix_typo_body_weight',
          'name'  => 'typography_body_weight',
          'label' => 'Body Font Weight',
          'type'  => 'text',
          'default_value' => '400',
        ],
        [
          'key'   => 'field_optix_typo_line_height',
          'name'  => 'typography_line_height',
          'label' => 'Body Line Height',
          'type'  => 'number',
          'default_value' => 1.6,
          'instructions' => 'Line height multiplier (e.g., 1.6).',
        ],
        [
          'key'   => 'field_optix_typo_letter_spacing',
          'name'  => 'typography_letter_spacing',
          'label' => 'Body Letter Spacing (px)',
          'type'  => 'number',
          'default_value' => 0,
          'instructions' => 'Letter spacing in pixels. Can be negative.',
        ],
      ],
      'location' => [
        [ [ 'param' => 'options_page', 'operator' => '==', 'value' => self::PARENT_SLUG . '-typography' ] ],
      ],
    ] );
  }

  /**
   * Colors tab fields.
   */
  private function register_colors_fields() {
    acf_add_local_field_group( [
      'key'      => 'group_optix_colors',
      'title'    => 'Color Settings',
      'fields'   => [
        [
          'key'   => 'field_optix_color_primary',
          'name'  => 'color_primary',
          'label' => 'Primary Color',
          'type'  => 'color_picker',
          'default_value' => '#705b53',
        ],
        [
          'key'   => 'field_optix_color_secondary',
          'name'  => 'color_secondary',
          'label' => 'Secondary Color',
          'type'  => 'color_picker',
          'default_value' => '#c19a6b',
        ],
        [
          'key'   => 'field_optix_color_text',
          'name'  => 'color_text',
          'label' => 'Text Color',
          'type'  => 'color_picker',
          'default_value' => '#666666',
        ],
        [
          'key'   => 'field_optix_color_heading',
          'name'  => 'color_heading',
          'label' => 'Heading Color',
          'type'  => 'color_picker',
          'default_value' => '#222222',
        ],
        [
          'key'   => 'field_optix_color_background',
          'name'  => 'color_background',
          'label' => 'Background Color',
          'type'  => 'color_picker',
          'default_value' => '#ffffff',
        ],
        [
          'key'   => 'field_optix_color_header_bg',
          'name'  => 'color_header_bg',
          'label' => 'Header Background',
          'type'  => 'color_picker',
          'default_value' => '#ffffff',
        ],
        [
          'key'   => 'field_optix_color_footer_bg',
          'name'  => 'color_footer_bg',
          'label' => 'Footer Background',
          'type'  => 'color_picker',
          'default_value' => '#222222',
        ],
        [
          'key'   => 'field_optix_color_accent',
          'name'  => 'color_accent',
          'label' => 'Accent Color',
          'type'  => 'color_picker',
          'default_value' => '#d4a373',
        ],
        [
          'key'   => 'field_optix_color_link',
          'name'  => 'color_link',
          'label' => 'Link Color',
          'type'  => 'color_picker',
          'default_value' => '#705b53',
        ],
        [
          'key'   => 'field_optix_color_link_hover',
          'name'  => 'color_link_hover',
          'label' => 'Link Hover Color',
          'type'  => 'color_picker',
          'default_value' => '#c19a6b',
        ],
        [
          'key'   => 'field_optix_color_border',
          'name'  => 'color_border',
          'label' => 'Border Color',
          'type'  => 'color_picker',
          'default_value' => '#e5e5e5',
        ],
        [
          'key'   => 'field_optix_color_sale',
          'name'  => 'color_sale',
          'label' => 'Sale / Discount Color',
          'type'  => 'color_picker',
          'default_value' => '#e74c3c',
        ],
      ],
      'location' => [
        [ [ 'param' => 'options_page', 'operator' => '==', 'value' => self::PARENT_SLUG . '-colors' ] ],
      ],
    ] );
  }

  /**
   * Home Page tab fields.
   */
  private function register_home_page_fields() {
    acf_add_local_field_group( [
      'key'      => 'group_optix_home_page',
      'title'    => 'Home Page Settings',
      'fields'   => [
        [
          'key'   => 'field_optix_home_banner_enable',
          'name'  => 'home_banner_enable',
          'label' => 'Enable Banner Section',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_home_banner_heading',
          'name'  => 'home_banner_heading',
          'label' => 'Banner Heading',
          'type'  => 'text',
          'default_value' => 'Claudia Kids Collection',
        ],
        [
          'key'   => 'field_optix_home_banner_title',
          'name'  => 'home_banner_title',
          'label' => 'Banner Title',
          'type'  => 'textarea',
          'default_value' => 'Little Treasures,<br>Big Smiles!',
        ],
        [
          'key'   => 'field_optix_home_banner_description',
          'name'  => 'home_banner_description',
          'label' => 'Banner Description',
          'type'  => 'textarea',
          'default_value' => 'Discover a world of fun and joy with our exclusive kids collection, designed for comfort, style, and endless adventures.',
        ],
        [
          'key'   => 'field_optix_home_banner_btn_text',
          'name'  => 'home_banner_btn_text',
          'label' => 'Banner Button Text',
          'type'  => 'text',
          'default_value' => 'Shop Now',
        ],
        [
          'key'   => 'field_optix_home_banner_btn_url',
          'name'  => 'home_banner_btn_url',
          'label' => 'Banner Button URL',
          'type'  => 'url',
          'default_value' => '/shop/',
        ],
        [
          'key'           => 'field_optix_home_banner_img1',
          'name'          => 'home_banner_img1',
          'label'         => 'Banner Image 1',
          'type'          => 'image',
          'return_format' => 'url',
        ],
        [
          'key'           => 'field_optix_home_banner_img2',
          'name'          => 'home_banner_img2',
          'label'         => 'Banner Image 2',
          'type'          => 'image',
          'return_format' => 'url',
        ],
        [
          'key'   => 'field_optix_home_promotion_enable',
          'name'  => 'home_promotion_enable',
          'label' => 'Enable Promotion Section',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'      => 'field_optix_home_promotion_boxes',
          'name'     => 'home_promotion_boxes',
          'label'    => 'Promotion Boxes',
          'type'     => 'repeater',
          'layout'   => 'block',
          'min'      => 4,
          'max'      => 4,
          'sub_fields' => [
            [
              'key'   => 'field_optix_home_promo_tag',
              'name'  => 'tag',
              'label' => 'Tag',
              'type'  => 'text',
            ],
            [
              'key'   => 'field_optix_home_promo_title',
              'name'  => 'title',
              'label' => 'Title',
              'type'  => 'text',
            ],
            [
              'key'   => 'field_optix_home_promo_discount',
              'name'  => 'discount',
              'label' => 'Discount Text',
              'type'  => 'text',
            ],
            [
              'key'           => 'field_optix_home_promo_image',
              'name'          => 'image',
              'label'         => 'Image',
              'type'          => 'image',
              'return_format' => 'url',
            ],
            [
              'key'   => 'field_optix_home_promo_bg_class',
              'name'  => 'bg_class',
              'label' => 'Background CSS Class',
              'type'  => 'text',
            ],
            [
              'key'   => 'field_optix_home_promo_link',
              'name'  => 'link',
              'label' => 'Link URL',
              'type'  => 'url',
            ],
            [
              'key'   => 'field_optix_home_promo_btn_text',
              'name'  => 'btn_text',
              'label' => 'Button Text',
              'type'  => 'text',
            ],
          ],
        ],
        [
          'key'   => 'field_optix_home_products_enable',
          'name'  => 'home_products_enable',
          'label' => 'Enable Products Section',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_home_products_heading',
          'name'  => 'home_products_heading',
          'label' => 'Products Section Heading',
          'type'  => 'text',
          'default_value' => 'Our Collection',
        ],
        [
          'key'   => 'field_optix_home_products_title',
          'name'  => 'home_products_title',
          'label' => 'Products Section Title',
          'type'  => 'text',
          'default_value' => 'Popular Products',
        ],
        [
          'key'   => 'field_optix_home_products_count',
          'name'  => 'home_products_count',
          'label' => 'Number of Products',
          'type'  => 'number',
          'default_value' => 6,
        ],
        [
          'key'   => 'field_optix_home_products_btn_text',
          'name'  => 'home_products_btn_text',
          'label' => 'View All Button Text',
          'type'  => 'text',
          'default_value' => 'View All',
        ],
        [
          'key'   => 'field_optix_home_products_fallback_img',
          'name'  => 'home_products_fallback_img',
          'label' => 'Fallback Product Image Path',
          'type'  => 'text',
          'default_value' => '/product-img1.png',
        ],
        [
          'key'   => 'field_optix_home_products_price_mult',
          'name'  => 'home_products_price_multiplier',
          'label' => 'Price Comparison Multiplier',
          'type'  => 'number',
          'default_value' => 1.3,
        ],
        [
          'key'   => 'field_optix_home_cta_enable',
          'name'  => 'home_cta_enable',
          'label' => 'Enable CTA Sale Section',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_home_cta_title',
          'name'  => 'home_cta_title',
          'label' => 'CTA Title',
          'type'  => 'textarea',
          'default_value' => 'Mid Season Sale!',
        ],
        [
          'key'   => 'field_optix_home_cta_subtitle',
          'name'  => 'home_cta_subtitle',
          'label' => 'CTA Subtitle',
          'type'  => 'text',
          'default_value' => 'Get 20% Off on All New Arrivals!',
        ],
        [
          'key'   => 'field_optix_home_cta_btn_text',
          'name'  => 'home_cta_btn_text',
          'label' => 'CTA Button Text',
          'type'  => 'text',
          'default_value' => 'Get this Deal',
        ],
        [
          'key'   => 'field_optix_home_cta_btn_url',
          'name'  => 'home_cta_btn_url',
          'label' => 'CTA Button URL',
          'type'  => 'url',
          'default_value' => '/shop/',
        ],
        [
          'key'   => 'field_optix_home_categories_enable',
          'name'  => 'home_categories_enable',
          'label' => 'Enable Categories Section',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_home_categories_heading',
          'name'  => 'home_categories_heading',
          'label' => 'Categories Section Heading',
          'type'  => 'text',
          'default_value' => 'magna aliqua',
        ],
        [
          'key'   => 'field_optix_home_categories_title',
          'name'  => 'home_categories_title',
          'label' => 'Categories Section Title',
          'type'  => 'text',
          'default_value' => 'Product Categories',
        ],
        [
          'key'      => 'field_optix_home_categories',
          'name'     => 'home_categories',
          'label'    => 'Category Items',
          'type'     => 'repeater',
          'layout'   => 'block',
          'sub_fields' => [
            [
              'key'   => 'field_optix_home_cat_title',
              'name'  => 'title',
              'label' => 'Title',
              'type'  => 'text',
            ],
            [
              'key'           => 'field_optix_home_cat_image',
              'name'          => 'image',
              'label'         => 'Image',
              'type'          => 'image',
              'return_format' => 'url',
            ],
            [
              'key'   => 'field_optix_home_cat_bg_class',
              'name'  => 'bg_class',
              'label' => 'Background CSS Class',
              'type'  => 'text',
            ],
            [
              'key'   => 'field_optix_home_cat_url',
              'name'  => 'url',
              'label' => 'Link URL',
              'type'  => 'url',
            ],
          ],
        ],
        [
          'key'   => 'field_optix_home_top_selling_enable',
          'name'  => 'home_top_selling_enable',
          'label' => 'Enable Top Selling Section',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_home_top_selling_title',
          'name'  => 'home_top_selling_title',
          'label' => 'Top Selling Title',
          'type'  => 'text',
          'default_value' => 'Top Selling Products',
        ],
        [
          'key'   => 'field_optix_home_top_selling_count',
          'name'  => 'home_top_selling_count',
          'label' => 'Top Selling Count',
          'type'  => 'number',
          'default_value' => 4,
        ],
        [
          'key'   => 'field_optix_home_testimonials_enable',
          'name'  => 'home_testimonials_enable',
          'label' => 'Enable Testimonials Section',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_home_testimonials_heading',
          'name'  => 'home_testimonials_heading',
          'label' => 'Testimonials Heading',
          'type'  => 'text',
          'default_value' => 'Testimonials',
        ],
        [
          'key'   => 'field_optix_home_testimonials_title',
          'name'  => 'home_testimonials_title',
          'label' => 'Testimonials Title',
          'type'  => 'text',
          'default_value' => 'Our Client Reviews',
        ],
        [
          'key'      => 'field_optix_home_testimonials',
          'name'     => 'home_testimonials',
          'label'    => 'Testimonials',
          'type'     => 'repeater',
          'layout'   => 'block',
          'sub_fields' => [
            [
              'key'   => 'field_optix_home_test_rating',
              'name'  => 'rating',
              'label' => 'Rating (1-5)',
              'type'  => 'number',
              'default_value' => 5,
              'min'   => 1,
              'max'   => 5,
            ],
            [
              'key'   => 'field_optix_home_test_text',
              'name'  => 'text',
              'label' => 'Testimonial Text',
              'type'  => 'textarea',
            ],
            [
              'key'   => 'field_optix_home_test_name',
              'name'  => 'name',
              'label' => 'Name',
              'type'  => 'text',
            ],
            [
              'key'   => 'field_optix_home_test_role',
              'name'  => 'role',
              'label' => 'Role',
              'type'  => 'text',
            ],
            [
              'key'           => 'field_optix_home_test_avatar',
              'name'          => 'avatar',
              'label'         => 'Avatar',
              'type'          => 'image',
              'return_format' => 'url',
            ],
          ],
        ],
        [
          'key'   => 'field_optix_home_instagram_enable',
          'name'  => 'home_instagram_enable',
          'label' => 'Enable Instagram Section',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_home_instagram_heading',
          'name'  => 'home_instagram_heading',
          'label' => 'Instagram Heading',
          'type'  => 'text',
          'default_value' => '@claudia instagram',
        ],
        [
          'key'   => 'field_optix_home_instagram_title',
          'name'  => 'home_instagram_title',
          'label' => 'Instagram Title',
          'type'  => 'text',
          'default_value' => 'Find us On Instagram',
        ],
        [
          'key'      => 'field_optix_home_instagram_images',
          'name'     => 'home_instagram_images',
          'label'    => 'Instagram Images',
          'type'     => 'repeater',
          'layout'   => 'block',
          'sub_fields' => [
            [
              'key'           => 'field_optix_home_insta_image',
              'name'          => 'image',
              'label'         => 'Image',
              'type'          => 'image',
              'return_format' => 'url',
            ],
            [
              'key'   => 'field_optix_home_insta_url',
              'name'  => 'url',
              'label' => 'Link URL',
              'type'  => 'url',
            ],
          ],
        ],
        [
          'key'   => 'field_optix_home_benefits_enable',
          'name'  => 'home_benefits_enable',
          'label' => 'Enable Benefits Section',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'      => 'field_optix_home_benefits',
          'name'     => 'home_benefits',
          'label'    => 'Benefits',
          'type'     => 'repeater',
          'layout'   => 'block',
          'sub_fields' => [
            [
              'key'           => 'field_optix_home_benefit_icon',
              'name'          => 'icon',
              'label'         => 'Icon Image',
              'type'          => 'image',
              'return_format' => 'url',
            ],
            [
              'key'   => 'field_optix_home_benefit_text',
              'name'  => 'text',
              'label' => 'Benefit Text',
              'type'  => 'text',
            ],
          ],
        ],
      ],
      'location' => [
        [ [ 'param' => 'options_page', 'operator' => '==', 'value' => self::PARENT_SLUG . '-home-page' ] ],
      ],
    ] );
  }

  /**
   * About Page tab fields.
   */
  private function register_about_page_fields() {
    acf_add_local_field_group( [
      'key'      => 'group_optix_about_page',
      'title'    => 'About Page Settings',
      'fields'   => [
        [
          'key'   => 'field_optix_about_about_enable',
          'name'  => 'about_about_enable',
          'label' => 'Enable About Us Section',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_about_about_heading',
          'name'  => 'about_about_heading',
          'label' => 'About Us Heading',
          'type'  => 'text',
          'default_value' => 'About Us',
        ],
        [
          'key'   => 'field_optix_about_about_title',
          'name'  => 'about_about_title',
          'label' => 'About Us Title',
          'type'  => 'text',
          'default_value' => 'Unique Clothes & Toys For Kids',
        ],
        [
          'key'   => 'field_optix_about_about_text_1',
          'name'  => 'about_about_text_1',
          'label' => 'About Text (Paragraph 1)',
          'type'  => 'textarea',
          'default_value' => 'Nostrud officia tempor laboris ullamco id labore consequat enim...',
        ],
        [
          'key'   => 'field_optix_about_about_text_2',
          'name'  => 'about_about_text_2',
          'label' => 'About Text (Paragraph 2)',
          'type'  => 'textarea',
          'default_value' => 'Ea voluptate reprehenderit Lorem irure minim ea...',
        ],
        [
          'key'           => 'field_optix_about_about_image',
          'name'          => 'about_about_image',
          'label'         => 'About Us Image',
          'type'          => 'image',
          'return_format' => 'url',
        ],
        [
          'key'   => 'field_optix_about_about_btn_text',
          'name'  => 'about_about_btn_text',
          'label' => 'About Button Text',
          'type'  => 'text',
          'default_value' => 'Shop Now',
        ],
        [
          'key'   => 'field_optix_about_about_btn_url',
          'name'  => 'about_about_btn_url',
          'label' => 'About Button URL',
          'type'  => 'url',
          'default_value' => '/shop/',
        ],
        [
          'key'   => 'field_optix_about_mission_enable',
          'name'  => 'about_mission_enable',
          'label' => 'Enable Mission Section',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_about_mission_heading',
          'name'  => 'about_mission_heading',
          'label' => 'Mission Heading',
          'type'  => 'text',
          'default_value' => 'Our Mission',
        ],
        [
          'key'   => 'field_optix_about_mission_title',
          'name'  => 'about_mission_title',
          'label' => 'Mission Title',
          'type'  => 'text',
          'default_value' => 'Start of Countless Collection.',
        ],
        [
          'key'   => 'field_optix_about_mission_text_1',
          'name'  => 'about_mission_text_1',
          'label' => 'Mission Text (Paragraph 1)',
          'type'  => 'textarea',
        ],
        [
          'key'   => 'field_optix_about_mission_text_2',
          'name'  => 'about_mission_text_2',
          'label' => 'Mission Text (Paragraph 2)',
          'type'  => 'textarea',
        ],
        [
          'key'   => 'field_optix_about_team_enable',
          'name'  => 'about_team_enable',
          'label' => 'Enable Team Section',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_about_team_heading',
          'name'  => 'about_team_heading',
          'label' => 'Team Heading',
          'type'  => 'text',
          'default_value' => 'Experts Team',
        ],
        [
          'key'   => 'field_optix_about_team_title',
          'name'  => 'about_team_title',
          'label' => 'Team Title',
          'type'  => 'text',
          'default_value' => 'Our Team Members',
        ],
        [
          'key'      => 'field_optix_about_team_members',
          'name'     => 'about_team_members',
          'label'    => 'Team Members',
          'type'     => 'repeater',
          'layout'   => 'block',
          'sub_fields' => [
            [
              'key'           => 'field_optix_about_team_member_image',
              'name'          => 'image',
              'label'         => 'Member Photo',
              'type'          => 'image',
              'return_format' => 'url',
            ],
            [
              'key'   => 'field_optix_about_team_member_name',
              'name'  => 'name',
              'label' => 'Member Name',
              'type'  => 'text',
            ],
            [
              'key'   => 'field_optix_about_team_member_role',
              'name'  => 'role',
              'label' => 'Role / Designation',
              'type'  => 'text',
            ],
            [
              'key'   => 'field_optix_about_team_member_fb',
              'name'  => 'facebook',
              'label' => 'Facebook URL',
              'type'  => 'url',
            ],
            [
              'key'   => 'field_optix_about_team_member_ig',
              'name'  => 'instagram',
              'label' => 'Instagram URL',
              'type'  => 'url',
            ],
            [
              'key'   => 'field_optix_about_team_member_yt',
              'name'  => 'youtube',
              'label' => 'YouTube URL',
              'type'  => 'url',
            ],
          ],
        ],
        [
          'key'   => 'field_optix_about_categories_enable',
          'name'  => 'about_categories_enable',
          'label' => 'Enable Categories Section',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_about_instagram_enable',
          'name'  => 'about_instagram_enable',
          'label' => 'Enable Instagram Section',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_about_benefits_enable',
          'name'  => 'about_benefits_enable',
          'label' => 'Enable Benefits Section',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
      ],
      'location' => [
        [ [ 'param' => 'options_page', 'operator' => '==', 'value' => self::PARENT_SLUG . '-about-page' ] ],
      ],
    ] );
  }

  /**
   * Blog tab fields.
   */
  private function register_blog_fields() {
    acf_add_local_field_group( [
      'key'      => 'group_optix_blog',
      'title'    => 'Blog Settings',
      'fields'   => [
        [
          'key'   => 'field_optix_blog_title',
          'name'  => 'blog_title',
          'label' => 'Blog Page Title',
          'type'  => 'text',
          'default_value' => 'Blog',
        ],
        [
          'key'   => 'field_optix_blog_enable',
          'name'  => 'blog_enable',
          'label' => 'Enable Blog Section',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'      => 'field_optix_blog_tabs',
          'name'     => 'blog_tabs',
          'label'    => 'Blog Category Tabs',
          'type'     => 'repeater',
          'layout'   => 'table',
          'instructions' => 'First tab is "All" (shows all posts). Each additional tab filters by category slug.',
          'sub_fields' => [
            [
              'key'   => 'field_optix_blog_tab_label',
              'name'  => 'tab_label',
              'label' => 'Tab Label',
              'type'  => 'text',
            ],
          ],
        ],
        [
          'key'   => 'field_optix_blog_posts_per_page',
          'name'  => 'blog_posts_per_page',
          'label' => 'Posts Per Page',
          'type'  => 'number',
          'default_value' => 6,
        ],
      ],
      'location' => [
        [ [ 'param' => 'options_page', 'operator' => '==', 'value' => self::PARENT_SLUG . '-blog' ] ],
      ],
    ] );
  }

  /**
   * Contact tab fields.
   */
  private function register_contact_fields() {
    acf_add_local_field_group( [
      'key'      => 'group_optix_contact',
      'title'    => 'Contact Settings',
      'fields'   => [
        [
          'key'   => 'field_optix_contact_info_heading',
          'name'  => 'contact_info_heading',
          'label' => 'Info Section Heading',
          'type'  => 'text',
          'default_value' => 'Get in touch',
        ],
        [
          'key'   => 'field_optix_contact_info_title',
          'name'  => 'contact_info_title',
          'label' => 'Info Section Title',
          'type'  => 'text',
          'default_value' => 'Contact Information',
        ],
        [
          'key'           => 'field_optix_contact_location_icon',
          'name'          => 'contact_location_icon',
          'label'         => 'Location Icon',
          'type'          => 'image',
          'return_format' => 'url',
        ],
        [
          'key'   => 'field_optix_contact_location_title',
          'name'  => 'contact_location_title',
          'label' => 'Location Card Title',
          'type'  => 'text',
          'default_value' => 'Our Location',
        ],
        [
          'key'   => 'field_optix_contact_location_text',
          'name'  => 'contact_location_text',
          'label' => 'Location Address',
          'type'  => 'textarea',
        ],
        [
          'key'           => 'field_optix_contact_phone_icon',
          'name'          => 'contact_phone_icon',
          'label'         => 'Phone Icon',
          'type'          => 'image',
          'return_format' => 'url',
        ],
        [
          'key'   => 'field_optix_contact_phone_title',
          'name'  => 'contact_phone_title',
          'label' => 'Phone Card Title',
          'type'  => 'text',
          'default_value' => 'Phone Number',
        ],
        [
          'key'      => 'field_optix_contact_phone_numbers',
          'name'     => 'contact_phone_numbers',
          'label'    => 'Phone Numbers',
          'type'     => 'repeater',
          'layout'   => 'table',
          'sub_fields' => [
            [
              'key'   => 'field_optix_contact_phone_num',
              'name'  => 'number',
              'label' => 'Phone Number',
              'type'  => 'text',
            ],
          ],
        ],
        [
          'key'           => 'field_optix_contact_email_icon',
          'name'          => 'contact_email_icon',
          'label'         => 'Email Icon',
          'type'          => 'image',
          'return_format' => 'url',
        ],
        [
          'key'   => 'field_optix_contact_email_title',
          'name'  => 'contact_email_title',
          'label' => 'Email Card Title',
          'type'  => 'text',
          'default_value' => 'Email Us:',
        ],
        [
          'key'      => 'field_optix_contact_email_addresses',
          'name'     => 'contact_email_addresses',
          'label'    => 'Email Addresses',
          'type'     => 'repeater',
          'layout'   => 'table',
          'sub_fields' => [
            [
              'key'   => 'field_optix_contact_email_addr',
              'name'  => 'address',
              'label' => 'Email Address',
              'type'  => 'email',
            ],
          ],
        ],
        [
          'key'   => 'field_optix_contact_form_heading',
          'name'  => 'contact_form_heading',
          'label' => 'Form Section Heading',
          'type'  => 'text',
          'default_value' => 'Have Any Questions?',
        ],
        [
          'key'   => 'field_optix_contact_form_title',
          'name'  => 'contact_form_title',
          'label' => 'Form Section Title',
          'type'  => 'text',
          'default_value' => 'Send Your Massage',
        ],
        [
          'key'   => 'field_optix_contact_form_btn_text',
          'name'  => 'contact_form_btn_text',
          'label' => 'Form Submit Button Text',
          'type'  => 'text',
          'default_value' => 'Send Message',
        ],
        [
          'key'   => 'field_optix_contact_map_embed',
          'name'  => 'contact_map_embed',
          'label' => 'Google Maps Embed URL',
          'type'  => 'textarea',
          'default_value' => 'https://www.google.com/maps/embed?pb=...',
          'instructions' => 'Paste the full iframe src URL from Google Maps embed.',
        ],
      ],
      'location' => [
        [ [ 'param' => 'options_page', 'operator' => '==', 'value' => self::PARENT_SLUG . '-contact' ] ],
      ],
    ] );
  }

  /**
   * Shop tab fields.
   */
  private function register_shop_fields() {
    acf_add_local_field_group( [
      'key'      => 'group_optix_shop',
      'title'    => 'Shop Settings',
      'fields'   => [
        [
          'key'   => 'field_optix_shop_title',
          'name'  => 'shop_title',
          'label' => 'Shop Page Title',
          'type'  => 'text',
          'default_value' => 'Shop',
        ],
        [
          'key'   => 'field_optix_shop_enable',
          'name'  => 'shop_enable',
          'label' => 'Enable Shop Section',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_shop_products_per_page',
          'name'  => 'shop_products_per_page',
          'label' => 'Products Per Page',
          'type'  => 'number',
          'default_value' => 12,
        ],
        [
          'key'   => 'field_optix_shop_columns',
          'name'  => 'shop_columns',
          'label' => 'Product Grid Columns',
          'type'  => 'number',
          'default_value' => 3,
        ],
        [
          'key'   => 'field_optix_shop_enable_sidebar',
          'name'  => 'shop_enable_sidebar',
          'label' => 'Enable Shop Sidebar',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
      ],
      'location' => [
        [ [ 'param' => 'options_page', 'operator' => '==', 'value' => self::PARENT_SLUG . '-shop' ] ],
      ],
    ] );
  }

  /**
   * Product Detail tab fields.
   */
  private function register_product_detail_fields() {
    acf_add_local_field_group( [
      'key'      => 'group_optix_product_detail',
      'title'    => 'Product Detail Settings',
      'fields'   => [
        [
          'key'   => 'field_optix_pd_title',
          'name'  => 'product_detail_title',
          'label' => 'Page Title',
          'type'  => 'text',
          'default_value' => 'Product Detail',
        ],
        [
          'key'   => 'field_optix_pd_related_count',
          'name'  => 'product_detail_related_count',
          'label' => 'Related Products Count',
          'type'  => 'number',
          'default_value' => 12,
        ],
        [
          'key'   => 'field_optix_pd_tabs_enable',
          'name'  => 'product_detail_tabs_enable',
          'label' => 'Enable Description/Info/Reviews Tabs',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_pd_related_title',
          'name'  => 'product_detail_related_title',
          'label' => 'Related Products Title',
          'type'  => 'text',
          'default_value' => 'Related Products',
        ],
        [
          'key'   => 'field_optix_pd_sale_tag',
          'name'  => 'product_detail_sale_tag',
          'label' => 'Sale Tag Text',
          'type'  => 'text',
          'default_value' => 'Sale',
        ],
        [
          'key'   => 'field_optix_pd_more_title',
          'name'  => 'product_detail_more_title',
          'label' => 'More Products Title',
          'type'  => 'text',
          'default_value' => 'More Products',
        ],
      ],
      'location' => [
        [ [ 'param' => 'options_page', 'operator' => '==', 'value' => self::PARENT_SLUG . '-product-detail' ] ],
      ],
    ] );
  }

  /**
   * Cart & Checkout tab fields.
   */
  private function register_cart_checkout_fields() {
    acf_add_local_field_group( [
      'key'      => 'group_optix_cart_checkout',
      'title'    => 'Cart & Checkout Settings',
      'fields'   => [
        [
          'key'   => 'field_optix_cart_title',
          'name'  => 'cart_title',
          'label' => 'Cart Page Title',
          'type'  => 'text',
          'default_value' => 'Cart',
        ],
        [
          'key'   => 'field_optix_checkout_title',
          'name'  => 'checkout_title',
          'label' => 'Checkout Page Title',
          'type'  => 'text',
          'default_value' => 'Checkout',
        ],
        [
          'key'   => 'field_optix_cart_coupon_enable',
          'name'  => 'cart_coupon_enable',
          'label' => 'Enable Coupon Code Field',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_cart_cross_sell_enable',
          'name'  => 'cart_cross_sell_enable',
          'label' => 'Enable Cross-Sell Products',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
      ],
      'location' => [
        [ [ 'param' => 'options_page', 'operator' => '==', 'value' => self::PARENT_SLUG . '-cart-checkout' ] ],
      ],
    ] );
  }

  /**
   * Other Pages tab fields.
   */
  private function register_other_pages_fields() {
    acf_add_local_field_group( [
      'key'      => 'group_optix_other_pages',
      'title'    => 'Other Pages Settings',
      'fields'   => [
        [
          'key'   => 'field_optix_pages_coming_soon_heading',
          'name'  => 'coming_soon_heading',
          'label' => 'Coming Soon Heading',
          'type'  => 'text',
          'default_value' => 'We are Coming Soon',
        ],
        [
          'key'   => 'field_optix_pages_coming_soon_subtitle',
          'name'  => 'coming_soon_subtitle',
          'label' => 'Coming Soon Subtitle',
          'type'  => 'text',
          'default_value' => 'Our Website is under construction',
        ],
        [
          'key'           => 'field_optix_pages_coming_soon_logo',
          'name'          => 'coming_soon_logo',
          'label'         => 'Coming Soon Logo',
          'type'          => 'image',
          'return_format' => 'url',
        ],
        [
          'key'   => 'field_optix_pages_coming_soon_date',
          'name'  => 'coming_soon_date',
          'label' => 'Coming Soon Target Date',
          'type'  => 'date_picker',
          'instructions' => 'Set the target date for the countdown timer.',
        ],
        [
          'key'   => 'field_optix_pages_404_title',
          'name'  => 'pages_404_title',
          'label' => '404 Page Title',
          'type'  => 'text',
          'default_value' => '404',
        ],
        [
          'key'   => 'field_optix_pages_404_text',
          'name'  => 'pages_404_text',
          'label' => '404 Page Message',
          'type'  => 'textarea',
          'default_value' => 'Oops! This page could not be found.',
        ],
        [
          'key'   => 'field_optix_pages_404_btn_text',
          'name'  => 'pages_404_btn_text',
          'label' => '404 Button Text',
          'type'  => 'text',
          'default_value' => 'Back to Home',
        ],
      ],
      'location' => [
        [ [ 'param' => 'options_page', 'operator' => '==', 'value' => self::PARENT_SLUG . '-pages' ] ],
      ],
    ] );
  }

  /**
   * Animations tab fields.
   */
  private function register_animations_fields() {
    acf_add_local_field_group( [
      'key'      => 'group_optix_animations',
      'title'    => 'Animation Settings',
      'fields'   => [
        [
          'key'   => 'field_optix_animations_enable',
          'name'  => 'animations_enable',
          'label' => 'Enable WOW.js Animations',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_animations_duration',
          'name'  => 'animation_duration',
          'label' => 'Default Animation Duration',
          'type'  => 'text',
          'default_value' => '2s',
        ],
        [
          'key'   => 'field_optix_animations_delay',
          'name'  => 'animation_delay',
          'label' => 'Default Animation Delay',
          'type'  => 'text',
          'default_value' => '0.05s',
        ],
        [
          'key'   => 'field_optix_animations_mobile',
          'name'  => 'animations_mobile',
          'label' => 'Enable Animations on Mobile',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_effect_3d_enable',
          'name'  => 'effect_3d_enable',
          'label' => 'Enable 3D Tilt Effects',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 0,
        ],
        [
          'key'   => 'field_optix_effect_3d_perspective',
          'name'  => 'effect_3d_perspective',
          'label' => '3D Perspective (px)',
          'type'  => 'number',
          'default_value' => 1000,
          'min' => 100,
          'max' => 2000,
          'step' => 50,
        ],
        [
          'key'   => 'field_optix_effect_3d_rotate_x',
          'name'  => 'effect_3d_rotate_x',
          'label' => 'Max X Rotation (deg)',
          'type'  => 'number',
          'default_value' => 5,
          'min' => 1,
          'max' => 30,
          'step' => 1,
        ],
        [
          'key'   => 'field_optix_effect_3d_rotate_y',
          'name'  => 'effect_3d_rotate_y',
          'label' => 'Max Y Rotation (deg)',
          'type'  => 'number',
          'default_value' => 5,
          'min' => 1,
          'max' => 30,
          'step' => 1,
        ],
      ],
      'location' => [
        [ [ 'param' => 'options_page', 'operator' => '==', 'value' => self::PARENT_SLUG . '-animations' ] ],
      ],
    ] );
  }

  /**
   * Advanced tab fields.
   */
  private function register_advanced_fields() {
    acf_add_local_field_group( [
      'key'      => 'group_optix_advanced',
      'title'    => 'Advanced Settings',
      'fields'   => [
        [
          'key'   => 'field_optix_advanced_wc_enable',
          'name'  => 'advanced_wc_enable',
          'label' => 'Enable WooCommerce Features',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_advanced_preloader_toggle',
          'name'  => 'advanced_preloader',
          'label' => 'Enable Preloader',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_advanced_breadcrumbs',
          'name'  => 'advanced_breadcrumbs',
          'label' => 'Enable Breadcrumbs',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_advanced_back_to_top',
          'name'  => 'advanced_back_to_top',
          'label' => 'Enable Back to Top Button',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 1,
        ],
        [
          'key'   => 'field_optix_maintenance_mode',
          'name'  => 'maintenance_mode_enable',
          'label' => 'Enable Maintenance Mode',
          'type'  => 'true_false',
          'ui'    => true,
          'default_value' => 0,
          'instructions' => 'Show a maintenance page to non-logged-in visitors. Admins can still browse the site.',
        ],
      ],
      'location' => [
        [ [ 'param' => 'options_page', 'operator' => '==', 'value' => self::PARENT_SLUG . '-advanced' ] ],
      ],
    ] );
  }

  /**
   * Portfolio tab fields.
   */
  private function register_portfolio_fields() {
    acf_add_local_field_group( [
      'key'      => 'group_optix_portfolio',
      'title'    => 'Portfolio Settings',
      'fields'   => [
        [
          'key'   => 'field_optix_portfolio_title',
          'name'  => 'portfolio_title',
          'label' => 'Archive Page Title',
          'type'  => 'text',
          'default_value' => 'Our Projects',
        ],
        [
          'key'   => 'field_optix_portfolio_projects_per_page',
          'name'  => 'portfolio_projects_per_page',
          'label' => 'Projects Per Page',
          'type'  => 'number',
          'default_value' => 9,
        ],
        [
          'key'   => 'field_optix_portfolio_columns',
          'name'  => 'portfolio_columns',
          'label' => 'Grid Columns',
          'type'  => 'number',
          'default_value' => 3,
        ],
        [
          'key'   => 'field_optix_portfolio_gallery',
          'name'  => 'portfolio_gallery',
          'label' => 'Gallery Images',
          'type'  => 'gallery',
          'return_format' => 'id',
          'instructions' => 'Select images for the project gallery.',
        ],
        [
          'key'   => 'field_optix_portfolio_client',
          'name'  => 'portfolio_client',
          'label' => 'Client Name',
          'type'  => 'text',
        ],
        [
          'key'   => 'field_optix_portfolio_date',
          'name'  => 'portfolio_date',
          'label' => 'Project Date',
          'type'  => 'date_picker',
        ],
        [
          'key'   => 'field_optix_portfolio_url',
          'name'  => 'portfolio_url',
          'label' => 'External Project URL',
          'type'  => 'url',
          'instructions' => 'Link to the live project (opens in new tab).',
        ],
      ],
      'location' => [
        [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'portfolio' ] ],
      ],
    ] );
  }

  /**
   * Import / Export tab fields.
   *
   * Points users to the dedicated Tools > Optix Import/Export page
   * where actual export file download and JSON import are handled.
   */
  private function register_import_export_fields() {
    acf_add_local_field_group( [
      'key'      => 'group_optix_import_export',
      'title'    => 'Import / Export',
      'fields'   => [
        [
          'key'   => 'field_optix_import_export_message',
          'name'  => 'import_export_message',
          'label' => '',
          'type'  => 'message',
          'message' => '<p><a href="' . esc_url( admin_url( 'tools.php?page=optix-import-export' ) ) . '" class="button button-primary">Open Import / Export Tool</a></p><p>Use the dedicated tool to download a JSON backup of all theme options or upload a previously exported file to restore settings.</p>',
        ],
      ],
      'location' => [
        [ [ 'param' => 'options_page', 'operator' => '==', 'value' => self::PARENT_SLUG . '-import-export' ] ],
      ],
    ] );
  }
}
