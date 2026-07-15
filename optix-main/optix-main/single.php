<?php
/**
 * The template for displaying all single posts
 *
 * @package optix
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 */

namespace Optix;

the_post();

get_header(); ?>

<main class="site-main has-global-padding is-layout-constrained">

  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
      <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

      <div class="entry-meta">
        <p>
          <time datetime="<?php the_time( 'c' ); ?>">
            <?php echo esc_html( get_the_date( get_option( 'date_format' ) ) ); ?>
          </time>
          <?php esc_html_e( 'by', 'optix' ); ?>
          <?php the_author_posts_link(); ?>
        </p>
      </div>
    </header>

    <div class="entry-content">
      <?php the_content(); ?>
    </div>

    <footer class="entry-footer">
      <?php
        wp_link_pages( array(
          'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'optix' ),
          'after'  => '</div>',
        ) );

        the_tags( '<div class="post-tags">' . esc_html__( 'Tags: ', 'optix' ), ', ', '</div>' );
      ?>
    </footer>
  </article>

  <?php
    if ( comments_open() || get_comments_number() ) :
      comments_template();
    endif;

    the_post_navigation( array(
      'prev_text' => esc_html__( '&laquo; Previous Post', 'optix' ),
      'next_text' => esc_html__( 'Next Post &raquo;', 'optix' ),
    ) );
  ?>

</main>

<?php get_footer();
