<?php
declare(strict_types=1);

namespace OptixCore;

defined( 'ABSPATH' ) || exit;

class Taxonomy_Manager {

	private static ?Taxonomy_Manager $instance = null;

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'init', [ $this, 'register_taxonomies' ], 5 );
	}

	public function register_taxonomies(): void {
		require_once OPTIX_CORE_PATH . 'includes/taxonomy.php';

		$taxonomies = [
			'Portfolio_Category' => OPTIX_CORE_PATH . 'includes/taxonomies/portfolio-category.php',
		];

		foreach ( $taxonomies as $name => $file_path ) {
			if ( ! file_exists( $file_path ) ) {
				continue;
			}

			require_once $file_path;

			$classname = __NAMESPACE__ . '\\' . $name;
			if ( ! class_exists( $classname ) ) {
				continue;
			}

			$slug = 'portfolio_category';
			$instance = new $classname( $slug );
			$instance->register( [ 'portfolio' ] );
		}
	}
}
