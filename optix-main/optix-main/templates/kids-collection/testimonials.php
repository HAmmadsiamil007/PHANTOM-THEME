<?php
/**
 * Kids Collection - Testimonials
 *
 * @package optix
 */

$kc_img = kc_img_base();
$testimonials = optix_get_option( 'home_testimonials' );
$insta_images = optix_get_option( 'home_instagram_images' );
$benefits = optix_get_option( 'home_benefits' );
?>
<!-- SUB BANNER SECTION -->
<section
    class="sub-banner-con position-relative float-left w-100 gradient-overlay d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="col-xl-12 col-lg-12 mr-auto ml-auto">
            <div class="sub-banner-inner-con text-center">
                <h1 class=""><?php echo esc_html( optix_get_option( 'testimonials_title', 'Testimonials' ) ); ?></h1>
                <div class="breadcrumb-con d-inline-block">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'optix' ); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( optix_get_option( 'testimonials_title', 'Testimonials' ) ); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONIALS SECTION -->
<section class="float-left w-100 testimonial-con2 position-relative padding-top main-box">
    <div class="container">
        <div class="heading-title-con text-center">
            <span class="special-text d-block wow fadeInLeft" data-wow-duration="2s"
                data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'testimonials_heading', 'Testimonials' ) ); ?></span>
            <h2 class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'testimonials_title_inner', 'Our Client Reviews' ) ); ?></h2>
        </div>
        <div class="row">
            <div class="col-xl-10 col-12 mx-auto position-relative wow fadeInUp" data-wow-duration="2s"
                data-wow-delay="0.05s">
                <figure class="testimonial-sideimage mb-0" data-aos="fade-up">
                    <img loading="lazy" src="<?php echo esc_url( $kc_img . '/testimonial-sideimage.png' ); ?>" alt="" class="img-fluid">
                </figure>
                <div id="testimonialcarousel" class="carousel slide" data-bs-ride="carousel" data-aos="fade-up">
                    <div class="testimonial_carousel text-center position-relative">
                        <div class="carousel-inner">
                            <?php if ( ! empty( $testimonials ) && is_array( $testimonials ) ) :
                                foreach ( $testimonials as $index => $t ) :
                                    $rating = is_array( $t ) ? (int) ( $t['rating'] ?? 5 ) : 5;
                                    $text = is_array( $t ) ? ( $t['text'] ?? '' ) : '';
                                    $name = is_array( $t ) ? ( $t['name'] ?? '' ) : '';
                                    $role = is_array( $t ) ? ( $t['role'] ?? '' ) : '';
                            ?>
                            <div class="carousel-item <?php echo $index === 1 ? 'active' : ''; ?>">
                                <div class="testimonial-box">
                                    <ul class="list-unstyled">
                                        <?php for ( $i = 0; $i < $rating; $i++ ) : ?>
                                        <li><i class="fa-solid fa-star" aria-hidden="true"></i></li>
                                        <?php endfor; ?>
                                    </ul>
                                    <p class="paragarph">&ldquo;<?php echo esc_html( $text ); ?>&rdquo;</p>
                                    <div class="lower_content">
                                        <span class="name"><?php echo esc_html( $name ); ?></span>
                                        <span class="review"><?php echo esc_html( $role ); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; endif; ?>
                        </div>
                    </div>
                    <ul class="carousel-indicators">
                        <?php if ( ! empty( $testimonials ) && is_array( $testimonials ) ) :
                            foreach ( $testimonials as $index => $t ) :
                                $avatar = is_array( $t ) ? ( $t['avatar'] ?? '' ) : '';
                        ?>
                        <li data-bs-target="#testimonialcarousel" data-bs-slide-to="<?php echo esc_attr( $index ); ?>" class="<?php echo $index === 1 ? 'active' : ''; ?>">
                            <figure class="mb-0 image<?php echo esc_attr( $index + 1 ); ?>">
                                <img loading="lazy" src="<?php echo esc_url( optix_img( $avatar, $kc_img . '/review-person' . ( $index + 1 ) . '.jpg' ) ); ?>" alt=""
                                    class="img-fluid invert_effect">
                            </figure>
                        </li>
                        <?php endforeach; endif; ?>
                    </ul>
                    <div class="pagination_outer">
                        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialcarousel"
                            data-bs-slide="prev">
                            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#testimonialcarousel"
                            data-bs-slide="next">
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FOLLOW INSTAGRAM SECTION -->
<section class="float-left w-100 follow-con position-relative padding-top padding-bottom main-box background-grey">
    <figure><img loading="lazy" src="<?php echo esc_url( $kc_img . '/yellow-elipse.png' ); ?>" alt="" class="position-absolute yellow-elipse">
    </figure>
    <figure><img loading="lazy" src="<?php echo esc_url( $kc_img . '/purple-elipse.png' ); ?>" alt="" class="position-absolute purple-elipse">
    </figure>
    <div class="container">
        <div class="heading-title-con text-center">
            <span class="special-text d-block wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'home_instagram_heading' ) ); ?></span>
            <h2 class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'home_instagram_title' ) ); ?></h2>
        </div>
        <div class="row wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.05s">
            <div class="col-12 position-relative">
                <ul class="list-unstyled mb-0" data-aos="fade-up">
                    <?php if ( ! empty( $insta_images ) && is_array( $insta_images ) ) :
                        foreach ( $insta_images as $index => $img ) :
                            $img_url = is_array( $img ) ? ( $img['image'] ?? '' ) : $img;
                            $link_url = is_array( $img ) ? ( $img['url'] ?? 'https://www.instagram.com/' ) : 'https://www.instagram.com/';
                            $top_class = ( $index % 2 === 1 ) ? ' top-box' : '';
                    ?>
                    <li class="<?php echo esc_attr( $top_class ); ?>">
                        <a href="<?php echo esc_url( $link_url ); ?>">
                            <figure class="image mb-0">
                                <img loading="lazy" src="<?php echo esc_url( optix_img( $img_url, $kc_img . '/follow-image' . ( $index + 1 ) . '.jpg' ) ); ?>" alt="" class="img-fluid">
                            </figure>
                            <div class="icon"><i class="fa-brands fa-instagram" aria-hidden="true"></i></div>
                        </a>
                    </li>
                    <?php endforeach; endif; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- BENEFITS SECTION -->
<div class="float-left w-100 position-relative main-box benefits-con">
    <div class="container">
        <div class="row">
            <?php if ( ! empty( $benefits ) && is_array( $benefits ) ) :
                $animations = [ 'fadeInLeft', 'fadeInRight', 'fadeInLeft', 'fadeInRight' ];
                foreach ( $benefits as $index => $ben ) :
                    $ben_icon = is_array( $ben ) ? ( $ben['icon'] ?? '' ) : '';
                    $ben_text = is_array( $ben ) ? ( $ben['text'] ?? '' ) : '';
                    $anim = $animations[ $index % count( $animations ) ];
            ?>
            <div class="col-lg-3 col-md-6 d-flex wow <?php echo esc_attr( $anim ); ?>" data-wow-duration="2s" data-wow-delay="0.05s">
                <div class="benefits-box w-100">
                    <img loading="lazy" src="<?php echo esc_url( optix_img( $ben_icon, $kc_img . '/benefit-icon' . ( $index + 1 ) . '.png' ) ); ?>" alt="" class="img-fluid d-inline-block">
                    <span class="d-inline-block"><?php echo esc_html( $ben_text ); ?></span>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</div>
