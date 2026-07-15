<?php
/**
 * Maintenance Mode template.
 *
 * Shown when maintenance mode is enabled and user is not admin.
 *
 * @package optix
 */

$maintenance_heading = \Optix\optix_get_option( 'coming_soon_heading', 'We are Coming Soon' );
$maintenance_text    = \Optix\optix_get_option( 'coming_soon_subtitle', 'Our Website is under construction' );
$maintenance_logo    = \Optix\optix_get_option( 'coming_soon_logo' );
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo esc_html( $maintenance_heading ); ?> &mdash; <?php bloginfo( 'name' ); ?></title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f7f4f0; color: #222; display: flex; align-items: center; justify-content: center; min-height: 100vh; text-align: center; padding: 2rem; }
    .maintenance-wrapper { max-width: 600px; }
    .maintenance-logo { max-width: 200px; margin-bottom: 2rem; }
    h1 { font-size: 2.5rem; margin-bottom: 1rem; color: #705b53; }
    p { font-size: 1.125rem; color: #666; line-height: 1.6; margin-bottom: 2rem; }
    .maintenance-icon { font-size: 4rem; margin-bottom: 1.5rem; color: #c19a6b; }
    @media ( max-width: 480px ) { h1 { font-size: 1.75rem; } }
  </style>
</head>
<body>
  <div class="maintenance-wrapper">
    <?php if ( $maintenance_logo ) : ?>
      <img src="<?php echo esc_url( $maintenance_logo ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="maintenance-logo">
    <?php endif; ?>
    <div class="maintenance-icon">&#9881;</div>
    <h1><?php echo esc_html( $maintenance_heading ); ?></h1>
    <p><?php echo esc_html( $maintenance_text ); ?></p>
  </div>
</body>
</html>
