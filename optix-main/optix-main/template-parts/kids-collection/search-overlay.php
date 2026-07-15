<?php
/**
 * Kids Collection - Search overlay
 *
 * @package optix
 */
?>
<div id="search" class="">
  <button class="close" aria-label="<?php echo esc_attr_x( 'Close search', 'search', 'optix' ); ?>">&times;</button>
  <form role="search" id="searchform" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <input value="" name="s" type="search" placeholder="<?php echo esc_attr_x( 'Type to Search', 'search', 'optix' ); ?>" aria-label="<?php echo esc_attr_x( 'Search', 'search', 'optix' ); ?>">
  </form>
</div>
