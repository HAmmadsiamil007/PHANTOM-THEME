<?php
declare(strict_types=1);

namespace OptixCore;

use OptixCore\Registry\Settings_Registry;

defined( 'ABSPATH' ) || exit;

class Dynamic_CSS_Generator {

	private static ?Dynamic_CSS_Generator $instance = null;
	private static string $generated_css = '';

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'wp_head', [ $this, 'output_styles' ], 99 );
	}

	public function output_styles(): void {
		echo '<style id="optix-dynamic-css">' . "\n";
		echo wp_strip_all_tags( $this->generate() ) . "\n";
		echo '</style>' . "\n";
	}

	public function generate(): string {
		if ( ! empty( self::$generated_css ) ) {
			return self::$generated_css;
		}

		$css = '';
		$css .= $this->get_registry_styles();
		$css .= $this->get_mega_menu_styles();
		$css .= $this->get_tilt_3d_styles();

		$css = apply_filters( 'optix/dynamic_css', trim( $css ) );
		self::$generated_css = $css;

		return $css;
	}

	private function get_registry_styles(): string {
		if ( ! class_exists( 'OptixCore\\Registry\\Settings_Registry' ) ) {
			return '';
		}

		$registry = Settings_Registry::get_instance();
		$registry->register();

		$declarations = [];
		$defaults = $registry->get_defaults();

		foreach ( $defaults as $key => $default ) {
			$entry = $registry->get_schema( $key );

			if ( empty( $entry['css_property'] ) || empty( $entry['css_selector'] ) ) {
				continue;
			}

			$value = $registry->get( $key );
			if ( null === $value ) {
				$value = $default;
			}
			if ( null === $value ) {
				continue;
			}

			$type = $entry['type'] ?? 'string';
			$property = $entry['css_property'];
			$selector = $entry['css_selector'];

			if ( 'color' === $type ) {
				$css_value = $this->resolve_color( $value, $default );
			} elseif ( in_array( $type, [ 'int', 'float' ], true ) ) {
				$css_value = $this->resolve_numeric( $value, $type, $property );
			} else {
				$css_value = is_scalar( $value ) ? (string) $value : '';
			}

			if ( '' === $css_value ) {
				continue;
			}

			$declarations[ $selector ][ $property ] = $css_value;
		}

		if ( empty( $declarations ) ) {
			return '';
		}

		return $this->build_css_blocks( $declarations );
	}

	private function resolve_color( mixed $value, mixed $default ): string {
		$candidates = [ $value, $default, '#000000' ];
		foreach ( $candidates as $candidate ) {
			if ( is_string( $candidate ) && '' !== $candidate ) {
				$sanitized = sanitize_hex_color( $candidate );
				if ( $sanitized ) {
					return $sanitized;
				}
			}
		}
		return '#000000';
	}

	private function resolve_numeric( mixed $value, string $type, string $property ): string {
		$numeric = 'float' === $type ? floatval( $value ) : absint( $value );
		$unit = $this->needs_px_unit( $property ) ? 'px' : '';
		return (string) $numeric . $unit;
	}

	private function needs_px_unit( string $property ): bool {
		$patterns = [ 'padding', 'margin', 'width', 'height', 'gap', 'radius', 'breakpoint' ];
		foreach ( $patterns as $pattern ) {
			if ( str_contains( $property, $pattern ) ) {
				return true;
			}
		}
		return false;
	}

	private function build_css_blocks( array $declarations ): string {
		$css = '';
		foreach ( $declarations as $selector => $props ) {
			$css .= $selector . " {\n";
			foreach ( $props as $prop => $val ) {
				$css .= "\t" . $prop . ': ' . $val . ";\n";
			}
			$css .= "}\n";
		}
		return $css;
	}

	private function get_mega_menu_styles(): string {
		if ( ! optix_get_option( 'mega_menu_enable' ) ) {
			return '';
		}

		$selector = apply_filters( 'optix/css/mega-menu', '.optix-mega-menu' );

		return sprintf( "%s { position: relative; }\n", $selector )
		 . sprintf( "%s .sub-menu { display: none; position: absolute; }\n", $selector )
		 . sprintf( "%s:hover .sub-menu { display: block; }\n", $selector );
	}

	private function get_tilt_3d_styles(): string {
		if ( ! optix_get_option( 'effect_3d_enable' ) ) {
			return '';
		}

		$selector    = apply_filters( 'optix/css/tilt-3d', '.optix-tilt-3d' );
		$perspective = (int) optix_get_option( 'effect_3d_perspective', 1000 );
		$rotate_x    = (int) optix_get_option( 'effect_3d_rotate_x', 5 );
		$rotate_y    = (int) optix_get_option( 'effect_3d_rotate_y', 5 );

		return sprintf( "%s { perspective: %dpx; transform-style: preserve-3d; }\n", $selector, $perspective )
			 . sprintf( "%s:hover { transform: rotateX(%ddeg) rotateY(%ddeg); transition: transform 0.3s ease; }\n", $selector, $rotate_x, $rotate_y );
	}
}
