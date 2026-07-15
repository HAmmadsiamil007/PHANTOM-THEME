<?php
/**
 * WooCommerce My Account
 *
 * @package optix
 */

namespace Optix;

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

get_header(); ?>

<main id="content" class="woocommerce-kids-wrapper">

  <?php
  $kc_img = kc_img_base();
  ?>

  <!-- Sub Banner -->
  <section class="sub-banner-con position-relative float-left w-100 gradient-overlay d-flex align-items-center justify-content-center">
    <div class="container">
      <div class="col-xl-12 col-lg-12 mr-auto ml-auto">
        <div class="sub-banner-inner-con text-center">
          <h1><?php esc_html_e( 'My Account', 'optix' ); ?></h1>
          <div class="breadcrumb-con d-inline-block">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'optix' ); ?></a></li>
              <li class="breadcrumb-item active" aria-current="page"><?php esc_html_e( 'My Account', 'optix' ); ?></li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="float-left w-100 cart-con position-relative padding-top padding-bottom main-box">
    <div class="main-container">
      <div class="row">

        <div class="col-lg-3 col-12 mb-4 mb-lg-0">
          <div class="account-navigation">
            <?php
            /**
             * My Account navigation.
             */
            do_action( 'woocommerce_account_navigation' );
            ?>
          </div>
        </div>

        <div class="col-lg-9 col-12">
          <div class="account-content">
            <?php
            /**
             * My Account content.
             */
            do_action( 'woocommerce_account_content' );
            ?>
          </div>
        </div>

      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>
