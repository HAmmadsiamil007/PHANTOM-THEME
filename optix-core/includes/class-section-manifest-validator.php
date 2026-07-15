<?php
declare(strict_types=1);

namespace OptixCore;

defined('ABSPATH') || exit;

class Section_Manifest_Validator {

	private static ?Section_Manifest_Validator $instance = null;

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {}
	private function __clone() {}
	public function __wakeup(): void {
		throw new \Exception( 'Cannot unserialize singleton' );
	}

	public function init(): void {
		add_action( 'optix_core/init', [ $this, 'validate' ], 99 );
	}

	public function validate(): array {
		$section_registry  = Registry\Section_Registry::get_instance();
		$settings_registry = Registry\Settings_Registry::get_instance();
		$missing           = [];

		foreach ( $section_registry->get_entries() as $section_key => $entry ) {
			$fields = $entry['default']['fields'] ?? [];
			foreach ( $fields as $field ) {
				if ( ! $settings_registry->has( $field ) ) {
					$missing[] = $field;
				}
			}
		}

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG && ! empty( $missing ) ) {
			wp_trigger_error(
				__METHOD__,
				'Section manifest validation found fields missing from Settings_Registry: ' . implode( ', ', $missing ),
				E_USER_WARNING
			);
		}

		return $missing;
	}
}
