<?php
declare(strict_types=1);

namespace OptixCore;

defined( 'ABSPATH' ) || exit;

class Portfolio_Category extends Taxonomy {

	public function register( $post_types = [] ): void {
		$labels = [
			'name'              => __( 'Project Categories', 'optix-core' ),
			'singular_name'     => __( 'Project Category', 'optix-core' ),
			'search_items'      => __( 'Search Project Categories', 'optix-core' ),
			'all_items'         => __( 'All Project Categories', 'optix-core' ),
			'parent_item'       => __( 'Parent Project Category', 'optix-core' ),
			'parent_item_colon' => __( 'Parent Project Category:', 'optix-core' ),
			'edit_item'         => __( 'Edit Project Category', 'optix-core' ),
			'update_item'       => __( 'Update Project Category', 'optix-core' ),
			'add_new_item'      => __( 'Add New Project Category', 'optix-core' ),
			'new_item_name'     => __( 'New Project Category Name', 'optix-core' ),
			'menu_name'         => __( 'Categories', 'optix-core' ),
		];

		$args = [
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => [ 'slug' => 'project-category' ],
		];

		$this->register_wp_taxonomy( 'portfolio_category', $post_types, $args );
	}
}
