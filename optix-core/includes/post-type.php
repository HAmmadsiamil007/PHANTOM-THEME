<?php
declare(strict_types=1);

namespace OptixCore;

defined('ABSPATH') || exit;

abstract class Post_Type {

	public $post_type = null;
	public $slug;
	public $translations = [];

	public function __construct( string $slug ) {
		$this->slug = $slug;
		$this->translations = [];
	}

	abstract protected function register(): void;

	public function register_wp_post_type( string $slug, array $args ) {
		if ( ! empty( $args['pll_translatable'] ) && false === ( $args['public'] ?? true ) ) {
			add_filter( 'pll_get_post_types', function ( $cpts ) use ( $slug ) {
				$cpts[ $slug ] = $slug;
				return $cpts;
			}, 9, 2 );
		}

		$this->register_translations();
		return register_post_type( $slug, $args );
	}

	public function ask__( string $key, string $value ): string {
		$pll_key = "{$key}: {$value}";
		$this->translations[ $pll_key ] = $value;
		if ( function_exists( 'ask__' ) ) {
			return ask__( $pll_key );
		}
		return $value;
	}

	private function register_translations(): void {
		$translations = $this->translations;
		add_filter( 'optix_translations', function ( $strings ) use ( $translations ) {
			return array_merge( $translations, (array) $strings );
		}, 10, 2 );
	}
}
