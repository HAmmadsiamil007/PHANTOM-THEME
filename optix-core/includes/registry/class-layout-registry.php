<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Layout_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'container_width' => [
                'type' => 'int',
                'default' => 1200,
                'label' => __('Container Width (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'content_width' => [
                'type' => 'int',
                'default' => 800,
                'label' => __('Content Width (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'sidebar_width' => [
                'type' => 'int',
                'default' => 380,
                'label' => __('Sidebar Width (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'gutter' => [
                'type' => 'int',
                'default' => 30,
                'label' => __('Grid Gutter (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'columns' => [
                'type' => 'int',
                'default' => 12,
                'label' => __('Grid Columns', 'optix-core'),
                'sanitize' => 'absint',
            ],
        ];
    }
}
