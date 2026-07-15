<?php
/**
 * WooCommerce Hooks
 *
 * Cart fragments, mini-cart, style cleanup, and wrapper overrides.
 *
 * @package optix
 */

namespace Optix;

if ( ! class_exists( 'WooCommerce' ) ) {
  return;
}

/**
 * 1. Cart fragments — live header count via AJAX
 */
add_filter( 'woocommerce_add_to_cart_fragments', __NAMESPACE__ . '\cart_fragments' );
function cart_fragments( $fragments ) {
  if ( ! is_object( WC()->cart ) ) {
    return $fragments;
  }

  $fragments['.cart-count'] = '<span class="cart-count">' . esc_html( WC()->cart->get_cart_contents_count() ) . '</span>';

  ob_start();
  woocommerce_mini_cart();
  $fragments['.mini-cart-dropdown'] = '<div class="mini-cart-dropdown">' . ob_get_clean() . '</div>';

  return $fragments;
}

/**
 * 2. Remove default WooCommerce styles
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * 3. Enqueue theme WooCommerce scripts
 */
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_wc_scripts', 30 );
function enqueue_wc_scripts() {
  $is_wc_page = is_woocommerce() || is_cart() || is_checkout() || is_account_page();
  if ( ! $is_wc_page ) {
    return;
  }

  $wc_js = get_theme_file_path( 'assets/kids-collection/js/woocommerce.js' );
  if ( ! file_exists( $wc_js ) ) {
    return;
  }

  wp_enqueue_script(
    'kc-woocommerce',
    get_theme_file_uri( 'assets/kids-collection/js/woocommerce.js' ),
    [ 'jquery', 'wc-cart-fragments' ],
    filemtime( $wc_js ),
    true
  );

}

/**
 * 4. Remove default WC wrappers — theme templates handle wrapping.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * 5. Remove default WC sidebar
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * 6. WC notices at top of WC content area
 */
add_action( 'woocommerce_before_main_content', __NAMESPACE__ . '\wc_notices_wrapper_start', 5 );
function wc_notices_wrapper_start() {
  echo '<div class="kc-wc-notices">';
  wc_print_notices();
  echo '</div>';
}

/**
 * 7. Register optional WC customizations gated by the advanced_wc_enable option.
 */
add_action( 'after_setup_theme', __NAMESPACE__ . '\register_optional_wc_hooks' );
function register_optional_wc_hooks() {
  if ( ! optix_get_option( 'advanced_wc_enable', 1 ) ) {
    return;
  }

  // 7a. Make phone required in checkout
  add_filter( 'woocommerce_default_address_fields', __NAMESPACE__ . '\wc_require_phone_field' );
  add_filter( 'woocommerce_billing_fields', __NAMESPACE__ . '\wc_customize_billing_fields' );
  add_filter( 'woocommerce_shipping_fields', __NAMESPACE__ . '\wc_customize_shipping_fields' );

  // 7b. Redirect to shop after add-to-cart
  add_filter( 'woocommerce_add_to_cart_redirect', __NAMESPACE__ . '\wc_add_to_cart_redirect' );

  // 7c. Cart item thumbnail size
  add_filter( 'woocommerce_cart_item_thumbnail', __NAMESPACE__ . '\wc_cart_item_thumbnail', 10, 2 );

  // 7d. Shop columns & products per page
  add_filter( 'loop_shop_columns', __NAMESPACE__ . '\wc_shop_columns' );
  add_filter( 'loop_shop_per_page', __NAMESPACE__ . '\wc_shop_per_page' );

  // 7e. Related products
  add_filter( 'woocommerce_output_related_products_args', __NAMESPACE__ . '\wc_related_products_args' );
}

/**
 * 7a. Make phone field required in default address fields.
 */
function wc_require_phone_field( $fields ) {
  if ( isset( $fields['phone'] ) ) {
    $fields['phone']['required'] = true;
  }
  return $fields;
}

/**
 * 7a. Customize billing fields — add placeholder and class.
 */
function wc_customize_billing_fields( $fields ) {
  if ( isset( $fields['billing_phone'] ) ) {
    $fields['billing_phone']['required'] = true;
    $fields['billing_phone']['placeholder'] = esc_attr__( 'Phone number', 'optix' );
  }
  if ( isset( $fields['billing_email'] ) ) {
    $fields['billing_email']['placeholder'] = esc_attr__( 'Email address', 'optix' );
  }
  return $fields;
}

/**
 * 7a. Customize shipping fields.
 */
function wc_customize_shipping_fields( $fields ) {
  if ( isset( $fields['shipping_phone'] ) ) {
    $fields['shipping_phone']['required'] = true;
    $fields['shipping_phone']['placeholder'] = esc_attr__( 'Phone number', 'optix' );
  }
  return $fields;
}

/**
 * 7b. Redirect to shop page after adding to cart.
 */
function wc_add_to_cart_redirect() {
  return wc_get_page_permalink( 'shop' );
}

/**
 * 7c. Use optix_shop_thumbnail for cart thumbnails.
 */
function wc_cart_item_thumbnail( $thumbnail, $cart_item ) {
  if ( isset( $cart_item['data'] ) ) {
    $product_id = $cart_item['data']->get_id();
    return wp_get_attachment_image(
      get_post_thumbnail_id( $product_id ),
      'optix_shop_thumbnail',
      false,
      [ 'class' => 'kc-cart-thumb' ]
    );
  }
  return $thumbnail;
}

/**
 * 7d. Set shop columns from theme option (default 3).
 */
function wc_shop_columns() {
  return absint( optix_get_option( 'shop_columns', 3 ) );
}

/**
 * 7d. Set products per page from theme option (default 12).
 */
function wc_shop_per_page() {
  return absint( optix_get_option( 'shop_products_per_page', 12 ) );
}

/**
 * 7e. Limit related products to 4.
 */
function wc_related_products_args( $args ) {
  $args['posts_per_page'] = 4;
  $args['columns'] = 4;
  return $args;
}
