<?php
/**
 * The template for displaying archive pages
 *
 * @package optix
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

namespace Optix;

get_header(); ?>

<main class="site-main has-global-padding is-layout-constrained">

  <?php if ( have_posts() ) : ?>

    <header class="archive-header">
      <?php
        the_archive_title( '<h1 class="archive-title">', '</h1>' );
        the_archive_description( '<div class="archive-description">', '</div>' );
      ?>
    </header>

    <?php while ( have_posts() ) :
      the_post();
      ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="post-inner">
          <h2>
            <a href="<?php echo esc_url( get_the_permalink() ); ?>">
              <?php the_title(); ?>
            </a>
          </h2>

          <p>
            <time datetime="<?php the_time( 'c' ); ?>">
              <?php echo esc_html( get_the_date( get_option( 'date_format' ) ) ); ?>
            </time>
          </p>

          <div class="content">
            <?php the_excerpt(); ?>
          </div>
        </div>
      </article>
    <?php endwhile; ?>

    <?php the_posts_pagination(); ?>

  <?php else : ?>
    <p><?php esc_html_e( 'No posts found.', 'optix' ); ?></p>
  <?php endif; ?>

</main>

<?php get_footer();
