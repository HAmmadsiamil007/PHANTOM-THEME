<?php
/**
 * Kids Collection - Blog
 *
 * @package optix
 */

$kc_img = kc_img_base();

$blog_tabs = optix_get_option( 'blog_tabs' );
if ( empty( $blog_tabs ) || ! is_array( $blog_tabs ) ) {
    $blog_tabs = array( 'All', 'Advices', 'Announcements', 'News', 'Consultation', 'Development' );
}
?>
<!-- SUB BANNER SECTION -->
<section class="sub-banner-con position-relative float-left w-100 gradient-overlay d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="col-xl-12 col-lg-12 mr-auto ml-auto">
            <div class="sub-banner-inner-con text-center">
                <h1 class=""><?php echo esc_html( optix_get_option( 'blog_title' ) ); ?></h1>
                <div class="breadcrumb-con d-inline-block">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'optix' ); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Blog </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if ( optix_get_option( 'blog_enable' ) !== 0 ) : ?>
<!-- BLOG MAIN SECTION START HERE -->
<div class="blog-tabs-section padding-top padding-bottom float-left w-100">
    <div class="container wow fadeInDown" data-wow-duration="2s" data-wow-delay="0.4s">
        <div class="blog-tabs-inner-section">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <?php foreach ( $blog_tabs as $index => $tab_label ) : ?>
                    <?php $tab_id = 'tab-' . $index; ?>
                    <li class="nav-item">
                        <a class="nav-link<?php echo 0 === $index ? ' active' : ''; ?>" id="<?php echo esc_attr( $tab_id ); ?>-tab" data-bs-toggle="tab" href="#<?php echo esc_attr( $tab_id ); ?>" role="tab"
                            aria-controls="<?php echo esc_attr( $tab_id ); ?>"<?php echo 0 === $index ? ' aria-expanded="true"' : ''; ?>><?php echo esc_html( $tab_label ); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="tab-content" id="myTabContent">
                <?php foreach ( $blog_tabs as $index => $tab_label ) : ?>
                    <?php
                    $tab_id = 'tab-' . $index;
                    $category_slug = sanitize_title( $tab_label );

                    $paged_key = 'tab_' . $index . '_page';
                    $paged_value = isset( $_GET[ $paged_key ] ) ? max( 1, intval( $_GET[ $paged_key ] ) ) : 1; // phpcs:ignore WordPress.Security.NonceVerification

                    $args = array(
                        'post_type'      => 'post',
                        'posts_per_page' => 6,
                        'paged'          => $paged_value,
                    );

                    if ( 0 !== $index ) {
                        $args['category_name'] = $category_slug;
                    }

                    $query = new WP_Query( $args );
                    ?>
                    <div class="tab-pane fade<?php echo 0 === $index ? ' show active' : ''; ?>" id="<?php echo esc_attr( $tab_id ); ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr( $tab_id ); ?>-tab">
                        <div class="single-blog-outer-con">
                            <?php if ( $query->have_posts() ) : ?>
                                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                                <div class="single-blog-box">
                                    <figure class="mb-0">
                                        <?php
                                        $featured_img_id = get_post_thumbnail_id();
                                        if ( $featured_img_id ) {
                                            echo wp_get_attachment_image( $featured_img_id, 'medium', false, array( 'loading' => 'lazy', 'class' => 'img-fluid' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
                                        } else {
                                            $fallback_num = ( ( $query->current_post % 10 ) + 1 );
                                            echo '<img src="' . esc_url( optix_img( '/single-blog-tab-img' . $fallback_num . '.jpg' ) ) . '" alt="' . esc_attr( get_the_title() ) . '" loading="lazy" class="img-fluid">';
                                        }
                                        ?>
                                    </figure>
                                    <div class="single-blog-details">
                                        <ul class="list-unstyled">
                                            <li class="position-relative"><i class="fas fa-user" aria-hidden="true"></i> Posted by <?php echo esc_html( get_the_author() ); ?></li>
                                            <li class="position-relative"><i class="fas fa-calendar-alt" aria-hidden="true"></i> <?php echo esc_html( get_the_date() ); ?></li>
                                        </ul>
                                        <h4><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></h4>
                                        <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                                        <div class="generic-btn2">
                                            <a href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'Read More', 'optix' ); ?></a>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                                <?php wp_reset_postdata(); ?>
                            <?php endif; ?>
                        </div>
                        <?php if ( $query->max_num_pages > 1 ) : ?>
                            <?php
                            $current_url = trailingslashit( home_url( $GLOBALS['wp']->request ) );
                            $pages = paginate_links( array(
                                'base'      => $current_url . '%_%',
                                'format'    => '?' . $paged_key . '=%#%',
                                'current'   => $paged_value,
                                'total'     => $query->max_num_pages,
                                'type'      => 'array',
                                'prev_text' => '<i class="fas fa-angle-left" aria-hidden="true"></i>',
                                'next_text' => '<i class="fas fa-angle-right" aria-hidden="true"></i>',
                            ) );
                            ?>
                            <?php if ( is_array( $pages ) ) : ?>
                            <nav aria-label="...">
                                <ul class="pagination">
                                    <?php foreach ( $pages as $page_link ) : ?>
                                    <li class="page-item<?php echo strpos( $page_link, 'current' ) !== false ? ' active' : ''; ?>">
                                        <?php echo str_replace( 'page-numbers', 'page-link', $page_link ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </nav>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
