<?php
declare(strict_types=1);

namespace OptixCore;

defined( 'ABSPATH' ) || exit;

class Mega_Menu {

	private static ?Mega_Menu $instance = null;

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_filter( 'wp_nav_menu_args', [ $this, 'add_menu_class' ] );
		add_action( 'init', [ $this, 'register_settings' ] );
	}

	public function register_settings(): void {
		register_meta( 'nav_menu_item', '_optix_mega_menu_enabled', [
			'type'              => 'string',
			'single'            => true,
			'sanitize_callback' => 'sanitize_text_field',
			'show_in_rest'      => true,
			'default'           => 'disabled',
		] );
		register_meta( 'nav_menu_item', '_optix_mega_menu_columns', [
			'type'              => 'integer',
			'single'            => true,
			'sanitize_callback' => 'absint',
			'show_in_rest'      => true,
			'default'           => 3,
		] );
	}

	public function add_menu_class( array $args ): array {
		if ( ! $this->is_enabled() ) {
			return $args;
		}
		if ( isset( $args['theme_location'] ) && 'primary' === $args['theme_location'] ) {
			$args['menu_class']     = ( $args['menu_class'] ?? '' ) . ' optix-mega-menu';
			$args['walker']         = new Mega_Menu_Walker();
			$args['items_wrap']     = '<ul id="%1$s" class="%2$s" data-mega-menu="1">%3$s</ul>';
		}
		return $args;
	}

	public function is_enabled(): bool {
		return (bool) optix_get_option( 'mega_menu_enable' );
	}
}

class Mega_Menu_Walker extends \Walker_Nav_Menu {

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ): void {
		if ( ! $args instanceof \stdClass ) {
			parent::start_el( $output, $item, $depth, $args, $id );
			return;
		}

		$mega_enabled  = get_post_meta( $item->ID, '_optix_mega_menu_enabled', true );
		$mega_columns  = (int) get_post_meta( $item->ID, '_optix_mega_menu_columns', true );
		$mega_columns  = max( 2, min( 6, $mega_columns ) );

		$classes   = empty( $item->classes ) ? [] : (array) $item->classes;
		$has_children = in_array( 'menu-item-has-children', $classes, true );

		if ( 0 === $depth && 'enabled' === $mega_enabled && $has_children ) {
			$classes[] = 'optix-mega-menu-item';
			$classes[] = 'optix-mega-menu-' . $mega_columns . '-cols';
		}

		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= '<li' . $id . $class_names . '>';

		$atts           = [];
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( is_string( $value ) && '' !== $value && false !== $value ) {
				$attributes .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
			}
		}

		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output  = $args->before ?? '';
		$item_output .= '<a' . $attributes . '>';
		$item_output .= ( $args->link_before ?? '' ) . $title . ( $args->link_after ?? '' );
		$item_output .= '</a>';
		$item_output .= $args->after ?? '';

		if ( 0 === $depth && 'enabled' === $mega_enabled && $has_children ) {
			$item_output .= '<div class="optix-mega-menu-panel" data-columns="' . esc_attr( (string) $mega_columns ) . '">';
			$item_output .= '<ul class="optix-mega-menu-sub-menu">';
		}

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ): void {
		if ( 0 === $depth ) {
			$mega_enabled = get_post_meta( $item->ID, '_optix_mega_menu_enabled', true );
			$classes      = (array) ( $item->classes ?? [] );
			$has_children = in_array( 'menu-item-has-children', $classes, true );
			if ( 'enabled' === $mega_enabled && $has_children ) {
				$output .= '</ul></div>';
			}
		}
		$output .= '</li>' . "\n";
	}
}
