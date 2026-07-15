<?php
declare(strict_types=1);

namespace OptixCore\Tests\Cli;

use PHPUnit\Framework\TestCase;

class Commands_Test extends TestCase {

	protected function setUp(): void {
		parent::setUp();

		if ( ! defined( 'ABSPATH' ) ) {
			define( 'ABSPATH', dirname( __DIR__, 4 ) . '/' );
		}

		$this->load_commands_source();
	}

	private function load_commands_source(): void {
		$path = dirname( __DIR__, 2 ) . '/includes/cli/class-commands.php';
		if ( ! file_exists( $path ) ) {
			$this->markTestSkipped( 'Commands source not found' );
		}

		if ( ! class_exists( '\WP_CLI_Command' ) ) {
			$this->markTestSkipped( 'WP_CLI_Command not available' );
		}

		require_once $path;
	}

	public function test_class_exists(): void {
		$this->assertTrue( class_exists( '\OptixCore\Cli\Commands' ) );
	}

	public function test_class_extends_wp_cli_command(): void {
		$ref = new \ReflectionClass( '\OptixCore\Cli\Commands' );
		$this->assertSame( 'WP_CLI_Command', $ref->getParentClass()->getName() );
	}

	public function test_has_setting_get_method(): void {
		$this->assertTrue( method_exists( '\OptixCore\Cli\Commands', 'setting_get' ) );
	}

	public function test_has_setting_list_method(): void {
		$this->assertTrue( method_exists( '\OptixCore\Cli\Commands', 'setting_list' ) );
	}

	public function test_has_setting_update_method(): void {
		$this->assertTrue( method_exists( '\OptixCore\Cli\Commands', 'setting_update' ) );
	}

	public function test_has_setting_delete_method(): void {
		$this->assertTrue( method_exists( '\OptixCore\Cli\Commands', 'setting_delete' ) );
	}

	public function test_has_setting_export_method(): void {
		$this->assertTrue( method_exists( '\OptixCore\Cli\Commands', 'setting_export' ) );
	}

	public function test_has_setting_import_method(): void {
		$this->assertTrue( method_exists( '\OptixCore\Cli\Commands', 'setting_import' ) );
	}

	public function test_has_profile_get_method(): void {
		$this->assertTrue( method_exists( '\OptixCore\Cli\Commands', 'profile_get' ) );
	}

	public function test_has_profile_set_method(): void {
		$this->assertTrue( method_exists( '\OptixCore\Cli\Commands', 'profile_set' ) );
	}

	public function test_has_cache_flush_method(): void {
		$this->assertTrue( method_exists( '\OptixCore\Cli\Commands', 'cache_flush' ) );
	}

	public function test_has_cache_status_method(): void {
		$this->assertTrue( method_exists( '\OptixCore\Cli\Commands', 'cache_status' ) );
	}

	public function test_has_schema_method(): void {
		$this->assertTrue( method_exists( '\OptixCore\Cli\Commands', 'schema' ) );
	}

	public function test_has_migrate_method(): void {
		$this->assertTrue( method_exists( '\OptixCore\Cli\Commands', 'migrate' ) );
	}

	public function test_setting_get_accepts_array_args(): void {
		$ref  = new \ReflectionMethod( '\OptixCore\Cli\Commands', 'setting_get' );
		$params = $ref->getParameters();
		$this->assertCount( 1, $params );
		$this->assertTrue( $params[0]->isArray() );
	}

	public function test_setting_list_accepts_array_args(): void {
		$ref    = new \ReflectionMethod( '\OptixCore\Cli\Commands', 'setting_list' );
		$params = $ref->getParameters();
		$this->assertCount( 2, $params );
		$this->assertTrue( $params[0]->isArray() );
		$this->assertTrue( $params[1]->isArray() );
	}
}
