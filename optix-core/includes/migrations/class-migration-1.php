<?php
declare(strict_types=1);

namespace OptixCore\Migrations;

defined('ABSPATH') || exit;

class Migration_1 {

	public static function up(): bool {
		if (method_exists('OptixCore\Options_Manager', 'migrate_from_theme')) {
			\OptixCore\Options_Manager::migrate_from_theme();
		}
		return true;
	}
}
