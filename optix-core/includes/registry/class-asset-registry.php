<?php
/**
 * Asset registry — schema for enqueueable styles and scripts.
 *
 * @package optix-core
 */

declare(strict_types=1);

namespace OptixCore\Registry;

defined( 'ABSPATH' ) || exit;

/**
 * Registry defining and managing theme/plugin assets.
 */
class Asset_Registry extends Base_Registry {

	/**
	 * Define default asset entries.
	 *
	 * @return array
	 */
	protected function define_entries(): array {
		return array(
			'optix-main'        => array(
				'type'        => 'style',
				'src'         => '',
				'deps'        => array(),
				'version'     => OPTIX_CORE_VERSION,
				'media'       => 'all',
				'enqueue'     => false,
				'description' => __( 'Main theme stylesheet — register via Asset_Registry::register_style()', 'optix-core' ),
			),
			'optix-woocommerce' => array(
				'type'        => 'style',
				'src'         => '',
				'deps'        => array( 'optix-main' ),
				'version'     => OPTIX_CORE_VERSION,
				'media'       => 'all',
				'enqueue'     => false,
				'description' => __( 'WooCommerce-specific styles — register via Asset_Registry::register_style()', 'optix-core' ),
			),
			'optix-frontend'    => array(
				'type'        => 'script',
				'src'         => '',
				'deps'        => array(),
				'version'     => OPTIX_CORE_VERSION,
				'in_footer'   => true,
				'enqueue'     => true,
				'description' => __( 'Frontend JS handle — engines localize/add_inline via this handle.', 'optix-core' ),
			),
		);
	}

	/**
	 * Initialize — register entries and hook enqueue.
	 *
	 * @return void
	 */
	public function init(): void {
		$this->register();
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 5 );
	}

	/**
	 * Add a dynamic asset entry.
	 *
	 * @param  string $handle Asset handle.
	 * @param  array  $args   Asset arguments (type, src, deps, version, etc.).
	 * @return void
	 */
	public function add_asset( string $handle, array $args ): void {
		$defaults                 = array(
			'type'      => 'style',
			'src'       => '',
			'deps'      => array(),
			'version'   => OPTIX_CORE_VERSION,
			'media'     => 'all',
			'in_footer' => false,
			'enqueue'   => true,
		);
		$this->entries[ $handle ] = wp_parse_args( $args, $defaults );
	}

	/**
	 * Get a single asset entry by handle.
	 *
	 * @param  string $handle Asset handle.
	 * @return array|null
	 */
	public function get_asset( string $handle ): ?array {
		return $this->entries[ $handle ] ?? null;
	}

	/**
	 * Remove an asset entry by handle.
	 *
	 * @param  string $handle Asset handle.
	 * @return void
	 */
	public function remove_asset( string $handle ): void {
		unset( $this->entries[ $handle ] );
	}

	/**
	 * Enqueue all registered assets that meet their enqueue condition.
	 *
	 * @return void
	 */
	public function enqueue_assets(): void {
		foreach ( $this->entries as $handle => $asset ) {
			$should_enqueue = $this->resolve_enqueue_condition( $asset['enqueue'] ?? true );

			if ( ! $should_enqueue ) {
				continue;
			}

			$src     = $asset['src'] ?? '';
			$deps    = $asset['deps'] ?? array();
			$version = $asset['version'] ?? OPTIX_CORE_VERSION;

			if ( ( $asset['type'] ?? 'style' ) === 'script' ) {
				$in_footer = $asset['in_footer'] ?? false;
				if ( ! wp_script_is( $handle, 'registered' ) ) {
					wp_register_script( $handle, $src, $deps, $version, $in_footer );
				}
				wp_enqueue_script( $handle );
			} else {
				$media = $asset['media'] ?? 'all';
				if ( ! wp_style_is( $handle, 'registered' ) ) {
					wp_register_style( $handle, $src, $deps, $version, $media );
				}
				wp_enqueue_style( $handle );
			}
		}
	}

	/**
	 * Register a style asset.
	 *
	 * @param  string $handle  Asset handle.
	 * @param  string $src     Source URL.
	 * @param  array  $deps    Dependencies.
	 * @param  string $version Version string.
	 * @param  string $media   CSS media type.
	 * @param  mixed  $enqueue Enqueue condition.
	 * @return void
	 */
	public function register_style( string $handle, string $src, array $deps = array(), string $version = '', string $media = 'all', $enqueue = true ): void {
		$this->entries[ $handle ] = array(
			'type'    => 'style',
			'src'     => $src,
			'deps'    => $deps,
			'version' => $version ? $version : OPTIX_CORE_VERSION,
			'media'   => $media,
			'enqueue' => $enqueue,
		);
	}

	/**
	 * Register a script asset.
	 *
	 * @param  string $handle    Asset handle.
	 * @param  string $src       Source URL.
	 * @param  array  $deps      Dependencies.
	 * @param  string $version   Version string.
	 * @param  bool   $in_footer Whether to load in footer.
	 * @param  mixed  $enqueue   Enqueue condition.
	 * @return void
	 */
	public function register_script( string $handle, string $src, array $deps = array(), string $version = '', bool $in_footer = false, $enqueue = true ): void {
		$this->entries[ $handle ] = array(
			'type'      => 'script',
			'src'       => $src,
			'deps'      => $deps,
			'version'   => $version ? $version : OPTIX_CORE_VERSION,
			'in_footer' => $in_footer,
			'enqueue'   => $enqueue,
		);
	}

	/**
	 * Resolve an enqueue condition to a boolean.
	 *
	 * @param  mixed $condition Callable, function name, or boolean.
	 * @return bool
	 */
	private function resolve_enqueue_condition( $condition ): bool {
		if ( is_callable( $condition ) ) {
			return (bool) $condition();
		}

		if ( is_string( $condition ) && function_exists( $condition ) ) {
			return (bool) $condition();
		}

		return (bool) $condition;
	}
}
