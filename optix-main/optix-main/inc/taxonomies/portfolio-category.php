<?php
/**
 * Portfolio Category taxonomy.
 *
 * @package optix
 */

namespace Optix;

/**
 * Portfolio Category taxonomy.
 */
class Portfolio_Category extends Taxonomy {

  public function register( $post_types = [] ) {
    $labels = [
      'name'              => __( 'Project Categories', 'optix' ),
      'singular_name'     => __( 'Project Category', 'optix' ),
      'search_items'      => __( 'Search Project Categories', 'optix' ),
      'all_items'         => __( 'All Project Categories', 'optix' ),
      'parent_item'       => __( 'Parent Project Category', 'optix' ),
      'parent_item_colon' => __( 'Parent Project Category:', 'optix' ),
      'edit_item'         => __( 'Edit Project Category', 'optix' ),
      'update_item'       => __( 'Update Project Category', 'optix' ),
      'add_new_item'      => __( 'Add New Project Category', 'optix' ),
      'new_item_name'     => __( 'New Project Category Name', 'optix' ),
      'menu_name'         => __( 'Categories', 'optix' ),
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
