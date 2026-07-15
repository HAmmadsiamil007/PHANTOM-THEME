<?php
declare(strict_types=1);

namespace OptixCore\Cli;

use OptixCore\Demo_Importer;
use OptixCore\Options_Manager;
use OptixCore\Profile_Router;
use OptixCore\Registry\Demo_Registry;
use OptixCore\Registry\Preset_Registry;
use OptixCore\Registry\Settings_Registry;

defined('ABSPATH') || exit;

class Commands extends \WP_CLI_Command {

    private function get_registry(): Settings_Registry {
        $registry = Settings_Registry::get_instance();
        if (!$registry->count()) {
            $registry->register();
        }
        return $registry;
    }

    /**
     * Get a setting value with full metadata.
     *
     * ## OPTIONS
     *
     * <key>
     * : The setting key.
     *
     * ## EXAMPLES
     *
     *     wp optix setting get header_sticky
     */
    public function setting_get(array $args): void {
        $key = $args[0] ?? '';
        if (empty($key)) {
            \WP_CLI::error('Please provide a setting key.');
        }

        $registry = $this->get_registry();
        $schema = $registry->get_schema($key);

        if (null === $schema) {
            \WP_CLI::error("Unknown setting key: {$key}");
        }

        $current = Options_Manager::get($key, '__not_set__');
        $default = $schema['default'] ?? null;

        $data = [
            'key'     => $key,
            'value'   => '__not_set__' === $current ? $default : $current,
            'type'    => $schema['type'] ?? 'string',
            'section' => $schema['section'] ?? '',
            'default' => $default,
            'label'   => $schema['label'] ?? '',
        ];

        \WP_CLI\Utils\format_items('table', [$data], ['key', 'value', 'type', 'section', 'default', 'label']);
    }

    /**
     * List all settings, optionally filtered by section.
     *
     * ## OPTIONS
     *
     * [--section=<section>]
     * : Filter by section name.
     *
     * [--format=<format>]
     * : Output format. One of table, json, csv. Default: table.
     *
     * ## EXAMPLES
     *
     *     wp optix setting list
     *     wp optix setting list --section=header
     *     wp optix setting list --format=json
     */
    public function setting_list(array $args, array $assoc_args): void {
        $registry = $this->get_registry();
        $entries = $registry->get_all();
        $section_filter = $assoc_args['section'] ?? '';
        $format = $assoc_args['format'] ?? 'table';

        $rows = [];
        foreach ($entries as $key => $value) {
            $schema = $registry->get_schema($key);
            if ($section_filter && ($schema['section'] ?? '') !== $section_filter) {
                continue;
            }
            $rows[] = [
                'key'     => $key,
                'value'   => $value,
                'type'    => $schema['type'] ?? 'string',
                'section' => $schema['section'] ?? '',
                'default' => $schema['default'] ?? null,
                'label'   => $schema['label'] ?? '',
            ];
        }

        if (empty($rows)) {
            \WP_CLI::warning('No settings found' . ($section_filter ? " for section: {$section_filter}" : ''));
            return;
        }

        \WP_CLI\Utils\format_items($format, $rows, ['key', 'value', 'type', 'section', 'default', 'label']);
    }

    /**
     * Update a setting value.
     *
     * ## OPTIONS
     *
     * <key>
     * : The setting key.
     *
     * <value>
     * : The new value.
     *
     * ## EXAMPLES
     *
     *     wp optix setting update header_sticky 1
     *     wp optix setting update site_logo /new-logo.png
     */
    public function setting_update(array $args): void {
        $key = $args[0] ?? '';
        $value = $args[1] ?? '';

        if (empty($key)) {
            \WP_CLI::error('Please provide a setting key.');
        }

        $registry = $this->get_registry();
        if (!$registry->has($key)) {
            \WP_CLI::error("Unknown setting key: {$key}");
        }

        $schema = $registry->get_schema($key);
        $sanitized = $value;

        $callback = $schema['sanitize'] ?? null;
        if ($callback && is_callable($callback)) {
            $sanitized = $callback($value);
        }

        Options_Manager::set($key, $sanitized);
        \WP_CLI::success("Updated setting: {$key}");
    }

