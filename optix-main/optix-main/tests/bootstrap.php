<?php
declare(strict_types=1);

define( 'ABSPATH', dirname( __DIR__, 4 ) . '/' );
define( 'WP_CONTENT_DIR', dirname( __DIR__, 3 ) );

$autoload = __DIR__ . '/../vendor/autoload.php';
if ( file_exists( $autoload ) ) {
	require_once $autoload;
}

$theme_dir = dirname( __DIR__ );
$includes  = [
	'inc/class-fallback-router.php',
	'inc/class-web-vitals.php',
	'inc/fallback-functions.php',
];

foreach ( $includes as $file ) {
	$path = $theme_dir . '/' . $file;
	if ( file_exists( $path ) ) {
		require_once $path;
	}
}

$wp_tests = getenv( 'WP_TESTS_DIR' ) ?: '/tmp/wordpress-tests-lib/bootstrap.php';
if ( file_exists( $wp_tests ) ) {
	require_once $wp_tests;
}
