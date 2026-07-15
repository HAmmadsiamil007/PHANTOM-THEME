<?php
/**
 * Loop Add to Cart
 *
 * Override with theme button styling.
 *
 * @package optix
 */

namespace Optix;

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $product;

$args = wp_parse_args(
  $args,
  [
    'quantity'   => 1,
    'class'      => implode( ' ', array_filter( [
      'primary_btn',
      'product_type_' . $product->get_type(),
      $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
      $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
    ] ) ),
    'attributes' => [
      'data-product_id'  => $product->get_id(),
      'data-product_sku' => $product->get_sku(),
      'aria-label'       => $product->add_to_cart_description(),
      'rel'              => 'nofollow',
    ],
  ]
);

echo sprintf(
  '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
  esc_url( $product->add_to_cart_url() ),
  esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
  esc_attr( $args['class'] ),
  wc_implode_html_attributes( $args['attributes'] ),
  esc_html( $product->add_to_cart_text() )
);
