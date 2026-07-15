<?php
declare(strict_types=1);

namespace OptixCore\Tests;

use OptixCore\Dynamic_CSS_Generator;
use OptixCore\Registry\Settings_Registry;
use OptixCore\Registry\Base_Registry;
use PHPUnit\Framework\TestCase;

$plugin_root = dirname( __DIR__ );
require_once $plugin_root . '/includes/class-dynamic-css-generator.php';

class Dynamic_CSS_Test extends TestCase {
	private Dynamic_CSS_Generator $generator;

	protected function setUp(): void {
		parent::setUp();
		$this->generator = Dynamic_CSS_Generator::get_instance();

		$ref = new \ReflectionClass( Dynamic_CSS_Generator::class );
		$css_prop = $ref->getProperty( 'generated_css' );
		$css_prop->setAccessible( true );
		$css_prop->setValue( '' );

		$instance_prop = $ref->getProperty( 'instance' );
		$instance_prop->setAccessible( true );
		$instance_prop->setValue( null );

		$reg = Settings_Registry::get_instance();
		$reg_ref = new \ReflectionClass( $reg );
		$reg_prop = $reg_ref->getProperty( 'registered' );
		$reg_prop->setAccessible( true );
		$reg_prop->setValue( $reg, false );

		$cache_ref = new \ReflectionClass( Base_Registry::class );
		$dc_prop = $cache_ref->getProperty( 'defaults_cache' );
		$dc_prop->setAccessible( true );
		$dc_prop->setValue( [] );

		$rc_prop = $cache_ref->getProperty( 'runtime_cache' );
		$rc_prop->setAccessible( true );
		$rc_prop->setValue( [] );
	}

	public function test_get_instance(): void {
		$instance = Dynamic_CSS_Generator::get_instance();
		$this->assertInstanceOf( Dynamic_CSS_Generator::class, $instance );
	}

	public function test_generate_returns_string(): void {
		$css = $this->generator->generate();
		$this->assertIsString( $css );
	}

	public function test_generated_css_contains_root_declarations(): void {
		$css = $this->generator->generate();
		$this->assertStringContainsString( ':root {', $css );
	}

	public function test_generated_css_is_cached_on_second_call(): void {
		$first = $this->generator->generate();

		$ref = new \ReflectionClass( Dynamic_CSS_Generator::class );
		$css_prop = $ref->getProperty( 'generated_css' );
		$css_prop->setAccessible( true );
		$cached = $css_prop->getValue();
		$this->assertNotEmpty( $cached );

		$this->assertSame( $first, $this->generator->generate() );
	}

	public function test_output_styles_echoes_style_tag(): void {
		ob_start();
		$this->generator->output_styles();
		$output = ob_get_clean();

		$this->assertStringContainsString( '<style id="optix-dynamic-css">', $output );
		$this->assertStringContainsString( '</style>', $output );
	}

	public function test_generated_css_includes_color_variables(): void {
		$css = $this->generator->generate();
		$this->assertStringContainsString( '--color-primary', $css );
		$this->assertStringContainsString( '--color-secondary', $css );
	}

	public function test_generated_css_includes_typography_variables(): void {
		$css = $this->generator->generate();
		$this->assertStringContainsString( '--font-heading', $css );
		$this->assertStringContainsString( '--font-body', $css );
	}

	public function test_generated_css_includes_button_variables(): void {
		$css = $this->generator->generate();
		$this->assertStringContainsString( '--button-bg', $css );
		$this->assertStringContainsString( '--button-text', $css );
	}

	public function test_generated_css_uses_correct_format(): void {
		$css = $this->generator->generate();
		$this->assertMatchesRegularExpression( '/:root\s*\{[^}]+--color-primary:\s*#[0-9a-fA-F]+/', $css );
	}

	public function test_generate_returns_same_output_for_same_state(): void {
		$first = $this->generator->generate();
		$second = $this->generator->generate();
		$this->assertSame( $first, $second );
	}

	public function test_css_blocks_have_proper_syntax(): void {
		$css = $this->generator->generate();
		$this->assertMatchesRegularExpression( '/\{[^}]*\}/s', $css );
	}
}
