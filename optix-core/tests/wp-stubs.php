<?php
/**
 * Minimal WordPress function stubs for unit testing.
 */

/**
 * In-memory storage for WordPress options.
 */
if ( ! function_exists( '_wp_test_options' ) ) {
	function &_wp_test_options(): array {
		static $options = [];
		return $options;
	}
}

if ( ! function_exists( '_wp_set_option' ) ) {
	function _wp_set_option( string $key, $value ): void {
		$opts =& _wp_test_options();
		$opts[ $key ] = $value;
	}
}

if ( ! function_exists( 'remove_all_filters' ) ) {
	function remove_all_filters( $tag, $priority = false ): void {
		if ( isset( $GLOBALS['wp_filter'][ $tag ] ) ) {
			unset( $GLOBALS['wp_filter'][ $tag ] );
		}
	}
}

if ( ! function_exists( 'wp_cache_flush' ) ) {
	function wp_cache_flush(): bool {
		return true;
	}
}

if ( ! function_exists( 'wp_cache_set' ) ) {
	function wp_cache_set( $key, $data, $group = '', $expire = 0 ): bool {
		return true;
	}
}

if ( ! function_exists( 'wp_cache_get' ) ) {
	function wp_cache_get( $key, $group = '', $force = false, &$found = null ) {
		return false;
	}
}

if ( ! function_exists( 'wp_cache_delete' ) ) {
	function wp_cache_delete( $key, $group = '' ): bool {
		return true;
	}
}

/**
 * In-memory storage for action/filter callbacks (stub-level tracking).
 */
function _wp_test_filters(): array {
	static $filters = [];
	return $filters;
}

if ( ! function_exists( 'get_option' ) ) {
	function get_option( $option, $default = false ) {
		$opts = _wp_test_options();
		return array_key_exists( $option, $opts ) ? $opts[ $option ] : $default;
	}
}

if ( ! function_exists( 'update_option' ) ) {
	function update_option( $option, $value, $autoload = null ) {
		_wp_set_option( $option, $value );
		return true;
	}
}

if ( ! function_exists( 'delete_option' ) ) {
	function delete_option( $option ) {
		$opts =& _wp_test_options();
		unset( $opts[ $option ] );
		return true;
	}
}

if ( ! function_exists( 'add_option' ) ) {
	function add_option( $option, $value = '', $deprecated = '', $autoload = 'yes' ) {
		$opts =& _wp_test_options();
		if ( ! array_key_exists( $option, $opts ) ) {
			$opts[ $option ] = $value;
		}
		return true;
	}
}

if ( ! function_exists( '__' ) ) {
	function __( $text, $domain = 'default' ) {
		return $text;
	}
}

if ( ! function_exists( 'esc_html__' ) ) {
	function esc_html__( $text, $domain = 'default' ) {
		return $text;
	}
}

if ( ! function_exists( 'esc_attr__' ) ) {
	function esc_attr__( $text, $domain = 'default' ) {
		return $text;
	}
}

if ( ! function_exists( '_x' ) ) {
	function _x( $text, $context, $domain = 'default' ) {
		return $text;
	}
}

if ( ! class_exists( 'WP_Hook' ) ) {
	class WP_Hook {
		private $callbacks = [];
		public function add_filter( $name, $callback, $priority, $accepted_args ) {
			$this->callbacks[ $priority ][] = [ 'function' => $callback, 'accepted_args' => $accepted_args ];
		}
		public function remove_filter( $name, $callback, $priority ) {
			unset( $this->callbacks[ $priority ] );
		}
		public function has_filter( $name, $callback = null ) {
			if ( null === $callback ) {
				return ! empty( $this->callbacks );
			}
			foreach ( $this->callbacks as $prio => $cbs ) {
				foreach ( $cbs as $cb ) {
					if ( $cb['function'] === $callback ) {
						return true;
					}
				}
			}
			return false;
		}
		public function apply_filters( $value, $args ) {
			ksort( $this->callbacks );
			foreach ( $this->callbacks as $prio => $cbs ) {
				foreach ( $cbs as $cb ) {
					$value = $cb['function']( $value, ...$args );
				}
			}
			return $value;
		}
		public function do_action( $args ) {
			ksort( $this->callbacks );
			foreach ( $this->callbacks as $prio => $cbs ) {
				foreach ( $cbs as $cb ) {
					$cb['function']( ...$args );
				}
			}
		}
	}
}

