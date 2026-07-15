<?php
declare(strict_types=1);

namespace OptixCore\Engine;

defined('ABSPATH') || exit;

class Cache {

	private static ?Cache $instance = null;
	private static array $static_cache = [];
	private string $group = 'optix';
	private array $stats = ['static_count' => 0, 'object_cache_hits' => 0, 'total_keys' => 0];

	private function __construct() {}

	public static function get_instance(): self {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action('update_option', [$this, 'on_option_update'], 10, 3);
		add_action('added_option', [$this, 'on_option_added'], 10, 2);
	}

	public function on_option_update(string $option, mixed $old_value, mixed $value): void {
		if (str_starts_with($option, 'optix_')) {
			$key = substr($option, 6);
			$this->delete($key);
		}
	}

	public function on_option_added(string $option, mixed $value): void {
		if (str_starts_with($option, 'optix_')) {
			$key = substr($option, 6);
			$this->delete($key);
		}
	}

	public function get(string $key, mixed $default = null): mixed {
		if (array_key_exists($key, self::$static_cache)) {
			$this->stats['static_count']++;
			return self::$static_cache[$key];
		}

		$cached = wp_cache_get($key, $this->group);
		if (false !== $cached) {
			$this->stats['object_cache_hits']++;
			self::$static_cache[$key] = $cached;
			return $cached;
		}

		$value = get_option('optix_cache_' . $key, '__not_set__');
		if ('__not_set__' !== $value) {
			self::$static_cache[$key] = $value;
			wp_cache_set($key, $value, $this->group);
			return $value;
		}

		return $default;
	}

	public function set(string $key, mixed $value, int $expires = 0): bool {
		self::$static_cache[$key] = $value;
		wp_cache_set($key, $value, $this->group, $expires);
		return update_option('optix_cache_' . $key, $value);
	}

	public function delete(string $key): bool {
		unset(self::$static_cache[$key]);
		wp_cache_delete($key, $this->group);
		return delete_option('optix_cache_' . $key);
	}

	public function flush_group(): bool {
		self::$static_cache = [];
		wp_cache_flush();
		$this->stats = ['static_count' => 0, 'object_cache_hits' => 0, 'total_keys' => 0];
		return true;
	}

	public function flush_all(): bool {
		global $wpdb;
		self::$static_cache = [];
		wp_cache_flush();
		$count = $wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
				$wpdb->esc_like('optix_cache_') . '%'
			)
		);
		$this->stats = ['static_count' => 0, 'object_cache_hits' => 0, 'total_keys' => 0];
		return false !== $count;
	}

	public function remember(string $key, callable $callback, int $expires = 0): mixed {
		$value = $this->get($key, '__not_set__');
		if ('__not_set__' !== $value) {
			return $value;
		}
		$value = $callback();
		$this->set($key, $value, $expires);
		return $value;
	}

	public function stats(): array {
		$this->stats['total_keys'] = count(self::$static_cache);
		return $this->stats;
	}
}
