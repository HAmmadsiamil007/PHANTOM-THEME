<?php
/**
 * Template Name: Kids Collection Page
 *
 * Loads Kids Collection inner page content based on page slug.
 * Pure copy method — only asset paths use WordPress functions.
 *
 * @package optix
 */

namespace Optix;

global $wp_query;
$page_id = $wp_query->get_queried_object_id();

// Let WooCommerce handle its own pages (shop, cart, checkout, my-account).
if ( class_exists( 'WooCommerce' ) ) {
  $is_wc   = is_shop() || is_product() || is_product_category() || is_product_tag()
          || is_cart() || is_checkout() || is_account_page();

  if ( $is_wc ) {
    get_header();
    echo '<main id="content" class="woocommerce-kids-wrapper">';
    if ( is_cart() || is_checkout() || is_account_page() ) {
      echo '<div class="woocommerce">';
      the_content();
      echo '</div>';
    } else {
      woocommerce_content();
    }
    echo '</main>';
    get_footer();
    return;
  }
}

get_header();

$post_obj = get_queried_object();
$slug = $post_obj && isset( $post_obj->post_name ) ? $post_obj->post_name : '';
$parent_slug = '';
if ( $post_obj && isset( $post_obj->post_parent ) && $post_obj->post_parent > 0 ) {
  $parent_slug = get_post_field( 'post_name', $post_obj->post_parent );
}
$slug_map = [
  'error'           => '404',
  'page-not-found'  => '404',
];
$template_dir = get_template_directory() . '/templates/kids-collection/';
$template = '404';
if ( $slug && isset( $slug_map[ $slug ] ) ) {
  $template = $slug_map[ $slug ];
} elseif ( $slug && file_exists( $template_dir . $slug . '.php' ) ) {
  $template = $slug;
} elseif ( $parent_slug && file_exists( $template_dir . $parent_slug . '.php' ) ) {
  $template = $parent_slug;
} elseif ( $page_id && file_exists( $template_dir . $page_id . '.php' ) ) {
  $template = (string) $page_id;
}
echo '<main id="main" class="site-main">';
get_template_part( 'templates/kids-collection/' . $template );
echo '</main>';

get_footer();