if ( ! function_exists( 'add_filter' ) ) {
	$GLOBALS['wp_filter'] = [];
	function add_filter( $tag, $callback, $priority = 10, $accepted_args = 1 ) {
		if ( ! isset( $GLOBALS['wp_filter'][ $tag ] ) ) {
			$GLOBALS['wp_filter'][ $tag ] = new WP_Hook();
		}
		$GLOBALS['wp_filter'][ $tag ]->add_filter( $tag, $callback, $priority, $accepted_args );
		return true;
	}
}

if ( ! function_exists( 'remove_filter' ) ) {
	function remove_filter( $tag, $callback, $priority = 10 ) {
		if ( isset( $GLOBALS['wp_filter'][ $tag ] ) ) {
			$GLOBALS['wp_filter'][ $tag ]->remove_filter( $tag, $callback, $priority );
		}
		return true;
	}
}

if ( ! function_exists( 'apply_filters' ) ) {
	function apply_filters( $tag, $value, ...$args ) {
		if ( isset( $GLOBALS['wp_filter'][ $tag ] ) ) {
			return $GLOBALS['wp_filter'][ $tag ]->apply_filters( $value, $args );
		}
		return $value;
	}
}

if ( ! function_exists( 'add_action' ) ) {
	function add_action( $tag, $callback, $priority = 10, $accepted_args = 1 ) {
		return add_filter( $tag, $callback, $priority, $accepted_args );
	}
}

if ( ! function_exists( 'remove_action' ) ) {
	function remove_action( $tag, $callback, $priority = 10 ) {
		return remove_filter( $tag, $callback, $priority );
	}
}

if ( ! function_exists( 'has_action' ) ) {
	function has_action( $tag, $callback = null ) {
		return has_filter( $tag, $callback );
	}
}

if ( ! function_exists( 'has_filter' ) ) {
	function has_filter( $tag, $callback = null ) {
		if ( ! isset( $GLOBALS['wp_filter'][ $tag ] ) ) {
			return false;
		}
		return $GLOBALS['wp_filter'][ $tag ]->has_filter( $tag, $callback );
	}
}

if ( ! function_exists( 'do_action' ) ) {
	function do_action( $tag, ...$args ) {
		if ( isset( $GLOBALS['wp_filter'][ $tag ] ) ) {
			$GLOBALS['wp_filter'][ $tag ]->do_action( $args );
		}
	}
}

if ( ! function_exists( 'home_url' ) ) {
	function home_url( $path = '', $scheme = null ) {
		return 'http://example.com' . ( $path ? '/' . ltrim( $path, '/' ) : '' );
	}
}

if ( ! function_exists( 'is_child_theme' ) ) {
	function is_child_theme() {
		return false;
	}
}

if ( ! function_exists( 'is_wp_error' ) ) {
	function is_wp_error( $thing ) {
		return ( $thing instanceof WP_Error );
	}
}

if ( ! class_exists( 'WP_Error' ) ) {
	class WP_Error {
		private array $errors = [];
		public function __construct( $code = '', $message = '', $data = '' ) {
			if ( $code ) {
				$this->errors[ $code ] = [ $message ];
			}
		}
		public function get_error_message( $code = '' ) {
			return '';
		}
		public function get_error_code() {
			return '';
		}
	}
}

if ( ! function_exists( 'sanitize_text_field' ) ) {
	function sanitize_text_field( $str ) {
		return is_string( $str ) ? trim( $str ) : '';
	}
}

if ( ! function_exists( 'sanitize_hex_color' ) ) {
	function sanitize_hex_color( $color ) {
		if ( '' === $color ) {
			return '';
		}
		if ( preg_match( '/^#([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/', $color ) ) {
			return $color;
		}
		return null;
	}
}

if ( ! function_exists( 'sanitize_text_field' ) ) {
	function sanitize_text_field( $str ) {
		return is_string( $str ) ? trim( $str ) : '';
	}
}

if ( ! function_exists( 'wp_unslash' ) ) {
	function wp_unslash( $value ) {
		return is_string( $value ) ? stripslashes( $value ) : $value;
	}
}

if ( ! function_exists( 'wp_strip_all_tags' ) ) {
	function wp_strip_all_tags( $string, $remove_breaks = false ) {
		return strip_tags( $string );
	}
}

if ( ! function_exists( 'wp_kses_post' ) ) {
	function wp_kses_post( $data ) {
		return $data;
	}
}

if ( ! function_exists( 'esc_html' ) ) {
	function esc_html( $text ) {
		return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
	}
}

if ( ! function_exists( 'esc_attr' ) ) {
	function esc_attr( $text ) {
		return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
	}
}

if ( ! function_exists( 'esc_url' ) ) {
	function esc_url( $url, $protocols = null, $_context = 'display' ) {
		return $url;
	}
}

