<?php
declare(strict_types=1);

namespace OptixCore\Tests;

use PHPUnit\Framework\TestCase;

class Setup_Wizard_Test extends TestCase {

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

		$path = OPTIX_CORE_PATH . 'admin/class-setup-wizard.php';
		if ( ! file_exists( $path ) ) {
			$this->markTestSkipped( 'Setup_Wizard source not found' );
		}

		require_once $path;
	}

	public function test_get_instance(): void {
		$instance = \OptixCore\Setup_Wizard::get_instance();
		$this->assertInstanceOf( \OptixCore\Setup_Wizard::class, $instance );
		$this->assertSame( $instance, \OptixCore\Setup_Wizard::get_instance() );
	}

	public function test_init_does_not_error(): void {
		if ( ! function_exists( 'add_action' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$wizard = \OptixCore\Setup_Wizard::get_instance();
		$wizard->init();
		$this->assertTrue( true );
	}

	public function test_init_hooks_admin_menu(): void {
		if ( ! function_exists( 'has_action' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$wizard  = \OptixCore\Setup_Wizard::get_instance();

		$removed = remove_action( 'admin_menu', [ $wizard, 'add_wizard_page' ] );
		if ( $removed ) {
			$this->assertTrue( $removed );
		}

		$wizard->init();
		$this->assertNotFalse( has_action( 'admin_menu', [ $wizard, 'add_wizard_page' ] ) );
	}

	public function test_init_hooks_admin_init(): void {
		if ( ! function_exists( 'has_action' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$wizard  = \OptixCore\Setup_Wizard::get_instance();

		$removed = remove_action( 'admin_init', [ $wizard, 'maybe_redirect' ] );
		if ( $removed ) {
			$this->assertTrue( $removed );
		}

		$wizard->init();
		$this->assertNotFalse( has_action( 'admin_init', [ $wizard, 'maybe_redirect' ] ) );
	}

	public function test_init_hooks_admin_enqueue_scripts(): void {
		if ( ! function_exists( 'has_action' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$wizard  = \OptixCore\Setup_Wizard::get_instance();

		$removed = remove_action( 'admin_enqueue_scripts', [ $wizard, 'enqueue_styles' ] );
		if ( $removed ) {
			$this->assertTrue( $removed );
		}

		$wizard->init();
		$this->assertNotFalse( has_action( 'admin_enqueue_scripts', [ $wizard, 'enqueue_styles' ] ) );
	}

	public function test_steps_contains_expected(): void {
		if ( ! function_exists( 'add_action' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$wizard = \OptixCore\Setup_Wizard::get_instance();
		$wizard->init();
		$ref   = new \ReflectionClass( \OptixCore\Setup_Wizard::class );
		$prop  = $ref->getProperty( 'steps' );
		$prop->setAccessible( true );

		$steps = $prop->getValue( $wizard );

		$this->assertContains( 'welcome', $steps );
		$this->assertContains( 'done', $steps );
	}

	public function test_has_six_steps(): void {
		if ( ! function_exists( 'add_action' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$wizard = \OptixCore\Setup_Wizard::get_instance();
		$wizard->init();
		$ref   = new \ReflectionClass( \OptixCore\Setup_Wizard::class );
		$prop  = $ref->getProperty( 'steps' );
		$prop->setAccessible( true );

		$steps = $prop->getValue( $wizard );

		$this->assertCount( 6, $steps );
	}

	public function test_steps_in_correct_order(): void {
		if ( ! function_exists( 'add_action' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$wizard = \OptixCore\Setup_Wizard::get_instance();
		$wizard->init();
		$ref   = new \ReflectionClass( \OptixCore\Setup_Wizard::class );
		$prop  = $ref->getProperty( 'steps' );
		$prop->setAccessible( true );

		$steps = $prop->getValue( $wizard );

		$this->assertSame( 'welcome', $steps[0] );
		$this->assertSame( 'profile', $steps[1] );
		$this->assertSame( 'settings', $steps[2] );
		$this->assertSame( 'woocommerce', $steps[3] );
		$this->assertSame( 'demo', $steps[4] );
		$this->assertSame( 'done', $steps[5] );
	}

	public function test_has_render_method(): void {
		$this->assertTrue( method_exists( \OptixCore\Setup_Wizard::class, 'render' ) );
	}

	public function test_has_handle_submission_method(): void {
		$this->assertTrue( method_exists( \OptixCore\Setup_Wizard::class, 'handle_submission' ) );
	}
}
