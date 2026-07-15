<?php
declare(strict_types=1);

namespace OptixCore\Engine;

use OptixCore\Registry\Color_Registry;

defined( 'ABSPATH' ) || exit;

class Color_Engine {

	private static ?self $instance = null;

	private function __construct() {}

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'wp_head', array( $this, 'output_color_css' ), 5 );
		add_filter( 'body_class', array( $this, 'add_dark_mode_body_class' ) );
	}

	public function output_color_css(): void {
		$registry = Color_Registry::get_instance();
		$vars     = array(
			'--primary--color'       => $registry->get( 'primary' ) ? $registry->get( 'primary' ) : '#705b53',
			'--secondary--color'     => $registry->get( 'secondary' ) ? $registry->get( 'secondary' ) : '#c19a6b',
			'--accent--color'        => $registry->get( 'accent' ) ? $registry->get( 'accent' ) : '#d4a373',
			'--text--color'          => $registry->get( 'text' ) ? $registry->get( 'text' ) : '#666666',
			'--heading--color'       => $registry->get( 'heading' ) ? $registry->get( 'heading' ) : '#222222',
			'--bg--color'            => $registry->get( 'background' ) ? $registry->get( 'background' ) : '#ffffff',
		);

		echo '<style id="optix-color-vars">' . "\n";
		echo ':root {' . "\n";
		foreach ( $vars as $prop => $value ) {
			echo "\t" . esc_html( $prop ) . ': ' . esc_html( $value ) . ";\n";
		}
		echo '}' . "\n";

		$dark_mode = (bool) $registry->get( 'dark_mode_enable' );
		if ( $dark_mode ) {
			$dark_bg = $registry->get( 'dark_mode_bg' ) ? $registry->get( 'dark_mode_bg' ) : '#1a1a1a';
			echo '@media (prefers-color-scheme: dark) {' . "\n";
			echo "\t" . ':root {' . "\n";
			echo "\t\t" . '--bg--color: ' . esc_html( $dark_bg ) . ";\n";
			echo "\t\t" . '--text--color: #e0e0e0;' . "\n";
			echo "\t\t" . '--heading--color: #ffffff;' . "\n";
			echo "\t" . '}' . "\n";
			echo '}' . "\n";
		}

		echo '</style>' . "\n";
	}

	public function add_dark_mode_body_class( array $classes ): array {
		$registry = Color_Registry::get_instance();
		if ( (bool) $registry->get( 'dark_mode_enable' ) ) {
			$classes[] = 'optix-dark-mode';
		}
		return $classes;
	}

	public function get_contrast_ratio( string $hex1, string $hex2 ): float {
		$lum1    = $this->get_relative_luminance( $hex1 );
		$lum2    = $this->get_relative_luminance( $hex2 );
		$lighter = max( $lum1, $lum2 );
		$darker  = min( $lum1, $lum2 );
		return ( $lighter + 0.05 ) / ( $darker + 0.05 );
	}

	public function is_wcag_aa( string $foreground, string $background, bool $large_text = false ): bool {
		$ratio = $this->get_contrast_ratio( $foreground, $background );
		return $large_text ? $ratio >= 3.0 : $ratio >= 4.5;
	}

	public function is_wcag_aaa( string $foreground, string $background, bool $large_text = false ): bool {
		$ratio = $this->get_contrast_ratio( $foreground, $background );
		return $large_text ? $ratio >= 4.5 : $ratio >= 7.0;
	}

	private function get_relative_luminance( string $hex ): float {
		$hex = ltrim( $hex, '#' );
		$r   = hexdec( substr( $hex, 0, 2 ) ) / 255;
		$g   = hexdec( substr( $hex, 2, 2 ) ) / 255;
		$b   = hexdec( substr( $hex, 4, 2 ) ) / 255;

		$linearize = function ( float $c ): float {
			return $c <= 0.03928 ? $c / 12.92 : pow( ( $c + 0.055 ) / 1.055, 2.4 );
		};

		return 0.2126 * $linearize( $r ) + 0.7152 * $linearize( $g ) + 0.0722 * $linearize( $b );
	}
}
