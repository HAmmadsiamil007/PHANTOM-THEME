<?php
/**
 * Kids Collection - Thank You
 *
 * @package optix
 */

$kc_img = kc_img_base();
?>
<!-- THANK YOU SECTION -->
<div class="padding-rl float-left w-100">
    <section class="float-left w-100 position-relative thank-you-con padding-top padding-bottom text-center">
        <div class="main-container">
            <div class="thankyou-content-con">
                <figure class="wow bounce" data-wow-duration="2s" data-wow-delay="0.05s"><img loading="lazy"
                        src="<?php echo esc_url( optix_img( optix_get_option( 'thank_you_icon' ), $kc_img . '/smile-image.png' ) ); ?>" alt="smile icon" class="img-fluid">
                </figure>
                <h1 class="text-black wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s">
                    <?php echo esc_html( optix_get_option( 'thank_you_title', 'Thank You!' ) ); ?>
                </h1>
                <p class="wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo wp_kses_post( optix_get_option( 'thank_you_text', 'Thank you for your order! We\'re committed to your health and well-being.' ) ); ?></p>
                <a href="<?php echo esc_url( optix_get_option( 'thank_you_btn_url', home_url( '/' ) ) ); ?>" class="text-decoration-none primary_btn d-inline-block wow fadeInUp"
                    data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'thank_you_btn_text', 'Back to Home' ) ); ?></a>
            </div>
        </div>
    </section>
</div>
