<?php
declare(strict_types=1);

namespace OptixCore\Engine;


defined( 'ABSPATH' ) || exit;

class Product_Engine {

	private static ?self $instance = null;

	private function __construct() {}

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		add_filter( 'body_class', array( $this, 'add_product_body_classes' ) );
		add_filter( 'loop_shop_columns', array( $this, 'get_shop_columns' ) );
		add_filter( 'loop_shop_per_page', array( $this, 'get_products_per_page' ) );
		add_action( 'woocommerce_before_shop_loop', array( $this, 'add_shop_toolbar' ), 5 );
	}

	public function add_product_body_classes( array $classes ): array {
		if ( function_exists( 'is_product' ) && is_product() ) {
			$layout    = get_option( 'optix_product_layout', 'standard' );
			$classes[] = 'optix-product-' . $layout;
		}
		if ( function_exists( 'is_shop' ) && is_shop() ) {
			$classes[] = 'optix-shop-' . ( get_option( 'optix_shop_layout', 'grid' ) );
		}
		return $classes;
	}

	public function get_shop_columns(): int {
		$cols = (int) get_option( 'optix_shop_columns', 4 );
		return max( 1, min( 6, $cols ) );
	}

	public function get_products_per_page(): int {
		$per_page = (int) get_option( 'optix_shop_per_page', 12 );
		return max( 1, $per_page );
	}

	public function add_shop_toolbar(): void {
		$show = (bool) get_option( 'optix_show_shop_toolbar', true );
		if ( ! $show ) {
			return;
		}
		?>
		<div class="optix-shop-toolbar">
			<div class="optix-shop-toolbar-inner">
				<div class="optix-shop-result-count">
					<?php woocommerce_result_count(); ?>
				</div>
				<div class="optix-shop-view-toggle">
					<button class="optix-view-grid active" data-view="grid" aria-label="<?php esc_attr_e( 'Grid view', 'optix-core' ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
					</button>
					<button class="optix-view-list" data-view="list" aria-label="<?php esc_attr_e( 'List view', 'optix-core' ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
					</button>
				</div>
				<?php woocommerce_catalog_ordering(); ?>
			</div>
		</div>
		<?php
	}

	public function get_product_quick_view_data( int $product_id ): array {
		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			return array();
		}

		return array(
			'id'           => $product->get_id(),
			'title'        => $product->get_name(),
			'price'        => $product->get_price_html(),
			'image'        => wp_get_attachment_image_url( $product->get_image_id(), 'woocommerce_thumbnail' ),
			'url'          => $product->get_permalink(),
			'rating'       => $product->get_average_rating(),
			'rating_count' => $product->get_rating_count(),
			'short_desc'   => $product->get_short_description(),
			'on_sale'      => $product->is_on_sale(),
			'in_stock'     => $product->is_in_stock(),
		);
	}
}
