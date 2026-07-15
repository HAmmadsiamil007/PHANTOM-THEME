<?php
/**
 * WooCommerce Template
 *
 * Used by WooCommerce as the page template for Shop, Cart, Checkout,
 * My Account, and Single Product pages.
 *
 * @package optix
 */

namespace Optix;

get_header(); ?>

<main id="content" class="woocommerce-kids-wrapper">
  <?php woocommerce_content(); ?>
</main>

<?php get_footer(); ?>
