<?php
/**
 * Kids Collection - Load More
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
                <h1 class=""><?php echo esc_html( optix_get_option( 'load_more_title', 'Load More' ) ); ?></h1>
                <div class="breadcrumb-con d-inline-block">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'optix' ); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( optix_get_option( 'load_more_title', 'Load More' ) ); ?> </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="blog-posts blogpage-section loadblog-section float-left w-100">
    <div class="container">
        <div class="row wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.3s">
            <div id="blog" class="col-xl-12">
                <div class="row">
                    <div class="col-xl-4 col-lg-4">
                        <div class="blog-box load-blog float-left w-100 post-item mb-4 hide-blog">
                            <div class="post-item-wrap position-relative">
                                <div class="post-image">
                                    <a href="<?php echo esc_url( home_url( '/single-blog/' ) ); ?>">
                                        <img loading="lazy" alt="" src="<?php echo esc_url( $kc_img . '/' . optix_get_option( 'load_more_img_1', 'standard_post_img01.jpg' ) ); ?>" loading="lazy">
                                    </a>
                                </div>
                                <div class="lower-portion">
                                    <div class="span-i-con">
                                        <i class="fas fa-user" aria-hidden="true"></i>
                                        <span class="text-size-14 text-mr"><?php echo esc_html( optix_get_option( 'load_more_by_text', 'By : Admin' ) ); ?></span>
                                        <i class="fas fa-tag" aria-hidden="true"></i>
                                        <span class="text-size-14"><?php echo esc_html( optix_get_option( 'load_more_category_text', 'Virtual Assistant' ) ); ?></span>
                                    </div>
                                    <a href="<?php echo esc_url( home_url( '/single-blog/' ) ); ?>">
                                        <h5><?php echo esc_html( optix_get_option( 'load_more_post_title', 'Why You Need Virtual Assistant for Your Company' ) ); ?></h5>
                                    </a>
                                </div>
                                <div class="button-portion loadone_twocol">
                                    <div class="date">
                                        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                        <span class="mb-0 text-size-14"><?php echo esc_html( optix_get_option( 'load_more_date', 'Dec 20,2022' ) ); ?></span>
                                    </div>
                                    <div class="button">
                                        <a class="mb-0 read_more text-decoration-none"
                                            href="<?php echo esc_url( home_url( '/single-blog/' ) ); ?>"><?php echo esc_html( optix_get_option( 'load_more_read_more', 'Read More' ) ); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4">
                        <div class="blog-box load-blog float-left w-100 post-item mb-4 hide-blog">
                            <div class="post-item-wrap position-relative">
                                <div id="blogslider" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <div class="carousel-item">
                                            <img loading="lazy" src="<?php echo esc_url( $kc_img . '/' . optix_get_option( 'load_more_img_2', 'standard_post_img02.jpg' ) ); ?>" alt=""
                                                loading="lazy">
                                        </div>
                                        <div class="carousel-item">
                                            <img loading="lazy" src="<?php echo esc_url( $kc_img . '/' . optix_get_option( 'load_more_img_3', 'standard_post_img04.jpg' ) ); ?>" alt=""
                                                loading="lazy">
                                        </div>
                                        <div class="carousel-item active">
                                            <img loading="lazy" src="<?php echo esc_url( $kc_img . '/' . optix_get_option( 'load_more_img_4', 'standard_post_img06.jpg' ) ); ?>" alt=""
                                                loading="lazy">
                                        </div>
                                    </div>
                                    <a class="carousel-control-prev" href="#blogslider" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </a>
                                    <a class="carousel-control-next" href="#blogslider" data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </a>
                                </div>
                                <div class="lower-portion">
                                    <div class="span-i-con">
                                        <i class="fas fa-user" aria-hidden="true"></i>
                                        <span class="text-size-14 text-mr"><?php echo esc_html( optix_get_option( 'load_more_by_text', 'By : Admin' ) ); ?></span>
                                        <i class="fas fa-tag" aria-hidden="true"></i>
                                        <span class="text-size-14"><?php echo esc_html( optix_get_option( 'load_more_category_text', 'Virtual Assistant' ) ); ?></span>
                                    </div>
                                    <a href="<?php echo esc_url( home_url( '/single-blog/' ) ); ?>">
                                        <h5><?php echo esc_html( optix_get_option( 'load_more_post_title', 'Why You Need Virtual Assistant for Your Company' ) ); ?></h5>
                                    </a>
                                </div>
                                <div class="button-portion loadone_twocol">
                                    <div class="date">
                                        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                        <span class="mb-0 text-size-14"><?php echo esc_html( optix_get_option( 'load_more_date', 'Dec 20,2022' ) ); ?></span>
                                    </div>
                                    <div class="button">
                                        <a class="mb-0 read_more text-decoration-none" href="<?php echo esc_url( home_url( '/single-blog/' ) ); ?>"><?php echo esc_html( optix_get_option( 'load_more_read_more', 'Read More' ) ); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4">
                        <div class="blog-box load-blog float-left w-100 post-item mb-4 hide-blog">
                            <div class="post-item-wrap position-relative">
                                <div class="post-image">
                                    <a href="<?php echo esc_url( home_url( '/single-blog/' ) ); ?>"><img loading="lazy" alt=""
                                            src="<?php echo esc_url( $kc_img . '/' . optix_get_option( 'load_more_img_5', 'standard_post_img03.jpg' ) ); ?>" loading="lazy"> </a>
                                </div>
                                <div class="lower-portion">
                                    <div class="span-i-con">
                                        <i class="fas fa-user" aria-hidden="true"></i>
                                        <span class="text-size-14 text-mr"><?php echo esc_html( optix_get_option( 'load_more_by_text', 'By : Admin' ) ); ?></span>
                                        <i class="fas fa-tag" aria-hidden="true"></i>
                                        <span class="text-size-14"><?php echo esc_html( optix_get_option( 'load_more_category_text', 'Virtual Assistant' ) ); ?></span>
                                    </div>
                                    <a href="<?php echo esc_url( home_url( '/single-blog/' ) ); ?>">
                                        <h5><?php echo esc_html( optix_get_option( 'load_more_post_title', 'Why You Need Virtual Assistant for Your Company' ) ); ?></h5>
                                    </a>
                                </div>
                                <div class="button-portion loadone_twocol">
                                    <div class="date">
                                        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                        <span class="mb-0 text-size-14"><?php echo esc_html( optix_get_option( 'load_more_date', 'Dec 20,2022' ) ); ?></span>
                                    </div>
                                    <div class="button">
                                        <a class="mb-0 read_more text-decoration-none" href="<?php echo esc_url( home_url( '/single-blog/' ) ); ?>"><?php echo esc_html( optix_get_option( 'load_more_read_more', 'Read More' ) ); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row hide-blog hide-blog-outer-wrap">
                            <div class="col-xl-4 col-lg-4">
                                <div class="blog-box load-blog hide-blog">
                                    <div class="post-item-wrap position-relative">
                                        <div class="post-audio position-relative">
                                            <a href="<?php echo esc_url( home_url( '/single-blog/' ) ); ?>"><img loading="lazy" src="<?php echo esc_url( $kc_img . '/' . optix_get_option( 'load_more_img_6', 'blog-image4.jpg' ) ); ?>"
                                                    alt="" class="img-fluid" loading="lazy"></a>
                                        </div>
                                        <div class="lower-portion">
                                            <div class="span-i-con">
                                                <i class="fas fa-user" aria-hidden="true"></i>
                                                <span class="text-size-14 text-mr"><?php echo esc_html( optix_get_option( 'load_more_by_text', 'By : Admin' ) ); ?></span>
                                                <i class="fas fa-tag" aria-hidden="true"></i>
                                                <span class="text-size-14"><?php echo esc_html( optix_get_option( 'load_more_category_text', 'Virtual Assistant' ) ); ?></span>
                                            </div>
                                            <a href="<?php echo esc_url( home_url( '/single-blog/' ) ); ?>">
                                                <h5><?php echo esc_html( optix_get_option( 'load_more_post_title', 'Why You Need Virtual Assistant for Your Company' ) ); ?></h5>
                                            </a>
                                        </div>
                                        <div class="button-portion">
                                            <div class="date">
                                                <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                                <span class="mb-0 text-size-14"><?php echo esc_html( optix_get_option( 'load_more_date', 'Dec 20,2022' ) ); ?></span>
                                            </div>
                                            <div class="button">
                                                <a class="mb-0 read_more text-decoration-none"
                                                    href="<?php echo esc_url( home_url( '/single-blog/' ) ); ?>"><?php echo esc_html( optix_get_option( 'load_more_read_more', 'Read More' ) ); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4">
                                <div class="blog-box load-blog hide-blog">
                                    <div class="post-item-wrap position-relative">
                                        <div class="post-video">
                                            <div class="embed-container"><iframe
                                                    src="<?php echo esc_url( optix_get_option( 'load_more_video_vimeo', '' ) ); ?>"></iframe>
                                            </div>
                                        </div>
                                        <div class="lower-portion">
                                            <div class="span-i-con">
                                                <i class="fas fa-user" aria-hidden="true"></i>
                                                <span class="text-size-14 text-mr"><?php echo esc_html( optix_get_option( 'load_more_by_text', 'By : Admin' ) ); ?></span>
                                                <i class="fas fa-tag" aria-hidden="true"></i>
                                                <span class="text-size-14"><?php echo esc_html( optix_get_option( 'load_more_category_text', 'Virtual Assistant' ) ); ?></span>
                                            </div>
                                            <a href="<?php echo esc_url( home_url( '/single-blog/' ) ); ?>">
                                                <h5><?php echo esc_html( optix_get_option( 'load_more_post_title', 'Why You Need Virtual Assistant for Your Company' ) ); ?></h5>
                                            </a>
                                        </div>
                                        <div class="button-portion">
                                            <div class="date">
                                                <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                                <span class="mb-0 text-size-14"><?php echo esc_html( optix_get_option( 'load_more_date', 'Dec 20,2022' ) ); ?></span>
                                            </div>
                                            <div class="button">
                                                <a class="mb-0 read_more text-decoration-none"
                                                    href="<?php echo esc_url( home_url( '/single-blog/' ) ); ?>"><?php echo esc_html( optix_get_option( 'load_more_read_more', 'Read More' ) ); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4">
                                <div class="blog-box hide-blog">
                                    <div class="post-item-wrap position-relative">
                                        <div class="post-video">
                                            <div class="fluid-width-video-wrapper">
                                                <iframe width="560" height="376"
                                                    src="<?php echo esc_url( optix_get_option( 'load_more_video_youtube', '' ) ); ?>"></iframe>
                                            </div>
                                        </div>
                                        <div class="infinite-blog float-left">
                                            <div class="lower-portion">
                                                <div class="span-i-con">
                                                    <i class="fas fa-user" aria-hidden="true"></i>
                                                    <span class="text-size-14 text-mr"><?php echo esc_html( optix_get_option( 'load_more_by_text', 'By : Admin' ) ); ?></span>
                                                    <i class="fas fa-tag" aria-hidden="true"></i>
                                                    <span class="text-size-14"><?php echo esc_html( optix_get_option( 'load_more_category_text', 'Virtual Assistant' ) ); ?></span>
                                                </div>
                                                <a href="<?php echo esc_url( home_url( '/single-blog/' ) ); ?>">
                                                    <h5><?php echo esc_html( optix_get_option( 'load_more_post_title', 'Why You Need Virtual Assistant for Your Company' ) ); ?></h5>
                                                </a>
                                            </div>
                                            <div class="button-portion">
                                                <div class="date">
                                                    <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                                    <span class="mb-0 text-size-14"><?php echo esc_html( optix_get_option( 'load_more_date', 'Dec 20,2022' ) ); ?></span>
                                                </div>
                                                <div class="button">
                                                    <a class="mb-0 read_more text-decoration-none"
                                                        href="<?php echo esc_url( home_url( '/single-blog/' ) ); ?>"><?php echo esc_html( optix_get_option( 'load_more_read_more', 'Read More' ) ); ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="load-more d-inline-block m-auto align-top">
                        <a class="default-btn hover-effect" href="#" id="loadMore"><?php echo esc_html( optix_get_option( 'load_more_button_text', 'Load More' ) ); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
