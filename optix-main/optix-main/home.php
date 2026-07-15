<?php
/**
 * The home page template (used for the blog posts page).
 *
 * Redirects to the Kids Collection page template so the
 * custom blog layout (templates/kids-collection/blog.php)
 * is used for the posts page.
 *
 * @package optix
 */

namespace Optix;

$kc_template = get_theme_file_path( '/page-kids-collection.php' );
if ( file_exists( $kc_template ) ) {
  require $kc_template;
} else {
  require get_theme_file_path( '/index.php' );
}
