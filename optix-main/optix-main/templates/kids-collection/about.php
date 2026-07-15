<?php
/**
 * Kids Collection - About Us
 *
 * @package optix
 */

$kc_img = kc_img_base();
$about_enable_about = optix_get_option( 'about_about_enable' );
if ( '' === $about_enable_about ) {
	$about_enable_about = 1;
}
$about_enable_mission = optix_get_option( 'about_mission_enable' );
if ( '' === $about_enable_mission ) {
	$about_enable_mission = 1;
}
$about_enable_team = optix_get_option( 'about_team_enable' );
if ( '' === $about_enable_team ) {
	$about_enable_team = 1;
}
$about_enable_categories = optix_get_option( 'about_categories_enable' );
if ( '' === $about_enable_categories ) {
	$about_enable_categories = 1;
}
$about_enable_instagram = optix_get_option( 'about_instagram_enable' );
if ( '' === $about_enable_instagram ) {
	$about_enable_instagram = 1;
}
$about_enable_benefits = optix_get_option( 'about_benefits_enable' );
if ( '' === $about_enable_benefits ) {
	$about_enable_benefits = 1;
}
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

<?php if ( $about_enable_about ) : ?>
<!-- ABOUT US SECTION -->
<section class="float-left w-100 position-relative about-us-con padding-top padding-bottom main-box">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6">
                <div class="about-us-img-con wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s">
                    <figure><img loading="lazy" src="<?php echo esc_url( optix_img( optix_get_option( 'about_about_image' ), $kc_img . '/about-us-img.jpg' ) ); ?>" alt="" class="img-fluid"></figure>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="about-us-content-con">
                    <div class="heading-title-con mb-0">
                        <span class="special-text d-block archivo-font font-weight-500 wow fadeInRight"
                            data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'about_about_heading' ) ); ?></span>
                        <h2 class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo wp_kses_post( optix_get_option( 'about_about_title' ) ); ?></h2>
                        <p class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'about_about_text_1' ) ); ?></p>
                        <p class="last-prgrfh wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'about_about_text_2' ) ); ?></p>
                        <a href="<?php echo esc_url( optix_get_option( 'about_about_btn_url', '/shop/' ) ); ?>" class="text-decoration-none primary_btn d-inline-block wow fadeInRight"
                            data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'about_about_btn_text' ) ); ?> <i
                                class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ( $about_enable_mission ) : ?>
<!-- OUR MISSION SECTION -->
<section
    class="float-left w-100 position-relative our-mission-con padding-top padding-bottom main-box background-primary">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="mission-inner-con">
                    <div class="heading-title-con mb-0">
                        <span class="special-text d-block archivo-font font-weight-500 wow fadeInLeft"
                            data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'about_mission_heading' ) ); ?></span>
                        <h2 class="wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo wp_kses_post( optix_get_option( 'about_mission_title' ) ); ?></h2>
                        <p class="wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'about_mission_text_1' ) ); ?></p>
                        <p class="mb-0 wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'about_mission_text_2' ) ); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ( $about_enable_team ) : ?>
