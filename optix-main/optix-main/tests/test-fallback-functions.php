<?php
declare(strict_types=1);

namespace Optix\Tests;

use PHPUnit\Framework\TestCase;

class Fallback_Functions_Test extends TestCase {

	public function test_fallback_option_exists(): void {
		$this->assertTrue( function_exists( '\Optix\fallback_option' ) );
	}

	public function test_fallback_asset_url_exists(): void {
		$this->assertTrue( function_exists( '\Optix\fallback_asset_url' ) );
	}

	public function test_fallback_string_exists(): void {
		$this->assertTrue( function_exists( '\Optix\fallback_string' ) );
	}

	public function test_fallback_asset_url_returns_string(): void {
		$url = \Optix\fallback_asset_url();
		$this->assertIsString( $url );
	}
}
