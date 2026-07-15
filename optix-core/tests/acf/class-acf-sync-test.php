<?php
declare(strict_types=1);

namespace OptixCore\Tests\Acf;

use PHPUnit\Framework\TestCase;

class Acf_Sync_Test extends TestCase {

	protected function setUp(): void {
		parent::setUp();

		if ( ! defined( 'OPTIX_CORE_PATH' ) ) {
			define( 'OPTIX_CORE_PATH', dirname( __DIR__, 2 ) . '/' );
		}
		if ( ! defined( 'OPTIX_CORE_VERSION' ) ) {
			define( 'OPTIX_CORE_VERSION', '1.0.0' );
		}
		if ( ! defined( 'OPTIX_CORE_URL' ) ) {
			define( 'OPTIX_CORE_URL', 'http://example.com/wp-content/plugins/optix-core/' );
		}
		if ( ! defined( 'ABSPATH' ) ) {
			define( 'ABSPATH', dirname( __DIR__, 4 ) . '/' );
		}

		$path = OPTIX_CORE_PATH . 'includes/acf/class-acf-sync.php';
		if ( ! file_exists( $path ) ) {
			$this->markTestSkipped( 'Acf_Sync source not found' );
		}

		require_once $path;
	}

	public function test_get_instance(): void {
		$instance = \OptixCore\Acf\Acf_Sync::get_instance();
		$this->assertInstanceOf( \OptixCore\Acf\Acf_Sync::class, $instance );
		$this->assertSame( $instance, \OptixCore\Acf\Acf_Sync::get_instance() );
	}

