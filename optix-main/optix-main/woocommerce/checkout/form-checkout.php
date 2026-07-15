<?php
/**
 * WooCommerce Checkout
 *
 * @package optix
 */

namespace Optix;

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

get_header(); ?>

<main id="content" class="woocommerce-kids-wrapper">

  <!-- Sub Banner -->
  <section class="sub-banner-con position-relative float-left w-100 gradient-overlay d-flex align-items-center justify-content-center">
    <div class="container">
      <div class="col-xl-12 col-lg-12 mr-auto ml-auto">
        <div class="sub-banner-inner-con text-center">
          <h1><?php echo esc_html( optix_get_option( 'checkout_title', 'Checkout' ) ); ?></h1>
          <div class="breadcrumb-con d-inline-block">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'optix' ); ?></a></li>
              <li class="breadcrumb-item"><a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Shop', 'optix' ); ?></a></li>
              <li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( optix_get_option( 'checkout_title', 'Checkout' ) ); ?></li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php
  // If checkout is not enabled or cart is empty, redirect.
  if ( ! WC()->cart || WC()->cart->is_empty() ) :
    wc_get_template( 'cart/cart-empty.php' );
  else :
  ?>

  <section class="cart-con checkout-con position-relative float-left w-100 padding-top padding-bottom padding-rl">
    <div class="container">

      <?php wc_print_notices(); ?>

      <div class="row">

        <div class="col-lg-7 col-12">
          <div class="checkout-billing">
            <?php
            do_action( 'woocommerce_checkout_before_customer_details' );

            do_action( 'woocommerce_checkout_billing' );
            do_action( 'woocommerce_checkout_shipping' );

            do_action( 'woocommerce_checkout_after_customer_details' );
            ?>
          </div>
        </div>

        <div class="col-lg-5 col-12">
          <div class="checkout-order-summary">
            <h3><?php esc_html_e( 'Your order', 'optix' ); ?></h3>
            <?php
            do_action( 'woocommerce_checkout_before_order_review_heading' );

            do_action( 'woocommerce_checkout_order_review' );
            ?>
          </div>
        </div>

      </div>

    </div>
  </section>

  <?php endif; ?>

</main>

<?php get_footer(); ?>
