<?php
/**
 * Portfolio Grid ACF Block.
 *
 * @package optix
 */

$heading = get_field( 'block_heading' ) ?: 'Our Projects';
$count   = get_field( 'block_projects_count' ) ?: 6;
$columns = get_field( 'block_columns' ) ?: 3;

$query = new WP_Query( [
  'post_type'      => 'portfolio',
  'posts_per_page' => $count,
] );

if ( ! $query->have_posts() ) {
  return;
}

$col_class = 'col-lg-' . ( 12 / max( 1, $columns ) ) . ' col-md-6 mb-4';
?>
<section class="block block-portfolio-grid">
  <div class="container">
    <?php if ( $heading ) : ?>
      <div class="row mb-4">
        <div class="col-12 text-center">
          <h2 class="block-heading"><?php echo esc_html( $heading ); ?></h2>
        </div>
      </div>
    <?php endif; ?>
    <div class="row">
      <?php while ( $query->have_posts() ) : $query->the_post(); ?>
        <div class="<?php echo esc_attr( $col_class ); ?>">
          <article class="portfolio-card">
            <?php if ( has_post_thumbnail() ) : ?>
              <a href="<?php echo esc_url( get_permalink() ); ?>">
                <?php the_post_thumbnail( 'medium_large', [ 'class' => 'img-fluid' ] ); ?>
              </a>
            <?php endif; ?>
            <div class="portfolio-card-body p-3">
              <h3><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a></h3>
              <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn-sm btn-primary"><?php esc_html_e( 'View Project', 'optix' ); ?></a>
            </div>
          </article>
        </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
