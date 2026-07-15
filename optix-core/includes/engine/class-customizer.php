<?php
declare(strict_types=1);

namespace OptixCore\Engine;

use OptixCore\Registry\Settings_Registry;
use WP_Customize_Manager;
use WP_Customize_Control;
use WP_Customize_Color_Control;
use WP_Customize_Media_Control;

defined('ABSPATH') || exit;

class Customizer {

	private static ?self $instance = null;

	private function __construct() {}

	private array $customizable_sections = [
		'branding',
		'colors',
		'typography',
		'header',
		'footer',
		'layout',
		'buttons',
		'forms',
		'spacing',
		'responsive',
		'announcement_bar',
	];

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'customize_register', [ $this, 'register_customizer' ], 20 );
		add_action( 'customize_preview_init', [ $this, 'enqueue_preview_js' ] );
	}

	public function register_customizer( WP_Customize_Manager $wp_customize ): void {
		$registry = Settings_Registry::get_instance();
		$registry->register();
		$entries = $registry->get_entries();

		$this->add_panel_if_needed( $wp_customize );

		foreach ( $this->customizable_sections as $section_name ) {
			$section_entries = $this->filter_entries_by_section( $entries, $section_name );
			if ( empty( $section_entries ) ) {
				continue;
			}

			$section_id = 'optix_' . $section_name;
			$wp_customize->add_section( $section_id, [
				'title'    => $this->get_section_title( $section_name ),
				'panel'    => 'optix_theme_panel',
				'priority' => 30,
			] );

			foreach ( $section_entries as $key => $entry ) {
				if ( in_array( $entry['type'], [ 'repeater', 'array' ], true ) ) {
					continue;
				}

				$setting_id = 'optix_' . $key;
				$has_css    = ! empty( $entry['css_property'] ) && ! empty( $entry['css_selector'] );

				$wp_customize->add_setting( $setting_id, [
					'default'           => $entry['default'] ?? '',
					'transport'         => $has_css ? 'postMessage' : 'refresh',
					'sanitize_callback' => $this->resolve_sanitize( $entry ),
					'capability'        => 'manage_options',
				] );

				$this->add_control( $wp_customize, $setting_id, $section_id, $key, $entry );
			}
		}
	}

	public function enqueue_preview_js(): void {
		$script_url = OPTIX_CORE_URL . 'assets/js/customizer.js';
		wp_enqueue_script( 'optix-customizer', $script_url, [ 'jquery', 'customize-preview' ], OPTIX_CORE_VERSION, true );

		$registry       = Settings_Registry::get_instance();
		$registry->register();
		$entries = $registry->get_entries();

		$css_var_settings   = [];
		$regenerate_settings = [];

		foreach ( $this->customizable_sections as $section_name ) {
			$section_entries = $this->filter_entries_by_section( $entries, $section_name );
			foreach ( $section_entries as $key => $entry ) {
				if ( in_array( $entry['type'], [ 'repeater', 'array' ], true ) ) {
					continue;
				}
				if ( ! empty( $entry['css_property'] ) && ! empty( $entry['css_selector'] ) ) {
					$css_var_settings[] = [
						'key'      => $key,
						'property' => $entry['css_property'],
						'selector' => $entry['css_selector'],
					];
				} else {
					$regenerate_settings[] = $key;
				}
			}
		}

		wp_localize_script( 'optix-customizer', 'optixCustomizer', [
			'cssVarSettings'    => $css_var_settings,
			'regenerateSettings' => $regenerate_settings,
		] );
	}

	private function add_panel_if_needed( WP_Customize_Manager $wp_customize ): void {
		$wp_customize->add_panel( 'optix_theme_panel', [
			'title'    => __( 'Optix Theme Options', 'optix-core' ),
			'priority' => 30,
		] );
	}

	private function get_section_title( string $section ): string {
		$titles = [
			'branding'         => __( 'Branding', 'optix-core' ),
			'colors'           => __( 'Colors', 'optix-core' ),
			'typography'       => __( 'Typography', 'optix-core' ),
			'header'           => __( 'Header', 'optix-core' ),
			'footer'           => __( 'Footer', 'optix-core' ),
			'layout'           => __( 'Layout', 'optix-core' ),
			'buttons'          => __( 'Buttons', 'optix-core' ),
			'forms'            => __( 'Forms', 'optix-core' ),
			'spacing'          => __( 'Spacing', 'optix-core' ),
			'responsive'       => __( 'Responsive', 'optix-core' ),
			'announcement_bar' => __( 'Announcement Bar', 'optix-core' ),
		];
		return $titles[ $section ] ?? ucfirst( str_replace( '_', ' ', $section ) );
	}

	private function filter_entries_by_section( array $entries, string $section ): array {
		return array_filter( $entries, fn( $e ) => ( $e['section'] ?? '' ) === $section );
	}

	private function resolve_sanitize( array $entry ): callable {
		if ( isset( $entry['sanitize'] ) && is_string( $entry['sanitize'] ) && function_exists( $entry['sanitize'] ) ) {
			return $entry['sanitize'];
		}
		return match ( $entry['type'] ?? 'string' ) {
			'bool'     => 'absint',
			'int'      => 'absint',
			'float'    => 'floatval',
			'color'    => function ($value) {
                $sanitized = sanitize_hex_color($value);
                return $sanitized ? $sanitized : ($value ?? '');
            },
			'image'    => 'esc_url_raw',
			'select'   => 'sanitize_text_field',
			'text'     => 'sanitize_textarea_field',
			'code'     => 'wp_strip_all_tags',
			default    => 'sanitize_text_field',
		};
	}

	private function add_control( WP_Customize_Manager $wp_customize, string $setting_id, string $section_id, string $key, array $entry ): void {
		$label = $entry['label'] ?? $key;
		$type  = $entry['type'] ?? 'string';

		switch ( $type ) {
			case 'color':
				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting_id, [
					'label'    => $label,
					'section'  => $section_id,
					'settings' => $setting_id,
				] ) );
				break;

			case 'bool':
				$wp_customize->add_control( $setting_id, [
					'label'    => $label,
					'section'  => $section_id,
					'type'     => 'checkbox',
					'settings' => $setting_id,
				] );
				break;

			case 'image':
				$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $setting_id, [
					'label'    => $label,
					'section'  => $section_id,
					'settings' => $setting_id,
					'mime_type' => 'image',
				] ) );
				break;

			case 'select':
				$choices = $entry['options'] ?? [];
				$wp_customize->add_control( $setting_id, [
					'label'    => $label,
					'section'  => $section_id,
					'type'     => 'select',
					'choices'  => $choices,
					'settings' => $setting_id,
				] );
				break;

			case 'int':
			case 'float':
				$wp_customize->add_control( $setting_id, [
					'label'    => $label,
					'section'  => $section_id,
					'type'     => 'number',
					'settings' => $setting_id,
					'input_attrs' => [
						'step' => 'float' === $type ? 'any' : '1',
					],
				] );
				break;

			case 'text':
			case 'code':
				$wp_customize->add_control( $setting_id, [
					'label'    => $label,
					'section'  => $section_id,
					'type'     => 'textarea',
					'settings' => $setting_id,
				] );
				break;

			default:
				$wp_customize->add_control( $setting_id, [
					'label'    => $label,
					'section'  => $section_id,
					'type'     => 'text',
					'settings' => $setting_id,
				] );
				break;
		}
	}
}
