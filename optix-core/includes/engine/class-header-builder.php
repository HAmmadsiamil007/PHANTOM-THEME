<?php
declare(strict_types=1);

namespace OptixCore\Engine;

defined( 'ABSPATH' ) || exit;

class Header_Builder {

	private static ?self $instance = null;

	private function __construct() {}

	private const DEFAULT_LAYOUT = array(
		'topbar' => array(
			'items' => array( 'social', 'text' ),
			'text'  => 'Free shipping on orders over $50',
		),
		'main'   => array(
			'items' => array( 'logo', 'nav', 'search', 'cart' ),
		),
		'bottom' => array(
			'items' => array( 'text' ),
			'text'  => '',
		),
	);

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'init', array( $this, 'register_settings' ) );
	}

	public function register_settings(): void {
		register_setting(
			'optix_core',
			'optix_header_layout',
			array(
				'type'              => 'array',
				'description'       => __( 'Header layout configuration', 'optix-core' ),
				'sanitize_callback' => array( $this, 'sanitize_layout' ),
				'default'           => self::DEFAULT_LAYOUT,
				'show_in_rest'      => false,
			)
		);
	}

	public function sanitize_layout( $value ): array {
		if ( ! is_array( $value ) ) {
			return self::DEFAULT_LAYOUT;
		}
		return $value;
	}

	public function get_layout(): array {
		$stored = get_option( 'optix_header_layout', self::DEFAULT_LAYOUT );
		return is_array( $stored ) ? $stored : self::DEFAULT_LAYOUT;
	}

	public function has_topbar(): bool {
		$layout = $this->get_layout();
		return ! empty( $layout['topbar']['items'] );
	}

	public function get_topbar_text(): string {
		$layout = $this->get_layout();
		return $layout['topbar']['text'] ?? '';
	}

	public function render( string $location = 'main' ): void {
		$layout = $this->get_layout();
		if ( ! isset( $layout[ $location ] ) ) {
			return;
		}
		$row = $layout[ $location ];
		if ( empty( $row['items'] ) ) {
			return;
		}

		$class = 'optix-header-row optix-header-' . $location;
		echo '<div class="' . esc_attr( $class ) . '">' . "\n";
		echo '<div class="optix-container">' . "\n";
		echo '<div class="optix-header-row-inner">' . "\n";

		foreach ( $row['items'] as $item ) {
			$this->render_item( $item, $row );
		}

		echo '</div>' . "\n";
		echo '</div>' . "\n";
		echo '</div>' . "\n";
	}

	private function render_item( string $item, array $row ): void {
		switch ( $item ) {
			case 'logo':
				echo '<div class="optix-header-item optix-logo">';
				if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
					the_custom_logo();
				} else {
					echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="optix-site-title">';
					echo esc_html( get_bloginfo( 'name' ) );
					echo '</a>';
				}
				echo '</div>' . "\n";
				break;

			case 'nav':
				echo '<div class="optix-header-item optix-nav">';
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'optix-nav-menu',
							'depth'          => 3,
							'fallback_cb'    => false,
						)
					);
				}
				echo '</div>' . "\n";
				break;

			case 'search':
				echo '<div class="optix-header-item optix-search">';
				echo '<button class="optix-search-toggle" aria-label="' . esc_attr__( 'Toggle search', 'optix-core' ) . '">';
				echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>';
				echo '</button>';
				echo '</div>' . "\n";
				break;

			case 'cart':
				if ( class_exists( 'WooCommerce' ) ) {
					echo '<div class="optix-header-item optix-cart">';
					echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="optix-cart-link" aria-label="' . esc_attr__( 'View cart', 'optix-core' ) . '">';
					echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>';
					$count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
					if ( $count > 0 ) {
						echo '<span class="optix-cart-count">' . esc_html( $count ) . '</span>';
					}
					echo '</a>';
					echo '</div>' . "\n";
				}
				break;

			case 'social':
				echo '<div class="optix-header-item optix-social">';
				$this->render_social_icons();
				echo '</div>' . "\n";
				break;

			case 'text':
				if ( ! empty( $row['text'] ) ) {
					echo '<div class="optix-header-item optix-header-text">';
					echo '<span>' . esc_html( $row['text'] ) . '</span>';
					echo '</div>' . "\n";
				}
				break;
		}
	}

	private function get_svg_tags(): array {
		return array(
			'svg'    => array(
				'width'   => true,
				'height'  => true,
				'viewBox' => true,
				'fill'    => true,
				'stroke'  => true,
				'stroke-width' => true,
				'xmlns'   => true,
			),
			'path'   => array(
				'd'          => true,
				'fill'       => true,
				'stroke'     => true,
				'stroke-width' => true,
			),
			'circle' => array(
				'cx' => true,
				'cy' => true,
				'r'  => true,
			),
			'line'   => array(
				'x1'           => true,
				'y1'           => true,
				'x2'           => true,
				'y2'           => true,
				'stroke'       => true,
				'stroke-width' => true,
			),
			'rect'   => array(
				'x'      => true,
				'y'      => true,
				'width'  => true,
				'height' => true,
				'rx'     => true,
				'ry'     => true,
			),
			'polygon' => array(
				'points' => true,
				'fill'   => true,
			),
		);
	}

	private function render_social_icons(): void {
		$socials = array(
			'facebook'  => get_option( 'optix_social_facebook' ),
			'twitter'   => get_option( 'optix_social_twitter' ),
			'instagram' => get_option( 'optix_social_instagram' ),
			'youtube'   => get_option( 'optix_social_youtube' ),
		);
		$icons   = array(
			'facebook'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>',
			'twitter'   => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg>',
			'instagram' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>',
			'youtube'   => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 00-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 00-1.94 2A29 29 0 001 11.75a29 29 0 00.46 5.33A2.78 2.78 0 003.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 001.94-2 29 29 0 00.46-5.25 29 29 0 00-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48" fill="currentColor"/></svg>',
		);

		foreach ( $socials as $platform => $url ) {
			if ( $url ) {
				echo '<a href="' . esc_url( $url ) . '" class="optix-social-icon" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( ucfirst( $platform ) ) . '">';
				echo wp_kses( $icons[ $platform ] ?? '', $this->get_svg_tags() );
				echo '</a>' . "\n";
			}
		}
	}
}
