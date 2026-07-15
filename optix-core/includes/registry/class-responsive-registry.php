<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Responsive_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'xl' => [
                'type' => 'int',
                'default' => 1200,
                'label' => __('XL Breakpoint (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'lg' => [
                'type' => 'int',
                'default' => 992,
                'label' => __('LG Breakpoint (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'md' => [
                'type' => 'int',
                'default' => 768,
                'label' => __('MD Breakpoint (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'sm' => [
                'type' => 'int',
                'default' => 576,
                'label' => __('SM Breakpoint (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'xs' => [
                'type' => 'int',
                'default' => 0,
                'label' => __('XS Breakpoint (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
        ];
    }
}
