<?php
declare(strict_types=1);

namespace OptixCore\Engine;

use OptixCore\Registry\Responsive_Registry;

defined( 'ABSPATH' ) || exit;

class Responsive_Engine {

	private static ?self $instance = null;

	private function __construct() {}

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'wp_head', array( $this, 'output_responsive_css' ), 5 );
		add_filter( 'body_class', array( $this, 'add_responsive_body_classes' ) );
	}

	public function output_responsive_css(): void {
		$registry    = Responsive_Registry::get_instance();
		$breakpoints = $registry->get_all();

		if ( empty( $breakpoints ) ) {
			return;
		}

		echo '<style id="optix-responsive-vars">' . "\n";
		echo ':root {' . "\n";
		foreach ( $breakpoints as $key => $value ) {
			$var_name = '--bp--' . str_replace( '_', '-', $key );
			echo "\t" . esc_html( $var_name ) . ': ' . esc_html( $value ) . "px;\n";
		}
		echo '}' . "\n";
		echo '</style>' . "\n";
	}

	public function add_responsive_body_classes( array $classes ): array {
		$detect = $this->detect_device();
		if ( $detect ) {
			$classes[] = 'optix-device-' . $detect;
		}
		return $classes;
	}

	public function detect_device(): string {
		if ( wp_is_mobile() ) {
			if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
				$ua = strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) );
				if ( strpos( $ua, 'tablet' ) !== false || strpos( $ua, 'ipad' ) !== false ) {
					return 'tablet';
				}
			}
			return 'mobile';
		}
		return 'desktop';
	}

	public function get_breakpoint( string $key ): ?int {
		$registry = Responsive_Registry::get_instance();
		$value    = $registry->get( $key );
		return null !== $value ? (int) $value : null;
	}

	public function get_media_query( string $breakpoint, string $operator = 'max-width' ): string {
		$value = $this->get_breakpoint( $breakpoint );
		if ( null === $value ) {
			return '';
		}
		return sprintf( '@media (%s: %dpx)', $operator, $value );
	}
}
