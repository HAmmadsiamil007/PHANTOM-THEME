<?php
declare(strict_types=1);

namespace OptixCore\Api;

use OptixCore\Engine\Cache;
use OptixCore\Options_Manager;
use OptixCore\Profile_Router;
use OptixCore\Registry\Preset_Registry;
use OptixCore\Registry\Settings_Registry;

defined('ABSPATH') || exit;

class Rest_Controller extends \WP_REST_Controller {

    private static ?Rest_Controller $instance = null;
    protected $namespace = 'optix/v1';

    final public static function get_instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init(): void {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes(): void {
        register_rest_route($this->namespace, '/settings', [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this, 'get_settings'],
                'permission_callback' => [$this, 'permission_check'],
                'args'                => $this->get_settings_args(),
            ],
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'update_settings'],
                'permission_callback' => [$this, 'permission_check'],
                'args'                => $this->get_bulk_update_args(),
            ],
        ]);

        register_rest_route($this->namespace, '/settings/batch', [
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'update_settings'],
                'permission_callback' => [$this, 'permission_check'],
                'args'                => $this->get_bulk_update_args(),
            ],
        ]);

        register_rest_route($this->namespace, '/settings/(?P<key>[\w-]+)', [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this, 'get_setting'],
                'permission_callback' => [$this, 'permission_check'],
                'args'                => $this->get_single_args(),
            ],
            [
                'methods'             => \WP_REST_Server::EDITABLE,
                'callback'            => [$this, 'update_setting'],
                'permission_callback' => [$this, 'permission_check'],
                'args'                => $this->get_single_update_args(),
            ],
            [
                'methods'             => \WP_REST_Server::DELETABLE,
                'callback'            => [$this, 'delete_setting'],
                'permission_callback' => [$this, 'permission_check'],
                'args'                => $this->get_single_args(),
            ],
        ]);

        register_rest_route($this->namespace, '/schema', [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this, 'get_schema'],
                'permission_callback' => [$this, 'permission_check'],
            ],
        ]);

        register_rest_route($this->namespace, '/profile', [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this, 'get_profile'],
                'permission_callback' => [$this, 'permission_check'],
            ],
            [
                'methods'             => \WP_REST_Server::EDITABLE,
                'callback'            => [$this, 'update_profile'],
                'permission_callback' => [$this, 'permission_check'],
                'args'                => $this->get_profile_args(),
            ],
        ]);

        register_rest_route($this->namespace, '/export', [
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'export_settings'],
                'permission_callback' => [$this, 'permission_check'],
            ],
        ]);

        register_rest_route($this->namespace, '/import', [
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'import_settings'],
                'permission_callback' => [$this, 'permission_check'],
                'args'                => $this->get_import_args(),
            ],
        ]);

        register_rest_route($this->namespace, '/profiles', [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this, 'get_profiles'],
                'permission_callback' => [$this, 'permission_check'],
            ],
        ]);

        register_rest_route($this->namespace, '/presets', [
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'save_preset'],
                'permission_callback' => [$this, 'permission_check'],
                'args'                => $this->get_preset_save_args(),
            ],
        ]);

        register_rest_route($this->namespace, '/presets/(?P<name>[\w-]+)', [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this, 'load_preset'],
                'permission_callback' => [$this, 'permission_check'],
                'args'                => $this->get_preset_load_args(),
            ],
        ]);

        register_rest_route($this->namespace, '/cache/flush', [
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'flush_cache'],
                'permission_callback' => [$this, 'permission_check'],
            ],
        ]);
    }

    public function permission_check(): bool {
        return current_user_can('manage_options');
    }

    public function get_settings(\WP_REST_Request $request): \WP_REST_Response {
        $registry = Settings_Registry::get_instance();
        $entries  = $registry->get_entries();

        $section  = $request->get_param('section');
        $per_page = $request->get_param('per_page');
        $page     = $request->get_param('page');

        if (!empty($section)) {
            $entries = array_filter($entries, function ($entry) use ($section) {
                return ($entry['section'] ?? '') === $section;
            });
        }

        $total = count($entries);
        $offset = ($page - 1) * $per_page;
        $entries = array_slice($entries, $offset, $per_page, true);

        $items = [];
        foreach ($entries as $key => $entry) {
            $items[] = $this->format_entry($key, $entry);
        }

        $response = new \WP_REST_Response($items, 200);
        $response->header('X-WP-Total', $total);
        $response->header('X-WP-TotalPages', (int) ceil($total / $per_page));

        return $response;
    }

    public function get_setting(\WP_REST_Request $request): \WP_REST_Response {
        $key   = $request->get_param('key');
        $entry = $this->get_entry_or_error($key);

        if (is_wp_error($entry)) {
            return new \WP_REST_Response(array(
                'code'    => $entry->get_error_code(),
                'message' => $entry->get_error_message(),
            ), 404);
        }

        return new \WP_REST_Response($this->format_entry($key, $entry), 200);
    }

    public function update_settings(\WP_REST_Request $request): \WP_REST_Response {
        $settings = $request->get_param('settings');
        if (!is_array($settings) || empty($settings)) {
            return new \WP_REST_Response([
                'code'    => 'invalid_settings',
                'message' => __('The settings parameter must be a non-empty object.', 'optix-core'),
            ], 400);
        }

        $registry   = Settings_Registry::get_instance();
        $updated    = [];
        $errors     = [];

        foreach ($settings as $key => $value) {
            if (!$registry->has($key)) {
                $errors[] = sprintf(__('Unknown setting key: %s', 'optix-core'), $key);
                continue;
            }
            $registry->set($key, $value);
            $updated[$key] = $this->get_current_value($key);
        }

        return new \WP_REST_Response([
            'updated' => $updated,
            'errors'  => $errors,
        ], empty($errors) ? 200 : 207);
    }

    public function update_setting(\WP_REST_Request $request): \WP_REST_Response {
        $key   = $request->get_param('key');
        $entry = $this->get_entry_or_error($key);
        if (is_wp_error($entry)) {
            return new \WP_REST_Response($entry, 404);
        }

        $value = $request->get_param('value');
        Settings_Registry::get_instance()->set($key, $value);

        return new \WP_REST_Response($this->format_entry($key, $entry, true), 200);
    }

    public function delete_setting(\WP_REST_Request $request): \WP_REST_Response {
        $key   = $request->get_param('key');
        $entry = $this->get_entry_or_error($key);
        if (is_wp_error($entry)) {
            return new \WP_REST_Response($entry, 404);
        }

        $default = $entry['default'] ?? null;
        delete_option('optix_' . $key);
        Options_Manager::set($key, $default);

        return new \WP_REST_Response([
            'key'     => $key,
            'default' => $default,
            'reset'   => true,
        ], 200);
    }

    public function get_schema(): \WP_REST_Response {
        $registry = Settings_Registry::get_instance();
        $entries  = $registry->get_entries();

        $schema = [];
        foreach ($entries as $key => $entry) {
            $schema[$key] = $this->clean_entry($entry);
        }

        return new \WP_REST_Response($schema, 200);
    }

    public function get_profile(): \WP_REST_Response {
        $profile = get_option('optix_active_profile', 'default');
        if (!is_string($profile) || empty($profile)) {
            $profile = 'default';
        }

        return new \WP_REST_Response([
            'profile' => $profile,
        ], 200);
    }

    public function update_profile(\WP_REST_Request $request): \WP_REST_Response {
        $profile = $request->get_param('profile');

        if (!is_string($profile) || empty($profile)) {
            return new \WP_REST_Response([
                'code'    => 'invalid_profile',
                'message' => __('Profile name must be a non-empty string.', 'optix-core'),
            ], 400);
        }

        $profile = sanitize_file_name($profile);
        $path    = get_template_directory() . '/profiles/' . $profile;

        if (!is_dir($path)) {
            return new \WP_REST_Response([
                'code'    => 'profile_not_found',
                'message' => sprintf(__('Profile "%s" does not exist.', 'optix-core'), $profile),
            ], 404);
        }

        update_option('optix_active_profile', $profile);

        return new \WP_REST_Response([
            'profile' => $profile,
        ], 200);
    }

    public function export_settings(): \WP_REST_Response {
        $registry = Settings_Registry::get_instance();
        $entries  = $registry->get_entries();

        $settings = [];
        foreach ($entries as $key => $entry) {
            $settings[$key] = $this->get_current_value($key);
        }

        return new \WP_REST_Response([
            'version'  => OPTIX_CORE_VERSION,
            'exported' => current_time('mysql'),
            'settings' => $settings,
        ], 200);
    }

    public function import_settings(\WP_REST_Request $request): \WP_REST_Response {
        $settings = $request->get_param('settings');
        if (!is_array($settings) || empty($settings)) {
            return new \WP_REST_Response([
                'code'    => 'invalid_settings',
                'message' => __('The settings parameter must be a non-empty object.', 'optix-core'),
            ], 400);
        }

        $registry = Settings_Registry::get_instance();
        $imported = [];
        $errors   = [];

        foreach ($settings as $key => $value) {
            if (!$registry->has($key)) {
                $errors[] = sprintf(__('Unknown setting key: %s', 'optix-core'), $key);
                continue;
            }
            Options_Manager::set($key, $value);
            $imported[] = $key;
        }

        // Flush registry cache so subsequent reads are fresh.
        $registry->flush_cache();

        return new \WP_REST_Response([
            'imported' => $imported,
            'errors'   => $errors,
        ], empty($errors) ? 200 : 207);
    }

    public function get_profiles(): \WP_REST_Response {
        $router     = Profile_Router::get_instance();
        $active     = $router->get_active_profile();
        $theme_dir  = get_template_directory();
        $profiles   = [];
        $dir_handle = opendir($theme_dir . '/profiles');

        if ($dir_handle) {
            while (($entry = readdir($dir_handle)) !== false) {
                if ($entry[0] === '.') {
                    continue;
                }
                $path = $theme_dir . '/profiles/' . $entry;
                if (is_dir($path)) {
                    $profiles[] = [
                        'name'     => $entry,
                        'active'   => $entry === $active,
                        'has_css'  => file_exists($path . '/assets/css/style.css'),
                        'has_js'   => file_exists($path . '/assets/js/script.js'),
                    ];
                }
            }
            closedir($dir_handle);
        }

        sort($profiles);

        return new \WP_REST_Response($profiles, 200);
    }

    public function save_preset(\WP_REST_Request $request): \WP_REST_Response {
        $name = $request->get_param('name');
        $data = $request->get_param('data');

        if (empty($name)) {
            return new \WP_REST_Response([
                'code'    => 'invalid_name',
                'message' => __('Preset name must be a non-empty string.', 'optix-core'),
            ], 400);
        }

        if ($data !== null && !is_array($data)) {
            return new \WP_REST_Response([
                'code'    => 'invalid_data',
                'message' => __('Preset data must be an object if provided.', 'optix-core'),
            ], 400);
        }

        $name   = sanitize_text_field($name);
        $preset = Preset_Registry::get_instance();

        if ($data === null) {
            $registry = Settings_Registry::get_instance();
            $data     = [];
            foreach ($registry->get_entries() as $key => $entry) {
                $data[$key] = $this->get_current_value($key);
            }
        }

        $saved = $preset->save($name, $data);

        if (!$saved) {
            return new \WP_REST_Response([
                'code'    => 'save_failed',
                'message' => __('Failed to save preset.', 'optix-core'),
            ], 500);
        }

        return new \WP_REST_Response([
            'name'  => $name,
            'saved' => true,
        ], 201);
    }

    public function load_preset(\WP_REST_Request $request): \WP_REST_Response {
        $name   = $request->get_param('name');
        $preset = Preset_Registry::get_instance();

        $data = $preset->load($name);

        if ($data === null) {
            return new \WP_REST_Response([
                'code'    => 'not_found',
                'message' => sprintf(__('Preset "%s" not found.', 'optix-core'), $name),
            ], 404);
        }

        return new \WP_REST_Response([
            'name'   => $name,
            'values' => $data,
        ], 200);
    }

    public function flush_cache(): \WP_REST_Response {
        Settings_Registry::get_instance()->flush_cache();
        Cache::get_instance()->flush_all();

        return new \WP_REST_Response([
            'success' => true,
            'message' => __('All Optix caches flushed.', 'optix-core'),
        ], 200);
    }

    private function format_entry(string $key, array $entry, bool $fresh = false): array {
        return [
            'key'     => $key,
            'value'   => $fresh ? $this->get_fresh_value($key) : $this->get_current_value($key),
            'default' => $entry['default'] ?? null,
            'type'    => $entry['type'] ?? 'string',
            'section' => $entry['section'] ?? '',
            'label'   => $entry['label'] ?? '',
        ];
    }

    private function clean_entry(array $entry): array {
        $allowed = ['section', 'type', 'default', 'label'];
        $clean   = [];
        foreach ($allowed as $field) {
            if (array_key_exists($field, $entry)) {
                $clean[$field] = $entry[$field];
            }
        }
        return $clean;
    }

    private function get_current_value(string $key): mixed {
        return Settings_Registry::get_instance()->get($key);
    }

    private function get_fresh_value(string $key): mixed {
        Settings_Registry::get_instance()->flush_cache();
        return Settings_Registry::get_instance()->get($key);
    }

    private function get_entry_or_error(string $key): array|\WP_Error {
        $registry = Settings_Registry::get_instance();
        $entry    = $registry->get_schema($key);

        if (null === $entry) {
            return new \WP_Error(
                'not_found',
                sprintf(__('Setting "%s" not found in registry.', 'optix-core'), $key),
                ['status' => 404]
            );
        }

        return $entry;
    }

    private function get_settings_args(): array {
        return [
            'section' => [
                'description'       => __('Filter by section name.', 'optix-core'),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '',
            ],
            'per_page' => [
                'description'       => __('Number of items per page.', 'optix-core'),
                'type'              => 'integer',
                'minimum'           => 1,
                'maximum'           => 500,
                'default'           => 50,
                'sanitize_callback' => 'absint',
            ],
            'page' => [
                'description'       => __('Page number.', 'optix-core'),
                'type'              => 'integer',
                'minimum'           => 1,
                'default'           => 1,
                'sanitize_callback' => 'absint',
            ],
        ];
    }

    private function get_single_args(): array {
        return [
            'key' => [
                'description'       => __('Setting key.', 'optix-core'),
                'type'              => 'string',
                'required'          => true,
                'sanitize_callback' => 'sanitize_key',
            ],
        ];
    }

    private function get_single_update_args(): array {
        return [
            'key' => [
                'description'       => __('Setting key.', 'optix-core'),
                'type'              => 'string',
                'required'          => true,
                'sanitize_callback' => 'sanitize_key',
            ],
            'value' => [
                'description' => __('Setting value.', 'optix-core'),
                'required'    => true,
            ],
        ];
    }

    private function get_bulk_update_args(): array {
        return [
            'settings' => [
                'description' => __('Object of key-value pairs to update.', 'optix-core'),
                'type'        => 'object',
                'required'    => true,
            ],
        ];
    }

    private function get_profile_args(): array {
        return [
            'profile' => [
                'description'       => __('Profile name.', 'optix-core'),
                'type'              => 'string',
                'required'          => true,
                'sanitize_callback' => 'sanitize_file_name',
            ],
        ];
    }

    private function get_import_args(): array {
        return [
            'settings' => [
                'description' => __('Object of key-value pairs to import.', 'optix-core'),
                'type'        => 'object',
                'required'    => true,
            ],
        ];
    }

    private function get_preset_save_args(): array {
        return [
            'name' => [
                'description'       => __('Preset name.', 'optix-core'),
                'type'              => 'string',
                'required'          => true,
                'sanitize_callback' => 'sanitize_text_field',
            ],
            'data' => [
                'description' => __('Optional key-value data. If omitted, current settings are used.', 'optix-core'),
                'type'        => 'object',
                'required'    => false,
            ],
        ];
    }

    private function get_preset_load_args(): array {
        return [
            'name' => [
                'description'       => __('Preset name.', 'optix-core'),
                'type'              => 'string',
                'required'          => true,
                'sanitize_callback' => 'sanitize_text_field',
            ],
        ];
    }
}
