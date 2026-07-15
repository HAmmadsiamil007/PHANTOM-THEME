<?php
/**
 * DEV-ONLY: Seed WooCommerce products.
 *
 * This script is intended for development environments only.
 * It will abort unless OPTIX_DEV_MODE is defined and true,
 * or is executed via WP-CLI.
 *
 * @package optix
 */

if ( ! ( defined( 'OPTIX_DEV_MODE' ) && OPTIX_DEV_MODE ) && ! ( defined( 'WP_CLI' ) && WP_CLI ) ) {
  die( 'This script is for development use only. Define OPTIX_DEV_MODE as true to enable.' );
}

require_once dirname( __DIR__ ) . '/wp-load.php';

if ( ! class_exists( 'WooCommerce' ) ) {
  die( 'WooCommerce not active' );
}

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
  if ( ! current_user_can( 'manage_options' ) ) {
    die( 'Insufficient permissions.' );
  }
}

$names = [ 'Kids T-Shirt', 'Denim Jeans', 'Sneakers', 'Backpack' ];

foreach ( $names as $i => $name ) {
  $product = new WC_Product_Simple();
  $product->set_name( $name );
  $product->set_price( 29.99 + $i * 5 );
  $product->set_regular_price( 29.99 + $i * 5 );
  $product->set_description( "Description for $name." );
  $product->set_short_description( "Short desc for $name." );
  $product->set_manage_stock( true );
  $product->set_stock_quantity( 10 );
  $product->set_stock_status( 'instock' );
  $product->set_catalog_visibility( 'visible' );
  $product->save();
  echo 'Created: ' . $product->get_id() . ' - ' . $name . PHP_EOL;
}
