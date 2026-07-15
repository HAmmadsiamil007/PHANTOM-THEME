<?php
/**
 * Kids Collection - 404
 *
 * @package optix
 */

$kc_img = kc_img_base();
?>
<!-- 404 section start -->
<section class="error-section w-100 float-left position-relative gradient-overlay">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-10 col-12 mr-auto ml-auto">
                <div class="error-con wow fadeInUp" data-wow-duration="3s" data-wow-delay="0.2s">
                    <h2 class="wow fadeInRight" data-wow-duration="3s" data-wow-delay="0.2s">4 <i class=" fa-solid
                        fa-face-sad-tear wow fadeIn" aria-hidden="true" data-wow-duration="3s" data-wow-delay="0.2s"></i> 4</h2>
                    <h4 class="font-weight-700 wow fadeInLeft" data-wow-duration="3s" data-wow-delay="0.3s"><?php echo esc_html( optix_get_option( '404_title' ) ); ?></h4>
                    <p class="wow fadeInLeft" data-wow-duration="3s" data-wow-delay="0.3s"><?php echo wp_kses_post( optix_get_option( '404_description' ) ); ?>
                    </p>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-decoration-none primary_btn d-inline-block wow fadeInDown"
                        data-wow-duration="3s" data-wow-delay="0.3s">
                        <?php echo esc_html( optix_get_option( '404_btn_text' ) ); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

