<?php
declare(strict_types=1);

namespace OptixCore;

use OptixCore\Registry\Demo_Registry;
use OptixCore\Registry\Settings_Registry;

defined( 'ABSPATH' ) || exit;

class Demo_Importer {

	private static ?self $instance = null;
	private array $import_log = [];

	private function __construct() {}
	private function __clone() {}
	public function __wakeup(): void {
		throw new \RuntimeException( 'Cannot unserialize singleton' );
	}

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'admin_init', [ $this, 'process_pending_import' ], 20 );
		add_action( 'wp_ajax_optix_demo_import', [ $this, 'ajax_import_step' ] );
	}

	public function process_pending_import(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'optix_import_trigger' ) ) {
			return;
		}
		$pending = get_option( 'optix_demo_import_pending', false );
		if ( ! $pending ) {
			return;
		}
		delete_option( 'optix_demo_import_pending' );

		if ( ! headers_sent() ) {
			$this->import_package( $pending );
		}
	}

	public function import_package( string $package_id ): array {
		$registry = Demo_Registry::get_instance();
		$package  = $registry->get_package( $package_id );

		if ( null === $package ) {
			return [
				'success' => false,
				'error'   => sprintf(
					__( 'Invalid package: %s', 'optix-core' ),
					$package_id
				),
			];
		}

		$this->import_log = [];

		foreach ( $package['steps'] as $step ) {
			$method = 'import_' . $step['id'];
			if ( method_exists( $this, $method ) ) {
				$result = $this->$method( $package );
				$this->import_log[] = [
					'step'   => $step['label'],
					'success' => $result,
				];
			} else {
				$this->import_log[] = [
					'step'   => $step['label'],
					'success' => false,
					'error'  => sprintf(
						__( 'Import method not found: %s', 'optix-core' ),
						$method
					),
				];
			}
		}

		do_action( 'optix/demo_import/complete', $package_id, $this->import_log );
		update_option( 'optix_demo_imported', $package_id );

		return [
			'success' => true,
			'log'     => $this->import_log,
			'package' => $package_id,
		];
	}

	private function import_settings( array $package ): bool {
		$settings = $package['settings'] ?? [];
		if ( empty( $settings ) ) {
			return true;
		}
		try {
			$registry = Settings_Registry::get_instance();
			$registry->register();
			foreach ( $settings as $key => $value ) {
				$registry->set( $key, $value );
			}
			return true;
		} catch ( \Throwable $e ) {
			$this->import_log[] = [ 'error' => $e->getMessage() ];
			return false;
		}
	}

	private function import_pages( array $package ): bool {
		$pages = $package['pages'] ?? [];
		if ( empty( $pages ) ) {
			return true;
		}
		try {
			foreach ( $pages as $page_title ) {
				$slug     = sanitize_title( $page_title );
				$existing = get_page_by_path( $slug );
				if ( $existing ) {
					continue;
				}
				$result = wp_insert_post( [
					'post_title'   => $page_title,
					'post_name'    => $slug,
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_author'  => 1,
					'post_content' => $this->get_demo_content( $slug ),
				] );
				if ( is_wp_error( $result ) ) {
					$this->import_log[] = [
						'error' => sprintf(
							__( 'Failed to create page "%s": %s', 'optix-core' ),
							$page_title,
							$result->get_error_message()
						),
					];
					return false;
				}
			}
			return true;
		} catch ( \Throwable $e ) {
			$this->import_log[] = [ 'error' => $e->getMessage() ];
			return false;
		}
	}

	private function import_products( array $package ): bool {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return false;
		}
		try {
			$products = $package['products'] ?? [];
			if ( empty( $products ) ) {
				$products = $this->get_default_products();
			}
			foreach ( $products as $product_data ) {
				$existing = get_page_by_path( sanitize_title( $product_data['name'] ), OBJECT, 'product' );
				if ( $existing ) {
					continue;
				}
				$product = new \WC_Product_Simple();
				$product->set_name( $product_data['name'] );
				$product->set_regular_price( (string) ( $product_data['price'] ?? '19.99' ) );
				$product->set_description( $product_data['description'] ?? '' );
				$product->set_short_description( $product_data['short_description'] ?? '' );
				$product->set_catalog_visibility( 'visible' );
				$product->set_status( 'publish' );
				$product->save();
			}
			return true;
		} catch ( \Throwable $e ) {
			$this->import_log[] = [ 'error' => $e->getMessage() ];
			return false;
		}
	}

	private function import_images( array $package ): bool {
		$settings = $package['settings'] ?? [];
		$logo_id = $settings['site_logo'] ?? 0;
		if ( $logo_id ) {
			return true;
		}
		return true;
	}

	private function import_menus( array $package ): bool {
		$menus = $package['menus'] ?? [];
		if ( empty( $menus ) ) {
			return true;
		}
		try {
			foreach ( $menus as $location => $items ) {
				if ( ! has_nav_menu( $location ) ) {
					$menu_name = ucfirst( str_replace( [ '-', '_' ], ' ', $location ) );
					$menu_id   = wp_create_nav_menu( $menu_name );
					set_theme_mod( 'nav_menu_locations', array_merge(
						get_theme_mod( 'nav_menu_locations', [] ),
						[ $location => $menu_id ]
					) );
					foreach ( $items as $item ) {
						wp_update_nav_menu_item( $menu_id, 0, [
							'menu-item-title'  => $item['title'],
							'menu-item-url'    => $item['url'] ?? home_url( '/' ),
							'menu-item-status' => 'publish',
						] );
					}
				}
			}
			return true;
		} catch ( \Throwable $e ) {
			$this->import_log[] = [ 'error' => $e->getMessage() ];
			return false;
		}
	}

	public function ajax_import_step(): void {
		check_ajax_referer( 'optix_import_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'Permission denied.', 'optix-core' ) ] );
		}

		$package_id = isset( $_POST['package'] ) ? sanitize_key( wp_unslash( $_POST['package'] ) ) : '';
		$step       = isset( $_POST['step'] ) ? sanitize_key( wp_unslash( $_POST['step'] ) ) : '';

		if ( empty( $package_id ) || empty( $step ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid request.', 'optix-core' ) ] );
		}

		$registry = Demo_Registry::get_instance();
		$package  = $registry->get_package( $package_id );
		if ( null === $package ) {
			wp_send_json_error( [ 'message' => __( 'Invalid package.', 'optix-core' ) ] );
		}

		$method = 'import_' . $step;
		if ( ! method_exists( $this, $method ) ) {
			wp_send_json_error( [ 'message' => sprintf(
				__( 'Unknown step: %s', 'optix-core' ),
				$step
			) ] );
		}

		$result = $this->$method( $package );
		wp_send_json_success( [ 'completed' => $step, 'success' => $result ] );
	}

	private function get_demo_content( string $slug ): string {
		$contents = [
			'home'    => '<!-- wp:paragraph --><p>Welcome to our site. This is the home page content.</p><!-- /wp:paragraph -->',
			'shop'    => '<!-- wp:paragraph --><p>Browse our collection of products.</p><!-- /wp:paragraph -->',
			'about'   => '<!-- wp:paragraph --><p>Learn more about our story and mission.</p><!-- /wp:paragraph -->',
			'contact' => '<!-- wp:paragraph --><p>Get in touch with us. We would love to hear from you.</p><!-- /wp:paragraph -->',
			'blog'    => '<!-- wp:paragraph --><p>Welcome to our blog. Stay tuned for updates.</p><!-- /wp:paragraph -->',
		];
		return $contents[ $slug ] ?? '<!-- wp:paragraph --><p></p><!-- /wp:paragraph -->';
	}

	private function get_default_products(): array {
		return [
			[
				'name'              => __( 'Classic Product', 'optix-core' ),
				'price'             => 29.99,
				'description'       => __( 'A high-quality classic product.', 'optix-core' ),
				'short_description' => __( 'Our best-selling item.', 'optix-core' ),
			],
			[
				'name'              => __( 'Premium Item', 'optix-core' ),
				'price'             => 49.99,
				'description'       => __( 'Premium quality for discerning customers.', 'optix-core' ),
				'short_description' => __( 'Top-tier quality.', 'optix-core' ),
			],
		];
	}

	public function get_import_log(): array {
		return $this->import_log;
	}
}
