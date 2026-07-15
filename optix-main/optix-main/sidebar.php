<?php
/**
 * The sidebar containing the main widget area
 *
 * @package optix
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */

namespace Optix;

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
  return;
}
?>

<aside id="secondary" class="widget-area" role="complementary" aria-label="<?php esc_attr_e( 'Sidebar', 'optix' ); ?>">
  <?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
