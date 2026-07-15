<?php
/**
 * The template for displaying singular posts (fallback for single and page)
 *
 * @package optix
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#singular
 */

namespace Optix;

the_post();

get_header(); ?>

<main class="site-main has-global-padding is-layout-constrained">

  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
      <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
    </header>

    <div class="entry-content">
      <?php the_content(); ?>
    </div>
  </article>

</main>

<?php get_footer();
