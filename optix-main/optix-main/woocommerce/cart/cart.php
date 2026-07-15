<?php
/**
 * WooCommerce Cart
 *
 * @package optix
 */

namespace Optix;

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

get_header(); ?>

<main id="content" class="woocommerce-kids-wrapper">

  <!-- Sub Banner -->
  <section class="sub-banner-con position-relative float-left w-100 gradient-overlay d-flex align-items-center justify-content-center">
    <div class="container">
      <div class="col-xl-12 col-lg-12 mr-auto ml-auto">
        <div class="sub-banner-inner-con text-center">
          <h1><?php echo esc_html( optix_get_option( 'cart_title', 'Cart' ) ); ?></h1>
          <div class="breadcrumb-con d-inline-block">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'optix' ); ?></a></li>
              <li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( optix_get_option( 'cart_title', 'Cart' ) ); ?></li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php
  /**
   * Cart notices.
   */
  wc_print_notices();
  ?>

  <section class="float-left w-100 cart-con position-relative padding-top padding-bottom main-box">
    <div class="main-container">
      <div class="row">

        <?php
        if ( WC()->cart && ! WC()->cart->is_empty() ) :
        ?>

        <div class="col-lg-8 col-12">
          <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents table table-bordered">
              <thead>
                <tr>
                  <th class="product-remove">&nbsp;</th>
                  <th class="product-thumbnail">&nbsp;</th>
                  <th class="product-name"><?php esc_html_e( 'Product', 'optix' ); ?></th>
                  <th class="product-price"><?php esc_html_e( 'Price', 'optix' ); ?></th>
                  <th class="product-quantity"><?php esc_html_e( 'Quantity', 'optix' ); ?></th>
                  <th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'optix' ); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                <?php
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                  $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                  $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                  $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

                  if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                    $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                    ?>
                    <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                      <td class="product-remove">
                        <?php
                        echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                          'woocommerce_cart_item_remove_link',
                          sprintf(
                            '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                            esc_attr__( 'Remove this item', 'optix' ),
                            esc_attr( $product_id ),
                            esc_attr( $_product->get_sku() )
                          ),
                          $cart_item_key
                        );
                        ?>
                      </td>

                      <td class="product-thumbnail">
                        <?php
                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                        if ( ! $product_permalink ) {
                          echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        } else {
                          printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        }
                        ?>
                      </td>

                      <td class="product-name" data-title="<?php esc_attr_e( 'Product', 'optix' ); ?>">
                        <?php
                        if ( ! $product_permalink ) {
                          echo wp_kses_post( $product_name );
                        } else {
                          echo wp_kses_post( sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $product_name ) );
                        }
                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_meta', '', $cart_item ) );
                        ?>
                      </td>

                      <td class="product-price" data-title="<?php esc_attr_e( 'Price', 'optix' ); ?>">
                        <?php
                        echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        ?>
                      </td>

                      <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'optix' ); ?>">
                        <?php
                        if ( $_product->is_sold_individually() ) {
                          $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                        } else {
                          $product_quantity = woocommerce_quantity_input(
                            [
                              'input_name'   => "cart[{$cart_item_key}][qty]",
                              'input_value'  => $cart_item['quantity'],
                              'max_value'    => $_product->get_max_purchase_quantity(),
                              'min_value'    => '0',
                              'product_name' => $product_name,
                            ],
                            $_product,
                            false
                          );
                        }
                        echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        ?>
                      </td>

                      <td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'optix' ); ?>">
                        <?php
                        echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        ?>
                      </td>
                    </tr>
                    <?php
                  }
                }
                ?>

                <?php do_action( 'woocommerce_cart_contents' ); ?>

                <tr>
                  <td colspan="6" class="actions">
                    <?php if ( wc_coupons_enabled() ) : ?>
                      <div class="coupon d-flex flex-wrap gap-2">
                        <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'optix' ); ?></label>
                        <input type="text" name="coupon_code" class="form-control" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'optix' ); ?>" />
                        <button type="submit" class="primary_btn" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'optix' ); ?>"><?php esc_html_e( 'Apply coupon', 'optix' ); ?></button>
                        <?php do_action( 'woocommerce_cart_coupon' ); ?>
                      </div>
                    <?php endif; ?>

                    <button type="submit" class="primary_btn" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'optix' ); ?>"><?php esc_html_e( 'Update cart', 'optix' ); ?></button>

                    <?php do_action( 'woocommerce_cart_actions' ); ?>

                    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                  </td>
                </tr>

                <?php do_action( 'woocommerce_after_cart_contents' ); ?>
              </tbody>
            </table>

            <?php do_action( 'woocommerce_after_cart_table' ); ?>
          </form>

          <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
        </div>

        <div class="col-lg-4 col-12">
          <div class="cart-collaterals">
            <?php
            /**
             * Cart collaterals.
             */
            do_action( 'woocommerce_cart_collaterals' );
            ?>
          </div>
        </div>

        <?php else : ?>

        <div class="col-12 text-center py-5">
          <p class="cart-empty"><?php esc_html_e( 'Your cart is currently empty.', 'optix' ); ?></p>
          <?php do_action( 'woocommerce_cart_is_empty' ); ?>
          <p class="return-to-shop">
            <a class="primary_btn wc-backward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
              <?php esc_html_e( 'Return to shop', 'optix' ); ?>
            </a>
          </p>
        </div>

        <?php endif; ?>

      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>
