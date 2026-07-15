<?php
/**
 * Template for displaying the footer
 *
 * Site footer.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @package optix
 */

namespace Optix;

?>

    <?php
    // Show newsletter on most pages, hide on 404 and coming-soon
    $kc_newsletter_slug = is_page() ? get_queried_object()->post_name : '';
    if ( ! is_404() && $kc_newsletter_slug !== 'coming-soon' ) :
      get_template_part( 'template-parts/kids-collection/newsletter' );
    endif;
    ?>

    <?php get_template_part( 'template-parts/kids-collection/site-footer' ); ?>

  </div><!-- /.home_banner_outer (from header.php) -->
</div><!-- #page -->

<?php if ( optix_get_option( 'general_preloader_enable' ) ) : ?>
<?php get_template_part( 'template-parts/kids-collection/preloader' ); ?>
<?php endif; ?>

<?php // Cookie consent bar moved to plugin Head_Manager (class-head-manager.php) ?>

<?php wp_footer(); ?>

</body>
</html>
