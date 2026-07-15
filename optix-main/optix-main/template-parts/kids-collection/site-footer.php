<?php
/**
 * Kids Collection - Site footer
 *
 * @package optix
 */
?>
<footer class="footer-con position-relative float-left w-100">
  <div class="container">
    <div class="middle_portion">
      <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
          <div class="logo-content">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
              <figure class="footer-logo">
                <img src="<?php echo esc_url( optix_get_option( 'footer_logo' ) ? optix_img( optix_get_option( 'footer_logo' ) ) : optix_img( '/footer-logo.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" loading="lazy">
              </figure>
            </a>
            <p class="text-size-14 text"><?php echo esc_html( optix_get_option( 'footer_about_text' ) ); ?></p>
            <?php
            $optix_social = optix_get_option( 'footer_social_links' );
            if ( ! empty( $optix_social ) ) :
            ?>
            <ul class="list-unstyled mb-0 social-icons">
              <?php foreach ( $optix_social as $optix_soc ) : ?>
               <li><a href="<?php echo esc_url( $optix_soc['url'] ?? '#' ); ?>" class="text-decoration-none" aria-label="<?php echo esc_attr( $optix_soc['platform'] ?? 'Facebook' ); ?>"><i class="fa-brands fa-<?php echo esc_attr( strtolower( str_replace( [ ' ', '.com' ], [ '', '' ], $optix_soc['platform'] ?? 'facebook' ) ) ); ?> social-networks" aria-hidden="true"></i></a></li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-4 col-6">
          <div class="links">
            <h5 class="heading"><?php echo esc_html__( 'Navigation', 'optix' ); ?></h5>
            <?php
            if ( has_nav_menu( 'footer' ) ) :
              wp_nav_menu( [
                'theme_location' => 'footer',
                'container'      => false,
                'menu_class'     => 'list-unstyled mb-0',
                'depth'          => 1,
                'fallback_cb'    => '__return_false',
                'echo'           => true,
              ] );
            else :
            ?>
            <ul class="list-unstyled mb-0">
              <li><i class="fa-solid fa-angle-right" aria-hidden="true"></i><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( 'Home', 'optix' ); ?></a></li>
              <li><i class="fa-solid fa-angle-right" aria-hidden="true"></i><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php echo esc_html__( 'Shop', 'optix' ); ?></a></li>
              <li><i class="fa-solid fa-angle-right" aria-hidden="true"></i><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php echo esc_html__( 'About', 'optix' ); ?></a></li>
              <li><i class="fa-solid fa-angle-right" aria-hidden="true"></i><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php echo esc_html__( 'Blog', 'optix' ); ?></a></li>
              <li><i class="fa-solid fa-angle-right" aria-hidden="true"></i><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php echo esc_html__( 'Contact', 'optix' ); ?></a></li>
            </ul>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-6">
          <div class="use-link links">
            <h5 class="heading"><?php echo esc_html__( 'Support', 'optix' ); ?></h5>
            <ul class="list-unstyled mb-0">
              <li><i class="fa-solid fa-angle-right" aria-hidden="true"></i><a href="<?php echo esc_url( home_url( '/term-of-use/' ) ); ?>"><?php echo esc_html__( 'Term of use', 'optix' ); ?></a></li>
              <li><i class="fa-solid fa-angle-right" aria-hidden="true"></i><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php echo esc_html__( 'Privacy policy', 'optix' ); ?></a></li>
              <li><i class="fa-solid fa-angle-right" aria-hidden="true"></i><a href="<?php echo esc_url( home_url( '/cookie-policy/' ) ); ?>"><?php echo esc_html__( 'Cookie policy', 'optix' ); ?></a></li>
              <li><i class="fa-solid fa-angle-right" aria-hidden="true"></i><a href="<?php echo esc_url( home_url( '/single-blog/' ) ); ?>"><?php echo esc_html__( 'Latest Posts', 'optix' ); ?></a></li>
              <li><i class="fa-solid fa-angle-right" aria-hidden="true"></i><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php echo esc_html__( 'Care Guide', 'optix' ); ?></a></li>
            </ul>
          </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-12">
          <div class="icon">
            <h5 class="heading"><?php echo esc_html__( 'Contact Us', 'optix' ); ?></h5>
            <ul class="list-unstyled mb-0">
              <li class="text">
                <i class="fa-solid fa-phone" aria-hidden="true"></i>
                <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', optix_get_option( 'footer_phone' ) ) ); ?>" class="text-decoration-none"><?php echo esc_html( optix_get_option( 'footer_phone' ) ); ?></a>
              </li>
              <li class="text">
                <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                <a href="mailto:<?php echo esc_attr( optix_get_option( 'footer_email' ) ); ?>" class="text-decoration-none"><?php echo esc_html( optix_get_option( 'footer_email' ) ); ?></a>
              </li>
              <li class="text">
                <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                <a href="https://www.google.com/maps/place/21+King+St,+Melbourne+VIC+3000,+Australia/@-37.8199805,144.9529083,18z/data=!4m6!3m5!1s0x6ad65d52754eaecb:0x22f367daf52cbd47!8m2!3d-37.819936!4d144.9570765!16s%2Fg%2F11c2dj2n2c?entry=ttu" class="text-decoration-none address mb-0"><?php echo wp_kses_post( optix_get_option( 'footer_address' ) ); ?></a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="copyright">
    <div class="container">
      <div class="content">
        <p class="mb-0"><?php echo esc_html( sprintf( optix_get_option( 'footer_copyright' ), date( 'Y' ) ) ); ?></p>
        <img src="<?php echo esc_url( optix_img( '/payment-cards.png' ) ); ?>" alt="<?php echo esc_attr__( 'Payment methods', 'optix' ); ?>" class="img-fluid" loading="lazy">
      </div>
    </div>
  </div>
</footer>
