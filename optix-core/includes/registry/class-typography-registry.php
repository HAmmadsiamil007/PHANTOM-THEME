<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Typography_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'heading_font' => [
                'type' => 'string',
                'default' => 'Archivo',
                'label' => __('Heading Font Family', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'body_font' => [
                'type' => 'string',
                'default' => 'Jost',
                'label' => __('Body Font Family', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'h1_size' => [
                'type' => 'int',
                'default' => 48,
                'label' => __('H1 Font Size (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'h2_size' => [
                'type' => 'int',
                'default' => 36,
                'label' => __('H2 Font Size (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'h3_size' => [
                'type' => 'int',
                'default' => 24,
                'label' => __('H3 Font Size (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'body_size' => [
                'type' => 'int',
                'default' => 16,
                'label' => __('Body Font Size (px)', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'heading_weight' => [
                'type' => 'string',
                'default' => '700',
                'label' => __('Heading Font Weight', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'body_weight' => [
                'type' => 'string',
                'default' => '400',
                'label' => __('Body Font Weight', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'line_height' => [
                'type' => 'float',
                'default' => 1.6,
                'label' => __('Base Line Height', 'optix-core'),
                'sanitize' => 'floatval',
            ],
        ];
    }
}
