<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class WooCommerce_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'shop_layout' => [
                'type' => 'select',
                'default' => 'sidebar-left',
                'options' => ['full-width', 'sidebar-left', 'sidebar-right'],
                'label' => __('Shop Layout', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'columns' => [
                'type' => 'int',
                'default' => 4,
                'label' => __('Product Columns', 'optix-core'),
                'sanitize' => 'absint',
            ],
            'pagination' => [
                'type' => 'select',
                'default' => 'numbered',
                'options' => ['numbered', 'load-more', 'infinite-scroll'],
                'label' => __('Pagination Style', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'product_card_style' => [
                'type' => 'select',
                'default' => 'default',
                'options' => ['default', 'modern', 'classic', 'minimal'],
                'label' => __('Product Card Style', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'gallery' => [
                'type' => 'select',
                'default' => 'zoom',
                'options' => ['zoom', 'lightbox', 'slider', 'none'],
                'label' => __('Gallery Style', 'optix-core'),
                'sanitize' => 'sanitize_text_field',
            ],
            'related_count' => [
                'type' => 'int',
                'default' => 4,
                'label' => __('Related Products Count', 'optix-core'),
                'sanitize' => 'absint',
            ],
        ];
    }
}