	public function test_init_hooks_acf_init(): void {
		if ( ! function_exists( 'has_action' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$acf_sync = \OptixCore\Acf\Acf_Sync::get_instance();

		$removed = remove_action( 'acf/init', [ $acf_sync, 'sync_all' ] );
		if ( $removed ) {
			$this->assertTrue( $removed );
		}

		$acf_sync->init();
		$this->assertNotFalse( has_action( 'acf/init', [ $acf_sync, 'sync_all' ] ) );
	}

	public function test_init_hooks_load_json(): void {
		if ( ! function_exists( 'has_filter' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$acf_sync = \OptixCore\Acf\Acf_Sync::get_instance();

		$removed = remove_filter( 'acf/settings/load_json', [ $acf_sync, 'register_json_directory' ] );
		if ( $removed ) {
			$this->assertTrue( $removed );
		}

		$acf_sync->init();
		$this->assertNotFalse( has_filter( 'acf/settings/load_json', [ $acf_sync, 'register_json_directory' ] ) );
	}

	public function test_init_hooks_save_json(): void {
		if ( ! function_exists( 'has_filter' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$acf_sync  = \OptixCore\Acf\Acf_Sync::get_instance();
		$removed = remove_filter( 'acf/settings/save_json', [ $acf_sync, 'set_save_json_directory' ] );
		if ( $removed ) {
			$this->assertTrue( $removed );
		}

		$acf_sync->init();
		$this->assertNotFalse( has_filter( 'acf/settings/save_json', [ $acf_sync, 'set_save_json_directory' ] ) );
	}

	public function test_generate_field_group_returns_array(): void {
		$acf_sync = \OptixCore\Acf\Acf_Sync::get_instance();
		$result   = $acf_sync->generate_field_group( 'test_section', [
			'test_key' => [
				'type'    => 'string',
				'default' => 'hello',
				'label'   => 'Test Label',
			],
		] );

		$this->assertIsArray( $result );
	}

	public function test_generate_field_group_has_expected_structure(): void {
		$acf_sync = \OptixCore\Acf\Acf_Sync::get_instance();
		$result   = $acf_sync->generate_field_group( 'test_section', [
			'test_key' => [
				'type'    => 'string',
				'default' => 'hello',
				'label'   => 'Test Label',
			],
		] );

		$this->assertArrayHasKey( 'key', $result );
		$this->assertArrayHasKey( 'title', $result );
		$this->assertArrayHasKey( 'fields', $result );
		$this->assertArrayHasKey( 'location', $result );
		$this->assertSame( 'group_optix_test_section', $result['key'] );
		$this->assertSame( 'Test Section Settings', $result['title'] );
		$this->assertIsArray( $result['fields'] );
	}

	public function test_generate_field_group_location_is_options_page(): void {
		$acf_sync = \OptixCore\Acf\Acf_Sync::get_instance();
		$result   = $acf_sync->generate_field_group( 'test', [
			'k' => [ 'type' => 'string', 'default' => '', 'label' => 'K' ],
		] );

		$this->assertSame( 'options_page', $result['location'][0][0]['param'] );
		$this->assertSame( '==', $result['location'][0][0]['operator'] );
		$this->assertSame( 'optix-framework', $result['location'][0][0]['value'] );
	}

	public function test_build_field_from_entry_color(): void {
		$acf_sync = \OptixCore\Acf\Acf_Sync::get_instance();
		$result   = $acf_sync->generate_field_group( 'test', [
			'color_key' => [
				'type'    => 'color',
				'default' => '#ff6600',
				'label'   => 'Test Color',
			],
		] );

		$field = $result['fields'][0];
		$this->assertSame( 'color_picker', $field['type'] );
		$this->assertSame( '#ff6600', $field['default_value'] );
	}

	public function test_build_field_from_entry_image(): void {
		$acf_sync = \OptixCore\Acf\Acf_Sync::get_instance();
		$result   = $acf_sync->generate_field_group( 'test', [
			'image_key' => [
				'type'    => 'image',
				'default' => '',
				'label'   => 'Test Image',
			],
		] );

		$field = $result['fields'][0];
		$this->assertSame( 'image', $field['type'] );
		$this->assertSame( 'url', $field['return_format'] );
	}

	public function test_build_field_from_entry_select(): void {
		$acf_sync = \OptixCore\Acf\Acf_Sync::get_instance();
		$result   = $acf_sync->generate_field_group( 'test', [
			'select_key' => [
				'type'    => 'select',
				'default' => 'a',
				'label'   => 'Test Select',
				'options' => [
					'a' => 'Option A',
					'b' => 'Option B',
				],
			],
		] );

		$field = $result['fields'][0];
		$this->assertSame( 'select', $field['type'] );
		$this->assertSame( [ 'a' => 'Option A', 'b' => 'Option B' ], $field['choices'] );
	}

	public function test_build_field_from_entry_bool(): void {
		$acf_sync = \OptixCore\Acf\Acf_Sync::get_instance();
		$result   = $acf_sync->generate_field_group( 'test', [
			'bool_key' => [
				'type'    => 'bool',
				'default' => 1,
				'label'   => 'Test Bool',
			],
		] );

		$field = $result['fields'][0];
		$this->assertSame( 'true_false', $field['type'] );
		$this->assertTrue( $field['ui'] );
	}

	public function test_build_field_from_entry_int(): void {
		$acf_sync = \OptixCore\Acf\Acf_Sync::get_instance();
		$result   = $acf_sync->generate_field_group( 'test', [
			'int_key' => [
				'type'    => 'int',
				'default' => 42,
				'label'   => 'Test Int',
			],
		] );

		$field = $result['fields'][0];
		$this->assertSame( 'number', $field['type'] );
	}

	public function test_map_acf_type_maps_correctly(): void {
		$ref  = new \ReflectionClass( \OptixCore\Acf\Acf_Sync::class );
		$method = $ref->getMethod( 'map_acf_type' );
		$method->setAccessible( true );

		$acf_sync = \OptixCore\Acf\Acf_Sync::get_instance();

		$this->assertSame( 'text', $method->invoke( $acf_sync, 'string' ) );
		$this->assertSame( 'textarea', $method->invoke( $acf_sync, 'text' ) );
		$this->assertSame( 'textarea', $method->invoke( $acf_sync, 'code' ) );
		$this->assertSame( 'number', $method->invoke( $acf_sync, 'int' ) );
		$this->assertSame( 'number', $method->invoke( $acf_sync, 'float' ) );
		$this->assertSame( 'true_false', $method->invoke( $acf_sync, 'bool' ) );
		$this->assertSame( 'color_picker', $method->invoke( $acf_sync, 'color' ) );
		$this->assertSame( 'select', $method->invoke( $acf_sync, 'select' ) );
		$this->assertSame( 'image', $method->invoke( $acf_sync, 'image' ) );
		$this->assertSame( 'textarea', $method->invoke( $acf_sync, 'array' ) );
		$this->assertSame( 'textarea', $method->invoke( $acf_sync, 'repeater' ) );
		$this->assertSame( 'text', $method->invoke( $acf_sync, 'unknown_type' ) );
	}

	public function test_register_json_directory(): void {
		$acf_sync = \OptixCore\Acf\Acf_Sync::get_instance();
		$paths    = $acf_sync->register_json_directory( [ '/some/path' ] );

		$this->assertIsArray( $paths );
		$this->assertCount( 2, $paths );
		$this->assertStringContainsString( 'acf-json', $paths[1] );
	}
}
