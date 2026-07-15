<?php
declare(strict_types=1);

namespace OptixCore\Tests\Registry;

use OptixCore\Registry\Settings_Registry;
use OptixCore\Registry\Base_Registry;
use PHPUnit\Framework\TestCase;

class Settings_Registry_Test extends TestCase {
	use \AssertNotWPErrorTrait;

	private Settings_Registry $registry;

	protected function setUp(): void {
		parent::setUp();
		$this->registry = Settings_Registry::get_instance();
		Base_Registry::flush_cache();

		$base_ref = new \ReflectionClass( Base_Registry::class );
		$defaults_prop = $base_ref->getProperty( 'defaults_cache' );
		$defaults_prop->setAccessible( true );
		$defaults_prop->setValue( [] );

		$ref = new \ReflectionClass( $this->registry );
		$reg_prop = $ref->getProperty( 'registered' );
		$reg_prop->setAccessible( true );
		$reg_prop->setValue( $this->registry, false );

		$entries_prop = $ref->getProperty( 'entries' );
		$entries_prop->setAccessible( true );
		$entries_prop->setValue( $this->registry, [] );
	}

	private function inject_test_entries( array $entries ): void {
		$ref = new \ReflectionClass( $this->registry );

		$entries_prop = $ref->getProperty( 'entries' );
		$entries_prop->setAccessible( true );
		$entries_prop->setValue( $this->registry, $entries );

		$reg_prop = $ref->getProperty( 'registered' );
		$reg_prop->setAccessible( true );
		$reg_prop->setValue( $this->registry, true );
	}

	public function test_registry_has_entries(): void {
		$this->registry->register();
		$entries = $this->registry->get_entries();
		$this->assertNotEmpty( $entries );
	}

	public function test_get_schema_returns_valid_entry(): void {
		$this->registry->register();
		$schema = $this->registry->get_schema( 'header_logo' );
		$this->assertIsArray( $schema );
		$this->assertArrayHasKey( 'type', $schema );
		$this->assertArrayHasKey( 'default', $schema );
		$this->assertArrayHasKey( 'label', $schema );
		$this->assertEquals( 'string', $schema['type'] );
	}

	public function test_get_defaults(): void {
		$this->registry->register();
		$defaults = $this->registry->get_defaults();
		$this->assertNotEmpty( $defaults );
		foreach ( $defaults as $key => $value ) {
			$this->assertArrayHasKey( $key, $this->registry->get_entries(), "Entry '$key' missing from defaults" );
		}
	}

	public function test_get_bool_setting(): void {
		$this->inject_test_entries( [
			'test_bool' => [
				'type'    => 'bool',
				'default' => true,
				'label'   => 'Test Bool',
			],
		] );
		$result = $this->registry->get_bool( 'test_bool' );
		$this->assertIsBool( $result );
		$this->assertTrue( $result );
	}

	public function test_get_string_setting(): void {
		$this->inject_test_entries( [
			'test_string' => [
				'type'    => 'string',
				'default' => 'hello',
				'label'   => 'Test String',
			],
		] );
		$result = $this->registry->get_string( 'test_string' );
		$this->assertIsString( $result );
		$this->assertEquals( 'hello', $result );
	}

	public function test_get_int_setting(): void {
		$this->inject_test_entries( [
			'test_int' => [
				'type'    => 'int',
				'default' => 42,
				'label'   => 'Test Int',
			],
		] );
		$result = $this->registry->get_int( 'test_int' );
		$this->assertIsInt( $result );
		$this->assertEquals( 42, $result );
	}

	public function test_get_color_setting(): void {
		$this->inject_test_entries( [
			'test_color' => [
				'type'    => 'color',
				'default' => '#ff6600',
				'label'   => 'Test Color',
			],
		] );
		$result = $this->registry->get_color( 'test_color' );
		$this->assertIsString( $result );
		$this->assertStringStartsWith( '#', $result );
		$this->assertEquals( 7, strlen( $result ) );
	}

	public function test_get_missing_key(): void {
		$this->inject_test_entries( [] );
		$this->assertNull( $this->registry->get( 'non_existent_key' ) );
		$this->assertEquals( '', $this->registry->get_string( 'non_existent_key' ) );
		$this->assertEquals( 0, $this->registry->get_int( 'non_existent_key' ) );
		$this->assertFalse( $this->registry->get_bool( 'non_existent_key' ) );
	}

	public function test_set_and_get(): void {
		$this->inject_test_entries( [
			'test_key' => [
				'type'    => 'string',
				'default' => '',
				'label'   => 'Test Key',
			],
		] );
		$result = $this->registry->set( 'test_key', 'value' );
		$this->assertNotWPError( $result );
		$this->assertEquals( 'value', $this->registry->get( 'test_key' ) );
	}

	public function test_set_updates_database_option(): void {
		$this->inject_test_entries( [
			'db_key' => [
				'type'    => 'string',
				'default' => '',
				'label'   => 'DB Key',
			],
		] );
		$this->registry->set( 'db_key', 'db_value' );
		$this->assertEquals( 'db_value', get_option( 'optix_db_key' ) );
	}

	public function test_register_is_idempotent(): void {
		$this->registry->register();
		$count_first = $this->registry->count();

		$this->registry->register();
		$count_second = $this->registry->count();

		$this->assertEquals( $count_first, $count_second );
	}

