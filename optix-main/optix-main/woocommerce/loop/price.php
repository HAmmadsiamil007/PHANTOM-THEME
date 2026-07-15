<?php
/**
 * Loop Price
 *
 * Override for theme-styled price display in product loop.
 *
 * @package optix
 */

namespace Optix;

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $product;

$price_html = $product ? $product->get_price_html() : '';

if ( '' === $price_html ) {
  return;
}
?>

<div class="product-price">
  <?php echo wp_kses_post( $price_html ); ?>
</div>
