<?php
/**
 * Typography CSS Module
 *
 * @package Phantom_Core
 */

defined( 'ABSPATH' ) || exit;

add_filter(
	'phantom_dynamic_css',
	function ( string $css ): string {
		$map    = \PhantomCore\Settings_Registry::get_css_var_map();
		$output = '';
		$composite = get_option( 'phantom_typography_headings', array() );

		$keys = array(
			'typography_body_font', 'typography_body_weight', 'typography_base_size',
			'typography_line_height', 'typography_body_spacing',
			'typography_heading_font', 'typography_heading_weight',
			'typography_heading_case', 'typography_heading_spacing',
			'typography_h1_size', 'typography_h1_height',
			'typography_h2_size', 'typography_h2_height',
			'typography_h3_size', 'typography_h3_height',
			'typography_h4_size', 'typography_h4_height',
			'typography_h5_size', 'typography_h5_height',
			'typography_h6_size', 'typography_h6_height',
			'menu_font_size',
		);

		foreach ( $keys as $k ) {
			if ( ! isset( $map[ $k ] ) ) {
				continue;
			}

			$val = get_option( 'phantom_' . $k, '' );
			if ( '' !== $val ) {
				$val_display = $val;
				if ( in_array( $k, array( 'typography_base_size', 'menu_font_size' ), true ) && is_numeric( $val ) ) {
					$val_display .= 'px';
				}
				$output .= "\t" . $map[ $k ] . ': ' . esc_attr( $val_display ) . ';' . "\n";
			}
		}

		if ( is_array( $composite ) ) {
			$headings = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
			foreach ( $headings as $h_tag ) {
				if ( ! isset( $composite[ $h_tag ] ) ) {
					continue;
				}
				$h_data = $composite[ $h_tag ];
				if ( isset( $h_data['size'] ) && '' !== $h_data['size'] ) {
					$var = $map[ 'typography_' . $h_tag . '_size' ] ?? null;
					if ( $var ) {
						$output .= "\t" . $var . ': ' . esc_attr( $h_data['size'] ) . ";\n";
					}
				}
			}
		}

		if ( '' !== $output ) {
			$css .= ':root {' . "\n" . $output . '}' . "\n";
		}

		return $css;
	},
	20
);
