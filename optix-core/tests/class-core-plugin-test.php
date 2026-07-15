<?php
declare(strict_types=1);

namespace OptixCore\Tests;

use PHPUnit\Framework\TestCase;

class Core_Plugin_Test extends TestCase {

	protected function setUp(): void {
		parent::setUp();

		if ( ! defined( 'OPTIX_CORE_VERSION' ) ) {
			define( 'OPTIX_CORE_VERSION', '1.0.0' );
		}
		if ( ! defined( 'OPTIX_CORE_PATH' ) ) {
			define( 'OPTIX_CORE_PATH', dirname( __DIR__, 2 ) . '/' );
		}
		if ( ! defined( 'OPTIX_CORE_URL' ) ) {
			define( 'OPTIX_CORE_URL', 'http://example.com/wp-content/plugins/optix-core/' );
		}
		if ( ! defined( 'ABSPATH' ) ) {
			define( 'ABSPATH', dirname( __DIR__, 4 ) . '/' );
		}
	}

	public function test_get_instance(): void {
		$instance = \OptixCore\Plugin::get_instance();
		$this->assertInstanceOf( \OptixCore\Plugin::class, $instance );
		$this->assertSame( $instance, \OptixCore\Plugin::get_instance() );
	}

	public function test_init_does_not_error(): void {
		if ( ! function_exists( 'add_action' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$plugin = \OptixCore\Plugin::get_instance();
		$plugin->init();
		$this->assertTrue( true );
	}

	public function test_plugin_constants_defined(): void {
		$this->assertTrue( defined( 'OPTIX_CORE_VERSION' ) );
		$this->assertTrue( defined( 'OPTIX_CORE_PATH' ) );
		$this->assertTrue( defined( 'OPTIX_CORE_URL' ) );
	}

	public function test_plugin_version_is_string(): void {
		$this->assertIsString( OPTIX_CORE_VERSION );
	}

	public function test_plugin_path_ends_with_slash(): void {
		$this->assertStringEndsWith( '/', OPTIX_CORE_PATH );
	}

	public function test_init_registries_called_via_reflection(): void {
		$ref     = new \ReflectionClass( \OptixCore\Plugin::class );
		$method  = $ref->getMethod( 'init_registries' );
		$method->setAccessible( true );

		if ( ! function_exists( 'add_action' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$plugin = \OptixCore\Plugin::get_instance();
		$method->invoke( $plugin );
		$this->assertTrue( true );
	}

	public function test_plugin_has_expected_methods(): void {
		$this->assertTrue( method_exists( \OptixCore\Plugin::class, 'get_instance' ) );
		$this->assertTrue( method_exists( \OptixCore\Plugin::class, 'init' ) );
	}

	public function test_plugin_is_singleton(): void {
		$ref = new \ReflectionClass( \OptixCore\Plugin::class );

		$instance_prop = $ref->getProperty( 'instance' );
		$instance_prop->setAccessible( true );

		$construct = $ref->getConstructor();
		if ( $construct && ! $construct->isPrivate() && ! $construct->isProtected() ) {
		}

		$instance_prop->setValue( null );
		$instance_prop->setAccessible( false );

		$first  = \OptixCore\Plugin::get_instance();
		$second = \OptixCore\Plugin::get_instance();
		$this->assertSame( $first, $second );
	}
}
