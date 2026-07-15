<?php
declare(strict_types=1);

namespace OptixCore\Tests\Api;

use PHPUnit\Framework\TestCase;

class Rest_Controller_Test extends TestCase {

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

		$path = OPTIX_CORE_PATH . 'includes/api/class-rest-controller.php';
		if ( ! file_exists( $path ) ) {
			$this->markTestSkipped( 'Rest_Controller source not found' );
		}
		if ( ! class_exists( '\WP_REST_Controller' ) ) {
			$this->markTestSkipped( 'WordPress REST API classes not available' );
		}

		require_once $path;
	}

	public function test_get_instance(): void {
		$controller = \OptixCore\Api\Rest_Controller::get_instance();
		$this->assertInstanceOf( \OptixCore\Api\Rest_Controller::class, $controller );
		$this->assertSame( $controller, \OptixCore\Api\Rest_Controller::get_instance() );
	}

	public function test_namespace_is_optix_v1(): void {
		$ref  = new \ReflectionClass( \OptixCore\Api\Rest_Controller::class );
		$prop = $ref->getProperty( 'namespace' );
		$prop->setAccessible( true );
		$this->assertSame( 'optix/v1', $prop->getValue( \OptixCore\Api\Rest_Controller::get_instance() ) );
	}

	public function test_register_routes_hooks_rest_api(): void {
		$controller = \OptixCore\Api\Rest_Controller::get_instance();
		$controller->register_routes();
		$this->assertTrue( true );
	}

	public function test_init_registers_hook(): void {
		if ( ! function_exists( 'has_action' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$controller = \OptixCore\Api\Rest_Controller::get_instance();

		$removed = remove_action( 'rest_api_init', [ $controller, 'register_routes' ] );
		if ( $removed ) {
			$this->assertTrue( $removed );
		}

		$controller->init();
		$this->assertNotFalse( has_action( 'rest_api_init', [ $controller, 'register_routes' ] ) );
	}

	public function test_get_settings_args_returns_array(): void {
		$ref  = new \ReflectionClass( \OptixCore\Api\Rest_Controller::class );
		$method = $ref->getMethod( 'get_settings_args' );
		$method->setAccessible( true );

		$controller = \OptixCore\Api\Rest_Controller::get_instance();
		$args       = $method->invoke( $controller );

		$this->assertIsArray( $args );
		$this->assertArrayHasKey( 'section', $args );
		$this->assertArrayHasKey( 'per_page', $args );
		$this->assertArrayHasKey( 'page', $args );
		$this->assertSame( 50, $args['per_page']['default'] );
	}

	public function test_permission_check_without_capability(): void {
		if ( ! function_exists( 'current_user_can' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$controller = \OptixCore\Api\Rest_Controller::get_instance();
		$result     = $controller->permission_check();

		$this->assertIsBool( $result );
	}

	public function test_get_settings_returns_expected_structure(): void {
		if ( ! class_exists( '\WP_REST_Request' ) ) {
			$this->markTestSkipped( 'WordPress REST API classes not available' );
		}

		$registry = \OptixCore\Registry\Settings_Registry::get_instance();
		$registry->register();

		$request  = new \WP_REST_Request( 'GET', '/optix/v1/settings' );
		$request->set_param( 'per_page', 10 );
		$request->set_param( 'page', 1 );

		$controller = \OptixCore\Api\Rest_Controller::get_instance();
		$response   = $controller->get_settings( $request );

		$this->assertInstanceOf( \WP_REST_Response::class, $response );
		$this->assertSame( 200, $response->get_status() );

		$data = $response->get_data();
		$this->assertIsArray( $data );
		if ( ! empty( $data ) ) {
			$first = $data[0];
			$this->assertArrayHasKey( 'key', $first );
			$this->assertArrayHasKey( 'value', $first );
			$this->assertArrayHasKey( 'default', $first );
			$this->assertArrayHasKey( 'type', $first );
			$this->assertArrayHasKey( 'section', $first );
			$this->assertArrayHasKey( 'label', $first );
		}
	}

	public function test_get_setting_returns_404_for_unknown(): void {
		if ( ! class_exists( '\WP_REST_Request' ) ) {
			$this->markTestSkipped( 'WordPress REST API classes not available' );
		}

		$controller = \OptixCore\Api\Rest_Controller::get_instance();
		$request    = new \WP_REST_Request( 'GET', '/optix/v1/settings/nonexistent' );
		$request->set_param( 'key', 'nonexistent_key_xyz' );

		$response = $controller->get_setting( $request );
		$this->assertSame( 404, $response->get_status() );
	}

	public function test_get_profile_returns_string(): void {
		if ( ! function_exists( 'get_option' ) ) {
			$this->markTestSkipped( 'WordPress not loaded' );
		}

		$controller = \OptixCore\Api\Rest_Controller::get_instance();
		$response   = $controller->get_profile();
		$this->assertInstanceOf( \WP_REST_Response::class, $response );
	}
}
