<?php
/**
 * Project Highlight ACF Block.
 *
 * @package optix
 */

$project_id = get_field( 'highlight_project' );
if ( ! $project_id ) {
  return;
}

$post = get_post( $project_id );
if ( ! $post || 'portfolio' !== $post->post_type ) {
  return;
}

setup_postdata( $post );

$image = get_the_post_thumbnail_url( $post->ID, 'full' );
$cats  = wp_get_post_terms( $post->ID, 'portfolio_category' );
?>
<section class="block block-project-highlight">
  <div class="container">
    <div class="row align-items-center">
      <?php if ( $image ) : ?>
        <div class="col-lg-6 mb-4 mb-lg-0">
          <div class="highlight-image">
            <img src="<?php echo esc_url( $image ); ?>" alt="<?php the_title_attribute(); ?>" class="img-fluid">
          </div>
        </div>
      <?php endif; ?>
      <div class="<?php echo $image ? 'col-lg-6' : 'col-12'; ?>">
        <div class="highlight-content">
          <?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
            <div class="highlight-cats mb-2">
              <?php foreach ( $cats as $cat ) : ?>
                <span class="badge bg-secondary me-1"><?php echo esc_html( $cat->name ); ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <h2 class="highlight-title"><?php the_title(); ?></h2>
          <div class="highlight-excerpt"><?php the_excerpt(); ?></div>
          <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn-primary"><?php esc_html_e( 'View Project', 'optix' ); ?></a>
        </div>
      </div>
    </div>
  </div>
</section>
<?php wp_reset_postdata(); ?>
