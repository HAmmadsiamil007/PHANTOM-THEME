<?php
/**
 * Kids Collection - Coming Soon
 *
 * @package optix
 */

$kc_img = kc_img_base();
$cs_logo = optix_get_option( 'coming_soon_logo' );
$cs_date = optix_get_option( 'coming_soon_date' );
?>
<!-- SUB BANNER / COMING SOON SECTION -->
<section
    class="float-left w-100 coming-soon-con d-flex flex-column justify-content-center position-relative main-box gradient-overlay">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 mr-auto ml-auto">
                <div class="sub-content-con position-relative">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="d-inline-block"> <img loading="lazy" src="<?php echo esc_url( optix_img( $cs_logo, $kc_img . '/large-logo.png' ) ); ?>"
                            alt="logo-icon" class="img-fluid new-logo wow fadeInUp" data-wow-duration="3s"
                            data-wow-delay="0.4s"></a>
                    <div class="position-relative coming-content-con">
                        <h3 class="font-weight-500 wow fadeInLeft" data-wow-duration="3s" data-wow-delay="0.5s"><?php echo esc_html( optix_get_option( 'coming_soon_subtitle' ) ); ?></h3>
                        <h1 class="black-text wow fadeInRight" data-wow-duration="3s" data-wow-delay="0.6s"><?php echo esc_html( optix_get_option( 'coming_soon_title' ) ); ?></h1>
                        <div id="compaign_countdown2" class="compaign_countdown wow fadeInDown" data-wow-duration="3s"
                            data-wow-delay="0.7s"<?php if ( ! empty( $cs_date ) ) : ?> data-target-date="<?php echo esc_attr( $cs_date ); ?>"<?php endif; ?>>
                            <ul class="p-0 d-flex justify-content-center align-items-center">
                                <li><span id="days" class="days"></span> Days</li>
                                <li><span id="hours" class="hours"></span>Hours</li>
                                <li><span id="minutes" class="minutes"></span>Minutes</li>
                                <li><span id="seconds" class="seconds"></span>Sec</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
