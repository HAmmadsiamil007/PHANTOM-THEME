<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Animation_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'enable' => [
                'type' => 'bool',
                'default' => true,
                'label' => __('Enable Animations', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'duration' => [
                'type' => 'string',
                'default' => '0.3s',
                'label' => __('Default Duration', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'easing' => [
                'type' => 'select',
                'default' => 'ease-out',
                'options' => ['linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out'],
                'label' => __('Default Easing', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'scroll_animations' => [
                'type' => 'bool',
                'default' => true,
                'label' => __('Enable Scroll Animations', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'parallax_enable' => [
                'type' => 'bool',
                'default' => true,
                'label' => __('Enable Parallax', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'hover_effects' => [
                'type' => 'bool',
                'default' => true,
                'label' => __('Enable Hover Effects', 'optix-core'),
                'sanitize' => 'absint',
            ],
        ];
    }
}
