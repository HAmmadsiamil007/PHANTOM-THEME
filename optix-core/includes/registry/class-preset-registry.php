<?php
declare(strict_types=1);

namespace OptixCore\Registry;

use OptixCore\Registry\Settings_Registry;

defined('ABSPATH') || exit;

class Preset_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'default' => [
                'label'       => 'Default Settings',
                'description' => 'Factory default settings snapshot',
                'built_in'    => true,
            ],
            'dark' => [
                'label'       => 'Dark Mode',
                'description' => 'Dark color scheme preset',
                'built_in'    => true,
            ],
            'light' => [
                'label'       => 'Light & Airy',
                'description' => 'Light color scheme preset',
                'built_in'    => true,
            ],
        ];
    }

    public function save(string $name, array $data): bool {
        $key = sanitize_key($name);
        if ('' === $key) {
            return false;
        }
        return update_option('optix_preset_' . $key, $data, false);
    }

    public function load(string $name): ?array {
        $key = sanitize_key($name);
        if ('' === $key) {
            return null;
        }
        $value = get_option('optix_preset_' . $key, null);
        if (null === $value) {
            return null;
        }
        return $value;
    }

    public function delete(string $name): bool {
        $key = sanitize_key($name);
        if ('' === $key) {
            return false;
        }
        return delete_option('optix_preset_' . $key);
    }

    public function list(): array {
        global $wpdb;
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT option_name, option_value FROM {$wpdb->options}
                 WHERE option_name LIKE %s",
                $wpdb->esc_like('optix_preset_') . '%'
            )
        );
        $presets = [];
        foreach ($results as $row) {
            $name = substr($row->option_name, strlen('optix_preset_'));
            $presets[$name] = maybe_unserialize($row->option_value);
        }
        return $presets;
    }

    public function apply(string $name): bool|\WP_Error {
        $data = $this->load($name);
        if (null === $data) {
            return new \WP_Error(
                'preset_not_found',
                sprintf(__('Preset "%s" not found.', 'optix-core'), $name)
            );
        }
        $settings = Settings_Registry::get_instance();
        foreach ($data as $key => $value) {
            $settings->set($key, $value);
        }
        do_action('optix/preset/load/' . $name, $name, $data);
        return true;
    }
}
