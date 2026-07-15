<?php
/**
 * The template for displaying search results pages
 *
 * @package optix
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 */

namespace Optix;

get_header(); ?>

<main class="site-main has-global-padding is-layout-constrained">

  <?php if ( have_posts() ) : ?>

    <header class="archive-header page-header">
      <h1 class="archive-title page-title">
        <?php
          printf(
            esc_html__( 'Search Results for: %s', 'optix' ),
            '<span>' . esc_html( get_search_query() ) . '</span>'
          );
        ?>
      </h1>
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

    <header class="page-header">
      <h1 class="page-title">
        <?php
          printf(
            esc_html__( 'Search Results for: %s', 'optix' ),
            '<span>' . esc_html( get_search_query() ) . '</span>'
          );
        ?>
      </h1>
    </header>

    <p><?php esc_html_e( 'Sorry, no results were found. Please try again with different keywords.', 'optix' ); ?></p>

    <?php get_search_form(); ?>

  <?php endif; ?>

</main>

<?php get_footer();
