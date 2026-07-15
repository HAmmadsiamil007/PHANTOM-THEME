<?php
declare(strict_types=1);

namespace OptixCore\Engine;

use OptixCore\Registry\Layout_Registry;
use OptixCore\Registry\Responsive_Registry;

defined( 'ABSPATH' ) || exit;

class Layout_Engine {

	private static ?self $instance = null;

	private function __construct() {}

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'after_setup_theme', array( $this, 'add_theme_support' ), 20 );
		add_action( 'wp_head', array( $this, 'output_layout_css' ), 5 );
		add_filter( 'body_class', array( $this, 'add_layout_body_class' ) );
	}

	public function add_theme_support(): void {
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
	}

	public function output_layout_css(): void {
		$layout  = Layout_Registry::get_instance();
		$width   = (int) ( $layout->get( 'container_width' ) ? $layout->get( 'container_width' ) : 1200 );
		$gutter  = (int) ( $layout->get( 'gutter' ) ? $layout->get( 'gutter' ) : 30 );
		$content = (int) ( $layout->get( 'content_width' ) ? $layout->get( 'content_width' ) : 67 );
		$sidebar = (int) ( $layout->get( 'sidebar_width' ) ? $layout->get( 'sidebar_width' ) : 33 );

		echo '<style id="optix-layout-vars">' . "\n";
		echo ':root {' . "\n";
		echo "\t" . '--container--width: ' . esc_html( $width ) . "px;\n";
		echo "\t" . '--gutter: ' . esc_html( $gutter ) . "px;\n";
		echo "\t" . '--content--width: ' . esc_html( $content ) . "%;\n";
		echo "\t" . '--sidebar--width: ' . esc_html( $sidebar ) . "%;\n";
		echo '}' . "\n";
		echo '</style>' . "\n";
	}

	public function add_layout_body_class( array $classes ): array {
		$layout    = Layout_Registry::get_instance();
		$sidebar   = $layout->get( 'sidebar_width' ) ? $layout->get( 'sidebar_width' ) : '33';
		$content   = $layout->get( 'content_width' ) ? $layout->get( 'content_width' ) : '67';
		$classes[] = 'optix-sidebar-' . $sidebar;
		$classes[] = 'optix-content-' . $content;
		return $classes;
	}

	public function get_container_class( string $extra = '' ): string {
		$classes = array( 'optix-container' );
		if ( $extra ) {
			$classes[] = $extra;
		}
		return implode( ' ', $classes );
	}

	public function get_content_width(): int {
		$layout = Layout_Registry::get_instance();
		return (int) ( $layout->get( 'content_width' ) ? $layout->get( 'content_width' ) : 67 );
	}

	public function get_sidebar_width(): int {
		$layout = Layout_Registry::get_instance();
		return (int) ( $layout->get( 'sidebar_width' ) ? $layout->get( 'sidebar_width' ) : 33 );
	}

	public function has_sidebar(): bool {
		return $this->get_sidebar_width() > 0;
	}
}
