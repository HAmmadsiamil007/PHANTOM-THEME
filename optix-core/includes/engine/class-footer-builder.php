<?php
declare(strict_types=1);

namespace OptixCore\Engine;

defined( 'ABSPATH' ) || exit;

class Footer_Builder {

	private static ?self $instance = null;

	private function __construct() {}

	private const DEFAULT_LAYOUT = array(
		'columns'   => 4,
		'copyright' => '© ' . '2026 Optix. All rights reserved.',
	);

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'init', array( $this, 'register_settings' ) );
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
	}

	public function register_settings(): void {
		register_setting(
			'optix_core',
			'optix_footer_layout',
			array(
				'type'              => 'array',
				'description'       => __( 'Footer layout configuration', 'optix-core' ),
				'sanitize_callback' => array( $this, 'sanitize_layout' ),
				'default'           => self::DEFAULT_LAYOUT,
				'show_in_rest'      => false,
			)
		);
		register_setting(
			'optix_core',
			'optix_footer_copyright',
			array(
				'type'              => 'string',
				'description'       => __( 'Footer copyright text', 'optix-core' ),
				'sanitize_callback' => 'wp_kses_post',
				'default'           => self::DEFAULT_LAYOUT['copyright'],
				'show_in_rest'      => true,
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
		$stored = get_option( 'optix_footer_layout', self::DEFAULT_LAYOUT );
		return is_array( $stored ) ? $stored : self::DEFAULT_LAYOUT;
	}

	public function get_column_count(): int {
		$layout = $this->get_layout();
		return (int) ( $layout['columns'] ?? 4 );
	}

	public function get_copyright(): string {
		return get_option( 'optix_footer_copyright', self::DEFAULT_LAYOUT['copyright'] );
	}

	public function render(): void {
		$layout    = $this->get_layout();
		$columns   = (int) ( $layout['columns'] ?? 4 );
		$col_class = 'optix-footer-col-' . $columns;

		echo '<footer id="optix-footer" class="optix-footer ' . esc_attr( $col_class ) . '">' . "\n";
		echo '<div class="optix-container">' . "\n";

		if ( $columns > 0 ) {
			echo '<div class="optix-footer-grid">' . "\n";
			for ( $i = 1; $i <= $columns; $i++ ) {
				echo '<div class="optix-footer-column">' . "\n";
				if ( is_active_sidebar( 'footer-' . $i ) ) {
					dynamic_sidebar( 'footer-' . $i );
				}
				echo '</div>' . "\n";
			}
			echo '</div>' . "\n";
		}

		$copyright = $this->get_copyright();
		if ( $copyright ) {
			echo '<div class="optix-footer-bottom">' . "\n";
			echo '<div class="optix-copyright">' . wp_kses_post( $copyright ) . '</div>' . "\n";
			echo '</div>' . "\n";
		}

		echo '</div>' . "\n";
		echo '</footer>' . "\n";
	}

	public function register_sidebars(): void {
		$columns = $this->get_column_count();
		for ( $i = 1; $i <= $columns; $i++ ) {
			register_sidebar(
				array(
					'name'          => sprintf( __( 'Footer Column %d', 'optix-core' ), $i ),
					'id'            => 'footer-' . $i,
					'description'   => sprintf( __( 'Footer widget column %d', 'optix-core' ), $i ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="widget-title">',
					'after_title'   => '</h3>',
				)
			);
		}
	}
}
