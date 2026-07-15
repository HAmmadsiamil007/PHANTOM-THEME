<?php
namespace Optix;
get_header(); ?>
<main class="site-main has-global-padding is-layout-constrained">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <h1><?php the_title(); ?></h1>
      <div class="attachment-content">
        <?php echo wp_get_attachment_image( get_the_ID(), 'large' ); ?>
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; endif; ?>
</main>
<?php get_footer();
