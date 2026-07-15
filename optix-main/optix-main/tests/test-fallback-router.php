<?php
declare(strict_types=1);

namespace Optix\Tests;

use PHPUnit\Framework\TestCase;
use Optix\Fallback_Router;

class Fallback_Router_Test extends TestCase {

	protected function setUp(): void {
		parent::setUp();

		$ref = new \ReflectionClass( Fallback_Router::class );
		$prop = $ref->getProperty( 'instance' );
		$prop->setAccessible( true );
		$prop->setValue( null );
	}

	public function test_get_instance(): void {
		$instance = Fallback_Router::get_instance();
		$this->assertInstanceOf( Fallback_Router::class, $instance );
		$this->assertSame( $instance, Fallback_Router::get_instance() );
	}

	public function test_init_does_not_error(): void {
		if ( ! function_exists( 'add_filter' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$router = Fallback_Router::get_instance();
		$router->init();
		$this->assertTrue( true );
	}

	public function test_get_active_profile_default(): void {
		$ref = new \ReflectionClass( Fallback_Router::class );
		$prop = $ref->getProperty( 'active_profile' );
		$prop->setAccessible( true );

		$router = Fallback_Router::get_instance();
		$profile = $prop->getValue( $router );
		$this->assertIsString( $profile );
	}
}
