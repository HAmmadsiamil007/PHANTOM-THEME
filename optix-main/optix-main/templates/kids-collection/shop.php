<?php
/**
 * Kids Collection - Shop
 *
 * @package optix
 */

if ( ! class_exists( 'WooCommerce' ) ) {
  return;
}

$kc_img = kc_img_base();
?>
<!-- SUB BANNER SECTION -->
<section class="sub-banner-con position-relative float-left w-100 gradient-overlay d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="col-xl-12 col-lg-12 mr-auto ml-auto">
            <div class="sub-banner-inner-con text-center">
                <h1 class=""><?php echo esc_html( optix_get_option( 'shop_title', 'Shop' ) ); ?></h1>
                <div class="breadcrumb-con d-inline-block">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'optix' ); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( optix_get_option( 'shop_title', 'Shop' ) ); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if ( optix_get_option( 'shop_enable', true ) ) : ?>
<!-- Shop -->
<section class="shop-con feature-con position-relative float-left w-100 padding-top padding-bottom main-box">
    <div class="main-container">
        <div class="row">
            <div class="col-lg-3 sidebar sticky-sidebar wow fadeInLeft" data-wow-duration="2s"
                data-wow-delay="0.05s">
                <div class="theiaStickySidebar">
                    <div class="widget widget-newsletter">
                        <form id="widget-search-form-sidebar" class="form-inline">
                            <div class="input-group">
                                <input type="text" aria-required="true" name="q"
                                    class="form-control widget-search-form" placeholder="<?php echo esc_attr( optix_get_option( 'shop_search_placeholder', 'Search' ) ); ?>" aria-label="<?php esc_attr_e( 'Search products', 'optix' ); ?>">
                                <div class="input-group-append">
                                    <span class="input-group-btn">
                                        <button type="submit" id="widget-widget-search-form-button" class="btn" aria-label="<?php esc_attr_e( 'Search', 'optix' ); ?>"><i
                                                class="fa fa-search" aria-hidden="true"></i></button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="widget widget-categories">
                        <div class="estimate-header" data-bs-toggle="collapse" data-bs-target="#category1"
                            aria-expanded="true" role="button" tabindex="0">
                            <div class="widget-title font_weight_600">Categories :</div>
                            <span class="collapse-icon"><i class="fa-solid fa-angle-up" aria-hidden="true"></i></span>
                        </div>
                        <div id="category1" class="collapse show mt-2">
                            <ul class="list-unstyled">
                                <li class="cat-item">
                                    <span class="d-block"><?php echo esc_html( optix_get_option( 'shop_cat_1', 'Baby Care & Essentials' ) ); ?></span>
                                </li>
                                <li class="cat-item">
                                    <span class="d-block"><?php echo esc_html( optix_get_option( 'shop_cat_2', 'Personal Wellness & Hygiene' ) ); ?></span>
                                </li>
                                <li class="cat-item">
                                    <span class="d-block"><?php echo esc_html( optix_get_option( 'shop_cat_3', 'Prescription Medicines' ) ); ?></span>
                                </li>
                                <li class="cat-item">
                                    <span class="d-block"><?php echo esc_html( optix_get_option( 'shop_cat_4', 'Vitamins & Health Supplements' ) ); ?></span>
                                </li>
                                <li class="cat-item">
                                    <span class="d-block"><?php echo esc_html( optix_get_option( 'shop_cat_5', 'Home Health Devices' ) ); ?></span>
                                </li>
                                <li class="cat-item">
                                    <span class="d-block"><?php echo esc_html( optix_get_option( 'shop_cat_6', 'First Aid & Medical Supplies' ) ); ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="widget filter">
                        <div class="estimate-header" data-bs-toggle="collapse" data-bs-target="#category3"
                            aria-expanded="true" role="button" tabindex="0">
                            <div class="widget-title font_weight_600">Filter by Price</div>
                            <span class="collapse-icon"><i class="fa-solid fa-angle-up" aria-hidden="true"></i></span>
                        </div>
                        <div id="category3" class="collapse show mt-2">
                            <div class="wrapper">
                                <fieldset class="filter-price">
                                    <div class="price-field">
                                        <input type="range" min="5" max="50" value="5" id="lower">
                                        <input type="range" min="5" max="50" value="50" id="upper">
                                    </div>
                                    <div class="price-wrap">
                                        <span class="price-title">Range:</span>
                                        <div class="price-wrap-1">
                                            <input id="one">
                                            <label for="one">$</label>
                                        </div>
                                        <div class="price-wrap_line">-</div>
                                        <div class="price-wrap-2">
                                            <input id="two">
                                            <label for="two">$</label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="widget filter">
                        <div class="estimate-header" data-bs-toggle="collapse" data-bs-target="#category4"
                            aria-expanded="true" role="button" tabindex="0">
                            <div class="widget-title font_weight_600">Colors</div>
                            <span class="collapse-icon"><i class="fa-solid fa-angle-up" aria-hidden="true"></i></span>
                        </div>
                        <div id="category4" class="collapse show mt-2">
                            <div class="wrapper">
                                <div class="color_wrap">
                                    <div class="color-dot <?php echo esc_attr( optix_get_option( 'shop_color_1', 'dot1' ) ); ?>"></div>
                                    <div class="color-dot <?php echo esc_attr( optix_get_option( 'shop_color_2', 'dot2' ) ); ?>"></div>
                                    <div class="color-dot <?php echo esc_attr( optix_get_option( 'shop_color_3', 'dot3' ) ); ?>"></div>
                                    <div class="color-dot <?php echo esc_attr( optix_get_option( 'shop_color_4', 'dot4' ) ); ?>"></div>
                                    <div class="color-dot <?php echo esc_attr( optix_get_option( 'shop_color_5', 'dot5' ) ); ?>"></div>
                                    <div class="color-dot <?php echo esc_attr( optix_get_option( 'shop_color_6', 'dot6' ) ); ?>"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="widget widget-size">
                        <div class="estimate-header" data-bs-toggle="collapse" data-bs-target="#category2"
                            aria-expanded="true" role="button" tabindex="0">
                            <div class="widget-title font_weight_600">Size :</div>
                            <span class="collapse-icon"><i class="fa-solid fa-angle-up" aria-hidden="true"></i></span>
                        </div>
                        <div id="category2" class="collapse show mt-2 mb-3">
                            <span class="d-inline-block size-option"><?php echo esc_html( optix_get_option( 'shop_size_1', '100 ml' ) ); ?></span>
                            <span class="d-inline-block size-option"><?php echo esc_html( optix_get_option( 'shop_size_2', '50 ml' ) ); ?></span>
                            <span class="d-inline-block size-option"><?php echo esc_html( optix_get_option( 'shop_size_3', '200 ml' ) ); ?></span>
                            <span class="d-inline-block size-option"><?php echo esc_html( optix_get_option( 'shop_size_4', '150 ml' ) ); ?></span>
                            <span class="d-inline-block size-option"><?php echo esc_html( optix_get_option( 'shop_size_5', '30 ml' ) ); ?></span>
                            <span class="d-inline-block size-option"><?php echo esc_html( optix_get_option( 'shop_size_6', '300 mg' ) ); ?></span>
                            <span class="d-inline-block size-option"><?php echo esc_html( optix_get_option( 'shop_size_7', '500 mg' ) ); ?></span>
                            <span class="d-inline-block size-option"><?php echo esc_html( optix_get_option( 'shop_size_8', '90 mg' ) ); ?></span>
                            <span class="d-inline-block size-option"><?php echo esc_html( optix_get_option( 'shop_size_9', '400 mg' ) ); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s">
                <?php
                $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
                $args = array(
                    'post_type'      => 'product',
                    'posts_per_page' => optix_get_option( 'shop_products_per_page', 12 ),
                    'paged'          => $paged,
                );
                $loop = new WP_Query( $args );

                $total        = $loop->found_posts;
                $per_page     = $loop->get( 'posts_per_page' );
                $current      = $paged;
                $start        = ( $current - 1 ) * $per_page + 1;
                $end          = min( $current * $per_page, $total );
                ?>
                <div class="row default-sorting-con">
                    <div class="col-12">
                        <div class="top-icons">
                            <div class="icons-list">
                                <figure class="grid icon mb-0">
                                    <img loading="lazy" src="<?php echo esc_url( $kc_img . '/shop-gridicon.png' ); ?>" alt="" class="img-fluid">
                                </figure>
                                <figure class="list icon mb-0">
                                    <img loading="lazy" src="<?php echo esc_url( $kc_img . '/shop-listicon.png' ); ?>" alt="" class="img-fluid">
                                </figure>
                                <span><?php printf( esc_html__( 'Showing %1$s&ndash;%2$s of %3$s results', 'optix' ), $start, $end, $total ); ?></span>
                            </div>
                            <div id="toolbar">
                                <select class="form-control">
                                    <option value="">Default Sorting</option>
                                    <option value="all"><?php echo esc_html( optix_get_option( 'shop_sort_1', 'DermaGlow' ) ); ?></option>
                                    <option value="selected"><?php echo esc_html( optix_get_option( 'shop_sort_2', 'ImmunoBoost' ) ); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="shop-box-wrapper">
                    <div class="row shop-products-con">
                        <?php if ( $loop->have_posts() ) : ?>
                            <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                                <?php global $product; ?>
                                <?php if ( empty( $product ) || ! $product instanceof WC_Product ) : continue; endif; ?>
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 d-flex">
                                    <div class="seller-box w-100">
                                        <div class="seller_image_box position-relative">
                                            <?php if ( $product->is_on_sale() ) : ?>
                                                <span class="d-inline-block position-absolute sale-tag background-primary text-white"><?php esc_html_e( 'Sale', 'optix' ); ?></span>
                                            <?php endif; ?>
                                            <figure class="mb-0">
                                                <?php echo woocommerce_get_product_thumbnail(); ?>
                                            </figure>
                                            <ul class="list-unstyled mb-0">
                                                <li class="icon"><a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Add %s to cart', 'optix' ), $product->get_name() ) ); ?>"><img loading="lazy"
                                                            src="<?php echo esc_url( $kc_img . '/feature-cart.png' ); ?>" alt="<?php esc_attr_e( 'Add to cart', 'optix' ); ?>"
                                                            class="img-fluid"></a></li>
                                                <li class="icon"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Add to wishlist', 'optix' ); ?>"><img loading="lazy"
                                                            src="<?php echo esc_url( $kc_img . '/feature-heart.png' ); ?>" alt="<?php esc_attr_e( 'Add to wishlist', 'optix' ); ?>"
                                                            class="img-fluid"></a></li>
                                                <li class="icon"><a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'View %s', 'optix' ), $product->get_name() ) ); ?>"><img loading="lazy"
                                                            src="<?php echo esc_url( $kc_img . '/feature-eye.png' ); ?>" alt="<?php esc_attr_e( 'View details', 'optix' ); ?>"
                                                            class="img-fluid"></a></li>
                                            </ul>
                                        </div>
                                        <div class="seller_box_content">
                                            <div class="text_wrapper position-relative">
                                                <div class="rating d-flex align-items-center justify-content-center">
                                                    <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                                                    <span class="d-inline-block">(<?php echo esc_html( $product->get_average_rating() ); ?>/5)</span>
                                                </div>
                                                <h6 class="heading6 archivo-font">
                                                    <?php echo esc_html( $product->get_name() ); ?>
                                                </h6>
                                                <div class="objct-price">
                                                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                    <?php
                    $big = 999999999;
                    echo paginate_links( array(
                        'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                        'format'    => '?paged=%#%',
                        'current'   => max( 1, $paged ),
                        'total'     => $loop->max_num_pages,
                        'type'      => 'list',
                        'prev_text' => '<i class="fas fa-angle-left" aria-hidden="true"></i>',
                        'next_text' => '<i class="fas fa-angle-right" aria-hidden="true"></i>',
                    ) );
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
