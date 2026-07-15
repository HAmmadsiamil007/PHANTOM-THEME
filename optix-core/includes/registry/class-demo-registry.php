<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Demo_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'default' => [
                'type' => 'demo_package',
                'title'       => __('Default Demo', 'optix-core'),
                'description' => __('Full demo content with products, pages, and settings', 'optix-core'),
                'thumbnail'   => '',
                'steps'       => [
                    [
                        'id'    => 'settings',
                        'label' => __('Import Settings', 'optix-core'),
                    ],
                    [
                        'id'    => 'pages',
                        'label' => __('Create Pages', 'optix-core'),
                    ],
                    [
                        'id'       => 'products',
                        'label'    => __('Import Products', 'optix-core'),
                        'optional' => true,
                    ],
                    [
                        'id'       => 'images',
                        'label'    => __('Download Images', 'optix-core'),
                        'optional' => true,
                    ],
                    [
                        'id'    => 'menus',
                        'label' => __('Configure Menus', 'optix-core'),
                    ],
                ],
                'settings'         => [
                    'hero_title'    => 'Welcome to Our Store',
                    'color_primary' => '#705b53',
                ],
                'pages'            => ['Home', 'Shop', 'About', 'Contact', 'Blog'],
                'required_plugins' => ['woocommerce'],
                'label'       => __('Default Demo', 'optix-core'),
            ],
            'minimal' => [
                'type' => 'demo_package',
                'title'       => __('Minimal Setup', 'optix-core'),
                'description' => __('Just the essential pages and settings, no products', 'optix-core'),
                'thumbnail'   => '',
                'steps'       => [
                    [
                        'id'    => 'settings',
                        'label' => __('Import Settings', 'optix-core'),
                    ],
                    [
                        'id'    => 'pages',
                        'label' => __('Create Pages', 'optix-core'),
                    ],
                    [
                        'id'    => 'menus',
                        'label' => __('Configure Menus', 'optix-core'),
                    ],
                ],
                'settings'         => [
                    'hero_title'    => 'Welcome',
                    'color_primary' => '#705b53',
                ],
                'pages'            => ['Home', 'Shop', 'About'],
                'required_plugins' => [],
                'label'       => __('Minimal Setup', 'optix-core'),
            ],
        ];
    }

    public function list_packages(): array {
        $packages = [];
        foreach ($this->entries as $id => $entry) {
            $packages[$id] = [
                'title'             => $entry['title'],
                'description'       => $entry['description'],
                'thumbnail'         => $entry['thumbnail'],
                'step_count'        => count($entry['steps']),
                'required_plugins'  => $entry['required_plugins'],
            ];
        }
        return $packages;
    }

    public function get_package(string $package_id): ?array {
        if (!isset($this->entries[$package_id])) {
            return null;
        }
        return $this->entries[$package_id];
    }

    public function get_import_steps(string $package_id): ?array {
        if (!isset($this->entries[$package_id])) {
            return null;
        }
        return $this->entries[$package_id]['steps'];
    }

    public function get_settings(string $package_id): array {
        if (!isset($this->entries[$package_id])) {
            return [];
        }
        return $this->entries[$package_id]['settings'];
    }

    public function get_pages(string $package_id): array {
        if (!isset($this->entries[$package_id])) {
            return [];
        }
        return $this->entries[$package_id]['pages'];
    }

    public function get_required_plugins(string $package_id): array {
        if (!isset($this->entries[$package_id])) {
            return [];
        }
        return $this->entries[$package_id]['required_plugins'];
    }
}
