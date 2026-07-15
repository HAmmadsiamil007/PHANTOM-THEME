<?php
declare(strict_types=1);

namespace OptixCore\Tests;

use OptixCore\Profile_Router;
use PHPUnit\Framework\TestCase;

class Profile_Router_Test extends TestCase {
	private Profile_Router $router;

	protected function setUp(): void {
		parent::setUp();
		$this->router = Profile_Router::get_instance();

		$ref = new \ReflectionClass( Profile_Router::class );
		$active_prop = $ref->getProperty( 'active_profile' );
		$active_prop->setAccessible( true );
		$active_prop->setValue( $this->router, null );

		$path_prop = $ref->getProperty( 'profile_path' );
		$path_prop->setAccessible( true );
		$path_prop->setValue( $this->router, null );

		delete_option( 'optix_active_profile' );
	}

	public function test_get_instance(): void {
		$instance = Profile_Router::get_instance();
		$this->assertInstanceOf( Profile_Router::class, $instance );
		$this->assertSame( $this->router, $instance );
	}

	public function test_get_active_profile_returns_default(): void {
		$profile = $this->router->get_active_profile();
		$this->assertEquals( 'default', $profile );
	}

	public function test_get_active_profile_from_option(): void {
		update_option( 'optix_active_profile', 'default' );
		$profile = $this->router->get_active_profile();
		$this->assertEquals( 'default', $profile );
	}

	public function test_get_profile_path_ends_with_profiles_profile(): void {
		$path = $this->router->get_profile_path();
		$this->assertStringEndsWith( '/profiles/default', $path );
	}

	public function test_get_profile_path_contains_template_directory(): void {
		$path = $this->router->get_profile_path();
		$this->assertStringContainsString( 'profiles', $path );
	}

	public function test_is_valid_profile_true(): void {
		$ref = new \ReflectionClass( Profile_Router::class );
		$method = $ref->getMethod( 'is_valid_profile' );
		$method->setAccessible( true );

		$result = $method->invoke( $this->router, 'kids-collection' );
		$this->assertTrue( $result );
	}

	public function test_is_valid_profile_false(): void {
		$ref = new \ReflectionClass( Profile_Router::class );
		$method = $ref->getMethod( 'is_valid_profile' );
		$method->setAccessible( true );

		$result = $method->invoke( $this->router, 'non_existent_profile_xyz' );
		$this->assertFalse( $result );
	}

	public function test_get_active_profile_caches_result(): void {
		$first = $this->router->get_active_profile();

		update_option( 'optix_active_profile', 'default' );
		$second = $this->router->get_active_profile();

		$this->assertSame( $first, $second );
	}

	public function test_get_profile_path_caches_result(): void {
		$first = $this->router->get_profile_path();
		$second = $this->router->get_profile_path();
		$this->assertSame( $first, $second );
	}

	public function test_route_template_returns_original_when_not_found(): void {
		$result = $this->router->route_template( '/nonexistent/template.php' );
		$this->assertEquals( '/nonexistent/template.php', $result );
	}

	public function test_route_template_returns_template_for_empty_basename(): void {
		$result = $this->router->route_template( '' );
		$this->assertEquals( '', $result );
	}

	public function test_route_stylesheet_returns_original_when_no_profile_css(): void {
		$ref = new \ReflectionClass( Profile_Router::class );
		$prop = $ref->getProperty( 'active_profile' );
		$prop->setAccessible( true );
		$prop->setValue( $this->router, 'no_such_profile' );

		$uri = 'http://example.com/theme/style.css';
		$result = $this->router->route_stylesheet( $uri );
		$this->assertEquals( $uri, $result );
	}

	public function test_route_directory_uri_appends_profile(): void {
		$result = $this->router->route_directory_uri( 'http://example.com/wp-content/themes/optix' );
		$this->assertStringContainsString( '/profiles/default', $result );
	}

	public function test_optix_profile_template_part_function_exists(): void {
		$this->assertTrue( function_exists( 'optix_profile_template_part' ) );
	}
}
