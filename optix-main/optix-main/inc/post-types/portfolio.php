<?php
/**
 * Portfolio custom post type.
 *
 * @package optix
 */

namespace Optix;

/**
 * Portfolio post type.
 */
class Portfolio extends Post_Type {

  public function register() {
    $labels = [
      'name'               => __( 'Projects', 'optix' ),
      'singular_name'      => __( 'Project', 'optix' ),
      'add_new'            => __( 'Add New', 'optix' ),
      'add_new_item'       => __( 'Add New Project', 'optix' ),
      'edit_item'          => __( 'Edit Project', 'optix' ),
      'new_item'           => __( 'New Project', 'optix' ),
      'view_item'          => __( 'View Project', 'optix' ),
      'search_items'       => __( 'Search Projects', 'optix' ),
      'not_found'          => __( 'No projects found', 'optix' ),
      'not_found_in_trash' => __( 'No projects found in Trash', 'optix' ),
      'all_items'          => __( 'All Projects', 'optix' ),
      'menu_name'          => __( 'Projects', 'optix' ),
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
