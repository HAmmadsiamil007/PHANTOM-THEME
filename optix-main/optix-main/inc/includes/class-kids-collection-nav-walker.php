<?php
/**
 * Kids Collection Nav Walker — extends the existing Optix Nav_Walker.
 *
 * Overrides output to match the custom header navigation structure:
 *   <li class="nav-item dropdown active">
 *     <a class="nav-link dropdown-toggle dropdown-color navbar-text-color"
 *        href="#" id="navbarDropdown4" role="button"
 *        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
 *     <div class="dropdown-menu drop-down-content">
 *       <ul class="list-unstyled drop-down-pages">
 *
 * @package optix
 */

namespace Optix;

/**
 * Custom nav walker for the Kids Collection header navigation.
 */
class Kids_Collection_Nav_Walker extends Nav_Walker {

  /**
   * Start a sub-menu level.
   *
   * @param string   $output Passed by reference; appended to.
   * @param int      $depth  Depth of menu item.
   * @param stdClass $args   An object of wp_nav_menu() arguments.
   */
  public function start_lvl( &$output, $depth = 0, $args = null ) {
    if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
      $t = '';
      $n = '';
    } else {
      $t = "\t";
      $n = "\n";
    }
    $indent  = str_repeat( $t, $depth );
    $output .= "\n{$n}{$indent}<div class=\"dropdown-menu drop-down-content\">\n";
    $output .= "{$indent}\t<ul class=\"list-unstyled drop-down-pages\">\n";
  }

  /**
   * End a sub-menu level.
   *
   * @param string   $output Passed by reference; appended to.
   * @param int      $depth  Depth of menu item.
   * @param stdClass $args   An object of wp_nav_menu() arguments.
   */
  public function end_lvl( &$output, $depth = 0, $args = null ) {
    if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
      $t = '';
      $n = '';
    } else {
      $t = "\t";
      $n = "\n";
    }
    $indent = str_repeat( $t, $depth );
    $output .= "{$indent}\t</ul>\n";
    $output .= "{$indent}</div>\n";
  }

  /**
   * Start an element.
   *
   * Kids Collection-specific overrides:
   * - Top-level parents get .nav-item.dropdown
   * - Top-level parent links get .nav-link.dropdown-toggle.dropdown-color.navbar-text-color
   *   with role="button", data-bs-toggle="dropdown", aria-haspopup, aria-expanded
   * - Child items get class="nav-item" on <li> and class="dropdown-item nav-link" on <a>
   *
   * @param string   $output Passed by reference; appended to.
   * @param WP_Post  $item   Menu item data object.
   * @param int      $depth  Depth of menu item.
   * @param stdClass $args   An object of wp_nav_menu() arguments.
   * @param int      $id     Current item ID.
   */
  public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
    if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
      $t = '';
      $n = '';
    } else {
      $t = "\t";
      $n = "\n";
    }
    $indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

    $classes = empty( $item->classes ) ? array() : (array) $item->classes;

    // Ensure nav-item is present on all <li> elements.
    if ( ! in_array( 'nav-item', $classes, true ) ) {
      $classes[] = 'nav-item';
    }

    // Add .dropdown for top-level items with children.
    if ( $this->has_children && 0 === $depth ) {
      $classes[] = 'dropdown';
    }

    // Add .active for current items.
    if ( in_array( 'current-menu-item', $classes, true ) || in_array( 'current-menu-parent', $classes, true ) ) {
      $classes[] = 'active';
    }

    $classes[] = 'menu-item-' . $item->ID;
    $classes   = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth );
    $class_names = join( ' ', $classes );
    $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

    $id_attr = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
    $id_attr = $id_attr ? ' id="' . esc_attr( $id_attr ) . '"' : '';

    $output .= $indent . '<li' . $id_attr . $class_names . '>';

    // Build the <a> tag attributes.
    $atts           = array();
    $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
    $atts['target'] = ! empty( $item->target ) ? $item->target : '';

    if ( '_blank' === $item->target && empty( $item->xfn ) ) {
      $atts['rel'] = 'noopener noreferrer';
    } else {
      $atts['rel'] = ! empty( $item->xfn ) ? $item->xfn : '';
    }

    if ( $this->has_children && 0 === $depth ) {
      // Top-level parent: Kids Collection dropdown link style.
      $atts['href']              = ! empty( $item->url ) ? $item->url : '#';
      $atts['class']             = 'nav-link dropdown-toggle dropdown-color navbar-text-color';
      $atts['id']                = 'navbar-dropdown-' . $item->ID;
      $atts['role']              = 'button';
      $atts['data-bs-toggle']    = 'dropdown';
      $atts['aria-haspopup']     = 'true';
      $atts['aria-expanded']     = 'false';
    } elseif ( $depth > 0 ) {
      // Child item: dropdown-item nav-link.
      $atts['href']  = ! empty( $item->url ) ? $item->url : '#';
      $atts['class'] = 'dropdown-item nav-link';
    } else {
      // Top-level leaf: plain nav-link.
      $atts['href']  = ! empty( $item->url ) ? $item->url : '#';
      $atts['class'] = 'nav-link';
    }

    $atts['aria-current'] = $item->current ? 'page' : '';

    $title = apply_filters( 'the_title', $item->title, $item->ID );
    $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

    $link_attrs = '';
    foreach ( $atts as $key => $value ) {
      if ( '' !== $value && 'title' !== $key ) {
        $link_attrs .= ' ' . $key . '="' . esc_attr( $value ) . '"';
      } elseif ( 'title' === $key && '' !== $value ) {
        $link_attrs .= ' title="' . esc_attr( $value ) . '"';
      }
    }

    $item_output  = isset( $args->before ) ? $args->before : '';
    $item_output .= '<a' . $link_attrs . '>';
    $item_output .= isset( $args->link_before ) ? $args->link_before : '';
    $item_output .= $title;
    $item_output .= isset( $args->link_after ) ? $args->link_after : '';
    $item_output .= '</a>';
    $item_output .= isset( $args->after ) ? $args->after : '';

    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }
}
