<?php
/**
 * Kids Collection - Privacy Policy
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
                <h1 class=""><?php echo esc_html( optix_get_option( 'privacy_title', 'Privacy Policy' ) ); ?></h1>
                <div class="breadcrumb-con d-inline-block">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'optix' ); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( optix_get_option( 'privacy_title', 'Privacy Policy' ) ); ?></li>
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

<!-- PRIVACY POLICY CONTENT SECTION -->
<section class="float-left w-100 privacy-policy-content-con position-relative padding-top padding-bottom main-box">
    <div class="container wow fadeIn" data-wow-duration="2s" data-wow-delay="0.3s">
        <div class="row">
            <div class="col-12">
                <?php echo wp_kses_post( optix_get_option( 'privacy_content', '' ) ); ?>
            </div>
        </div>
    </div>
</section>