    /**
     * Reset a setting to its default value.
     *
     * ## OPTIONS
     *
     * <key>
     * : The setting key.
     *
     * ## EXAMPLES
     *
     *     wp optix setting delete header_sticky
     */
    public function setting_delete(array $args): void {
        $key = $args[0] ?? '';

        if (empty($key)) {
            \WP_CLI::error('Please provide a setting key.');
        }

        $registry = $this->get_registry();
        if (!$registry->has($key)) {
            \WP_CLI::error("Unknown setting key: {$key}");
        }

        delete_option('optix_' . $key);
        Settings_Registry::flush_cache();
        \WP_CLI::success("Reset setting to default: {$key}");
    }

    /**
     * Export all settings as JSON.
     *
     * ## OPTIONS
     *
     * [--file=<path>]
     * : Write to file instead of stdout.
     *
     * ## EXAMPLES
     *
     *     wp optix setting export
     *     wp optix setting export --file=/tmp/optix-settings.json
     */
    public function setting_export(array $args, array $assoc_args): void {
        $registry = $this->get_registry();
        $entries = $registry->get_all();
        $json = json_encode($entries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $file = $assoc_args['file'] ?? '';
        if ($file) {
            $written = file_put_contents($file, $json);
            if (false === $written) {
                \WP_CLI::error("Failed to write to file: {$file}");
            }
            \WP_CLI::success("Settings exported to: {$file}");
        } else {
            \WP_CLI::line($json);
        }
    }

    /**
     * Import settings from a JSON file.
     *
     * ## OPTIONS
     *
     * <file>
     * : Path to JSON file.
     *
     * ## EXAMPLES
     *
     *     wp optix setting import /tmp/optix-settings.json
     */
    public function setting_import(array $args): void {
        $file = $args[0] ?? '';

        if (empty($file) || !file_exists($file) || !is_readable($file)) {
            \WP_CLI::error('File not found or not readable.');
        }

        $contents = file_get_contents($file);
        $data = json_decode($contents, true);

        if (!is_array($data)) {
            \WP_CLI::error('Invalid JSON file.');
        }

        $registry = $this->get_registry();
        $imported = 0;
        $skipped = 0;

        foreach ($data as $key => $value) {
            if (!$registry->has($key)) {
                $skipped++;
                continue;
            }
            Options_Manager::set($key, $value);
            $imported++;
        }

        Settings_Registry::flush_cache();

        if ($skipped > 0) {
            \WP_CLI::warning("Imported {$imported} settings, skipped {$skipped} unknown keys.");
        } else {
            \WP_CLI::success("Imported {$imported} settings.");
        }
    }

    /**
     * Get the active profile name and path.
     *
     * ## EXAMPLES
     *
     *     wp optix profile get
     */
    public function profile_get(): void {
        $router = Profile_Router::get_instance();
        $profile = $router->get_active_profile();
        $path = $router->get_profile_path();

        $data = [
            'name' => $profile,
            'path' => $path,
        ];

        \WP_CLI\Utils\format_items('table', [$data], ['name', 'path']);
    }

    /**
     * Set the active profile.
     *
     * ## OPTIONS
     *
     * <name>
     * : Profile name. Must be an existing profile directory.
     *
     * ## EXAMPLES
     *
     *     wp optix profile set default
     *     wp optix profile set custom-profile
     */
    public function profile_set(array $args): void {
        $name = $args[0] ?? '';

        if (empty($name)) {
            \WP_CLI::error('Please provide a profile name.');
        }

        $name = sanitize_file_name($name);
        $profile_dir = get_template_directory() . '/profiles/' . $name;

        if (!is_dir($profile_dir)) {
            \WP_CLI::error("Profile directory does not exist: {$profile_dir}");
        }

        update_option('optix_active_profile', $name);
        \WP_CLI::success("Active profile set to: {$name}");
    }

    /**
     * Flush all Optix caches.
     *
     * Deletes optix_* transients and flushes the registry runtime cache.
     *
     * ## EXAMPLES
     *
     *     wp optix cache flush
     */
    public function cache_flush(): void {
        global $wpdb;

        $deleted = $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options}
                 WHERE option_name LIKE %s
                  AND (option_name LIKE %s OR option_name LIKE %s)",
                $wpdb->esc_like('_transient_') . '%',
                '%' . $wpdb->esc_like('optix_') . '%',
                '%' . $wpdb->esc_like('_optix_') . '%'
            )
        );

        Settings_Registry::flush_cache();

        \WP_CLI::success("Cache flushed. Deleted {$deleted} transients.");
    }

    /**
     * Show cache status.
     *
     * Displays count of cached optix options in the database.
     *
     * ## EXAMPLES
     *
     *     wp optix cache status
     */
    public function cache_status(): void {
        global $wpdb;

        $total = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->options}
                 WHERE option_name LIKE %s",
                $wpdb->esc_like('optix_') . '%'
            )
        );

        $with_defaults = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->options}
                 WHERE option_name LIKE %s
                  AND option_name NOT LIKE %s",
                $wpdb->esc_like('optix_') . '%',
                '%' . $wpdb->esc_like('_transient_') . '%'
            )
        );

        $transients = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->options}
                 WHERE (option_name LIKE %s OR option_name LIKE %s)
                  AND (option_value LIKE %s OR option_value LIKE %s)",
                $wpdb->esc_like('_transient_optix_') . '%',
                $wpdb->esc_like('_transient__optix_') . '%',
                '%optix%',
                '%optix%'
            )
        );

        $data = [
            ['metric' => 'Total optix_* options', 'count' => (int) $total],
            ['metric' => 'Settings stored',       'count' => (int) $with_defaults],
            ['metric' => 'Optix-related transients', 'count' => (int) $transients],
        ];

        \WP_CLI\Utils\format_items('table', $data, ['metric', 'count']);
    }

    /**
     * Print the full settings schema as JSON.
     *
     * Outputs all registered settings with their full metadata.
     *
     * ## EXAMPLES
     *
     *     wp optix schema
     */
    public function schema(): void {
        $registry = $this->get_registry();
        $entries = $registry->get_all();

        $schema = [];

        foreach ($registry->get_entries() as $key => $entry) {
            $schema[$key] = [
                'type'    => $entry['type'] ?? 'string',
                'section' => $entry['section'] ?? '',
                'default' => $entry['default'] ?? null,
                'label'   => $entry['label'] ?? '',
            ];
            if (isset($entry['options'])) {
                $schema[$key]['options'] = $entry['options'];
            }
            if (isset($entry['css_property'])) {
                $schema[$key]['css_property'] = $entry['css_property'];
            }
            if (isset($entry['css_selector'])) {
                $schema[$key]['css_selector'] = $entry['css_selector'];
            }
            if (isset($entry['autoload'])) {
                $schema[$key]['autoload'] = $entry['autoload'];
            }
        }

        $output = json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        \WP_CLI::line($output);
    }

    /**
     * Run pending migrations.
     *
     * Calls Options_Manager::migrate_from_theme() if not yet run.
     *
     * ## EXAMPLES
     *
     *     wp optix migrate
     */
    public function migrate(): void {
        $completed = get_option('optix_migration_complete', false);

        if (false !== $completed) {
            $date = date('Y-m-d H:i:s', (int) $completed);
            \WP_CLI::warning("Migration already completed at: {$date}");
            return;
        }

        Options_Manager::migrate_from_theme();
        \WP_CLI::success('Migration completed successfully.');
    }

    /**
     * List all available profiles.
     *
     * ## EXAMPLES
     *
     *     wp optix profile list
     *     wp optix profile list --format=json
     */
    public function profile_list(array $args, array $assoc_args): void {
        $theme_dir = get_template_directory() . '/profiles';
        $active    = Profile_Router::get_instance()->get_active_profile();
        $profiles  = [];

        if (!is_dir($theme_dir)) {
            \WP_CLI::error("Profiles directory not found: {$theme_dir}");
        }

        $items = scandir($theme_dir);
        sort($items);

        foreach ($items as $entry) {
            if ($entry[0] === '.') {
                continue;
            }
            $path = $theme_dir . '/' . $entry;
            if (!is_dir($path)) {
                continue;
            }
            $profiles[] = [
                'name'   => $entry,
                'active' => $entry === $active ? 'yes' : 'no',
                'css'    => file_exists($path . '/assets/css/style.css') ? 'yes' : 'no',
                'js'     => file_exists($path . '/assets/js/script.js') ? 'yes' : 'no',
            ];
        }

        if (empty($profiles)) {
            \WP_CLI::warning('No profiles found.');
            return;
        }

        $format = $assoc_args['format'] ?? 'table';
        \WP_CLI\Utils\format_items($format, $profiles, ['name', 'active', 'css', 'js']);
    }

    /**
     * Save current settings as a preset.
     *
     * ## OPTIONS
     *
     * <name>
     * : Preset name.
     *
     * ## EXAMPLES
     *
     *     wp optix preset save my-preset
     */
    public function preset_save(array $args): void {
        $name = $args[0] ?? '';

        if (empty($name)) {
            \WP_CLI::error('Please provide a preset name.');
        }

        $registry = Settings_Registry::get_instance();
        $data     = [];
        foreach ($registry->get_entries() as $key => $entry) {
            $data[$key] = $registry->get($key);
        }

        $saved = Preset_Registry::get_instance()->save($name, $data);

        if (!$saved) {
            \WP_CLI::error("Failed to save preset: {$name}");
        }

        \WP_CLI::success("Preset saved: {$name} (" . count($data) . ' settings)');
    }

    /**
     * Load settings from a preset.
     *
     * ## OPTIONS
     *
     * <name>
     * : Preset name to load.
     *
     * ## EXAMPLES
     *
     *     wp optix preset load my-preset
     */
    public function preset_load(array $args): void {
        $name = $args[0] ?? '';

        if (empty($name)) {
            \WP_CLI::error('Please provide a preset name.');
        }

        $preset = Preset_Registry::get_instance();
        $data   = $preset->load($name);

        if ($data === null) {
            \WP_CLI::error("Preset not found: {$name}");
        }

        $result = $preset->apply($name);

        if (is_wp_error($result)) {
            \WP_CLI::error("Failed to apply preset: " . $result->get_error_message());
        }

        \WP_CLI::success("Preset loaded: {$name} (" . count($data) . ' settings applied)');
    }

    /**
     * Import demo content.
     *
     * ## OPTIONS
     *
     * <package>
     * : Demo package ID (default, minimal).
     *
     * ## EXAMPLES
     *
     *     wp optix demo import default
     *     wp optix demo import minimal
     */
    public function demo_import(array $args): void {
        $package_id = $args[0] ?? '';

        if (empty($package_id)) {
            \WP_CLI::error('Please provide a demo package ID.');
        }

        $demo_registry = Demo_Registry::get_instance();
        $package       = $demo_registry->get_package($package_id);

        if ($package === null) {
            $available = implode(', ', array_keys($demo_registry->list_packages()));
            \WP_CLI::error("Unknown demo package: {$package_id}. Available: {$available}");
        }

        $importer = Demo_Importer::get_instance();
        $result   = $importer->import_package($package_id);

        if (empty($result['success'])) {
            \WP_CLI::error($result['error'] ?? 'Demo import failed with unknown error.');
        }

        $log    = $result['log'] ?? [];
        $failed = 0;

        foreach ($log as $entry) {
            $status = $entry['success'] ? 'SUCCESS' : 'FAILED';
            $label  = $entry['step'] ?? 'unknown';
            \WP_CLI::line("  {$status}: {$label}");
            if (!$entry['success']) {
                $failed++;
                if (!empty($entry['error'])) {
                    \WP_CLI::line("         reason: {$entry['error']}");
                }
            }
        }

        if ($failed > 0) {
            \WP_CLI::warning("Demo import completed with {$failed} failed step(s).");
            return;
        }

        \WP_CLI::success("Demo package '{$package_id}' imported successfully.");
    }

    /**
     * Show system status overview.
     *
     * Displays plugin version, active profile, registry stats, and WordPress info.
     *
     * ## EXAMPLES
     *
     *     wp optix status
     */
    public function status(): void {
        $registry  = Settings_Registry::get_instance();
        $router    = Profile_Router::get_instance();
        $preset    = Preset_Registry::get_instance();

        $data = [
            ['key' => 'Optix Version',        'value' => defined('OPTIX_CORE_VERSION') ? OPTIX_CORE_VERSION : 'unknown'],
            ['key' => 'Active Profile',        'value' => $router->get_active_profile()],
            ['key' => 'Profile Path',          'value' => $router->get_profile_path()],
            ['key' => 'Settings Registered',   'value' => (string) $registry->count()],
            ['key' => 'Presets Available',     'value' => (string) count($preset->list())],
            ['key' => 'WordPress Version',     'value' => $GLOBALS['wp_version'] ?? 'unknown'],
            ['key' => 'PHP Version',           'value' => PHP_VERSION],
            ['key' => 'Demo Importer Ready',   'value' => class_exists(Demo_Importer::class) ? 'yes' : 'no'],
            ['key' => 'WooCommerce Active',    'value' => class_exists('WooCommerce') ? 'yes' : 'no'],
        ];

        \WP_CLI\Utils\format_items('table', $data, ['key', 'value']);
    }

    /**
     * Run diagnostic checks.
     *
     * Verifies profile structure, template files, required plugins, and settings integrity.
     *
     * ## EXAMPLES
     *
     *     wp optix doctor
     */
    public function doctor(): void {
        $checks   = 0;
        $passed   = 0;
        $warnings = 0;
        $errors   = 0;

        \WP_CLI::line('Running Optix diagnostics...');
        \WP_CLI::line('');

        // 1. Plugin version constant.
        $checks++;
        if (defined('OPTIX_CORE_VERSION')) {
            \WP_CLI::line("  [OK] OPTIX_CORE_VERSION: " . OPTIX_CORE_VERSION);
            $passed++;
        } else {
            \WP_CLI::line("  [FAIL] OPTIX_CORE_VERSION not defined.");
            $errors++;
        }

        // 2. Settings registry loaded.
        $checks++;
        $registry = Settings_Registry::get_instance();
        $count    = $registry->count();
        if ($count > 0) {
            \WP_CLI::line("  [OK] Settings registered: {$count}");
            $passed++;
        } else {
            \WP_CLI::line("  [WARN] No settings registered.");
            $warnings++;
        }

        // 3. Active profile exists.
        $checks++;
        $router    = Profile_Router::get_instance();
        $profile   = $router->get_active_profile();
        $prof_path = $router->get_profile_path();
        if (!empty($profile) && !empty($prof_path)) {
            \WP_CLI::line("  [OK] Active profile: {$profile} ({$prof_path})");
            $passed++;
        } else {
            \WP_CLI::line("  [FAIL] No active profile or path.");
            $errors++;
        }

        // 4. Profile directory is readable.
        $checks++;
        if (is_dir($prof_path)) {
            \WP_CLI::line("  [OK] Profile directory readable.");
            $passed++;
        } else {
            \WP_CLI::line("  [WARN] Profile directory not readable: {$prof_path}");
            $warnings++;
        }

        // 5. Template cascade fallback.
        $checks++;
        $tmpl = $router->route_template(get_template_directory() . '/index.php');
        if (!empty($tmpl)) {
            \WP_CLI::line("  [OK] Template cascade resolved: " . basename($tmpl));
            $passed++;
        } else {
            \WP_CLI::line("  [WARN] Template cascade returned empty path.");
            $warnings++;
        }

        // 6. Options API accessible.
        $checks++;
        $test_val = get_option('optix_active_profile', '__not_set__');
        if ('__not_set__' !== $test_val) {
            \WP_CLI::line("  [OK] Options API accessible (optix_active_profile).");
            $passed++;
        } else {
            \WP_CLI::line("  [WARN] Options API read returned unexpected value.");
            $warnings++;
        }

        // 7. Presets directory.
        $checks++;
        $presets = Preset_Registry::get_instance()->list();
        \WP_CLI::line("  [OK] Presets available: " . count($presets));
        $passed++;

        // Summary.
        \WP_CLI::line('');
        \WP_CLI::line("Results: {$checks} checks, {$passed} passed, {$warnings} warnings, {$errors} errors.");

        if ($errors > 0) {
            \WP_CLI::error('Some diagnostic checks failed.');
        } elseif ($warnings > 0) {
            \WP_CLI::warning('All checks passed with warnings.');
        } else {
            \WP_CLI::success('All diagnostic checks passed.');
        }
    }
}
