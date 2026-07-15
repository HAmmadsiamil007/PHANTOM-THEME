<?php
/**
 * Template for header
 *
 * <head> section and everything up until <div id="content">
 *
 * @package optix
 */

namespace Optix;

?>

<!doctype html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="http://gmpg.org/xfn/11">

  <?php // SEO tags, OG, analytics moved to plugin Head_Manager (class-head-manager.php) ?>
  <?php wp_head(); ?>
</head>

  <body <?php body_class( 'no-js' ); ?>>
  <?php
  $kc_gtm_for_noscript = optix_get_option( 'google_tag_manager' );
  ?>
  <?php if ( $kc_gtm_for_noscript ) : ?>
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $kc_gtm_for_noscript ); ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <?php endif; ?>
  <a class="skip-link screen-reader-text" href="#content"><?php echo esc_html( get_default_localization( 'Skip to content' ) ); ?></a>

  <?php wp_body_open(); ?>
  <div id="page" class="site">

    <!-- Back to top button -->
    <a id="button"></a>

    <div class="home_banner_outer position-relative float-left w-100">

      <?php get_template_part( 'template-parts/kids-collection/topbar' ); ?>
      <?php get_template_part( 'template-parts/kids-collection/site-header' ); ?>
      <?php get_template_part( 'template-parts/kids-collection/search-overlay' ); ?>
