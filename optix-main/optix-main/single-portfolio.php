<?php
/**
 * Single Portfolio template.
 *
 * @package optix
 */

get_header();

$project_gallery = get_field( 'portfolio_gallery' );
$client          = get_field( 'portfolio_client' );
$project_date    = get_field( 'portfolio_date' );
$external_url    = get_field( 'portfolio_url' );
$categories      = wp_get_post_terms( get_the_ID(), 'portfolio_category' );
?>

<main id="main" class="single-portfolio-con">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'portfolio-detail' ); ?>>
          <header class="portfolio-header">
            <h1 class="portfolio-title"><?php the_title(); ?></h1>
            <?php if ( $categories && ! is_wp_error( $categories ) ) : ?>
              <div class="portfolio-categories">
                <?php foreach ( $categories as $i => $cat ) : ?>
                  <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="portfolio-cat"><?php echo esc_html( $cat->name ); ?></a><?php echo $i < count( $categories ) - 1 ? ', ' : ''; ?>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </header>

          <?php if ( has_post_thumbnail() ) : ?>
            <div class="portfolio-featured-image">
              <?php the_post_thumbnail( 'large', [ 'class' => 'img-fluid' ] ); ?>
            </div>
          <?php endif; ?>

          <?php if ( ! empty( $project_gallery ) && is_array( $project_gallery ) ) : ?>
            <div class="portfolio-gallery row">
              <?php foreach ( $project_gallery as $image_id ) : ?>
                <div class="col-md-4 col-6 mb-3">
                  <?php echo wp_get_attachment_image( $image_id, 'medium', false, [ 'class' => 'img-fluid' ] ); ?>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <div class="portfolio-content">
            <?php the_content(); ?>
          </div>

          <div class="portfolio-meta">
            <?php if ( $client ) : ?>
              <div class="portfolio-meta-item">
                <strong><?php esc_html_e( 'Client:', 'optix' ); ?></strong>
                <span><?php echo esc_html( $client ); ?></span>
              </div>
            <?php endif; ?>
            <?php if ( $project_date ) : ?>
              <div class="portfolio-meta-item">
                <strong><?php esc_html_e( 'Date:', 'optix' ); ?></strong>
                <span><?php echo esc_html( $project_date ); ?></span>
              </div>
            <?php endif; ?>
            <?php if ( $external_url ) : ?>
              <div class="portfolio-meta-item">
                <a href="<?php echo esc_url( $external_url ); ?>" class="btn btn-primary" target="_blank" rel="noopener"><?php esc_html_e( 'Visit Project', 'optix' ); ?></a>
              </div>
            <?php endif; ?>
          </div>
        </article>
      </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>
