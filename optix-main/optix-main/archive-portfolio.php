<?php
/**
 * Archive Portfolio template.
 *
 * @package optix
 */

get_header();
?>

<main id="main" class="portfolio-archive-con">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <header class="archive-header text-center mb-5">
          <h1><?php post_type_archive_title(); ?></h1>
          <?php
          $archive_description = get_the_archive_description();
          if ( $archive_description ) {
            echo wp_kses_post( $archive_description );
          }
          ?>
        </header>
      </div>
    </div>

    <?php if ( have_posts() ) : ?>
      <div class="row portfolio-grid">
        <?php while ( have_posts() ) : the_post(); ?>
          <div class="col-lg-4 col-md-6 mb-4">
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'portfolio-card' ); ?>>
              <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php echo esc_url( get_permalink() ); ?>" class="portfolio-card-image">
                  <?php the_post_thumbnail( 'medium_large', [ 'class' => 'img-fluid' ] ); ?>
                </a>
              <?php endif; ?>
              <div class="portfolio-card-body">
                <?php
                $categories = wp_get_post_terms( get_the_ID(), 'portfolio_category' );
                if ( $categories && ! is_wp_error( $categories ) ) :
                ?>
                  <div class="portfolio-card-cats">
                    <?php foreach ( $categories as $i => $cat ) : ?>
                      <span class="portfolio-card-cat"><?php echo esc_html( $cat->name ); ?></span><?php echo $i < count( $categories ) - 1 ? ', ' : ''; ?>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
                <h3 class="portfolio-card-title">
                  <a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
                </h3>
                <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn-sm btn-outline-primary"><?php esc_html_e( 'View Project', 'optix' ); ?></a>
              </div>
            </article>
          </div>
        <?php endwhile; ?>
      </div>

      <div class="row">
        <div class="col-12">
          <?php the_posts_pagination(); ?>
        </div>
      </div>
    <?php else : ?>
      <div class="row">
        <div class="col-12 text-center">
          <p><?php esc_html_e( 'No projects found.', 'optix' ); ?></p>
        </div>
      </div>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>