	public function test_get_sections_returns_grouped_entries(): void {
		$this->registry->register();
		$entries = $this->registry->get_entries();
		$sections = [];
		foreach ( $entries as $key => $entry ) {
			$section = $entry['section'] ?? 'unknown';
			$sections[ $section ][] = $key;
		}
		$this->assertArrayHasKey( 'branding', $sections );
		$this->assertArrayHasKey( 'header', $sections );
		$this->assertArrayHasKey( 'hero', $sections );
		$this->assertArrayHasKey( 'footer', $sections );
		$this->assertNotEmpty( $sections['header'] );
	}

	public function test_get_float_returns_float(): void {
		$this->inject_test_entries( [
			'test_float' => [
				'type'    => 'float',
				'default' => 1.5,
				'label'   => 'Test Float',
			],
		] );
		$result = $this->registry->get_float( 'test_float' );
		$this->assertIsFloat( $result );
		$this->assertEquals( 1.5, $result );
	}

	public function test_get_array_returns_array(): void {
		$this->inject_test_entries( [
			'test_array' => [
				'type'    => 'array',
				'default' => [ 'a', 'b', 'c' ],
				'label'   => 'Test Array',
			],
		] );
		$result = $this->registry->get_array( 'test_array' );
		$this->assertIsArray( $result );
		$this->assertCount( 3, $result );
	}

	public function test_has_returns_true_for_registered_key(): void {
		$this->inject_test_entries( [
			'existing' => [
				'type'    => 'string',
				'default' => 'yes',
				'label'   => 'Existing',
			],
		] );
		$this->assertTrue( $this->registry->has( 'existing' ) );
		$this->assertFalse( $this->registry->has( 'missing' ) );
	}

	public function test_get_option_shim(): void {
		$this->inject_test_entries( [
			'shim_key' => [
				'type'    => 'string',
				'default' => 'shim_val',
				'label'   => 'Shim',
			],
		] );
		$this->assertEquals( 'shim_val', $this->registry->get_option( 'shim_key' ) );
		$this->assertNull( $this->registry->get_option( 'missing' ) );
	}

	public function test_count_matches_entry_count(): void {
		$this->inject_test_entries( [
			'a' => [ 'type' => 'string', 'default' => '', 'label' => 'A' ],
			'b' => [ 'type' => 'string', 'default' => '', 'label' => 'B' ],
			'c' => [ 'type' => 'string', 'default' => '', 'label' => 'C' ],
		] );
		$this->assertEquals( 3, $this->registry->count() );
	}

	public function test_get_all_returns_all_entries(): void {
		$this->inject_test_entries( [
			'one' => [ 'type' => 'string', 'default' => '1', 'label' => 'One' ],
			'two' => [ 'type' => 'string', 'default' => '2', 'label' => 'Two' ],
		] );
		$all = $this->registry->get_all();
		$this->assertCount( 2, $all );
		$this->assertEquals( '1', $all['one'] );
		$this->assertEquals( '2', $all['two'] );
	}

	public function test_get_string_returns_custom_default(): void {
		$this->inject_test_entries( [] );
		$this->assertEquals( 'custom', $this->registry->get_string( 'missing', 'custom' ) );
	}

	public function test_get_int_returns_custom_default(): void {
		$this->inject_test_entries( [] );
		$this->assertEquals( 99, $this->registry->get_int( 'missing', 99 ) );
	}

	public function test_get_bool_returns_custom_default(): void {
		$this->inject_test_entries( [] );
		$this->assertTrue( $this->registry->get_bool( 'missing', true ) );
	}

	public function test_get_float_returns_custom_default(): void {
		$this->inject_test_entries( [] );
		$this->assertEquals( 3.14, $this->registry->get_float( 'missing', 3.14 ) );
	}

	public function test_get_color_returns_custom_default(): void {
		$this->inject_test_entries( [] );
		$this->assertEquals( '#000000', $this->registry->get_color( 'missing' ) );
	}

	public function test_get_array_returns_custom_default(): void {
		$this->inject_test_entries( [] );
		$this->assertEquals( [], $this->registry->get_array( 'missing' ) );
	}

	public function test_bulk_get_returns_multiple_keys(): void {
		$this->inject_test_entries( [
			'key_a' => [ 'type' => 'string', 'default' => 'val_a', 'label' => 'A' ],
			'key_b' => [ 'type' => 'string', 'default' => 'val_b', 'label' => 'B' ],
			'key_c' => [ 'type' => 'string', 'default' => 'val_c', 'label' => 'C' ],
		] );
		$result = $this->registry->bulk_get( [ 'key_a', 'key_c' ] );
		$this->assertCount( 2, $result );
		$this->assertEquals( 'val_a', $result['key_a'] );
		$this->assertEquals( 'val_c', $result['key_c'] );
	}

	public function test_get_image_returns_string_for_path(): void {
		$this->inject_test_entries( [
			'test_img' => [
				'type'    => 'image',
				'default' => '/path/to/img.png',
				'label'   => 'Test Image',
			],
		] );
		$result = $this->registry->get_image( 'test_img' );
		$this->assertIsString( $result );
	}

	public function test_get_color_invalid_fallback(): void {
		$this->inject_test_entries( [
			'bad_color' => [
				'type'    => 'color',
				'default' => 'not-a-color',
				'label'   => 'Bad Color',
			],
		] );
		$result = $this->registry->get_color( 'bad_color' );
		$this->assertEquals( '#000000', $result );
	}
}