if ( ! function_exists( 'esc_url_raw' ) ) {
	function esc_url_raw( $url, $protocols = null ) {
		return $url;
	}
}

if ( ! function_exists( 'absint' ) ) {
	function absint( $maybeint ) {
		return abs( intval( $maybeint ) );
	}
}

if ( ! function_exists( 'wp_create_nonce' ) ) {
	function wp_create_nonce( $action = -1 ) {
		return 'test_nonce_' . $action;
	}
}

if ( ! function_exists( 'wp_verify_nonce' ) ) {
	function wp_verify_nonce( $nonce, $action = -1 ) {
		return 1;
	}
}

if ( ! function_exists( 'current_user_can' ) ) {
	function current_user_can( $capability, ...$args ) {
		return true;
	}
}

if ( ! function_exists( 'admin_url' ) ) {
	function admin_url( $path = '', $scheme = 'admin' ) {
		return 'http://example.com/wp-admin/' . ltrim( $path, '/' );
	}
}

if ( ! function_exists( 'wp_die' ) ) {
	function wp_die( $message = '', $title = '', $args = [] ) {
		throw new \RuntimeException( $message ?: 'wp_die called' );
	}
}

if ( ! function_exists( 'wp_doing_ajax' ) ) {
	function wp_doing_ajax() {
		return defined( 'DOING_AJAX' ) && DOING_AJAX;
	}
}

if ( ! function_exists( 'get_template_directory' ) ) {
	function get_template_directory() {
		return defined( 'TEMPLATE_DIR' ) ? TEMPLATE_DIR : '/var/www/wp-content/themes/optix-main';
	}
}

if ( ! function_exists( 'get_template_directory_uri' ) ) {
	function get_template_directory_uri() {
		return 'http://example.com/wp-content/themes/optix-main';
	}
}

if ( ! function_exists( 'get_stylesheet_directory' ) ) {
	function get_stylesheet_directory() {
		return get_template_directory();
	}
}

if ( ! function_exists( 'get_stylesheet_uri' ) ) {
	function get_stylesheet_uri() {
		return get_template_directory_uri() . '/style.css';
	}
}

if ( ! function_exists( 'get_stylesheet_directory_uri' ) ) {
	function get_stylesheet_directory_uri() {
		return get_template_directory_uri();
	}
}

if ( ! function_exists( 'get_theme_file_path' ) ) {
	function get_theme_file_path( $file = '' ) {
		$path = get_template_directory();
		return $file ? $path . '/' . ltrim( $file, '/' ) : $path;
	}
}

if ( ! function_exists( 'get_theme_file_uri' ) ) {
	function get_theme_file_uri( $file = '' ) {
		$uri = get_template_directory_uri();
		return $file ? $uri . '/' . ltrim( $file, '/' ) : $uri;
	}
}

if ( ! function_exists( 'wp_upload_dir' ) ) {
	function wp_upload_dir( $time = null ) {
		return [
			'path'    => '/var/www/wp-content/uploads',
			'url'     => 'http://example.com/wp-content/uploads',
			'subdir'  => '',
			'basedir' => '/var/www/wp-content/uploads',
			'baseurl' => 'http://example.com/wp-content/uploads',
			'error'   => false,
		];
	}
}

if ( ! function_exists( 'wp_get_attachment_image_url' ) ) {
	function wp_get_attachment_image_url( $attachment_id, $size = 'full', $icon = false ) {
		return 'http://example.com/wp-content/uploads/attachment.jpg';
	}
}

if ( ! function_exists( 'plugin_basename' ) ) {
	function plugin_basename( $file ) {
		return 'optix-core/optix-core.php';
	}
}

if ( ! function_exists( 'register_activation_hook' ) ) {
	function register_activation_hook( $file, $callback ) {}
}

if ( ! function_exists( 'register_deactivation_hook' ) ) {
	function register_deactivation_hook( $file, $callback ) {}
}

if ( ! function_exists( 'register_uninstall_hook' ) ) {
	function register_uninstall_hook( $file, $callback ) {}
}

if ( ! function_exists( 'load_theme_textdomain' ) ) {
	function load_theme_textdomain( $domain, $path = false ) {}
}

if ( ! function_exists( 'is_admin' ) ) {
	function is_admin() {
		return defined( 'WP_ADMIN' ) && WP_ADMIN;
	}
}

if ( ! function_exists( 'get_permalink' ) ) {
	function get_permalink( $post = 0 ) {
		return 'http://example.com/?p=' . ( $post ?: 1 );
	}
}

