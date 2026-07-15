<?php
declare(strict_types=1);

namespace OptixCore\Engine;

defined('ABSPATH') || exit;

class Schema_Migrator {

	private static ?Schema_Migrator $instance = null;
	private int $current_version = 0;
	private int $code_version;
	private array $migrations = [];

	public static function get_instance(): self {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->code_version = (int) (defined('OPTIX_CORE_SCHEMA_VERSION') ? OPTIX_CORE_SCHEMA_VERSION : 1);
	}

	public function init(): void {
		add_action('admin_init', [$this, 'check_migration']);
	}

	public function check_migration(): void {
		if (!current_user_can('manage_options')) {
			return;
		}
		if ($this->needs_migration()) {
			$this->run();
		}
	}

	public function get_current_version(): int {
		if (0 === $this->current_version) {
			$stored = get_option('optix_schema_version', 0);
			$this->current_version = is_numeric($stored) ? (int) $stored : 0;
		}
		return $this->current_version;
	}

	public function get_code_version(): int {
		return $this->code_version;
	}

	public function needs_migration(): bool {
		return $this->get_current_version() < $this->code_version;
	}

	public function add_migration(int $version, string $description, callable $callback): void {
		$this->migrations[$version] = [
			'description' => $description,
			'callback' => $callback,
		];
	}

	public function run(): array {
		$applied = [];
		$errors = [];
		$current = $this->get_current_version();

		for ($v = $current + 1; $v <= $this->code_version; $v++) {
			if (!isset($this->migrations[$v])) {
				$errors[] = "No migration registered for version {$v}";
				continue;
			}
			try {
				$result = call_user_func($this->migrations[$v]['callback']);
				if (false !== $result) {
					update_option('optix_schema_version', $v);
					$this->current_version = $v;
					$applied[] = "v{$v}: {$this->migrations[$v]['description']}";
				} else {
					$errors[] = "v{$v}: {$this->migrations[$v]['description']} returned false";
				}
			} catch (\Throwable $e) {
				$errors[] = "v{$v}: {$e->getMessage()}";
			}
		}

		return [
			'applied' => $applied,
			'errors' => $errors,
		];
	}
}
