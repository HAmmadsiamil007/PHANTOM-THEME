<?php
/**
 * Kids Collection - Contact Us
 *
 * @package optix
 */

$kc_img = kc_img_base();
?>
<!-- SUB BANNER SECTION -->
<section
    class="sub-banner-con position-relative float-left w-100 gradient-overlay d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="col-xl-12 col-lg-12 mr-auto ml-auto">
            <div class="sub-banner-inner-con text-center">
                <h1 class=""><?php the_title(); ?></h1>
                <div class="breadcrumb-con d-inline-block">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'optix' ); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$the_content = get_the_content();
if ( trim( $the_content ) ) :
?>
<section class="float-left w-100 position-relative padding-top padding-bottom main-box">
  <div class="container">
    <div class="row">
      <div class="col-12 page-content">
        <?php echo apply_filters( 'the_content', $the_content ); ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CONTACT HELP SECTION -->
<section
    class="float-left w-100 position-relative contact-help-con padding-top padding-bottom main-box text-center background-navy-medium">
    <div class="container wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.3s">
        <div class="heading-title-con text-center">
            <span class="special-text d-block wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'contact_info_heading' ) ); ?></span>
            <h2 class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'contact_info_title' ) ); ?></h2>
        </div>
        <div class="row align-items-center wow fadeInDown" data-wow-duration="2s" data-wow-delay="0.6s">
            <div class="col-lg-4 col-md-4 d-flex">
                <div class="white-box position-relative w-100">
                    <figure class="skin-circle"><img loading="lazy" src="<?php echo esc_url( optix_img( optix_get_option( 'contact_location_icon' ), $kc_img . '/loc-img.png' ) ); ?>" alt="" class="img-fluid">
                    </figure>
                    <h5 class=""><?php echo esc_html( optix_get_option( 'contact_location_title', 'Our Location' ) ); ?></h5>
                    <ul class="list-unstyled p-0">
                        <li><?php echo wp_kses_post( optix_get_option( 'contact_location_text' ) ); ?></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 d-flex">
                <div class="white-box position-relative w-100">
                    <figure class="light-circle"><img loading="lazy" src="<?php echo esc_url( optix_img( optix_get_option( 'contact_phone_icon' ), $kc_img . '/contact-img.png' ) ); ?>" alt=""
                            class="img-fluid"></figure>
                    <h5 class=""><?php echo esc_html( optix_get_option( 'contact_phone_title', 'Phone Number' ) ); ?></h5>
                    <ul class="list-unstyled p-0">
                        <?php
                        $phone_numbers = optix_get_option( 'contact_phone_numbers' );
                        if ( ! empty( $phone_numbers ) && is_array( $phone_numbers ) ) :
                            foreach ( $phone_numbers as $phone ) :
                                $phone_clean = is_array( $phone ) ? ( $phone['number'] ?? '' ) : $phone;
                        ?>
                        <li><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone_clean ) ); ?>"><?php echo esc_html( $phone_clean ); ?></a></li>
                        <?php endforeach; endif; ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 d-flex">
                <div class="white-box position-relative w-100">
                    <figure class="dark-circle"><img loading="lazy" src="<?php echo esc_url( optix_img( optix_get_option( 'contact_email_icon' ), $kc_img . '/email-img.png' ) ); ?>" alt="" class="img-fluid">
                    </figure>
                    <h5 class=""><?php echo esc_html( optix_get_option( 'contact_email_title', 'Email Us:' ) ); ?></h5>
                    <ul class="list-unstyled p-0">
                        <?php
                        $email_addresses = optix_get_option( 'contact_email_addresses' );
                        if ( ! empty( $email_addresses ) && is_array( $email_addresses ) ) :
                            foreach ( $email_addresses as $email ) :
                                $email_clean = is_array( $email ) ? ( $email['address'] ?? '' ) : $email;
                        ?>
                        <li><a href="mailto:<?php echo esc_attr( $email_clean ); ?>"><?php echo esc_html( $email_clean ); ?></a></li>
                        <?php endforeach; endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- CONTACT FORM SECTION-->
<section
    class="float-left w-100 position-relative contact-form-con padding-top padding-bottom main-box background-grey">
    <div class="container wow fadeIn" data-wow-duration="2s" data-wow-delay="0.3s">
        <div class="heading-title-con text-center">
            <span class="special-text d-block wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'contact_form_heading' ) ); ?></span>
            <h2 class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'contact_form_title' ) ); ?></h2>
        </div>
        <div class="row wow fadeInDown" data-wow-duration="2s" data-wow-delay="0.6s">
            <div class="col-xl-12 col-lg-12 mr-auto ml-auto">
                <form class="main-form text-center" method="post" id="contactpage">
                    <div id="form_result"></div>
                    <?php wp_nonce_field( 'optix_contact_nonce', 'optix_contact_nonce' ); ?>
                    <ul class="list-unstyled p-0 float-left w-100 mb-0">
                        <li>
                            <label for="fname"><?php esc_html_e( 'Your Name', 'optix' ); ?></label>
                            <input type="text" name="fname" id="fname">
                        </li>
                        <li>
                            <label for="phone"><?php esc_html_e( 'Phone Number', 'optix' ); ?></label>
                            <input type="tel" name="phone" id="phone">
                        </li>
                        <li>
                            <label for="email"><?php esc_html_e( 'Your Email', 'optix' ); ?></label>
                            <input type="email" name="email" id="email">
                        </li>
                        <li>
                            <label for="msg"><?php esc_html_e( 'Message', 'optix' ); ?></label>
                            <textarea rows="6" name="msg" id="msg"></textarea>
                        </li>
                    </ul>
                    <div class="outer-btn d-inline-block">
                        <button type="submit" id="submit" class="primary_btn"><?php echo esc_html( optix_get_option( 'contact_form_btn_text' ) ); ?> <i
                                class="fas fa-arrow-right" aria-hidden="true"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- MAP SECTION -->
<div class="float-left w-100 contact-map-con position-relative padding-top padding-bottom main-box">
    <div class="container p-0 wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.3s">
        <iframe
            src="<?php echo esc_url( optix_get_option( 'contact_map_embed' ) ); ?>"
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
</div>
<div class="clearfix"></div>
