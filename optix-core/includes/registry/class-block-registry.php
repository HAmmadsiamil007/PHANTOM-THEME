<?php
declare(strict_types=1);

namespace OptixCore\Registry;

defined('ABSPATH') || exit;

class Block_Registry extends Base_Registry {

    protected function define_entries(): array {
        return [
            'portfolio-grid' => [
                'component_id' => 'product_card',
                'description'   => __('Portfolio grid ACF block', 'optix-core'),
                'acf_block'     => 'portfolio-grid',
                'fields'        => [],
            ],
            'project-highlight' => [
                'component_id' => 'promo_box',
                'description'   => __('Project highlight ACF block', 'optix-core'),
                'acf_block'     => 'project-highlight',
                'fields'        => [],
            ],
        ];
    }

    public function get_component_id(string $block_name): ?string {
        foreach ($this->entries as $entry) {
            if ($entry['acf_block'] === $block_name) {
                return $entry['component_id'];
            }
        }
        return null;
    }

    public function get_block_for_component(string $component_id): ?string {
        foreach ($this->entries as $entry) {
            if ($entry['component_id'] === $component_id) {
                return $entry['acf_block'];
            }
        }
        return null;
    }
}
