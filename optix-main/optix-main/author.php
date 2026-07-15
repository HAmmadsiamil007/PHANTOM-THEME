<?php
namespace Optix;
get_header(); ?>
<main class="site-main has-global-padding is-layout-constrained">
  <?php if ( have_posts() ) : ?>
    <header class="archive-header">
      <h1 class="archive-title"><?php printf( esc_html__( 'Author: %s', 'optix' ), '<span>' . esc_html( get_the_author() ) . '</span>' ); ?></h1>
    </header>
    <?php while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <h2><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?></a></h2>
        <p><time datetime="<?php the_time( 'c' ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
        <div class="content"><?php the_excerpt(); ?></div>
      </article>
    <?php endwhile; ?>
    <?php the_posts_pagination(); ?>
  <?php else : ?>
    <p><?php esc_html_e( 'No posts found.', 'optix' ); ?></p>
  <?php endif; ?>
</main>
<?php get_footer();
