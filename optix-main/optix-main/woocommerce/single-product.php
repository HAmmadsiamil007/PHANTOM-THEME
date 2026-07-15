<?php
/**
 * WooCommerce Single Product
 *
 * @package optix
 */

namespace Optix;

if ( ! class_exists( 'WooCommerce' ) ) {
  return;
}

get_header(); ?>

<main id="content" class="woocommerce-kids-wrapper">

  <?php
  $kc_img = kc_img_base();

  $related_title = optix_get_option( 'product_related_title', 'Related Products' );
  $related_count = (int) optix_get_option( 'product_related_count', 12 );
  ?>

  <!-- Sub Banner -->
  <section class="sub-banner-con position-relative float-left w-100 gradient-overlay d-flex align-items-center justify-content-center">
    <div class="container">
      <div class="col-xl-12 col-lg-12 mr-auto ml-auto">
        <div class="sub-banner-inner-con text-center">
          <h1><?php echo esc_html( get_the_title() ); ?></h1>
          <div class="breadcrumb-con d-inline-block">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'optix' ); ?></a></li>
              <li class="breadcrumb-item"><a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Shop', 'optix' ); ?></a></li>
              <li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( get_the_title() ); ?></li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Product Detail -->
  <section class="types-con types2-con float-left w-100 position-relative main-box">
    <div class="main-container">
      <div class="row">

        <div class="col-lg-5 col-12">
          <?php
          /**
           * Hook: woocommerce_before_single_product_summary.
           */
          do_action( 'woocommerce_before_single_product_summary' );
          ?>
        </div>

        <div class="col-lg-7 col-12">
          <div class="product-summary">
            <?php
            /**
             * Hook: woocommerce_single_product_summary.
             */
            do_action( 'woocommerce_single_product_summary' );
            ?>
          </div>
        </div>

      </div>

      <div class="row mt-5">
        <div class="col-12">
          <?php
          /**
           * Hook: woocommerce_after_single_product_summary.
           */
          do_action( 'woocommerce_after_single_product_summary' );
          ?>
        </div>
      </div>

      <?php
      // Related Products
      $related_args = [
        'posts_per_page' => $related_count,
        'columns'        => 4,
      ];
      woocommerce_related_products( $related_args );
      ?>
    </div>
  </section>

</main>

<?php get_footer(); ?>
