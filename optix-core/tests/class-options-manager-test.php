<?php
declare(strict_types=1);

namespace OptixCore\Tests;

use OptixCore\Options_Manager;
use PHPUnit\Framework\TestCase;

class Options_Manager_Test extends TestCase {
	protected function setUp(): void {
		parent::setUp();

		$ref = new \ReflectionClass( Options_Manager::class );
		$cache_prop = $ref->getProperty( 'cache' );
		$cache_prop->setAccessible( true );
		$cache_prop->setValue( [] );
	}

	public function test_get_instance(): void {
		$instance = Options_Manager::get_instance();
		$this->assertInstanceOf( Options_Manager::class, $instance );
	}

	public function test_get_missing_key_default(): void {
		$result = Options_Manager::get( 'non_existent_key', 'fallback' );
		$this->assertEquals( 'fallback', $result );
	}

	public function test_set_and_get(): void {
		Options_Manager::set( 'test_key', 'test_value' );
		$result = Options_Manager::get( 'test_key' );
		$this->assertEquals( 'test_value', $result );
	}

	public function test_get_returns_defaults_for_registered_key(): void {
		$result = Options_Manager::get( 'header_logo' );
		$this->assertEquals( '/logo.png', $result );
	}

	public function test_get_all_returns_array(): void {
		$all = Options_Manager::get_defaults();
		$this->assertIsArray( $all );
		$this->assertNotEmpty( $all );
	}

	public function test_get_batch(): void {
		Options_Manager::set( 'batch_a', 'value_a' );
		Options_Manager::set( 'batch_b', 'value_b' );

		$this->assertEquals( 'value_a', Options_Manager::get( 'batch_a' ) );
		$this->assertEquals( 'value_b', Options_Manager::get( 'batch_b' ) );
	}

	public function test_set_overwrites_existing(): void {
		Options_Manager::set( 'overwrite_key', 'original' );
		$this->assertEquals( 'original', Options_Manager::get( 'overwrite_key' ) );

		Options_Manager::set( 'overwrite_key', 'updated' );
		$this->assertEquals( 'updated', Options_Manager::get( 'overwrite_key' ) );
	}

	public function test_get_with_null_default(): void {
		$result = Options_Manager::get( 'completely_missing' );
		$this->assertNull( $result );
	}

	public function test_init_does_not_throw(): void {
		$manager = Options_Manager::get_instance();
		$manager->init();
		$this->assertTrue( true );
	}

	public function test_set_creates_database_option(): void {
		Options_Manager::set( 'db_test', 'db_value' );
		$this->assertEquals( 'db_value', get_option( 'optix_db_test' ) );
	}

	public function test_get_uses_static_cache(): void {
		Options_Manager::set( 'cached_key', 'original' );
		Options_Manager::get( 'cached_key' );

		update_option( 'optix_cached_key', 'modified' );
		$result = Options_Manager::get( 'cached_key' );
		$this->assertEquals( 'original', $result );
	}

	public function test_get_defaults_contains_expected_keys(): void {
		$defaults = Options_Manager::get_defaults();
		$this->assertArrayHasKey( 'header_logo', $defaults );
		$this->assertArrayHasKey( 'footer_copyright', $defaults );
		$this->assertArrayHasKey( 'color_primary', $defaults );
	}

	public function test_get_defaults_returns_strings_for_text_fields(): void {
		$defaults = Options_Manager::get_defaults();
		$this->assertIsString( $defaults['header_logo'] );
		$this->assertIsString( $defaults['blog_title'] );
	}

	public function test_migrate_from_theme_does_not_throw(): void {
		$result = Options_Manager::migrate_from_theme();
		$this->assertNull( $result );
	}

	public function test_optix_get_option_function_exists(): void {
		$this->assertTrue( function_exists( 'optix_get_option' ) );
	}

	public function test_optix_get_option_fallback(): void {
		$result = optix_get_option( 'non_existent', 'fallback_val' );
		$this->assertEquals( 'fallback_val', $result );
	}

	public function test_optix_get_option_matches_static_get(): void {
		Options_Manager::set( 'func_key', 'func_val' );
		$this->assertEquals(
			Options_Manager::get( 'func_key' ),
			optix_get_option( 'func_key' )
		);
	}

	public function test_get_defaults_is_cached(): void {
		$first = Options_Manager::get_defaults();
		$second = Options_Manager::get_defaults();
		$this->assertSame( $first, $second );
	}
}
