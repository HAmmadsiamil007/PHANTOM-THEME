<?php
/**
 * Dynamic CSS generation for Optix Kids Collection.
 *
 * Injects ACF-driven CSS variables and custom CSS into the site head.
 * Runs at wp_head priority 99 so it overrides theme defaults.
 *
 * @package optix
 */

namespace Optix;

/**
 * Output dynamic CSS.
 */
function output_dynamic_css() {
  $css = '';

  // ── Color & dimension variables ──────────────────────────
  $css_vars = [
    // Colors (hex values — no unit needed)
    '--primary--color'           => [ 'val' => optix_get_option( 'color_primary' ),     'unit' => '' ],
    '--secondary--color'         => [ 'val' => optix_get_option( 'color_secondary' ),   'unit' => '' ],
    '--accent--color'            => [ 'val' => optix_get_option( 'color_accent' ),      'unit' => '' ],
    '--text--color'              => [ 'val' => optix_get_option( 'color_text' ),         'unit' => '' ],
    '--text--color2'             => [ 'val' => optix_get_option( 'color_heading' ),      'unit' => '' ],
    '--text--color3'             => [ 'val' => optix_get_option( 'color_background' ),   'unit' => '' ],
    '--header--bg--color'        => [ 'val' => optix_get_option( 'color_header_bg' ),   'unit' => '' ],
    '--footer--bg--color'        => [ 'val' => optix_get_option( 'color_footer_bg' ),   'unit' => '' ],
    '--link--color'              => [ 'val' => optix_get_option( 'color_link' ),         'unit' => '' ],
    '--link--hover--color'       => [ 'val' => optix_get_option( 'color_link_hover' ),  'unit' => '' ],
    '--border--color'            => [ 'val' => optix_get_option( 'color_border' ),       'unit' => '' ],
    '--sale--color'              => [ 'val' => optix_get_option( 'color_sale' ),         'unit' => '' ],
    '--button--bg'               => [ 'val' => optix_get_option( 'button_bg' ),          'unit' => '' ],
    '--button--text'             => [ 'val' => optix_get_option( 'button_text' ),        'unit' => '' ],
    '--button--bg--hover'        => [ 'val' => optix_get_option( 'button_bg_hover' ),   'unit' => '' ],
    '--button--text--hover'      => [ 'val' => optix_get_option( 'button_text_hover' ), 'unit' => '' ],
    '--topbar--bg'               => [ 'val' => optix_get_option( 'topbar_bg' ),          'unit' => '' ],
    '--topbar--text'             => [ 'val' => optix_get_option( 'topbar_text' ),        'unit' => '' ],
    '--footer--text'             => [ 'val' => optix_get_option( 'footer_text' ),        'unit' => '' ],
    '--footer--heading--text'    => [ 'val' => optix_get_option( 'footer_heading_text' ),'unit' => '' ],
    '--footer--link'             => [ 'val' => optix_get_option( 'footer_link' ),        'unit' => '' ],
    // Dimensions (numeric — need px unit)
    '--button--radius'           => [ 'val' => optix_get_option( 'button_radius' ),      'unit' => 'px' ],
    '--button--padding--y'       => [ 'val' => optix_get_option( 'button_padding_y' ),   'unit' => 'px' ],
    '--button--padding--x'       => [ 'val' => optix_get_option( 'button_padding_x' ),   'unit' => 'px' ],
    '--header--height'           => [ 'val' => optix_get_option( 'header_height' ),      'unit' => 'px' ],
  ];

  $css .= ':root {';
  foreach ( $css_vars as $var => $d ) {
    $v = $d['val'];
    if ( '' !== $v && false !== $v && null !== $v ) {
      $css .= ' ' . $var . ': ' . esc_attr( $v ) . $d['unit'] . ';';
    }
  }
  $css .= ' }';

  // ── 3D / Perspective effects ─────────────────────────────────
  $enable_3d = optix_get_option( 'effect_3d_enable' );
  $perspective = optix_get_option( 'effect_3d_perspective', '1000' );
  $rotate_x    = optix_get_option( 'effect_3d_rotate_x', '5' );
  $rotate_y    = optix_get_option( 'effect_3d_rotate_y', '5' );
  if ( $enable_3d ) {
    $css .= '.tilt-3d { transition: transform 0.3s ease; transform-style: preserve-3d; perspective: ' . esc_attr( $perspective ) . 'px; }';
    $css .= '.tilt-3d:hover { transform: rotateX(' . esc_attr( $rotate_x ) . 'deg) rotateY(' . esc_attr( $rotate_y ) . 'deg); }';
  }

  // ── Mega Menu ──────────────────────────────────────────────────
  $css .= '.menu-item-has-children.mega-menu { position: static !important; }';
  $css .= '.menu-item-has-children.mega-menu > .sub-menu { width: 100%; left: 0; right: 0; padding: 2rem; display: flex; flex-wrap: wrap; gap: 2rem; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 8px; }';
  $css .= '.menu-item-has-children.mega-menu > .sub-menu > .menu-item { flex: 1; min-width: 200px; }';
  $css .= '.menu-item-has-children.mega-menu > .sub-menu > .menu-item > a { font-weight: 600; color: #705b53; margin-bottom: 0.5rem; display: block; }';

  // ── Typography ───────────────────────────────────────────────
  $body_font    = optix_get_option( 'typography_body_font' );
  $heading_font = optix_get_option( 'typography_heading_font' );
  $body_size    = optix_get_option( 'typography_base_size' );
  $body_weight  = optix_get_option( 'typography_body_weight' );
  $heading_weight = optix_get_option( 'typography_heading_weight' );
  $line_height  = optix_get_option( 'typography_line_height' );
  $letter_space = optix_get_option( 'typography_letter_spacing' );

  if ( $body_font || $heading_font || $body_size || $body_weight || $heading_weight || $line_height || $letter_space ) {
    $css .= 'body {';
    if ( $body_font && 'System Default' !== $body_font ) {
      $css .= " font-family: '" . esc_attr( $body_font ) . "', sans-serif;";
    }
    if ( $body_size ) {
      $css .= ' font-size: ' . esc_attr( $body_size ) . 'px;';
    }
    if ( $body_weight ) {
      $css .= ' font-weight: ' . esc_attr( $body_weight ) . ';';
    }
    if ( $line_height ) {
      $css .= ' line-height: ' . esc_attr( $line_height ) . ';';
    }
    if ( '' !== $letter_space && false !== $letter_space && null !== $letter_space ) {
      $css .= ' letter-spacing: ' . esc_attr( $letter_space ) . 'px;';
    }
    $css .= ' }';

    if ( $heading_font && 'System Default' !== $heading_font ) {
      $css .= " h1, h2, h3, h4, h5, h6 { font-family: '" . esc_attr( $heading_font ) . "', sans-serif; }";
    }
    if ( $heading_weight ) {
      $css .= ' h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 { font-weight: ' . esc_attr( $heading_weight ) . '; }';
    }
  }

  // ── Custom CSS ───────────────────────────────────────────────
  $custom_css = optix_get_option( 'custom_css' );
  if ( $custom_css ) {
    $css .= "\n" . wp_strip_all_tags( $custom_css );
  }

  if ( $css ) {
    echo '<style id="optix-dynamic-css">' . "\n" . $css . "\n" . '</style>' . "\n";
  }
}
add_action( 'wp_head', __NAMESPACE__ . '\output_dynamic_css', 99 );

/**
 * Output custom JavaScript in the footer.
 */
function output_custom_js() {
  $custom_js = optix_get_option( 'custom_js' );
  if ( $custom_js ) {
    echo '<script id="optix-custom-js">' . "\n" . '// <![CDATA[' . "\n" . wp_kses( $custom_js, [] ) . "\n" . '// ]]>' . "\n" . '</script>' . "\n";
  }
}
add_action( 'wp_footer', __NAMESPACE__ . '\output_custom_js', 99 );
