<?php
/**
 * The template for displaying front page
 *
 * @package optix
 */

namespace Optix;

get_header(); ?>

<main class="site-main">
  <?php get_template_part( 'templates/kids-collection/front-page' ); ?>
</main>

<?php get_footer();
