<?php
declare(strict_types=1);

namespace Optix\Tests;

use PHPUnit\Framework\TestCase;
use Optix\Web_Vitals;

class Web_Vitals_Test extends TestCase {

	protected function setUp(): void {
		parent::setUp();

		$ref = new \ReflectionClass( Web_Vitals::class );
		$prop = $ref->getProperty( 'instance' );
		$prop->setAccessible( true );
		$prop->setValue( null );
	}

	public function test_get_instance(): void {
		$instance = Web_Vitals::get_instance();
		$this->assertInstanceOf( Web_Vitals::class, $instance );
		$this->assertSame( $instance, Web_Vitals::get_instance() );
	}

	public function test_init_does_not_error(): void {
		if ( ! function_exists( 'add_action' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$vitals = Web_Vitals::get_instance();
		$vitals->init();
		$this->assertTrue( true );
	}
}
