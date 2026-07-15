<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Color_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'primary' => [
                'type' => 'color',
                'default' => '#705b53',
                'label' => __('Primary Color', 'optix-core'),
                'sanitize' => 'sanitize_hex_color',
            ],
            'secondary' => [
                'type' => 'color',
                'default' => '#c19a6b',
                'label' => __('Secondary Color', 'optix-core'),
                'sanitize' => 'sanitize_hex_color',
            ],
            'accent' => [
                'type' => 'color',
                'default' => '#d4a373',
                'label' => __('Accent Color', 'optix-core'),
                'sanitize' => 'sanitize_hex_color',
            ],
            'text' => [
                'type' => 'color',
                'default' => '#666666',
                'label' => __('Text Color', 'optix-core'),
                'sanitize' => 'sanitize_hex_color',
            ],
            'heading' => [
                'type' => 'color',
                'default' => '#222222',
                'label' => __('Heading Color', 'optix-core'),
                'sanitize' => 'sanitize_hex_color',
            ],
            'background' => [
                'type' => 'color',
                'default' => '#ffffff',
                'label' => __('Background Color', 'optix-core'),
                'sanitize' => 'sanitize_hex_color',
            ],
            'dark_mode_enable' => [
                'type' => 'bool',
                'default' => false,
                'label' => __('Enable Dark Mode', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'dark_mode_bg' => [
                'type' => 'color',
                'default' => '#1a1a1a',
                'label' => __('Dark Mode Background', 'optix-core'),
                'sanitize' => 'sanitize_hex_color',
            ],
        ];
    }
}
