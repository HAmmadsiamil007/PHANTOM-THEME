<?php
declare(strict_types=1);

namespace OptixCore\Engine;

use OptixCore\Registry\Typography_Registry;

defined( 'ABSPATH' ) || exit;

class Typography_Engine {

	private static ?self $instance = null;

	private function __construct() {}

	private const GOOGLE_FONTS = array(
		'Archivo'          => 'Archivo:wght@300;400;500;600;700;800',
		'Jost'             => 'Jost:wght@300;400;500;600;700',
		'Inter'            => 'Inter:wght@300;400;500;600;700',
		'Montserrat'       => 'Montserrat:wght@300;400;500;600;700;800',
		'Poppins'          => 'Poppins:wght@300;400;500;600;700',
		'Playfair Display' => 'Playfair+Display:wght@400;500;600;700',
		'Lora'             => 'Lora:wght@400;500;600;700',
		'DM Sans'          => 'DM+Sans:wght@400;500;700',
		'Roboto'           => 'Roboto:wght@300;400;500;700',
		'Open Sans'        => 'Open+Sans:wght@300;400;500;600;700',
	);

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_google_fonts' ), 5 );
		add_action( 'wp_head', array( $this, 'output_typography_css' ), 5 );
	}

	public function get_google_fonts_url(): string {
		$registry = Typography_Registry::get_instance();
		$heading  = $registry->get( 'heading_font' ) ? $registry->get( 'heading_font' ) : 'Archivo';
		$body     = $registry->get( 'body_font' ) ? $registry->get( 'body_font' ) : 'Jost';
		$families = array();
		$fonts    = array_unique( array( $heading, $body ) );

		foreach ( $fonts as $font ) {
			if ( isset( self::GOOGLE_FONTS[ $font ] ) ) {
				$families[] = self::GOOGLE_FONTS[ $font ];
			}
		}

		if ( empty( $families ) ) {
			return '';
		}

		return add_query_arg(
			array(
				'family'  => implode( '&family=', $families ),
				'display' => 'swap',
			),
			'https://fonts.googleapis.com/css2'
		);
	}

	public function enqueue_google_fonts(): void {
		$url = $this->get_google_fonts_url();
		if ( $url ) {
			wp_enqueue_style( 'optix-google-fonts', $url, array(), OPTIX_CORE_VERSION );
		}
	}

	public function output_typography_css(): void {
		$registry = Typography_Registry::get_instance();
		$vars     = array(
			'--heading--font-family'   => $this->get_font_family_css( $registry->get( 'heading_font' ) ? $registry->get( 'heading_font' ) : 'Archivo' ),
			'--body--font-family'      => $this->get_font_family_css( $registry->get( 'body_font' ) ? $registry->get( 'body_font' ) : 'Jost' ),
			'--h1--font-size'          => ( $registry->get( 'h1_size' ) ? $registry->get( 'h1_size' ) : 48 ) . 'px',
			'--h2--font-size'          => ( $registry->get( 'h2_size' ) ? $registry->get( 'h2_size' ) : 36 ) . 'px',
			'--h3--font-size'          => ( $registry->get( 'h3_size' ) ? $registry->get( 'h3_size' ) : 24 ) . 'px',
			'--body--font-size'        => ( $registry->get( 'body_size' ) ? $registry->get( 'body_size' ) : 16 ) . 'px',
			'--heading--font-weight'   => $registry->get( 'heading_weight' ) ? $registry->get( 'heading_weight' ) : '700',
			'--body--font-weight'      => $registry->get( 'body_weight' ) ? $registry->get( 'body_weight' ) : '400',
			'--line-height'            => (string) ( $registry->get( 'line_height' ) ? $registry->get( 'line_height' ) : 1.6 ),
		);

		echo '<style id="optix-typography-vars">' . "\n";
		echo ':root {' . "\n";
		foreach ( $vars as $prop => $value ) {
			$safe_value = wp_strip_all_tags( $value );
			echo "\t" . esc_html( $prop ) . ': ' . $safe_value . ";\n";
		}
		echo '}' . "\n";
		echo '</style>' . "\n";
	}

	private function get_font_family_css( string $font ): string {
		return "'" . $font . "', sans-serif";
	}
}
