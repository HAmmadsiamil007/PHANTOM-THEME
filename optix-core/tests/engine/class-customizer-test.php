<?php
declare(strict_types=1);

namespace OptixCore\Tests\Engine;

use PHPUnit\Framework\TestCase;

class Customizer_Test extends TestCase {

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

		$path = OPTIX_CORE_PATH . 'includes/engine/class-customizer.php';
		if ( ! file_exists( $path ) ) {
			$this->markTestSkipped( 'Customizer source not found' );
		}

		require_once $path;
	}

	public function test_get_instance(): void {
		$instance = \OptixCore\Engine\Customizer::get_instance();
		$this->assertInstanceOf( \OptixCore\Engine\Customizer::class, $instance );
		$this->assertSame( $instance, \OptixCore\Engine\Customizer::get_instance() );
	}

	public function test_init_hooks_customize_register(): void {
		if ( ! function_exists( 'has_action' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$customizer = \OptixCore\Engine\Customizer::get_instance();

		$removed = remove_action( 'customize_register', [ $customizer, 'register_customizer' ] );
		if ( $removed ) {
			$this->assertTrue( $removed );
		}

		$customizer->init();
		$this->assertNotFalse( has_action( 'customize_register', [ $customizer, 'register_customizer' ] ) );
	}

	public function test_init_hooks_preview_js(): void {
		if ( ! function_exists( 'has_action' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );

		}

		$customizer = \OptixCore\Engine\Customizer::get_instance();

		$removed = remove_action( 'customize_preview_init', [ $customizer, 'enqueue_preview_js' ] );
		if ( $removed ) {
			$this->assertTrue( $removed );
		}

		$customizer->init();
		$this->assertNotFalse( has_action( 'customize_preview_init', [ $customizer, 'enqueue_preview_js' ] ) );
	}

	public function test_customizable_sections_contains_expected(): void {
		$ref  = new \ReflectionClass( \OptixCore\Engine\Customizer::class );
		$prop = $ref->getProperty( 'customizable_sections' );
		$prop->setAccessible( true );

		$sections = $prop->getValue( \OptixCore\Engine\Customizer::get_instance() );

		$this->assertContains( 'branding', $sections );
		$this->assertContains( 'colors', $sections );
		$this->assertContains( 'typography', $sections );
		$this->assertContains( 'header', $sections );
		$this->assertContains( 'footer', $sections );
		$this->assertContains( 'layout', $sections );
		$this->assertContains( 'buttons', $sections );
		$this->assertContains( 'forms', $sections );
		$this->assertContains( 'spacing', $sections );
		$this->assertContains( 'responsive', $sections );
		$this->assertContains( 'announcement_bar', $sections );
	}

	public function test_customizable_sections_contains_at_least_10(): void {
		$ref  = new \ReflectionClass( \OptixCore\Engine\Customizer::class );
		$prop = $ref->getProperty( 'customizable_sections' );
		$prop->setAccessible( true );

		$sections = $prop->getValue( \OptixCore\Engine\Customizer::get_instance() );

		$this->assertGreaterThanOrEqual( 10, count( $sections ) );
	}

	public function test_init_does_not_error(): void {
		if ( ! function_exists( 'add_action' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$customizer = \OptixCore\Engine\Customizer::get_instance();

		$removed1 = remove_action( 'customize_register', [ $customizer, 'register_customizer' ] );
		$removed2 = remove_action( 'customize_preview_init', [ $customizer, 'enqueue_preview_js' ] );
		if ( $removed1 || $removed2 ) {
		}

		$customizer->init();
		$this->assertTrue( true );
	}
}
