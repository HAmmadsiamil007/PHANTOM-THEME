<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Hook_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'optix_core/init' => [
                'type' => 'action',
                'description' => __('Fired after all registries and engines are initialized', 'optix-core'),
                'params' => [],
            ],
            'optix/settings/get/{key}' => [
                'type' => 'filter',
                'description' => __('Filter the value of any setting before returning', 'optix-core'),
                'params' => [],
            ],
            'optix/front_page/sections' => [
                'type' => 'filter',
                'description' => __('Modify the ordered list of sections rendered on front-page', 'optix-core'),
                'params' => [],
            ],
            'optix/section/{id}/before' => [
                'type' => 'action',
                'description' => __('Fired before a section is rendered', 'optix-core'),
                'params' => [],
            ],
            'optix/section/{id}/after' => [
                'type' => 'action',
                'description' => __('Fired after a section is rendered', 'optix-core'),
                'params' => [],
            ],
            'optix/component/{id}/settings' => [
                'type' => 'filter',
                'description' => __('Filter component settings before rendering', 'optix-core'),
                'params' => [],
            ],
            'optix/component/{id}/before' => [
                'type' => 'action',
                'description' => __('Fired before a component is rendered', 'optix-core'),
                'params' => [],
            ],
            'optix/component/{id}/after' => [
                'type' => 'action',
                'description' => __('Fired after a component is rendered', 'optix-core'),
                'params' => [],
            ],
            'optix/dynamic_css' => [
                'type' => 'filter',
                'description' => __('Filter the generated dynamic CSS before output', 'optix-core'),
                'params' => [],
            ],
            'optix/dynamic_css/selector/{key}' => [
                'type' => 'filter',
                'description' => __('Filter CSS selector for a specific setting', 'optix-core'),
                'params' => [],
            ],
            'optix/profile/switch' => [
                'type' => 'action',
                'description' => __('Fired when the active profile is switched', 'optix-core'),
                'params' => [],
            ],
            'optix/preset/save/{name}' => [
                'type' => 'action',
                'description' => __('Fired after a preset is saved', 'optix-core'),
                'params' => [],
            ],
            'optix/preset/load/{name}' => [
                'type' => 'action',
                'description' => __('Fired after a preset is loaded', 'optix-core'),
                'params' => [],
            ],
            'optix/cache/flush' => [
                'type' => 'action',
                'description' => __('Fired when all caches are flushed', 'optix-core'),
                'params' => [],
            ],
            'optix/rest/response/{endpoint}' => [
                'type' => 'filter',
                'description' => __('Filter REST API response data', 'optix-core'),
                'params' => [],
            ],
        ];
    }
}
