<?php
declare(strict_types=1);

namespace OptixCore\Engine;

defined( 'ABSPATH' ) || exit;

class Accessibility_Engine {

	private static ?self $instance = null;

	private function __construct() {}

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'wp_body_open', array( $this, 'output_skip_link' ) );
		add_filter( 'body_class', array( $this, 'add_accessibility_body_classes' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_accessibility_js' ) );
		add_filter( 'nav_menu_link_attributes', array( $this, 'add_menu_aria_attributes' ), 10, 3 );
	}

	public function output_skip_link(): void {
		echo '<a class="optix-skip-link screen-reader-text" href="#main">';
		echo esc_html__( 'Skip to content', 'optix-core' );
		echo '</a>' . "\n";
	}

	public function add_accessibility_body_classes( array $classes ): array {
		$classes[] = 'optix-a11y';

		$font_size = (int) get_option( 'optix_a11y_font_size', 100 );
		if ( 100 !== $font_size ) {
			$classes[] = 'optix-font-size-' . $font_size;
		}

		$high_contrast = (bool) get_option( 'optix_a11y_high_contrast', false );
		if ( $high_contrast ) {
			$classes[] = 'optix-high-contrast';
		}

		return $classes;
	}

	public function enqueue_accessibility_js(): void {
		wp_enqueue_script( 'optix-frontend' );
		wp_add_inline_script(
			'optix-frontend',
			'
document.addEventListener("DOMContentLoaded", function() {
	var skipLink = document.querySelector(".optix-skip-link");
	if (skipLink) {
		skipLink.addEventListener("click", function(e) {
			e.preventDefault();
			var target = document.querySelector(this.getAttribute("href"));
			if (target) {
				target.setAttribute("tabindex", "-1");
				target.focus();
			}
		});
	}

	var fontControls = document.querySelectorAll(".optix-font-size-control");
	fontControls.forEach(function(btn) {
		btn.addEventListener("click", function() {
			var size = parseInt(this.dataset.size) || 100;
			document.documentElement.style.setProperty("--a11y--font-scale", size / 100);
			document.body.className = document.body.className.replace(/optix-font-size-\d+/g, "").trim();
			document.body.classList.add("optix-font-size-" + size);
		});
	});
});
'
		);
	}

	public function add_menu_aria_attributes( array $atts, \WP_Post $item, \stdClass $args ): array {
		if ( in_array( 'menu-item-has-children', $item->classes ?? array(), true ) ) {
			$atts['aria-haspopup'] = 'true';
			$atts['aria-expanded'] = 'false';
		}
		return $atts;
	}
}
