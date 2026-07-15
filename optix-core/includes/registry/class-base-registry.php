<?php
/**
 * Abstract base for all Optix registries — singleton, define-entries pattern.
 *
 * @package optix-core
 */

declare(strict_types=1);

namespace OptixCore\Registry;

defined( 'ABSPATH' ) || exit;

/**
 * Abstract base registry providing common storage, caching, and retrieval logic.
 */
abstract class Base_Registry {

	/**
	 * Singleton instances keyed by class name.
	 *
	 * @var array
	 */
	private static array $instances = array();

	/**
	 * Runtime cache for resolved values.
	 *
	 * @var array
	 */
	private static array $runtime_cache = array();

	/**
	 * Defaults cache keyed by class name.
	 *
	 * @var array
	 */
	private static array $defaults_cache = array();

	/**
	 * Registry entries defined by subclass.
	 *
	 * @var array
	 */
	protected array $entries = array();

	/**
	 * Whether define_entries() has been called.
	 *
	 * @var bool
	 */
	protected bool $registered = false;

	// phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.Found
	/**
	 * Private constructor — singleton.
	 */
	private function __construct() {}

	/**
	 * Private clone — singleton.
	 */
	private function __clone() {}

	/**
	 * Prevent unserialization.
	 *
	 * @return never
	 */
	public function __wakeup(): void {
		throw new \Exception( 'Cannot unserialize singleton' );
	}
	// phpcs:enable

	/**
	 * Get the singleton instance.
	 *
	 * @return static
	 */
	final public static function get_instance(): static {
		$class = static::class;
		if ( ! isset( self::$instances[ $class ] ) ) {
			self::$instances[ $class ] = new static();
		}
		return self::$instances[ $class ];
	}

	/**
	 * Register entries from define_entries().
	 *
	 * @return void
	 */
	final public function register(): void {
		if ( $this->registered ) {
			return;
		}
		$this->entries    = $this->define_entries();
		$this->registered = true;
	}

	/**
	 * Define entries array. Subclasses must implement.
	 *
	 * @return array
	 */
	abstract protected function define_entries(): array;

	/**
	 * Get a single entry value.
	 *
	 * @param  string $key Entry key.
	 * @return mixed|null
	 */
	public function get( string $key ): mixed {
		if ( isset( self::$runtime_cache[ $key ] ) ) {
			return self::$runtime_cache[ $key ];
		}
		if ( ! isset( $this->entries[ $key ] ) ) {
			return null;
		}
		$value                       = $this->resolve_value( $key );
		self::$runtime_cache[ $key ] = $value;
		return $value;
	}

	/**
	 * Check if entry exists in registry.
	 *
	 * @param  string $key Entry key.
	 * @return bool
	 */
	public function has( string $key ): bool {
		return isset( $this->entries[ $key ] );
	}

