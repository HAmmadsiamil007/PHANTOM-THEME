<?php
declare(strict_types=1);

namespace OptixCore\Acf;

use OptixCore\Registry\Settings_Registry;

defined( 'ABSPATH' ) || exit;

class Acf_Sync {

	private static ?self $instance = null;

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'acf/init', [ $this, 'sync_all' ] );
		add_filter( 'acf/settings/load_json', [ $this, 'register_json_directory' ] );
		add_filter( 'acf/settings/save_json', [ $this, 'set_save_json_directory' ] );
	}

	public function register_json_directory( array $paths ): array {
		$paths[] = OPTIX_CORE_PATH . 'acf-json';
		return $paths;
	}

	public function set_save_json_directory(): string {
		return OPTIX_CORE_PATH . 'acf-json';
	}

	public function generate_field_group( string $section_name, array $entries ): array {
		$fields = [];
		$field_keys = [];

		foreach ( $entries as $key => $entry ) {
			$type = $entry['type'] ?? 'string';
			$acf_type = $this->map_acf_type( $type );

			if ( '' === $acf_type ) {
				continue;
			}

			$field_key = 'field_optix_' . $key;
			$field_keys[ $key ] = $field_key;

			$field = [
				'key'               => $field_key,
				'label'             => $entry['label'] ?? $key,
				'name'              => $key,
				'type'              => $acf_type,
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'default_value'     => $entry['default'] ?? '',
			];

			if ( 'true_false' === $acf_type ) {
				$field['ui'] = true;
				$field['default_value'] = (int) ( $entry['default'] ?? 0 );
			}

			if ( 'image' === $acf_type ) {
				$field['return_format'] = 'url';
			}

			if ( 'color_picker' === $acf_type ) {
				$field['default_value'] = $entry['default'] ?? '#000000';
			}

			if ( 'select' === $acf_type && ! empty( $entry['options'] ) ) {
				$field['choices'] = $entry['options'];
			}

			if ( 'textarea' === $acf_type && 'code' === $type ) {
				$field['instructions'] = __( 'Raw code input — no sanitization applied.', 'optix-core' );
			}

			if ( 'textarea' === $acf_type && in_array( $type, [ 'array', 'repeater' ], true ) ) {
				$field['instructions'] = __( 'JSON-encoded data. Use caution when editing.', 'optix-core' );
			}

			$fields[] = $field;
		}

		foreach ( $fields as &$field ) {
			$entry = $entries[ $field['name'] ] ?? [];
			if ( ! empty( $entry['dependencies'] ) && is_array( $entry['dependencies'] ) ) {
				$logic = [];
				foreach ( $entry['dependencies'] as $dep ) {
					$dep_key = $dep['key'] ?? '';
					if ( isset( $field_keys[ $dep_key ] ) ) {
						$logic[] = [
							[
								'field'    => $field_keys[ $dep_key ],
								'operator' => '==',
								'value'    => (string) (int) ( $dep['value'] ?? true ),
							],
						];
					}
				}
				if ( ! empty( $logic ) ) {
					$field['conditional_logic'] = $logic;
				}
			}
		}
		unset( $field );

		return [
			'key'                   => 'group_optix_' . $section_name,
			'title'                 => ucwords( str_replace( '_', ' ', $section_name ) ) . ' Settings',
			'fields'                => $fields,
			'location'              => [
				[
					[
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'optix-framework',
					],
				],
			],
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => '',
			'show_in_rest'          => 0,
			'modified'              => time(),
		];
	}

	public function sync_all(): int {
		if ( ! function_exists( 'acf_get_instance' ) && ! function_exists( 'acf_get_field_group' ) ) {
			return 0;
		}

		$registry = Settings_Registry::get_instance();
		$registry->register();

		$entries = $registry->get_entries();
		$grouped = [];

		foreach ( $entries as $key => $entry ) {
			$section = $entry['section'] ?? 'misc';
			if ( ! isset( $grouped[ $section ] ) ) {
				$grouped[ $section ] = [];
			}
			$grouped[ $section ][ $key ] = $entry;
		}

		$json_dir = OPTIX_CORE_PATH . 'acf-json';
		if ( ! is_dir( $json_dir ) ) {
			wp_mkdir_p( $json_dir );
		}

		$count = 0;
		foreach ( $grouped as $section_name => $section_entries ) {
			$group = $this->generate_field_group( $section_name, $section_entries );
			$filename = 'group_optix_' . $section_name . '.json';
			$filepath = $json_dir . '/' . $filename;

			$json = wp_json_encode( $group, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
			if ( false === $json ) {
				continue;
			}

			$written = file_put_contents( $filepath, $json );
			if ( false !== $written ) {
				++$count;
			}
		}

		return $count;
	}

	private function map_acf_type( string $type ): string {
		$map = [
			'string'  => 'text',
			'text'    => 'textarea',
			'code'    => 'textarea',
			'int'     => 'number',
			'float'   => 'number',
			'bool'    => 'true_false',
			'color'   => 'color_picker',
			'select'  => 'select',
			'image'   => 'image',
			'array'   => 'textarea',
			'repeater' => 'textarea',
		];
		return $map[ $type ] ?? 'text';
	}
}
