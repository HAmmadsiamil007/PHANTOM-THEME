<?php
/**
 * Kids Collection - Front Page Template
 *
 * Dynamic CMS version. All hardcoded values replaced with
 * optix_get_option() calls. Original HTML structure preserved.
 *
 * @package optix
 */

?>

<?php if ( optix_get_option( 'home_banner_enable' ) == 1 ) : ?>
<!-- BANNER SECTION -->
<section class="banner-con position-relative float-left w-100 text-center">
  <figure><img loading="lazy" src="<?php echo esc_url( optix_img( optix_get_option( 'home_banner_img1' ) ) ); ?>" alt="" class="position-absolute banner-img1"></figure>
  <figure><img loading="lazy" src="<?php echo esc_url( optix_img( optix_get_option( 'home_banner_img2' ) ) ); ?>" alt="" class="position-absolute banner-img2"></figure>
  <div class="main-container">
    <div class="banner-inner-wrapper">
      <div class="center-context">
        <figure><img loading="lazy" src="<?php echo esc_url( optix_img( '/banner-vector1.png' ) ); ?>" alt="vector"></figure>
        <span class="d-inline-block primary-text text-uppercase banner-span"><?php echo esc_html( optix_get_option( 'home_banner_heading' ) ); ?></span>
        <h1 class="font-size92"><?php echo wp_kses_post( optix_get_option( 'home_banner_title' ) ); ?></h1>
        <p><?php echo esc_html( optix_get_option( 'home_banner_description' ) ); ?></p>
        <a href="<?php echo esc_url( home_url( optix_get_option( 'home_banner_btn_url', '/shop/' ) ) ); ?>" class="text-decoration-none secondary_btn d-inline-block"><?php echo esc_html( optix_get_option( 'home_banner_btn_text' ) ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
      </div>
      <div class="button_down m-auto text-center">
        <a href="#promotion">
          <figure><img loading="lazy" src="<?php echo esc_url( optix_img( '/buttondown-img.png' ) ); ?>" alt="buttondown" class="img-fluid btndown-img"></figure>
          <figure><img loading="lazy" src="<?php echo esc_url( optix_img( '/buttondown-arrow.png' ) ); ?>" alt="buttondown" class="img-fluid"></figure>
        </a>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( optix_get_option( 'home_promotion_enable' ) == 1 ) : ?>
<!-- PROMOTION SECTION -->
<section class="float-left w-100 position-relative padding-top padding-bottom main-box promotion-con2" id="promotion">
  <div class="container">
    <div class="row">
      <?php
      $optix_promos = optix_get_option( 'home_promotion_boxes' );
      if ( ! empty( $optix_promos ) && count( $optix_promos ) >= 4 ) :
      ?>
      <div class="col-lg-4 col-md-6 wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s">
        <div class="col-12 p-0">
          <div class="promo-box1 <?php echo esc_attr( $optix_promos[0]['bg_class'] ?? 'bg-light1' ); ?> d-flex align-items-center position-relative">
            <div class="promo-content-con">
              <span class="d-inline-block text-uppercase font-weight-500 trending-text"><?php echo esc_html( $optix_promos[0]['tag'] ?? 'Trending' ); ?></span>
              <h4><?php echo esc_html( $optix_promos[0]['title'] ?? 'Kids Collection' ); ?></h4>
              <span class="discount-upto-tag">Upto <span class="d-inline-block primary-text"><?php echo esc_html( $optix_promos[0]['discount'] ?? '50%' ); ?></span> Off</span>
              <a href="<?php echo esc_url( home_url( $optix_promos[0]['link'] ?? '/shop/' ) ); ?>" class="d-inline-block"><?php echo esc_html( $optix_promos[0]['btn_text'] ?? 'Shop Now' ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            </div>
            <figure class="position-absolute"><img loading="lazy" src="<?php echo esc_url( optix_img( $optix_promos[0]['image'] ?? '/promotion-img1.png' ) ); ?>" alt="promo"></figure>
          </div>
        </div>
        <div class="col-12 p-0 mb-0">
          <div class="promo-box1 <?php echo esc_attr( $optix_promos[1]['bg_class'] ?? 'bg-light2' ); ?> d-flex align-items-center promo2">
            <figure><img loading="lazy" src="<?php echo esc_url( optix_img( $optix_promos[1]['image'] ?? '/promotion-img2.png' ) ); ?>" alt="promo"></figure>
            <div class="promo-content-con">
              <span class="d-inline-block text-uppercase font-weight-500 trending-text"><?php echo esc_html( $optix_promos[1]['tag'] ?? 'Latest' ); ?></span>
              <h4><?php echo esc_html( $optix_promos[1]['title'] ?? 'Boys Collection' ); ?></h4>
              <span class="discount-upto-tag">Upto <span class="d-inline-block primary-text"><?php echo esc_html( $optix_promos[1]['discount'] ?? '30%' ); ?></span> Off</span>
              <a href="<?php echo esc_url( home_url( $optix_promos[1]['link'] ?? '/shop/' ) ); ?>" class="d-inline-block"><?php echo esc_html( $optix_promos[1]['btn_text'] ?? 'Shop Now' ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 d-flex wow bounceIn" data-wow-duration="2s" data-wow-delay="0.05s">
        <div class="promo-box1 <?php echo esc_attr( $optix_promos[2]['bg_class'] ?? 'bg-light3' ); ?> promo3 text-center w-100 position-relative">
          <div class="promo-content-con px-0">
            <span class="d-inline-block text-uppercase font-weight-500 trending-text"><?php echo esc_html( $optix_promos[2]['tag'] ?? 'Hot Deals' ); ?></span>
            <h3 class="heading3 mb-0"><?php echo esc_html( $optix_promos[2]['title'] ?? 'Buy One Get One Free' ); ?></h3>
            <a href="<?php echo esc_url( home_url( $optix_promos[2]['link'] ?? '/shop/' ) ); ?>" class="d-inline-block"><?php echo esc_html( $optix_promos[2]['btn_text'] ?? 'Shop Now' ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            <figure><img loading="lazy" src="<?php echo esc_url( optix_img( $optix_promos[2]['image'] ?? '/promotion-img3.png' ) ); ?>" alt="promotion"></figure>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 d-flex wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s">
        <div class="promo-box1 <?php echo esc_attr( $optix_promos[3]['bg_class'] ?? 'bg-light4' ); ?> promo4 text-center w-100">
          <div class="promo-content-con px-0">
            <figure><img loading="lazy" src="<?php echo esc_url( optix_img( $optix_promos[3]['image'] ?? '/promotion-img4.png' ) ); ?>" alt="promotion"></figure>
            <span class="d-inline-block text-uppercase font-weight-500 trending-text"><?php echo esc_html( $optix_promos[3]['tag'] ?? 'New Arrivals' ); ?></span>
            <h3 class="heading5 mb-0"><?php echo esc_html( $optix_promos[3]['title'] ?? 'Girls Collection' ); ?></h3>
            <a href="<?php echo esc_url( home_url( $optix_promos[3]['link'] ?? '/shop/' ) ); ?>" class="d-inline-block"><?php echo esc_html( $optix_promos[3]['btn_text'] ?? 'Shop Now' ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
          </div>
        </div>
      </div>
      <?php else : ?>
        <?php
        // Fallback: all 4 hardcoded defaults from defaults.php
        $optix_fb = \Optix\get_default( 'home_promotion_boxes' );
        ?>
      <div class="col-lg-4 col-md-6 wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s">
        <div class="col-12 p-0">
          <div class="promo-box1 <?php echo esc_attr( $optix_fb[0]['bg_class'] ); ?> d-flex align-items-center position-relative">
            <div class="promo-content-con">
              <span class="d-inline-block text-uppercase font-weight-500 trending-text"><?php echo esc_html( $optix_fb[0]['tag'] ); ?></span>
              <h4><?php echo esc_html( $optix_fb[0]['title'] ); ?></h4>
              <span class="discount-upto-tag">Upto <span class="d-inline-block primary-text"><?php echo esc_html( $optix_fb[0]['discount'] ); ?></span> Off</span>
              <a href="<?php echo esc_url( home_url( $optix_fb[0]['link'] ) ); ?>" class="d-inline-block"><?php echo esc_html( $optix_fb[0]['btn_text'] ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            </div>
            <figure class="position-absolute"><img loading="lazy" src="<?php echo esc_url( optix_img( $optix_fb[0]['image'] ) ); ?>" alt="promo"></figure>
          </div>
        </div>
        <div class="col-12 p-0 mb-0">
          <div class="promo-box1 <?php echo esc_attr( $optix_fb[1]['bg_class'] ); ?> d-flex align-items-center promo2">
            <figure><img loading="lazy" src="<?php echo esc_url( optix_img( $optix_fb[1]['image'] ) ); ?>" alt="promo"></figure>
            <div class="promo-content-con">
              <span class="d-inline-block text-uppercase font-weight-500 trending-text"><?php echo esc_html( $optix_fb[1]['tag'] ); ?></span>
              <h4><?php echo esc_html( $optix_fb[1]['title'] ); ?></h4>
              <span class="discount-upto-tag">Upto <span class="d-inline-block primary-text"><?php echo esc_html( $optix_fb[1]['discount'] ); ?></span> Off</span>
              <a href="<?php echo esc_url( home_url( $optix_fb[1]['link'] ) ); ?>" class="d-inline-block"><?php echo esc_html( $optix_fb[1]['btn_text'] ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 d-flex wow bounceIn" data-wow-duration="2s" data-wow-delay="0.05s">
        <div class="promo-box1 <?php echo esc_attr( $optix_fb[2]['bg_class'] ); ?> promo3 text-center w-100 position-relative">
          <div class="promo-content-con px-0">
            <span class="d-inline-block text-uppercase font-weight-500 trending-text"><?php echo esc_html( $optix_fb[2]['tag'] ); ?></span>
            <h3 class="heading3 mb-0"><?php echo esc_html( $optix_fb[2]['title'] ); ?></h3>
            <a href="<?php echo esc_url( home_url( $optix_fb[2]['link'] ) ); ?>" class="d-inline-block"><?php echo esc_html( $optix_fb[2]['btn_text'] ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            <figure><img loading="lazy" src="<?php echo esc_url( optix_img( $optix_fb[2]['image'] ) ); ?>" alt="promotion"></figure>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 d-flex wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s">
        <div class="promo-box1 <?php echo esc_attr( $optix_fb[3]['bg_class'] ); ?> promo4 text-center w-100">
          <div class="promo-content-con px-0">
            <figure><img loading="lazy" src="<?php echo esc_url( optix_img( $optix_fb[3]['image'] ) ); ?>" alt="promotion"></figure>
            <span class="d-inline-block text-uppercase font-weight-500 trending-text"><?php echo esc_html( $optix_fb[3]['tag'] ); ?></span>
            <h3 class="heading5 mb-0"><?php echo esc_html( $optix_fb[3]['title'] ); ?></h3>
            <a href="<?php echo esc_url( home_url( $optix_fb[3]['link'] ) ); ?>" class="d-inline-block"><?php echo esc_html( $optix_fb[3]['btn_text'] ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( optix_get_option( 'home_products_enable' ) == 1 ) : ?>
<!-- OUR COLLECTION SECTION -->
<section class="float-left w-100 position-relative our-collection-con padding-top padding-bottom main-box background-grey">
  <div class="container">
    <div class="heading-title-con text-center">
      <span class="special-text d-block wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'home_products_heading' ) ); ?></span>
      <h2 class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'home_products_title' ) ); ?></h2>
    </div>
    <div class="row wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.05s">
      <?php
      if ( class_exists( 'WooCommerce' ) ) {
        $optix_limit = (int) optix_get_option( 'home_products_count', 6 );
        $products = wc_get_products( [ 'limit' => $optix_limit, 'status' => 'publish' ] );
      } else {
        $products = [];
      }
      $optix_fallback_img = optix_get_option( 'home_products_fallback_img', '/product-img1.png' );
      $optix_price_mult   = (float) optix_get_option( 'home_products_price_multiplier', 1.3 );
      foreach ( $products as $product ) :
        $pid   = $product->get_id();
        $name  = $product->get_name();
        $img   = wp_get_attachment_image_url( $product->get_image_id(), 'medium' ) ?: optix_img( $optix_fallback_img );
        $price = $product->get_price();
        $sale  = $product->is_on_sale();
        $link  = get_permalink( $pid );
      ?>
      <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 d-flex">
        <div class="seller-box w-100">
          <div class="seller_image_box position-relative">
            <?php if ( $sale ) : ?>
              <span class="d-inline-block position-absolute sale-tag background-primary text-white"><?php echo esc_html( optix_get_option( 'product_related_sale_tag', 'Sale' ) ); ?></span>
            <?php endif; ?>
            <figure class="mb-0"><a href="<?php echo esc_url( $link ); ?>"><img loading="lazy" src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $name ); ?>" class="img-fluid"></a></figure>
            <ul class="list-unstyled mb-0">
              <li class="icon"><a href="?add-to-cart=<?php echo esc_attr( $pid ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Add %s to cart', 'optix' ), $name ) ); ?>"><img loading="lazy" src="<?php echo esc_url( optix_img( '/feature-cart.png' ) ); ?>" alt="<?php echo esc_attr( sprintf( __( 'Add %s to cart', 'optix' ), $name ) ); ?>" class="img-fluid"></a></li>
              <li class="icon"><a href="<?php echo esc_url( $link ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'View %s', 'optix' ), $name ) ); ?>"><img loading="lazy" src="<?php echo esc_url( optix_img( '/feature-heart.png' ) ); ?>" alt="<?php echo esc_attr( sprintf( __( 'View %s', 'optix' ), $name ) ); ?>" class="img-fluid"></a></li>
              <li class="icon"><a href="<?php echo esc_url( $link ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'View %s details', 'optix' ), $name ) ); ?>"><img loading="lazy" src="<?php echo esc_url( optix_img( '/feature-eye.png' ) ); ?>" alt="<?php echo esc_attr( sprintf( __( 'View %s details', 'optix' ), $name ) ); ?>" class="img-fluid"></a></li>
            </ul>
          </div>
          <div class="seller_box_content">
            <div class="text_wrapper position-relative">
              <div class="rating d-flex align-items-center justify-content-center">
                <?php
                $optix_rating = optix_get_option( 'product_detail_rating', '4.9/5' );
                for ( $i = 0; $i < 5; $i++ ) {
                  echo '<i class="fa-solid fa-star" aria-hidden="true"></i>';
                }
                ?>
                <span class="d-inline-block">(<?php echo esc_html( $optix_rating ); ?>)</span>
              </div>
              <h6 class="heading6 archivo-font"><?php echo esc_html( $name ); ?></h6>
              <div class="objct-price"><?php echo wp_kses_post( wc_price( $price ) ); ?> <span class="d-inline-block"><?php echo wp_kses_post( wc_price( $price * $optix_price_mult ) ); ?></span></div>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="btn-outer-con text-center m-auto">
      <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="text-decoration-none secondary_btn d-inline-block"><?php echo esc_html( optix_get_option( 'home_products_btn_text' ) ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( optix_get_option( 'home_cta_enable' ) == 1 ) : ?>
<!-- CALL TO ACTION SECTION -->
<section class="float-left w-100 position-relative cta-con2 main-box background-primary">
  <figure><img loading="lazy" src="<?php echo esc_url( optix_img( '/vector1.png' ) ); ?>" alt="vector" class="position-absolute vector1"></figure>
  <figure><img loading="lazy" src="<?php echo esc_url( optix_img( '/vector2.png' ) ); ?>" alt="vector" class="position-absolute vector2"></figure>
  <figure><img loading="lazy" src="<?php echo esc_url( optix_img( '/vector3.png' ) ); ?>" alt="vector" class="position-absolute vector3"></figure>
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-md-6"></div>
      <div class="col-lg-6 col-md-6">
        <div class="cta-inner-wrapper wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s">
          <h1 class="font-size92"><?php echo wp_kses_post( optix_get_option( 'home_cta_title' ) ); ?></h1>
          <h4 class="font-size30"><?php echo wp_kses_post( optix_get_option( 'home_cta_subtitle' ) ); ?></h4>
          <a href="<?php echo esc_url( home_url( optix_get_option( 'home_cta_btn_url', '/shop/' ) ) ); ?>" class="text-decoration-none secondary_btn d-inline-block"><?php echo esc_html( optix_get_option( 'home_cta_btn_text' ) ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( optix_get_option( 'home_categories_enable' ) == 1 ) : ?>
<!-- PRODUCT CATEGORIES SECTION -->
<section class="float-left w-100 position-relative padding-top padding-bottom product-categories-con main-box text-center">
  <div class="container">
    <div class="heading-title-con text-center">
      <span class="special-text d-block wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'home_categories_heading' ) ); ?></span>
      <h2 class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'home_categories_title' ) ); ?></h2>
    </div>
    <?php
    $optix_cats = optix_get_option( 'home_categories' );
    if ( ! empty( $optix_cats ) ) :
    ?>
    <ul class="list-unstyled p-0 m-0 d-flex align-items-center justify-content-around wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.05s">
      <?php foreach ( $optix_cats as $optix_cat ) : ?>
      <li class="position-relative">
        <a href="<?php echo esc_url( home_url( $optix_cat['url'] ?? '/shop/' ) ); ?>"><figure class="<?php echo esc_attr( $optix_cat['bg_class'] ?? 'bg-light1' ); ?>"><img loading="lazy" src="<?php echo esc_url( optix_img( $optix_cat['image'] ?? '/pc-img1.png' ) ); ?>" alt=""></figure></a>
        <h4 class="mb-0"><?php echo esc_html( $optix_cat['title'] ?? '' ); ?></h4>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php else : ?>
    <ul class="list-unstyled p-0 m-0 d-flex align-items-center justify-content-around wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.05s">
      <li class="position-relative">
        <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><figure class="bg-light1"><img loading="lazy" src="<?php echo esc_url( optix_img( '/pc-img1.png' ) ); ?>" alt=""></figure></a>
        <h4 class="mb-0"><?php echo esc_html__( 'Kids Toys', 'optix' ); ?></h4>
      </li>
      <li class="position-relative">
        <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><figure class="bg-light2"><img loading="lazy" src="<?php echo esc_url( optix_img( '/pc-img2.png' ) ); ?>" alt=""></figure></a>
        <h4 class="mb-0"><?php echo esc_html__( 'Clothes', 'optix' ); ?></h4>
      </li>
      <li class="position-relative">
        <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><figure class="bg-light3"><img loading="lazy" src="<?php echo esc_url( optix_img( '/pc-img3.png' ) ); ?>" alt=""></figure></a>
        <h4 class="mb-0"><?php echo esc_html__( 'Girls', 'optix' ); ?></h4>
      </li>
      <li class="position-relative">
        <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><figure class="bg-light4"><img loading="lazy" src="<?php echo esc_url( optix_img( '/pc-img4.png' ) ); ?>" alt=""></figure></a>
        <h4 class="mb-0"><?php echo esc_html__( 'Accessories', 'optix' ); ?></h4>
      </li>
      <li class="position-relative">
        <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><figure class="bg-light5"><img loading="lazy" src="<?php echo esc_url( optix_img( '/pc-img5.png' ) ); ?>" alt=""></figure></a>
        <h4 class="mb-0"><?php echo esc_html__( 'New Born', 'optix' ); ?></h4>
      </li>
      <li class="position-relative">
        <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><figure class="bg-light6"><img loading="lazy" src="<?php echo esc_url( optix_img( '/pc-img6.png' ) ); ?>" alt=""></figure></a>
        <h4 class="mb-0"><?php echo esc_html__( 'Boys', 'optix' ); ?></h4>
      </li>
    </ul>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php if ( optix_get_option( 'home_top_selling_enable' ) == 1 ) : ?>
<!-- TOP SELLING PRODUCTS SECTION -->
<section class="float-left w-100 position-relative padding-top padding-bottom top-selling-products-con main-box background-grey">
  <div class="container">
    <div class="heading-title-con text-center">
      <span class="special-text d-block wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'home_top_selling_title' ) ); ?></span>
      <h2 class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'home_top_selling_title', __( 'Top Selling Products', 'optix' ) ) ); ?></h2>
    </div>
    <div class="row wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.05s">
      <?php
      if ( class_exists( 'WooCommerce' ) ) {
        $optix_ts_limit = (int) optix_get_option( 'home_top_selling_count', 4 );
        $top_sellers = wc_get_products( [ 'limit' => $optix_ts_limit, 'status' => 'publish', 'orderby' => 'total_sales', 'order' => 'DESC' ] );
      } else {
        $top_sellers = [];
      }
      foreach ( $top_sellers as $product ) :
        $pid   = $product->get_id();
        $name  = $product->get_name();
        $img   = wp_get_attachment_image_url( $product->get_image_id(), 'medium' ) ?: optix_img( $optix_fallback_img );
        $price = $product->get_price();
        $link  = get_permalink( $pid );
      ?>
      <div class="col-lg-3 col-md-6 d-flex">
        <div class="seller-box w-100">
          <div class="seller_image_box position-relative">
            <figure class="mb-0"><a href="<?php echo esc_url( $link ); ?>"><img loading="lazy" src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $name ); ?>" class="img-fluid"></a></figure>
            <ul class="list-unstyled mb-0">
              <li class="icon"><a href="?add-to-cart=<?php echo esc_attr( $pid ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Add %s to cart', 'optix' ), $name ) ); ?>"><img loading="lazy" src="<?php echo esc_url( optix_img( '/feature-cart.png' ) ); ?>" alt="<?php echo esc_attr( sprintf( __( 'Add %s to cart', 'optix' ), $name ) ); ?>" class="img-fluid"></a></li>
              <li class="icon"><a href="<?php echo esc_url( $link ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'View %s', 'optix' ), $name ) ); ?>"><img loading="lazy" src="<?php echo esc_url( optix_img( '/feature-heart.png' ) ); ?>" alt="<?php echo esc_attr( sprintf( __( 'View %s', 'optix' ), $name ) ); ?>" class="img-fluid"></a></li>
              <li class="icon"><a href="<?php echo esc_url( $link ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'View %s details', 'optix' ), $name ) ); ?>"><img loading="lazy" src="<?php echo esc_url( optix_img( '/feature-eye.png' ) ); ?>" alt="<?php echo esc_attr( sprintf( __( 'View %s details', 'optix' ), $name ) ); ?>" class="img-fluid"></a></li>
            </ul>
          </div>
          <div class="seller_box_content">
            <div class="text_wrapper position-relative">
              <div class="rating d-flex align-items-center justify-content-center">
                <?php
                $optix_ts_rating = optix_get_option( 'product_detail_rating', '4.9/5' );
                for ( $i = 0; $i < 5; $i++ ) {
                  echo '<i class="fa-solid fa-star" aria-hidden="true"></i>';
                }
                ?>
                <span class="d-inline-block">(<?php echo esc_html( $optix_ts_rating ); ?>)</span>
              </div>
              <h6 class="heading6 archivo-font"><?php echo esc_html( $name ); ?></h6>
              <div class="objct-price"><?php echo wp_kses_post( wc_price( $price ) ); ?> <span class="d-inline-block"><?php echo wp_kses_post( wc_price( $price * $optix_price_mult ) ); ?></span></div>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="btn-outer-con text-center m-auto">
      <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="text-decoration-none secondary_btn d-inline-block"><?php echo esc_html( optix_get_option( 'home_products_btn_text', 'View All' ) ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( optix_get_option( 'home_testimonials_enable' ) == 1 ) : ?>
<!-- TESTIMONIALS SECTION -->
<section class="float-left w-100 testimonial-con2 position-relative padding-top main-box">
  <div class="container">
    <div class="heading-title-con text-center">
      <span class="special-text d-block wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'home_testimonials_heading' ) ); ?></span>
      <h2 class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'home_testimonials_title' ) ); ?></h2>
    </div>
    <?php
    $optix_testimonials = optix_get_option( 'home_testimonials' );
    if ( ! empty( $optix_testimonials ) ) :
    ?>
    <div class="row">
      <div class="col-xl-10 col-12 mx-auto position-relative wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.05s">
        <figure class="testimonial-sideimage mb-0">
          <img loading="lazy" src="<?php echo esc_url( optix_img( '/testimonial-sideimage.png' ) ); ?>" alt="" class="img-fluid">
        </figure>
        <div id="testimonialcarousel" class="carousel slide" data-bs-ride="carousel" aria-roledescription="carousel" aria-label="<?php echo esc_attr_x( 'Testimonials', 'carousel', 'optix' ); ?>">
          <div class="testimonial_carousel text-center position-relative">
            <div class="carousel-inner">
              <?php foreach ( $optix_testimonials as $optix_ti => $optix_t ) : ?>
              <div class="carousel-item <?php echo $optix_ti === 0 ? 'active' : ''; ?>">
                <div class="testimonial-box">
                  <ul class="list-unstyled">
                    <?php $optix_tr = (int) ( $optix_t['rating'] ?? 5 ); for ( $optix_ri = 0; $optix_ri < $optix_tr; $optix_ri++ ) : ?>
                    <li><i class="fa-solid fa-star" aria-hidden="true"></i></li>
                    <?php endfor; ?>
                  </ul>
                  <p class="paragarph">&ldquo;<?php echo esc_html( $optix_t['text'] ?? '' ); ?>&rdquo;</p>
                  <div class="lower_content">
                    <span class="name"><?php echo esc_html( $optix_t['name'] ?? '' ); ?></span>
                    <span class="review"><?php echo esc_html( $optix_t['role'] ?? '' ); ?></span>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <ul class="carousel-indicators">
            <?php foreach ( $optix_testimonials as $optix_ti => $optix_t ) : ?>
            <li data-bs-target="#testimonialcarousel" data-bs-slide-to="<?php echo esc_attr( $optix_ti ); ?>" class="<?php echo $optix_ti === 0 ? 'active' : ''; ?>">
              <figure class="mb-0 image<?php echo esc_attr( $optix_ti + 1 ); ?>"><img loading="lazy" src="<?php echo esc_url( optix_img( $optix_t['avatar'] ?? '/review-person1.jpg' ) ); ?>" alt="" class="img-fluid invert_effect"></figure>
            </li>
            <?php endforeach; ?>
          </ul>
          <div class="pagination_outer">
            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialcarousel" data-bs-slide="prev" aria-label="<?php echo esc_attr_x( 'Previous slide', 'carousel', 'optix' ); ?>">
              <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialcarousel" data-bs-slide="next" aria-label="<?php echo esc_attr_x( 'Next slide', 'carousel', 'optix' ); ?>">
              <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php if ( optix_get_option( 'home_instagram_enable' ) == 1 ) : ?>
<!-- FOLLOW INSTAGRAM SECTION -->
<section class="float-left w-100 follow-con position-relative padding-top padding-bottom main-box background-grey">
  <figure><img loading="lazy" src="<?php echo esc_url( optix_img( '/yellow-elipse.png' ) ); ?>" alt="" class="position-absolute yellow-elipse wow fadeIn" data-wow-duration="2s" data-wow-delay="0.05s"></figure>
  <figure><img loading="lazy" src="<?php echo esc_url( optix_img( '/purple-elipse.png' ) ); ?>" alt="" class="position-absolute purple-elipse wow fadeIn" data-wow-duration="2s" data-wow-delay="0.05s"></figure>
  <div class="container">
    <div class="heading-title-con text-center">
      <span class="special-text d-block wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'home_instagram_heading' ) ); ?></span>
      <h2 class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'home_instagram_title' ) ); ?></h2>
    </div>
    <?php
    $optix_insta = optix_get_option( 'home_instagram_images' );
    if ( ! empty( $optix_insta ) ) :
    ?>
    <div class="row wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.05s">
      <div class="col-12 position-relative">
        <ul class="list-unstyled mb-0">
          <?php foreach ( $optix_insta as $optix_ii => $optix_in ) : ?>
          <li class="<?php echo in_array( $optix_ii, [ 1, 3 ], true ) ? 'top-box' : ''; ?>"><a href="<?php echo esc_url( $optix_in['url'] ?? 'https://www.instagram.com/' ); ?>" aria-label="<?php esc_attr_e( 'View on Instagram', 'optix' ); ?>"><figure class="image mb-0"><img loading="lazy" src="<?php echo esc_url( optix_img( $optix_in['image'] ?? '/follow-image1.jpg' ) ); ?>" alt="" class="img-fluid"></figure><div class="icon"><i class="fa-brands fa-instagram" aria-hidden="true"></i></div></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php if ( optix_get_option( 'home_benefits_enable' ) == 1 ) : ?>
<!-- BENEFITS SECTION -->
<?php
$optix_benefits = optix_get_option( 'home_benefits' );
if ( ! empty( $optix_benefits ) ) :
?>
<div class="float-left w-100 position-relative main-box benefits-con">
  <div class="container">
    <div class="row">
      <?php
      $optix_bwow = [ 'fadeInLeft', 'fadeInRight', 'fadeInLeft', 'fadeInRight' ];
      foreach ( $optix_benefits as $optix_bi => $optix_b ) :
      ?>
      <div class="col-lg-3 col-md-6 d-flex wow <?php echo esc_attr( $optix_bwow[ $optix_bi % 4 ] ); ?>" data-wow-duration="2s" data-wow-delay="0.05s">
        <div class="benefits-box w-100">
          <img loading="lazy" src="<?php echo esc_url( optix_img( $optix_b['icon'] ?? '/benefit-icon1.png' ) ); ?>" alt="" class="img-fluid d-inline-block">
          <span class="d-inline-block"><?php echo esc_html( $optix_b['text'] ?? '' ); ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>
<?php endif; ?>
