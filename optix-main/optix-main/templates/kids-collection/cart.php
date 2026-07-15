<?php
/**
 * Kids Collection - Cart
 *
 * @package optix
 */

if ( ! class_exists( 'WooCommerce' ) ) {
  return;
}
?>
<!-- SUB BANNER SECTION -->
<section class="sub-banner-con position-relative float-left w-100 gradient-overlay d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="col-xl-12 col-lg-12 mr-auto ml-auto">
            <div class="sub-banner-inner-con text-center">
                <h1 class=""><?php echo esc_html( optix_get_option( 'cart_title', 'Cart' ) ); ?></h1>
                <div class="breadcrumb-con d-inline-block">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( 'Home', 'optix' ); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( optix_get_option( 'cart_title', 'Cart' ) ); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="float-left w-100 cart-con position-relative padding-top padding-bottom main-box">
    <div class="main-container">
        <div class="row">
            <div class="col-12">
                <?php echo do_shortcode( '[woocommerce_cart]' ); ?>
            </div>
        </div>
    </div>
</section>