if ( ! function_exists( 'sanitize_file_name' ) ) {
	function sanitize_file_name( $filename ) {
		return preg_replace( '/[^a-zA-Z0-9\-_\.]/', '', $filename );
	}
}

if ( ! function_exists( 'wp_json_encode' ) ) {
	function wp_json_encode( $data, $options = 0, $depth = 512 ) {
		return json_encode( $data, $options, $depth );
	}
}

if ( ! function_exists( 'get_locale' ) ) {
	function get_locale() {
		return 'en_US';
	}
}

if ( ! function_exists( 'get_theme_mods' ) ) {
	function get_theme_mods() {
		return [];
	}
}

if ( ! function_exists( 'get_theme_mod' ) ) {
	function get_theme_mod( $key, $default = false ) {
		return $default;
	}
}

if ( ! function_exists( 'sanitize_email' ) ) {
	function sanitize_email( $email ) {
		return filter_var( $email, FILTER_SANITIZE_EMAIL );
	}
}

if ( ! function_exists( 'sanitize_textarea_field' ) ) {
	function sanitize_textarea_field( $text ) {
		return is_string( $text ) ? trim( $text ) : '';
	}
}

if ( ! function_exists( 'load_template' ) ) {
	function load_template( $template_file, $require_once = true ) {
		if ( file_exists( $template_file ) ) {
			if ( $require_once ) {
				require_once $template_file;
			} else {
				require $template_file;
			}
		}
	}
}

if ( ! function_exists( 'floatval' ) ) {
	function floatval( $value ) {
		return (float) $value;
	}
}

if ( ! function_exists( 'maybe_unserialize' ) ) {
	function maybe_unserialize( $original ) {
		if ( is_serialized( $original ) ) {
			return unserialize( $original );
		}
		return $original;
	}
}

if ( ! function_exists( 'is_serialized' ) ) {
	function is_serialized( $data ) {
		if ( ! is_string( $data ) ) {
			return false;
		}
		$test = @unserialize( $data );
		return false !== $test;
	}
}

// WordPress DB constants
if ( ! defined( 'OBJECT' ) ) {
	define( 'OBJECT', 'OBJECT' );
}
if ( ! defined( 'OBJECT_K' ) ) {
	define( 'OBJECT_K', 'OBJECT_K' );
}

// Global $wpdb mock
if ( ! isset( $GLOBALS['wpdb'] ) ) {
	$GLOBALS['wpdb'] = new class() {
		public $prefix = 'wp_';
		public $options = 'wp_options';
		public function get_results( $query = null, $output = OBJECT ) { return []; }
		public function get_row( $query = null, $output = OBJECT, $y = 0 ) { return null; }
		public function prepare( $query, ...$args ) { return $query; }
		public function insert( $table, $data, $format = null ) { return 1; }
		public function update( $table, $data, $where, $format = null, $where_format = null ) { return 1; }
		public function delete( $table, $where, $where_format = null ) { return 1; }
		public function query( $query ) { return 1; }
		public function esc_like( $text ) { return $text; }
	};
}

// PHPUnit's assertNotWPError as a trait
if ( ! trait_exists( 'AssertNotWPErrorTrait' ) ) {
	trait AssertNotWPErrorTrait {
		public function assertNotWPError( $actual, string $message = '' ): void {
			if ( $actual instanceof WP_Error ) {
				static::fail( $message ?: 'Expected no WP_Error, got: ' . $actual->get_error_message() );
			}
		}
	}
}

// WP_Block_Type_Registry stub
if ( ! class_exists( 'WP_Block_Type_Registry' ) ) {
	class WP_Block_Type_Registry {
		private static $instance = null;
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
		public function get_all_registered() { return []; }
	}
}

// WP_Post stub
if ( ! class_exists( 'WP_Post' ) ) {
	class WP_Post {
		public $ID = 0;
		public $post_title = '';
		public $post_content = '';
	}
}

// Walker stub
if ( ! class_exists( 'Walker' ) ) {
	class Walker {
		public $tree_type = '';
		public $db_fields = [];
		public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {}
		public function end_el( &$output, $item, $depth = 0, $args = null ) {}
		public function start_lvl( &$output, $depth = 0, $args = null ) {}
		public function end_lvl( &$output, $depth = 0, $args = null ) {}
		public function walk( $elements, $max_depth ) { return ''; }
	}
}

// Walker_Nav_Menu stub
if ( ! class_exists( 'Walker_Nav_Menu' ) ) {
	class Walker_Nav_Menu extends Walker {
		public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
			if ( is_array( $output ) ) { return; }
		}
		public function end_el( &$output, $item, $depth = 0, $args = null ) {
			if ( is_array( $output ) ) { return; }
		}
	}
}
