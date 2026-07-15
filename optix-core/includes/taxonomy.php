<?php
declare(strict_types=1);

namespace OptixCore;

defined('ABSPATH') || exit;

abstract class Taxonomy {

	protected $slug;
	public $translations = [];

	public function __construct( string $slug ) {
		$this->slug = $slug;
		$this->translations = [];
	}

	abstract protected function register( $post_types = [] ): void;

	protected function register_wp_taxonomy( string $slug, $object_types, array $args ): array {
		$register_result = [];

		$object_types_slugs = array_map( function ( $object_type ) {
			if ( is_string( $object_type ) ) {
				return $object_type;
			}
			if ( is_object( $object_type ) ) {
				return $object_type->slug;
			}
			return '';
		}, $object_types );

		if ( ! empty( $args['pll_translatable'] ) && false === $args['public'] ) {
			add_filter( 'pll_get_taxonomies', function ( $cpts ) use ( $slug ) {
				$cpts[ $slug ] = $slug;
				return $cpts;
			} );
		}

		$this->register_translations();
		register_taxonomy( $slug, $object_types_slugs, $args );

		foreach ( $object_types_slugs as $object_type ) {
			$register_result[ $object_type ] = register_taxonomy_for_object_type( $slug, $object_type );
		}

		return array_filter( $register_result, function ( $result ) {
			return (bool) $result;
		} );
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
