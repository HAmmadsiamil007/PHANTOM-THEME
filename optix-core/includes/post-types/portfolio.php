<?php
declare(strict_types=1);

namespace OptixCore;

defined( 'ABSPATH' ) || exit;

class Portfolio extends Post_Type {

	public function register(): void {
		$labels = [
			'name'               => __( 'Projects', 'optix-core' ),
			'singular_name'      => __( 'Project', 'optix-core' ),
			'add_new'            => __( 'Add New', 'optix-core' ),
			'add_new_item'       => __( 'Add New Project', 'optix-core' ),
			'edit_item'          => __( 'Edit Project', 'optix-core' ),
			'new_item'           => __( 'New Project', 'optix-core' ),
			'view_item'          => __( 'View Project', 'optix-core' ),
			'search_items'       => __( 'Search Projects', 'optix-core' ),
			'not_found'          => __( 'No projects found', 'optix-core' ),
			'not_found_in_trash' => __( 'No projects found in Trash', 'optix-core' ),
			'all_items'          => __( 'All Projects', 'optix-core' ),
			'menu_name'          => __( 'Projects', 'optix-core' ),
		];

		$args = [
			'labels'       => $labels,
			'public'       => true,
			'has_archive'  => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-portfolio',
			'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
			'rewrite'      => [ 'slug' => 'projects' ],
			'menu_position' => 5,
		];

		$this->register_wp_post_type( $this->slug, $args );
	}
}
