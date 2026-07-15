<?php
/**
 * The search form template
 *
 * @package optix
 * @link https://developer.wordpress.org/reference/functions/get_search_form/
 */

namespace Optix;

$unique_id = wp_unique_id( 'search-form-' );
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <label for="<?php echo esc_attr( $unique_id ); ?>">
    <span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'optix' ); ?></span>
  </label>
  <input type="search"
         id="<?php echo esc_attr( $unique_id ); ?>"
         class="search-field"
         placeholder="<?php esc_attr_e( 'Search &hellip;', 'optix' ); ?>"
         value="<?php echo esc_attr( get_search_query() ); ?>"
         name="s" />
  <button type="submit" class="search-submit">
    <span class="screen-reader-text"><?php esc_html_e( 'Search', 'optix' ); ?></span>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
      <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
    </svg>
  </button>
</form>
