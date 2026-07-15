<?php
declare(strict_types=1);

namespace OptixCore;

defined( 'ABSPATH' ) || exit;

class Cpt_Manager {

	private static ?Cpt_Manager $instance = null;

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'init', [ $this, 'register_post_types' ], 5 );
	}

	public function register_post_types(): void {
		require_once OPTIX_CORE_PATH . 'includes/post-type.php';

		$post_types = [
			'Portfolio' => OPTIX_CORE_PATH . 'includes/post-types/portfolio.php',
		];

		foreach ( $post_types as $name => $file_path ) {
			if ( ! file_exists( $file_path ) ) {
				continue;
			}

			require_once $file_path;

			$classname = __NAMESPACE__ . '\\' . $name;
			if ( ! class_exists( $classname ) ) {
				continue;
			}

			$slug = strtolower( $name );
			$instance = new $classname( $slug );
			$instance->register();
		}
	}
}
