<?php
/**
 * Include custom features etc.
 *
 * @package optix
 */

namespace Optix;

// Theme setup
require get_theme_file_path( '/inc/includes/theme-setup.php' );

// Localized strings
require get_theme_file_path( '/inc/includes/localization.php' );

// Nav walkers
require_once get_theme_file_path( '/inc/includes/nav-walker.php' );
require_once get_theme_file_path( '/inc/includes/nav-walker-footer.php' );
require_once get_theme_file_path( '/inc/includes/class-kids-collection-nav-walker.php' );
// Post type and taxonomy base classes
// We check this with if, because this stuff will not go to WP theme directory
if ( file_exists( get_theme_file_path( '/inc/includes/taxonomy.php' ) ) ) {
  require get_theme_file_path( '/inc/includes/taxonomy.php' );
}

if ( file_exists( get_theme_file_path( '/inc/includes/post-type.php' ) ) ) {
  require get_theme_file_path( '/inc/includes/post-type.php' );
}
