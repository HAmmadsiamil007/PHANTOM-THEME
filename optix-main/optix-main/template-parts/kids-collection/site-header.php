<?php
/**
 * Kids Collection - Site header with navigation
 *
 * @package optix
 */
?>
<header class="header-con position-relative float-left w-100 main-box">
  <div class="main-container">
    <nav class="navbar navbar-expand-lg navbar-light p-0" aria-label="<?php esc_attr_e( 'Primary', 'optix' ); ?>">
      <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
        <figure class="logo mb-0"><img src="<?php echo esc_url( optix_get_option( 'header_logo' ) ? optix_img( optix_get_option( 'header_logo' ) ) : optix_img( '/logo.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="img-fluid" loading="lazy"></figure>
      </a>
      <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        <span class="navbar-toggler-icon"></span>
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <?php
        if ( has_nav_menu( 'primary' ) ) :
          $walker = class_exists( 'Optix\Kids_Collection_Nav_Walker' ) ? new \Optix\Kids_Collection_Nav_Walker() : '';
          wp_nav_menu( [
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'navbar-nav ml-auto',
            'depth'          => 2,
            'walker'         => $walker,
            'fallback_cb'    => '__return_false',
            'echo'           => true,
          ] );
        else :
        ?>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown active">
            <a class="nav-link dropdown-toggle dropdown-color navbar-text-color" href="#"
              id="navbarDropdown4" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false"> <?php echo esc_html__( 'Home', 'optix' ); ?> </a>
            <div class="dropdown-menu drop-down-content">
              <ul class="list-unstyled drop-down-pages">
                <li class="nav-item"><a class="dropdown-item nav-link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( "Kid's Collection", 'optix' ); ?></a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php echo esc_html__( 'About', 'optix' ); ?></a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle dropdown-color navbar-text-color" href="#"
              id="navbarDropdown3" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false"> <?php echo esc_html__( 'Blog', 'optix' ); ?> </a>
            <div class="dropdown-menu drop-down-content">
              <ul class="list-unstyled drop-down-pages">
                <li class="nav-item"><a class="dropdown-item nav-link" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php echo esc_html__( 'Blog', 'optix' ); ?></a></li>
                <li class="nav-item"><a class="dropdown-item nav-link" href="<?php echo esc_url( home_url( '/single-blog/' ) ); ?>"><?php echo esc_html__( 'Single Blog', 'optix' ); ?></a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle dropdown-color navbar-text-color" href="#"
              id="navbarDropdown2" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false"> <?php echo esc_html__( 'Pages', 'optix' ); ?> </a>
            <div class="dropdown-menu drop-down-content">
              <ul class="list-unstyled drop-down-pages">
                <li class="nav-item"><a class="dropdown-item nav-link" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php echo esc_html__( 'Shop', 'optix' ); ?></a></li>
                <li class="nav-item"><a class="dropdown-item nav-link" href="<?php echo esc_url( home_url( '/cart/' ) ); ?>"><?php echo esc_html__( 'Cart', 'optix' ); ?></a></li>
                <li class="nav-item"><a class="dropdown-item nav-link" href="<?php echo esc_url( home_url( '/checkout/' ) ); ?>"><?php echo esc_html__( 'Checkout', 'optix' ); ?></a></li>
                <li class="nav-item"><a class="dropdown-item nav-link" href="<?php echo esc_url( home_url( '/testimonials/' ) ); ?>"><?php echo esc_html__( 'Testimonials', 'optix' ); ?></a></li>
                <li class="nav-item"><a class="dropdown-item nav-link" href="<?php echo esc_url( home_url( '/team/' ) ); ?>"><?php echo esc_html__( 'Team', 'optix' ); ?></a></li>
                <li class="nav-item"><a class="dropdown-item nav-link" href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php echo esc_html__( "FAQ's", 'optix' ); ?></a></li>
                <li class="nav-item"><a class="dropdown-item nav-link" href="<?php echo esc_url( home_url( '/coming-soon/' ) ); ?>"><?php echo esc_html__( 'Coming Soon', 'optix' ); ?></a></li>
                <li class="nav-item"><a class="dropdown-item nav-link" href="<?php echo esc_url( home_url( '/error/' ) ); ?>"><?php echo esc_html__( '404', 'optix' ); ?></a></li>
                <li class="nav-item"><a class="dropdown-item nav-link" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php echo esc_html__( 'Contact', 'optix' ); ?></a></li>
                <li class="nav-item"><a class="dropdown-item nav-link" href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php echo esc_html__( 'Privacy Policy', 'optix' ); ?></a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php echo esc_html__( 'Contact', 'optix' ); ?></a>
          </li>
        </ul>
        <?php endif; ?>
      </div>
      <div class="last_list">
        <a class="search" href="#search" aria-label="<?php echo esc_attr_x( 'Search', 'header', 'optix' ); ?>">
          <img src="<?php echo esc_url( optix_get_option( 'header_search_icon' ) ? optix_img( optix_get_option( 'header_search_icon' ) ) : optix_img( '/header-search.png' ) ); ?>" alt="<?php echo esc_attr_x( 'Search', 'header', 'optix' ); ?>" loading="lazy">
        </a>
        <a class="cart" href="<?php echo esc_url( function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' ) ); ?>" aria-label="<?php echo esc_attr_x( 'Cart', 'header', 'optix' ); ?>">
          <img src="<?php echo esc_url( optix_get_option( 'header_cart_icon' ) ? optix_img( optix_get_option( 'header_cart_icon' ) ) : optix_img( '/header-cart.png' ) ); ?>" alt="<?php echo esc_attr_x( 'Cart', 'header', 'optix' ); ?>" loading="lazy"><span class="cart-count"><?php echo class_exists( 'WooCommerce' ) ? esc_html( WC()->cart->get_cart_contents_count() ) : '0'; ?></span>
          <?php if ( class_exists( 'WooCommerce' ) ) : ?>
          <div class="mini-cart-dropdown">
            <?php woocommerce_mini_cart(); ?>
          </div>
          <?php endif; ?>
        </a>
        <a class="admin" href="<?php echo esc_url( home_url( '/login/' ) ); ?>" aria-label="<?php echo esc_attr_x( 'Login', 'header', 'optix' ); ?>">
          <img src="<?php echo esc_url( optix_get_option( 'header_login_icon' ) ? optix_img( optix_get_option( 'header_login_icon' ) ) : optix_img( '/header-admin.png' ) ); ?>" alt="<?php echo esc_attr_x( 'Login', 'header', 'optix' ); ?>" loading="lazy">
        </a>
      </div>
    </nav>
  </div>
</header>
