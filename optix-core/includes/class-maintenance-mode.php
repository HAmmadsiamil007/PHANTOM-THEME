<?php
declare(strict_types=1);

namespace OptixCore;

defined( 'ABSPATH' ) || exit;

class Maintenance_Mode {

	private static ?Maintenance_Mode $instance = null;

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'template_redirect', [ $this, 'handle_maintenance' ], 1 );
	}

	public function handle_maintenance(): void {
		if ( ! optix_get_option( 'maintenance_mode_enable' ) ) {
			return;
		}
		if ( is_user_logged_in() ) {
			return;
		}

		$template = '';

		$profile_path = Profile_Router::get_instance()->get_profile_path();
		$candidate    = $profile_path . '/maintenance.php';
		if ( file_exists( $candidate ) ) {
			$template = $candidate;
		}

		if ( ! $template ) {
			$candidate = get_template_directory() . '/profiles/default/maintenance.php';
			if ( file_exists( $candidate ) ) {
				$template = $candidate;
			}
		}

		if ( ! $template ) {
			$candidate = OPTIX_CORE_PATH . 'templates/maintenance.php';
			if ( file_exists( $candidate ) ) {
				$template = $candidate;
			}
		}

		if ( $template ) {
			require_once $template;
			exit;
		}

		wp_die(
			esc_html__( 'Site is under maintenance. Please check back soon.', 'optix-core' ),
			esc_html__( 'Maintenance Mode', 'optix-core' ),
			[ 'response' => 503 ]
		);
	}
}
