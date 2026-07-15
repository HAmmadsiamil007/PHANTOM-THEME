<?php
/**
 * Kids Collection - Team
 *
 * @package optix
 */

$kc_img = kc_img_base();
$team_members = optix_get_option( 'team_members' );
?>
<!-- SUB BANNER SECTION -->
<section
    class="sub-banner-con position-relative float-left w-100 gradient-overlay d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="col-xl-12 col-lg-12 mr-auto ml-auto">
            <div class="sub-banner-inner-con text-center">
                <h1 class=""><?php echo esc_html( optix_get_option( 'team_title', 'Team' ) ); ?></h1>
                <div class="breadcrumb-con d-inline-block">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'optix' ); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( optix_get_option( 'team_title', 'Team' ) ); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- OUR TEAM SECTION -->
<section class="float-left w-100 posiiton-relative our-team-con padding-top padding-bottom main-box">
    <div class="container">
        <div class="heading-title-con text-center">
            <span class="special-text d-block wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'team_heading', 'Experts Team' ) ); ?></span>
            <h2 class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'team_title_inner', 'Our Team Members' ) ); ?></h2>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="owl-carousel owl-theme wow fadeInUp" data-wow-duration="2s" data-wow-delay="0.05s">
                    <?php if ( ! empty( $team_members ) && is_array( $team_members ) ) :
                        foreach ( $team_members as $member ) :
                            $m_img = is_array( $member ) ? ( $member['image'] ?? '' ) : '';
                            $m_name = is_array( $member ) ? ( $member['name'] ?? '' ) : '';
                            $m_role = is_array( $member ) ? ( $member['role'] ?? '' ) : '';
                            $m_fb = is_array( $member ) ? ( $member['facebook'] ?? '' ) : '';
                            $m_ig = is_array( $member ) ? ( $member['instagram'] ?? '' ) : '';
                            $m_yt = is_array( $member ) ? ( $member['youtube'] ?? '' ) : '';
                    ?>
                    <div class="item">
                        <div class="team-box text-center position-relative">
                            <figure><img loading="lazy" src="<?php echo esc_url( optix_img( $m_img, $kc_img . '/team-person1.jpg' ) ); ?>" alt="" class="img-fluid">
                            </figure>
                            <h5 class="archivo-font"><?php echo esc_html( $m_name ); ?></h5>
                            <span class="designation text-color d-block"><?php echo esc_html( $m_role ); ?></span>
                            <ul class="list-unstyled p-0 mb-0">
                                <?php if ( ! empty( $m_fb ) ) : ?>
                                <li class="d-inline-block"><a href="<?php echo esc_url( $m_fb ); ?>" class="ml-0" aria-label="Facebook"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a></li>
                                <?php endif; ?>
                                <?php if ( ! empty( $m_ig ) ) : ?>
                                <li class="d-inline-block"><a href="<?php echo esc_url( $m_ig ); ?>" aria-label="Instagram"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a></li>
                                <?php endif; ?>
                                <?php if ( ! empty( $m_yt ) ) : ?>
                                <li class="d-inline-block"><a href="<?php echo esc_url( $m_yt ); ?>" aria-label="YouTube"><i class="fa-brands fa-youtube" aria-hidden="true"></i></a></li>
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

<!-- OUR MISSION SECTION -->
<section
    class="float-left w-100 position-relative our-mission-con padding-top padding-bottom main-box background-primary">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="mission-inner-con">
                    <div class="heading-title-con mb-0">
                        <span class="special-text d-block archivo-font font-weight-500 wow fadeInLeft"
                            data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'team_mission_heading', 'Our Mission' ) ); ?></span>
                        <h2 class="wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo wp_kses_post( optix_get_option( 'team_mission_title', 'Inspiring Homes <br>Enriching Lives' ) ); ?></h2>
                        <p class="wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'team_mission_text_1' ) ); ?></p>
                        <p class="mb-0 wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'team_mission_text_2' ) ); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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
