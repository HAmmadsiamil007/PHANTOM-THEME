<?php
/**
 * Kids Collection - Product Detail
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
                <h1 class=""><?php echo esc_html( optix_get_option( 'product_detail_title', 'Product Details' ) ); ?></h1>
                <div class="breadcrumb-con d-inline-block">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'optix' ); ?></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'Shop', 'optix' ); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( optix_get_option( 'product_detail_title', 'Product Details' ) ); ?> </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TYPES SECTION -->
<section class="types-con types2-con float-left w-100 position-relative main-box">
    <div class="main-container">
        <div class="row">
            <div class="col-lg-5 col-12">
                <div class="product2-tab">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane tab-pane1 fade active show" id="first" role="tabpanel"
                            aria-labelledby="first-tab">
                            <figure class="auction-img mb-0">
                                <img loading="lazy" class="img-fluid" src="<?php echo esc_url( $kc_img . '/shop-image2.png' ); ?>" alt="">
                            </figure>
                        </div>
                        <div class="tab-pane tab-pane1 fade" id="second" role="tabpanel"
                            aria-labelledby="second-tab">
                            <figure class="auction-img mb-0">
                                <img loading="lazy" class="img-fluid" src="<?php echo esc_url( $kc_img . '/shop-image1.png' ); ?>" alt="">
                            </figure>
                        </div>
                        <div class="tab-pane tab-pane1 fade" id="third" role="tabpanel" aria-labelledby="third-tab">
                            <figure class="auction-img mb-0">
                                <img loading="lazy" class="img-fluid" src="<?php echo esc_url( $kc_img . '/shop-image3.png' ); ?>" alt="">
                            </figure>
                        </div>
                        <div class="tab-pane tab-pane1 fade" id="fourth" role="tabpanel"
                            aria-labelledby="fourth-tab">
                            <figure class="auction-img mb-0">
                                <img loading="lazy" class="img-fluid" src="<?php echo esc_url( $kc_img . '/shop-image4.png' ); ?>" alt="">
                            </figure>
                        </div>
                    </div>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link active" id="first-tab" data-bs-toggle="tab" data-bs-target="#first"
                                role="tab" aria-controls="first" aria-selected="false">
                                <figure class="auction-img mb-0"><img loading="lazy" class="img-fluid"
                                        src="<?php echo esc_url( $kc_img . '/shop-image2.png' ); ?>" alt=""></figure>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" id="second-tab" data-bs-toggle="tab" data-bs-target="#second" role="tab"
                                aria-controls="second" aria-selected="false">
                                <figure class="auction-img mb-0"><img loading="lazy" class="img-fluid"
                                        src="<?php echo esc_url( $kc_img . '/shop-image1.png' ); ?>" alt=""></figure>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" id="third-tab" data-bs-toggle="tab" data-bs-target="#third" role="tab"
                                aria-controls="third" aria-selected="true">
                                <figure class="auction-img mb-0"><img loading="lazy" class="img-fluid"
                                        src="<?php echo esc_url( $kc_img . '/shop-image3.png' ); ?>" alt=""></figure>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" id="fourth-tab" data-bs-toggle="tab" data-bs-target="#fourth" role="tab"
                                aria-controls="fourth" aria-selected="true">
                                <figure class="auction-img mb-0"><img loading="lazy" class="img-fluid"
                                        src="<?php echo esc_url( $kc_img . '/shop-image4.png' ); ?>" alt=""></figure>
                            </a>
                        </li>
                    </ul>
                    <div class="propagation">
                        <button id="prevBtn" aria-label="<?php esc_attr_e( 'Previous image', 'optix' ); ?>"><i class="fa-solid fa-angle-left" aria-hidden="true"></i></button>
                        <button id="nextBtn" aria-label="<?php esc_attr_e( 'Next image', 'optix' ); ?>"><i class="fa-solid fa-angle-right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-12">
                <div class="types_content">

                    <h4 class="heading4 archivo-font"><?php echo esc_html( optix_get_option( 'product_detail_name', 'Dreamy Day Pajamaz' ) ); ?></h4>
                    <span class="d-block in-stock"><?php echo esc_html( optix_get_option( 'product_detail_stock', 'In stock' ) ); ?></span>
                    <div class="rating">
                        <i class="fa-solid fa-star" aria-hidden="true"></i>
                        <i class="fa-solid fa-star" aria-hidden="true"></i>
                        <i class="fa-solid fa-star" aria-hidden="true"></i>
                        <i class="fa-solid fa-star" aria-hidden="true"></i>
                        <i class="fa-solid fa-star" aria-hidden="true"></i>
                        <span>(<?php echo esc_html( optix_get_option( 'product_detail_rating', '4.9/5' ) ); ?>)</span>
                    </div>
                    <span class="price"><?php echo esc_html( optix_get_option( 'product_detail_price', '$38.00' ) ); ?> <span class="d-inline-block strike"><?php echo esc_html( optix_get_option( 'product_detail_original_price', '$89.00' ) ); ?></span></span>
                    <p class="text-size-16"><?php echo esc_html( optix_get_option( 'product_detail_desc', 'Neque porro ruisquam est aui dolorem iesum ruia do sit amet consectetur, adies velit, sed num eius modi tempoa incidunt ut labore et dolore magna aute re dolor in reprehenderit in velit esse cillum eaque ipsa quae ab illo inventore veritatis.' ) ); ?>
                    </p>
                    <div class="colors">
                        <span class="heading"><?php echo esc_html( optix_get_option( 'product_detail_color_label', 'Color:' ) ); ?></span>
                        <ul class="list-unstyled mb-0">
                            <li class="red"></li>
                            <li class="orange"></li>
                            <li class="green"></li>
                            <li class="brown"></li>
                        </ul>
                    </div>
                    <div class="quatity_button_wrapper">
                        <div class="quantity-field">
                            <button class="value-button decrease-button" onclick="decreaseValue(this)"
                                title="">-</button>
                            <div class="number">1</div>
                            <button class="value-button increase-button" onclick="increaseValue(this)"
                                title="">+</button>
                        </div>
                        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="text-decoration-none primary_btn"><?php echo esc_html( optix_get_option( 'product_detail_add_to_cart', 'Add to Cart' ) ); ?></a>
                    </div>
                    <div class="text">
                        <div class="wishlist">
                            <i class="fa-regular fa-heart" aria-hidden="true"></i>
                            <span><?php echo esc_html( optix_get_option( 'product_detail_wishlist', 'Add to wishlist' ) ); ?></span>
                        </div>
                        <div class="compare">
                            <i class="fa-solid fa-code-compare" aria-hidden="true"></i>
                            <span><?php echo esc_html( optix_get_option( 'product_detail_compare', 'Compare' ) ); ?></span>
                        </div>
                    </div>
                    <div class="guranted-safe-checkout">
                        <span class="heading"><?php echo esc_html( optix_get_option( 'product_detail_safe_checkout', 'Guaranteed Safe Checkout:' ) ); ?></span>
                        <img loading="lazy" src="<?php echo esc_url( $kc_img . '/cards-img.png' ); ?>" alt="cards" class="">
                        <div class="safe-types">
                            <div> <span class="text-uppercase sku-text d-inline-block"><?php echo esc_html( optix_get_option( 'product_detail_sku_label', 'SKU:' ) ); ?></span><span
                                    class="d-inline-block font-weight-600 hd-text"><?php echo esc_html( optix_get_option( 'product_detail_sku_value', 'HD_158' ) ); ?></span></div>
                            <div> <span class=" sku-text d-inline-block"><?php echo esc_html( optix_get_option( 'product_detail_categories_label', 'Categories:' ) ); ?></span><span
                                    class="d-inline-block font-weight-600 hd-text"><?php echo esc_html( optix_get_option( 'product_detail_categories_value', 'Decor, Home Decor, Furniture, Interior' ) ); ?></span></div>
                            <div> <span class=" sku-text d-inline-block"><?php echo esc_html( optix_get_option( 'product_detail_tags_label', 'Tags:' ) ); ?></span><span
                                    class="d-inline-block font-weight-600 hd-text"><?php echo esc_html( optix_get_option( 'product_detail_tags_value', 'Pjs, Pajamaz' ) ); ?></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MORE INFORMATION SECTION -->
<section class="more_information_section more_information_section2 float-left w-100 padding-bottom position-relative main-box">
    <div class="main-container">
        <div class="tabs-box tabs-options">
            <ul class="nav nav-tabs" data-aos="fade-up">
                <li><a class="active" data-bs-toggle="tab" data-bs-target="#description"><?php echo esc_html( optix_get_option( 'product_detail_tab_description', 'Description' ) ); ?></a></li>
                <li><a data-bs-toggle="tab" data-bs-target="#information"><?php echo esc_html( optix_get_option( 'product_detail_tab_additional', 'Additional Information' ) ); ?></a></li>
                <li><a data-bs-toggle="tab" data-bs-target="#reviews"><?php echo esc_html( optix_get_option( 'product_detail_tab_reviews', 'Reviews' ) ); ?></a></li>
            </ul>
            <div class="tab-content">
                <div id="description" class="tab-pane fade in active show">
                    <div class="row" data-aos="fade-up">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="description_content">
                                <p class="text-size-16"><?php echo esc_html( optix_get_option( 'product_detail_desc_paragraph_1', 'Ratione volurtatem serui nesciunt neaue porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur.' ) ); ?>
                                </p>
                                <p class="text-size-16 mb-0"><?php echo esc_html( optix_get_option( 'product_detail_desc_paragraph_2', 'Quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt porro quisquam est, qui dolore ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptate ruis aute irure dolor in reprehenderit.' ) ); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="information" class="tab-pane fade">
                    <div class="row" data-aos="fade-up">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="information_content">
                                <table>
                                    <tbody>
                                        <tr>
                                            <th><?php echo esc_html( optix_get_option( 'product_detail_info_types_label', 'Types' ) ); ?></th>
                                            <td><?php echo esc_html( optix_get_option( 'product_detail_info_types_value', 'Chairs, Tables, Sofas, Beds, Cabinets' ) ); ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo esc_html( optix_get_option( 'product_detail_info_materials_label', 'Materials' ) ); ?></th>
                                            <td><?php echo esc_html( optix_get_option( 'product_detail_info_materials_value', 'Wood, Metal, Plastic, Glass, Upholstery' ) ); ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo esc_html( optix_get_option( 'product_detail_info_features_label', 'Features' ) ); ?></th>
                                            <td><?php echo esc_html( optix_get_option( 'product_detail_info_features_value', 'Adjustable, Foldable, Cushioned, Storage, Reclining' ) ); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="reviews" class="tab-pane fade">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="reviews_content_box">
                                <figure class="mb-0 float-left">
                                    <img loading="lazy" class="img-fluid hover-effect" src="<?php echo esc_url( $kc_img . '/testimonials_image1.jpg' ); ?>"
                                        alt="">
                                </figure>
                                <div class="text_wrapper">
                                    <h5><?php echo esc_html( optix_get_option( 'product_detail_review_author', 'Jonathan Andrew' ) ); ?></h5>
                                    <p class="text-size-16"><?php echo esc_html( optix_get_option( 'product_detail_review_role', 'Happy Customer' ) ); ?></p>
                                    <ul class="list-unstyled mb-0">
                                        <li><i class="fa-solid fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa-solid fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa-solid fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa-solid fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa-solid fa-star" aria-hidden="true"></i></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" data-aos="fade-up">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="reviews_content">
                                <h6><?php echo esc_html( optix_get_option( 'product_detail_review_heading', 'Review' ) ); ?></h6>
                                <h3><?php echo esc_html( optix_get_option( 'product_detail_review_title', 'Post Your Review' ) ); ?></h3>
                                <form>
                                    <?php wp_nonce_field( 'optix_review', 'optix_review_nonce' ); ?>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                            <div class="form-group mb-0">
                                                <label for="fname" class="screen-reader-text"><?php esc_html_e( 'Your Name', 'optix' ); ?></label>
                                                <input type="text" name="name" id="fname" class="form-control"
                                                    placeholder="<?php echo esc_attr( optix_get_option( 'product_detail_review_name_placeholder', 'Your Name' ) ); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                            <div class="form-group mb-0">
                                                <label for="emailid" class="screen-reader-text"><?php esc_html_e( 'Your Email', 'optix' ); ?></label>
                                                <input type="email" name="email" id="emailid" class="form-control"
                                                    placeholder="<?php echo esc_attr( optix_get_option( 'product_detail_review_email_placeholder', 'Your Email' ) ); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                            <div class=" form-group mb-0">
                                                <label for="comments" class="screen-reader-text"><?php esc_html_e( 'Your Review', 'optix' ); ?></label>
                                                <textarea class="form-control" name="comments" id="comments"
                                                    rows="3" placeholder="<?php echo esc_attr( optix_get_option( 'product_detail_review_comment_placeholder', 'Your Review' ) ); ?>"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn_wrapper">
                                        <button type="submit" name="submitnow" id="submit_now"><?php echo esc_html( optix_get_option( 'product_detail_review_btn', 'Post Review' ) ); ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PRODUCTS SECTION -->
<section class="product-con feature-con position-relative float-left w-100 background-grey overflow-hidden padding-top padding-bottom main-box">
    <div class="container-fluid">
        <div class="heading-title-con text-center">
            <span class="special-text d-inline-block wow fadeInLeft" data-wow-duration="2s"
                data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'product_more_title', 'More Products' ) ); ?></span>
            <h2 class="wow fadeInRight mb-0" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'product_related_title', 'Related Products' ) ); ?>
            </h2>
        </div>
        <div class="product-box-wrapper">
            <div class="row">
                <div class="col-12">
                    <div class="owl-carousel owl-theme">
                        <?php
                        $related_product_ids = array();
                        $current_product_id  = get_the_ID();

                        if ( function_exists( 'wc_get_related_products' ) && $current_product_id ) {
                            $related_product_ids = wc_get_related_products( $current_product_id, 15 );
                        }

                        if ( ! empty( $related_product_ids ) ) :
                            foreach ( $related_product_ids as $related_id ) :
                                $rp = wc_get_product( $related_id );
                                if ( ! $rp ) {
                                    continue;
                                }
                                $rp_img = wp_get_attachment_image_url( $rp->get_image_id(), 'medium' );
                                if ( ! $rp_img ) {
                                    $rp_img = wc_placeholder_img_src( 'medium' );
                                }
                                $rp_name   = $rp->get_name();
                                $rp_price  = $rp->get_price_html();
                                $rp_link   = $rp->get_permalink();
                                $rp_rating = wc_get_rating_html( $rp->get_average_rating() );
                                $rp_sale   = $rp->is_on_sale();
                                $rp_cart   = $rp->add_to_cart_url();
                        ?>
                                <div class="item w-100">
                                    <div class="seller-box w-100">
                                        <div class="seller_image_box position-relative">
                                            <?php if ( $rp_sale ) : ?>
                                            <span class="d-inline-block position-absolute sale-tag background-primary text-white"><?php echo esc_html( optix_get_option( 'product_related_sale_tag', 'Sale' ) ); ?></span>
                                            <?php endif; ?>
                                            <figure class="mb-0">
                                                <img loading="lazy" src="<?php echo esc_url( $rp_img ); ?>" alt="<?php echo esc_attr( $rp_name ); ?>" class="img-fluid">
                                            </figure>
                                            <ul class="list-unstyled mb-0">
                                                <li class="icon"><a href="<?php echo esc_url( $rp_cart ); ?>" aria-label="<?php esc_attr_e( 'Add to cart', 'optix' ); ?>"><img loading="lazy"
                                                            src="<?php echo esc_url( $kc_img . '/feature-cart.png' ); ?>" alt=""
                                                            class="img-fluid"></a></li>
                                                <li class="icon"><a href="#" aria-label="<?php esc_attr_e( 'Add to wishlist', 'optix' ); ?>"><img loading="lazy"
                                                            src="<?php echo esc_url( $kc_img . '/feature-heart.png' ); ?>" alt=""
                                                            class="img-fluid"></a></li>
                                                <li class="icon"><a href="<?php echo esc_url( $rp_link ); ?>" aria-label="<?php esc_attr_e( 'View details', 'optix' ); ?>"><img loading="lazy"
                                                            src="<?php echo esc_url( $kc_img . '/feature-eye.png' ); ?>" alt=""
                                                            class="img-fluid"></a></li>
                                            </ul>
                                        </div>
                                        <div class="seller_box_content">
                                            <div class="text_wrapper position-relative">
                                                <div class="rating d-flex align-items-center justify-content-center">
                                                    <?php if ( $rp_rating ) : ?>
                                                        <?php echo wp_kses_post( $rp_rating ); ?>
                                                    <?php else : ?>
                                                        <i class="fa-solid fa-star" aria-hidden="true"></i>
                                                        <i class="fa-solid fa-star" aria-hidden="true"></i>
                                                        <i class="fa-solid fa-star" aria-hidden="true"></i>
                                                        <i class="fa-solid fa-star" aria-hidden="true"></i>
                                                        <i class="fa-solid fa-star" aria-hidden="true"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <h6 class="heading6 archivo-font">
                                                    <?php echo esc_html( $rp_name ); ?>
                                                </h6>
                                                <div class="objct-price">
                                                    <?php echo wp_kses_post( $rp_price ); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            endforeach;
                        else :
                            $fallback_products = array(
                                array( 'img' => 'product-img1.png',  'key' => 'product_related_1',  'sale' => false ),
                                array( 'img' => 'product-img2.png',  'key' => 'product_related_2',  'sale' => true  ),
                                array( 'img' => 'product-img3.png',  'key' => 'product_related_3',  'sale' => false ),
                                array( 'img' => 'product-img4.png',  'key' => 'product_related_4',  'sale' => true  ),
                                array( 'img' => 'product-img5.png',  'key' => 'product_related_5',  'sale' => true  ),
                                array( 'img' => 'product-img6.png',  'key' => 'product_related_6',  'sale' => false ),
                                array( 'img' => 'product-img7.png',  'key' => 'product_related_7',  'sale' => true  ),
                                array( 'img' => 'product-img8.png',  'key' => 'product_related_8',  'sale' => false ),
                                array( 'img' => 'product-img9.png',  'key' => 'product_related_9',  'sale' => true  ),
                                array( 'img' => 'product-img10.png', 'key' => 'product_related_10', 'sale' => true  ),
                                array( 'img' => 'product-img11.png', 'key' => 'product_related_11', 'sale' => false ),
                                array( 'img' => 'product-img12.png', 'key' => 'product_related_12', 'sale' => true  ),
                                array( 'img' => 'product-img3.png',  'key' => 'product_related_13', 'sale' => false ),
                                array( 'img' => 'product-img7.png',  'key' => 'product_related_14', 'sale' => true  ),
                                array( 'img' => 'product-img9.png',  'key' => 'product_related_15', 'sale' => true  ),
                            );
                            foreach ( $fallback_products as $fb ) :
                        ?>
                                <div class="item w-100">
                                    <div class="seller-box w-100">
                                        <div class="seller_image_box position-relative">
                                            <?php if ( $fb['sale'] ) : ?>
                                            <span class="d-inline-block position-absolute sale-tag background-primary text-white"><?php echo esc_html( optix_get_option( 'product_related_sale_tag', 'Sale' ) ); ?></span>
                                            <?php endif; ?>
                                            <figure class="mb-0">
                                                <img loading="lazy" src="<?php echo esc_url( $kc_img . '/' . $fb['img'] ); ?>" alt="" class="img-fluid">
                                            </figure>
                                            <ul class="list-unstyled mb-0">
                                                <li class="icon"><a href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'Add to cart', 'optix' ); ?>"><img loading="lazy"
                                                            src="<?php echo esc_url( $kc_img . '/feature-cart.png' ); ?>" alt=""
                                                            class="img-fluid"></a></li>
                                                <li class="icon"><a href="#" aria-label="<?php esc_attr_e( 'Add to wishlist', 'optix' ); ?>"><img loading="lazy"
                                                            src="<?php echo esc_url( $kc_img . '/feature-heart.png' ); ?>" alt=""
                                                            class="img-fluid"></a></li>
                                                <li class="icon"><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" aria-label="<?php esc_attr_e( 'View details', 'optix' ); ?>"><img loading="lazy"
                                                            src="<?php echo esc_url( $kc_img . '/feature-eye.png' ); ?>" alt=""
                                                            class="img-fluid"></a></li>
                                            </ul>
                                        </div>
                                        <div class="seller_box_content">
                                            <div class="text_wrapper position-relative">
                                                <div class="rating d-flex align-items-center justify-content-center">
                                                    <i class="fa-solid fa-star" aria-hidden="true"></i>
                                                    <i class="fa-solid fa-star" aria-hidden="true"></i>
                                                    <i class="fa-solid fa-star" aria-hidden="true"></i>
                                                    <i class="fa-solid fa-star" aria-hidden="true"></i>
                                                    <i class="fa-solid fa-star" aria-hidden="true"></i>
                                                </div>
                                                <h6 class="heading6 archivo-font">
                                                    <?php echo esc_html( optix_get_option( $fb['key'] . '_name', '' ) ); ?>
                                                </h6>
                                                <div class="objct-price">
                                                    <?php echo esc_html( optix_get_option( $fb['key'] . '_price', '' ) ); ?> <span class="d-inline-block"><?php echo esc_html( optix_get_option( $fb['key'] . '_original', '' ) ); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
