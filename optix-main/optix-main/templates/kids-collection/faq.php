<?php
/**
 * Kids Collection - FAQ
 *
 * @package optix
 */

$kc_img = kc_img_base();
$faq_items = optix_get_option( 'faq_items' );
$insta_images = optix_get_option( 'home_instagram_images' );
$benefits = optix_get_option( 'home_benefits' );
$faq_enable = optix_get_option( 'faq_enable' );
if ( empty( $faq_enable ) ) {
	$faq_enable = 1;
}
$insta_enable = optix_get_option( 'faq_instagram_enable' );
if ( empty( $insta_enable ) ) {
	$insta_enable = 1;
}
$benefits_enable = optix_get_option( 'faq_benefits_enable' );
if ( empty( $benefits_enable ) ) {
	$benefits_enable = 1;
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

<?php if ( $faq_enable ) : ?>
<!-- FAQ SECTION -->
<section class="float-left w-100 position-relative faq-con padding-top padding-bottom position-relative main-box">
    <div class="container">
        <div class="heading-title-con text-center">
            <span class="special-text d-inline-block wow fadeInLeft" data-wow-duration="2s"
                data-wow-delay="0.05s"><?php echo esc_html( optix_get_option( 'faq_heading', "Faq's" ) ); ?></span>
            <h2 class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.3s">
                <?php echo esc_html( optix_get_option( 'faq_title', 'Frequently Asked Questions' ) ); ?>
            </h2>
        </div>
        <div class="faq_content">
            <div class="accordian-section-inner position-relative">
                <div class="accordian-inner">
                    <div id="faq_accordion">
                        <div class="row">
                            <?php if ( ! empty( $faq_items ) && is_array( $faq_items ) ) :
                                $mid = ceil( count( $faq_items ) / 2 );
                                $cols = array_chunk( $faq_items, $mid );
                                foreach ( $cols as $col_index => $col_items ) :
                            ?>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 wow fadeIn<?php echo $col_index === 0 ? 'Left' : 'Right'; ?>"
                                data-wow-duration="2s" data-wow-delay="0.05s">
                                <?php foreach ( $col_items as $item_index => $item ) :
                                    $global_index = ( $col_index * $mid ) + $item_index;
                                    $question = is_array( $item ) ? ( $item['question'] ?? '' ) : '';
                                    $answer = is_array( $item ) ? ( $item['answer'] ?? '' ) : '';
                                    $collapse_id = 'collapse' . ( $global_index + 1 );
                                    $heading_id = 'heading' . ( $global_index + 1 );
                                ?>
                                <div class="accordion-card<?php echo ( $col_index === 1 && $item_index === count( $col_items ) - 1 ) ? ' mb-0' : ''; ?>">
                                    <div class="card-header" id="<?php echo esc_attr( $heading_id ); ?>">
                                        <a href="#" class="btn btn-link collapsed" data-bs-toggle="collapse"
                                            data-bs-target="#<?php echo esc_attr( $collapse_id ); ?>" aria-expanded="false"
                                            aria-controls="<?php echo esc_attr( $collapse_id ); ?>">
                                            <h6 class="mb-0"><?php echo esc_html( $question ); ?></h6>
                                        </a>
                                    </div>
                                    <div id="<?php echo esc_attr( $collapse_id ); ?>" class="collapse" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>"
                                        data-bs-parent="#faq_accordion">
                                        <div class="card-body">
                                            <p class="text-left mb-0"><?php echo esc_html( $answer ); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endforeach; else : ?>
                            <div class="col-12 text-center">
                                <p><?php esc_html_e( 'No FAQ items configured. Add them in the theme settings.', 'optix' ); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ( $insta_enable ) : ?>
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
<?php endif; ?>

<?php if ( $benefits_enable ) : ?>
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
<?php endif; ?>