<!-- OUR TEAM SECTION -->
<section
    class="float-left w-100 posiiton-relative our-team-con padding-top padding-bottom main-box background-grey">
    <div class="container">
        <div class="heading-title-con text-center">
            <span class="special-text d-block wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'about_team_heading' ) ); ?></span>
            <h2 class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'about_team_title' ) ); ?></h2>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="owl-carousel owl-theme wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.05s">
                    <?php
                    $team_members = optix_get_option( 'about_team_members' );
                    if ( ! empty( $team_members ) && is_array( $team_members ) ) :
                        foreach ( $team_members as $member ) :
                            $member_image = is_array( $member ) ? ( $member['image'] ?? '' ) : '';
                            $member_name = is_array( $member ) ? ( $member['name'] ?? '' ) : '';
                            $member_role = is_array( $member ) ? ( $member['role'] ?? '' ) : '';
                            $member_fb   = is_array( $member ) ? ( $member['facebook'] ?? '' ) : '';
                            $member_ig   = is_array( $member ) ? ( $member['instagram'] ?? '' ) : '';
                            $member_yt   = is_array( $member ) ? ( $member['youtube'] ?? '' ) : '';
                    ?>
                    <div class="item">
                        <div class="team-box text-center position-relative">
                            <figure><img loading="lazy" src="<?php echo esc_url( optix_img( $member_image, $kc_img . '/team-person1.jpg' ) ); ?>" alt="" class="img-fluid">
                            </figure>
                            <h5 class="archivo-font"><?php echo esc_html( $member_name ); ?></h5>
                            <span class="designation text-color d-block"><?php echo esc_html( $member_role ); ?></span>
                            <ul class="list-unstyled p-0 mb-0">
                                <?php if ( ! empty( $member_fb ) ) : ?>
                                <li class="d-inline-block"><a href="<?php echo esc_url( $member_fb ); ?>" class="ml-0" aria-label="Facebook"><i
                                            class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
                                </li>
                                <?php endif; ?>
                                <?php if ( ! empty( $member_ig ) ) : ?>
                                <li class="d-inline-block"><a href="<?php echo esc_url( $member_ig ); ?>" aria-label="Instagram"><i
                                            class="fa-brands fa-instagram" aria-hidden="true"></i></a>
                                </li>
                                <?php endif; ?>
                                <?php if ( ! empty( $member_yt ) ) : ?>
                                <li class="d-inline-block"><a href="<?php echo esc_url( $member_yt ); ?>" aria-label="YouTube"><i
                                            class="fa-brands fa-youtube" aria-hidden="true"></i></a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <?php endforeach; else : ?>
                    <?php for ( $i = 1; $i <= 3; $i++ ) : ?>
                    <div class="item">
                        <div class="team-box text-center position-relative">
                            <figure><img loading="lazy" src="<?php echo esc_url( $kc_img . '/team-person' . $i . '.jpg' ); ?>" alt="" class="img-fluid"></figure>
                            <h5 class="archivo-font">Team Member <?php echo esc_html( $i ); ?></h5>
                            <span class="designation text-color d-block">Position</span>
                            <ul class="list-unstyled p-0 mb-0">
                                <li class="d-inline-block"><a href="https://www.facebook.com/login/" class="ml-0" aria-label="Facebook"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a></li>
                                <li class="d-inline-block"><a href="https://www.instagram.com/" aria-label="Instagram"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a></li>
                                <li class="d-inline-block"><a href="https://www.youtube.com/" aria-label="YouTube"><i class="fa-brands fa-youtube" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <?php endfor; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ( $about_enable_categories ) : ?>
<!-- PRODUCT CATEGORIES SECTION -->
<section
    class="float-left w-100 position-relative padding-top padding-bottom  product-categories-con main-box text-center">
    <div class="container">
        <div class="heading-title-con text-center">
            <span class="special-text d-block wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'home_categories_heading' ) ); ?></span>
            <h2 class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'home_categories_title' ) ); ?></h2>
        </div>
        <ul class="list-unstyled p-0 m-0 d-flex align-items-center justify-content-around wow fadeInUp"
            data-wow-duration="2s" data-wow-delay="0.05s">
            <?php
            $categories = optix_get_option( 'home_categories' );
            if ( ! empty( $categories ) && is_array( $categories ) ) :
                foreach ( $categories as $cat ) :
                    $cat_title = is_array( $cat ) ? ( $cat['title'] ?? '' ) : '';
                    $cat_image = is_array( $cat ) ? ( $cat['image'] ?? '' ) : '';
                    $cat_bg    = is_array( $cat ) ? ( $cat['bg_class'] ?? 'bg-light1' ) : 'bg-light1';
                    $cat_url   = is_array( $cat ) ? ( $cat['url'] ?? '/shop/' ) : '/shop/';
            ?>
            <li class="position-relative">
                <a href="<?php echo esc_url( $cat_url ); ?>">
                    <figure class="<?php echo esc_attr( $cat_bg ); ?>"><img loading="lazy" src="<?php echo esc_url( optix_img( $cat_image, $kc_img . '/pc-img1.png' ) ); ?>" alt=""></figure>
                </a>
                <h4 class="mb-0"><?php echo esc_html( $cat_title ); ?></h4>
            </li>
            <?php endforeach; endif; ?>
        </ul>
    </div>
</section>
<?php endif; ?>

<?php if ( $about_enable_instagram ) : ?>
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
                    <?php
                    $insta_images = optix_get_option( 'home_instagram_images' );
                    if ( ! empty( $insta_images ) && is_array( $insta_images ) ) :
                        foreach ( $insta_images as $index => $img ) :
                            $img_url = is_array( $img ) ? ( $img['image'] ?? '' ) : $img;
                            $link_url = is_array( $img ) ? ( $img['url'] ?? 'https://www.instagram.com/' ) : 'https://www.instagram.com/';
                            $top_class = ( $index % 2 === 1 ) ? ' top-box' : '';
                    ?>
                    <li class="<?php echo esc_attr( $top_class ); ?>">
                        <a href="<?php echo esc_url( $link_url ); ?>" aria-label="<?php esc_attr_e( 'View on Instagram', 'optix' ); ?>">
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
<?php endif; ?>

<?php if ( $about_enable_benefits ) : ?>
<!-- BENEFITS SECTION -->
<div class="float-left w-100 position-relative main-box benefits-con">
    <div class="container">
        <div class="row">
            <?php
            $benefits = optix_get_option( 'home_benefits' );
            if ( ! empty( $benefits ) && is_array( $benefits ) ) :
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
<?php endif; ?>