	/**
	 * Bulk retrieve multiple entries in one DB query.
	 *
	 * @param  array $keys Array of entry keys.
	 * @return array
	 */
	public function bulk_get( array $keys ): array {
		$result       = array();
		$db_keys      = array();
		$option_names = array();

		foreach ( $keys as $i => $key ) {
			if ( isset( self::$runtime_cache[ $key ] ) ) {
				$result[ $key ] = self::$runtime_cache[ $key ];
				unset( $keys[ $i ] );
			} elseif ( isset( $this->entries[ $key ] ) ) {
				$option_names[]  = 'optix_' . $key;
				$db_keys[ $key ] = 'optix_' . $key;
			}
		}

		if ( ! empty( $db_keys ) ) {
			global $wpdb;
			$placeholders = implode( ',', array_fill( 0, count( $option_names ), '%s' ) );
			$db_results   = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT option_name, option_value FROM {$wpdb->options}
                     WHERE option_name IN ($placeholders)",
					$option_names
				)
			);
			$found        = array();
			foreach ( $db_results as $row ) {
				$found[ $row->option_name ] = maybe_unserialize( $row->option_value );
			}
			foreach ( $db_keys as $key => $opt_name ) {
				if ( array_key_exists( $opt_name, $found ) ) {
					$result[ $key ]              = $found[ $opt_name ];
					self::$runtime_cache[ $key ] = $found[ $opt_name ];
				} else {
					$default                     = $this->entries[ $key ]['default'] ?? null;
					$result[ $key ]              = $default;
					self::$runtime_cache[ $key ] = $default;
				}
			}
		}

		return $result;
	}

	/**
	 * Get all entry values.
	 *
	 * @return array
	 */
	public function get_all(): array {
		$all = array();
		foreach ( $this->entries as $key => $entry ) {
			$all[ $key ] = $this->get( $key );
		}
		return $all;
	}

	/**
	 * Get all entry defaults.
	 *
	 * @return array
	 */
	public function get_defaults(): array {
		$class = static::class;
		if ( ! isset( self::$defaults_cache[ $class ] ) ) {
			$defaults = array();
			foreach ( $this->entries as $key => $entry ) {
				$defaults[ $key ] = $entry['default'] ?? null;
			}
			self::$defaults_cache[ $class ] = $defaults;
		}
		return self::$defaults_cache[ $class ];
	}

	/**
	 * Get all registered entries (triggers registration).
	 *
	 * @return array
	 */
	public function get_entries(): array {
		$this->register();
		return $this->entries;
	}

	/**
	 * Get schema (entry definition) for a single key.
	 *
	 * @param  string $key Entry key.
	 * @return array|null
	 */
	public function get_schema( string $key ): ?array {
		return $this->entries[ $key ] ?? null;
	}

	/**
	 * Validate a value against the entry's validation callback.
	 *
	 * @param  string $key   Entry key.
	 * @param  mixed  $value Value to validate.
	 * @return bool|\WP_Error
	 */
	public function validate( string $key, mixed $value ): bool|\WP_Error {
		if ( ! isset( $this->entries[ $key ] ) ) {
			return new \WP_Error( 'unknown_key', __( 'Registry key not found.', 'optix-core' ) );
		}
		$entry = $this->entries[ $key ];
		if ( isset( $entry['validate'] ) ) {
			$result = $entry['validate']( $value );
			if ( true !== $result ) {
				return new \WP_Error(
					'validation_failed',
					sprintf( __( 'Value for "%s" failed validation.', 'optix-core' ), $key )
				);
			}
		}
		return true;
	}

	/**
	 * Count registered entries.
	 *
	 * @return int
	 */
	public function count(): int {
		return count( $this->entries );
	}

	/**
	 * Set a single entry value (validates, sanitizes, persists).
	 *
	 * @param  string $key   Entry key.
	 * @param  mixed  $value Value to set.
	 * @return bool|\WP_Error
	 */
	public function set( string $key, mixed $value ): bool|\WP_Error {
		if ( ! isset( $this->entries[ $key ] ) ) {
			return new \WP_Error( 'unknown_key', __( 'Registry key not found.', 'optix-core' ) );
		}
		$valid = $this->validate( $key, $value );
		if ( is_wp_error( $valid ) ) {
			return $valid;
		}
		$sanitized = $this->sanitize( $key, $value );
		$updated   = update_option( 'optix_' . $key, $sanitized );
		if ( $updated ) {
			self::$runtime_cache[ $key ] = $sanitized;
		}
		if ( $updated && ! empty( $this->entries[ $key ]['acf_sync'] ) && function_exists( 'update_field' ) ) {
			update_field( $key, $sanitized, 'option' );
		}
		return $updated;
	}

	/**
	 * Flush runtime cache.
	 *
	 * @return void
	 */
	public static function flush_cache(): void {
		self::$runtime_cache = array();
	}

	/**
	 * Sanitize a value per the entry's sanitize callback and type.
	 *
	 * @param  string $key   Entry key.
	 * @param  mixed  $value Raw value.
	 * @return mixed
	 */
	protected function sanitize( string $key, mixed $value ): mixed {
		$entry    = $this->entries[ $key ] ?? array();
		$type     = $entry['type'] ?? 'string';
		$callback = $entry['sanitize'] ?? null;
		if ( $callback ) {
			if ( is_callable( $callback ) ) {
				return $callback( $value );
			}
			if ( function_exists( $callback ) ) {
				return $callback( $value );
			}
		}
		if ( is_array( $value ) && in_array( $type, array( 'array', 'repeater' ), true ) ) {
			return $this->sanitize_array_recursive( $value );
		}
		return $value;
	}

	/**
	 * Recursively sanitize array values.
	 *
	 * @param  array $values Array to sanitize.
	 * @return array
	 */
	private function sanitize_array_recursive( array $values ): array {
		$result = array();
		foreach ( $values as $key => $value ) {
			if ( is_array( $value ) ) {
				$result[ $key ] = $this->sanitize_array_recursive( $value );
			} elseif ( is_string( $value ) ) {
				$result[ $key ] = sanitize_text_field( $value );
			} else {
				$result[ $key ] = $value;
			}
		}
		return $result;
	}

	/**
	 * Resolve value from DB option, falling back to default.
	 *
	 * @param  string $key Entry key.
	 * @return mixed
	 */
	protected function resolve_value( string $key ): mixed {
		$entry  = $this->entries[ $key ];
		$stored = get_option( 'optix_' . $key );
		if ( false === $stored ) {
			return $entry['default'] ?? null;
		}
		return $stored;
	}

	/**
	 * Get default value for a single key.
	 *
	 * @param  string $key Entry key.
	 * @return mixed
	 */
	protected function get_default_for( string $key ): mixed {
		$defaults = $this->get_defaults();
		return $defaults[ $key ] ?? null;
	}
}
