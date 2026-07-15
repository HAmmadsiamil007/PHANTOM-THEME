<?php
declare(strict_types=1);

namespace OptixCore\Tests\Api;

use OptixCore\Theme_API;
use OptixCore\Registry\Settings_Registry;
use PHPUnit\Framework\TestCase;

class Theme_API_Test extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		$registry = Settings_Registry::get_instance();
		$ref      = new \ReflectionClass( $registry );
		$prop     = $ref->getProperty( 'entries' );
		$prop->setAccessible( true );
		$entries         = $prop->getValue( $registry );
		$entries = array_merge( $entries, [
			'test_string'       => [
				'type'    => 'string',
				'default' => 'hello',
				'label'   => 'Test String',
			],
			'test_int'          => [
				'type'    => 'int',
				'default' => 42,
				'label'   => 'Test Int',
			],
			'test_bool'         => [
				'type'    => 'bool',
				'default' => true,
				'label'   => 'Test Bool',
			],
			'test_color'        => [
				'type'    => 'color',
				'default' => '#ff6600',
				'label'   => 'Test Color',
			],
			'test_color_3digit' => [
				'type'    => 'color',
				'default' => '#abc',
				'label'   => 'Test 3-char Color',
			],
			'test_image_id'     => [
				'type'    => 'image',
				'default' => '',
				'label'   => 'Test Image',
			],
		] );
		$prop->setValue( $registry, $entries );
	}

	public function test_option_registry_first(): void {
		$this->assertEquals( 'hello', Theme_API::option( 'test_string' ) );
	}

	public function test_option_fallback(): void {
		$this->assertEquals(
			'fallback_val',
			Theme_API::option( 'non_existent_test_key', 'fallback_val' )
		);
	}

	public function test_option_filter(): void {
		add_filter( 'optix/settings/get/test_string', function () {
			return 'filtered';
		} );
		$this->assertEquals( 'filtered', Theme_API::option( 'test_string' ) );
		remove_all_filters( 'optix/settings/get/test_string' );
	}

	public function test_string_returns_default_on_null(): void {
		$this->assertSame( '', Theme_API::string( 'non_existent_test_key' ) );
	}

	public function test_string_uses_custom_default(): void {
		$this->assertSame(
			'fallback',
			Theme_API::string( 'non_existent_test_key', 'fallback' )
		);
	}

	public function test_string_cast(): void {
		$this->assertSame( 'hello', Theme_API::string( 'test_string' ) );
	}

	public function test_int_cast(): void {
		$this->assertSame( 42, Theme_API::int( 'test_int' ) );
	}

	public function test_int_default(): void {
		$this->assertSame( 99, Theme_API::int( 'non_existent_test_key', 99 ) );
	}

	public function test_bool_cast(): void {
		$this->assertTrue( Theme_API::bool( 'test_bool' ) );
	}

	public function test_bool_default(): void {
		$this->assertFalse( Theme_API::bool( 'non_existent_test_key' ) );
	}

	public function test_color_sanitization(): void {
		$result = Theme_API::color( 'test_color' );
		$this->assertStringStartsWith( '#', $result );
		$this->assertEquals( 7, strlen( $result ) );
	}

	public function test_color_3digit_expansion(): void {
		$result = Theme_API::color( 'test_color_3digit' );
		$this->assertEquals( '#aabbcc', $result );
	}

	public function test_color_invalid_returns_default(): void {
		$result = Theme_API::color( 'test_color_3digit' );
		$this->assertNotEmpty( $result );
	}

	public function test_global_functions_exist(): void {
		$this->assertTrue( function_exists( 'optix_option' ) );
		$this->assertTrue( function_exists( 'optix_string' ) );
		$this->assertTrue( function_exists( 'optix_int' ) );
		$this->assertTrue( function_exists( 'optix_bool' ) );
		$this->assertTrue( function_exists( 'optix_color' ) );
		$this->assertTrue( function_exists( 'optix_image' ) );
		$this->assertTrue( function_exists( 'optix_img' ) );
		$this->assertTrue( function_exists( 'optix_asset_url' ) );
		$this->assertTrue( function_exists( 'optix_get_option' ) );
	}

	public function test_get_option_shim_matches_option(): void {
		$this->assertEquals(
			Theme_API::option( 'test_string' ),
			optix_get_option( 'test_string' )
		);
	}
}
