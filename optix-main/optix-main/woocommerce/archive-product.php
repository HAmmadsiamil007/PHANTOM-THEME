<?php
/**
 * WooCommerce Archive Product (Shop & Category)
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
  $kc_img      = kc_img_base();
  $columns     = (int) optix_get_option( 'shop_columns', 3 );
  $enable_side = (bool) optix_get_option( 'shop_enable_sidebar', 1 );
  $cols        = $enable_side ? 'col-lg-9' : 'col-lg-12';
  $sidebar_col = 'col-lg-3';
  $loop_cols   = max( 1, min( 6, $columns ) );
  $boot_col    = 'col-xl-' . ( 12 / $loop_cols ) . ' col-lg-6 col-md-6';

  if ( $loop_cols < 1 || $loop_cols > 6 ) {
    $boot_col = 'col-lg-4 col-md-6';
  }
  ?>

  <!-- Sub Banner -->
  <section class="sub-banner-con position-relative float-left w-100 gradient-overlay d-flex align-items-center justify-content-center">
    <div class="container">
      <div class="col-xl-12 col-lg-12 mr-auto ml-auto">
        <div class="sub-banner-inner-con text-center">
          <?php if ( is_shop() ) : ?>
            <h1><?php echo esc_html( optix_get_option( 'shop_title', 'Shop' ) ); ?></h1>
          <?php elseif ( is_product_category() ) : ?>
            <h1><?php echo esc_html( single_cat_title( '', false ) ); ?></h1>
          <?php elseif ( is_product_tag() ) : ?>
            <h1><?php echo esc_html( single_tag_title( '', false ) ); ?></h1>
          <?php else : ?>
            <h1><?php esc_html_e( 'Shop', 'optix' ); ?></h1>
          <?php endif; ?>
          <div class="breadcrumb-con d-inline-block">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'optix' ); ?></a></li>
              <li class="breadcrumb-item active" aria-current="page">
                <?php echo esc_html( optix_get_option( 'shop_title', 'Shop' ) ); ?>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Products -->
  <section class="shop-con feature-con position-relative float-left w-100 padding-top padding-bottom main-box">
    <div class="main-container">
      <div class="row">

        <?php if ( $enable_side ) : ?>
        <div class="<?php echo esc_attr( $sidebar_col ); ?> sidebar sticky-sidebar wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s">
          <div class="theiaStickySidebar">
            <?php if ( is_active_sidebar( 'shop-sidebar' ) ) : ?>
              <?php dynamic_sidebar( 'shop-sidebar' ); ?>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

        <div class="<?php echo esc_attr( $cols ); ?>">
          <div class="row">
            <div class="col-12">
              <div class="shop-toolbar d-flex flex-wrap align-items-center justify-content-between mb-4">
                <?php
                /**
                 * Hook: woocommerce_before_shop_loop.
                 */
                do_action( 'woocommerce_before_shop_loop' );
                ?>
              </div>
            </div>
          </div>

          <?php if ( woocommerce_product_loop() ) : ?>

            <?php woocommerce_product_loop_start(); ?>

            <?php while ( have_posts() ) : the_post(); ?>
              <div class="<?php echo esc_attr( $boot_col ); ?> mb-4">
                <?php wc_get_template_part( 'content', 'product' ); ?>
              </div>
            <?php endwhile; ?>

            <?php woocommerce_product_loop_end(); ?>

            <div class="row">
              <div class="col-12">
                <?php
                /**
                 * Hook: woocommerce_after_shop_loop.
                 */
                do_action( 'woocommerce_after_shop_loop' );
                ?>
              </div>
            </div>

          <?php else : ?>

            <div class="row">
              <div class="col-12 text-center">
                <?php
                /**
                 * Hook: woocommerce_no_products_found.
                 */
                do_action( 'woocommerce_no_products_found' );
                ?>
              </div>
            </div>

          <?php endif; ?>
        </div>

      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>
