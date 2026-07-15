<?php
declare(strict_types=1);

namespace OptixCore {

defined( 'ABSPATH' ) || exit;

class Profile_Router {

	private static ?Profile_Router $instance = null;
	private ?string $active_profile = null;
	private ?string $profile_path = null;

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_filter( 'template_include', [ $this, 'route_template' ], 99 );
		add_filter( 'get_template_part', [ $this, 'route_template_part' ], 10, 3 );
		add_filter( 'wc_get_template', [ $this, 'route_wc_template' ], 10, 5 );
		add_filter( 'stylesheet_uri', [ $this, 'route_stylesheet' ] );
	}

	public function get_active_profile(): string {
		if ( null !== $this->active_profile ) {
			return $this->active_profile;
		}

		if ( is_admin() && isset( $_GET['optix_preview_profile'] ) ) {
			$preview = sanitize_file_name( wp_unslash( $_GET['optix_preview_profile'] ) );
			if ( $this->is_valid_profile( $preview ) ) {
				$this->active_profile = $preview;
				return $preview;
			}
		}

		$profile = get_option( 'optix_active_profile', 'default' );
		if ( ! is_string( $profile ) || empty( $profile ) ) {
			$profile = 'default';
		}
		$profile = sanitize_file_name( $profile );

		if ( ! $this->is_valid_profile( $profile ) ) {
			$profile = 'default';
		}

		$this->active_profile = $profile;
		return $profile;
	}

	private function is_valid_profile( string $profile ): bool {
		if ( is_dir( get_template_directory() . '/profiles/' . $profile ) ) {
			return true;
		}
		if ( is_child_theme() && is_dir( get_stylesheet_directory() . '/profiles/' . $profile ) ) {
			return true;
		}
		return false;
	}

	public function get_profile_path(): string {
		if ( null !== $this->profile_path ) {
			return $this->profile_path;
		}
		$profile = $this->get_active_profile();
		if ( is_child_theme() ) {
			$child_path = get_stylesheet_directory() . '/profiles/' . $profile;
			if ( is_dir( $child_path ) ) {
				$this->profile_path = $child_path;
				return $this->profile_path;
			}
		}
		$this->profile_path = get_template_directory() . '/profiles/' . $profile;
		return $this->profile_path;
	}

	public function route_template( string $template ): string {
		$template_rel = $this->get_relative_template_path( $template );

		if ( ! $template_rel ) {
			return $template;
		}

		$profile_path = $this->get_profile_path();
		$candidate    = $profile_path . '/' . $template_rel;
		if ( file_exists( $candidate ) ) {
			return apply_filters( 'optix/route_template', $candidate, $template_rel, 'active' );
		}

		if ( is_child_theme() ) {
			$child_default = get_stylesheet_directory() . '/profiles/default/' . $template_rel;
			if ( file_exists( $child_default ) ) {
				return apply_filters( 'optix/route_template', $child_default, $template_rel, 'child_default' );
			}
		}

		$candidate = get_template_directory() . '/profiles/default/' . $template_rel;
		if ( file_exists( $candidate ) ) {
			return apply_filters( 'optix/route_template', $candidate, $template_rel, 'parent_default' );
		}

		$candidate = OPTIX_CORE_PATH . 'templates/' . $template_rel;
		if ( file_exists( $candidate ) ) {
			return apply_filters( 'optix/route_template', $candidate, $template_rel, 'plugin' );
		}

		return apply_filters( 'optix/route_template', $template, $template_rel, 'fallback' );
	}

	public function route_template_part( string $template, string $slug, mixed $name = null ): string {
		$profile_path = $this->get_profile_path();
		$name_str = is_array( $name ) ? ( $name[0] ?? null ) : ( is_string( $name ) ? $name : null );

		$candidates = [];
		if ( is_child_theme() ) {
			$child_active = get_stylesheet_directory() . '/profiles/' . $this->get_active_profile();
			if ( is_dir( $child_active ) && $child_active !== $profile_path ) {
				if ( $name_str ) {
					$candidates[] = $child_active . '/' . $slug . '-' . $name_str . '.php';
				}
				$candidates[] = $child_active . '/' . $slug . '.php';
			}
		}

		if ( $name_str ) {
			$candidates[] = $profile_path . '/' . $slug . '-' . $name_str . '.php';
		}
		$candidates[] = $profile_path . '/' . $slug . '.php';

		if ( is_child_theme() ) {
			$child_default = get_stylesheet_directory() . '/profiles/default';
			if ( is_dir( $child_default ) ) {
				if ( $name_str ) {
					$candidates[] = $child_default . '/' . $slug . '-' . $name_str . '.php';
				}
				$candidates[] = $child_default . '/' . $slug . '.php';
			}
		}

		$default_path = get_template_directory() . '/profiles/default';
		if ( $name_str ) {
			$candidates[] = $default_path . '/' . $slug . '-' . $name_str . '.php';
		}
		$candidates[] = $default_path . '/' . $slug . '.php';

		foreach ( $candidates as $candidate ) {
			if ( file_exists( $candidate ) ) {
				return apply_filters( 'optix/route_template_part', $candidate, $slug, $name_str );
			}
		}

		return apply_filters( 'optix/route_template_part', $template, $slug, $name_str );
	}

	public function route_wc_template( string $template, string $template_name, array $args, string $template_path, string $default_path ): string {
		$profile_wc = $this->get_profile_path() . '/woocommerce/' . $template_name;
		if ( file_exists( $profile_wc ) ) {
			return $profile_wc;
		}

		$default_wc = get_template_directory() . '/profiles/default/woocommerce/' . $template_name;
		if ( file_exists( $default_wc ) ) {
			return $default_wc;
		}

		return $template;
	}

	public function route_stylesheet( string $uri ): string {
		$profile_css_uri = get_template_directory_uri() . '/profiles/' . $this->get_active_profile() . '/assets/css/style.css';
		$profile_css_path = get_template_directory() . '/profiles/' . $this->get_active_profile() . '/assets/css/style.css';
		if ( file_exists( $profile_css_path ) ) {
			return $profile_css_uri;
		}
		return $uri;
	}

	public function route_directory_uri( string $uri ): string {
		return $uri . '/profiles/' . $this->get_active_profile();
	}

	private function get_relative_template_path( string $template ): string {
		$template_dir = get_template_directory();
		if ( 0 === strpos( $template, $template_dir ) ) {
			return ltrim( substr( $template, strlen( $template_dir ) ), '/' );
		}
		if ( is_child_theme() ) {
			$child_dir = get_stylesheet_directory();
			if ( 0 === strpos( $template, $child_dir ) ) {
				return ltrim( substr( $template, strlen( $child_dir ) ), '/' );
			}
		}
		return basename( $template );
	}
}

}

namespace {

if ( ! function_exists( 'optix_profile_template_part' ) ) {
	function optix_profile_template_part( string $slug, ?string $name = null ): void {
		$router = \OptixCore\Profile_Router::get_instance();
		$template = $router->route_template_part( '', $slug, $name );
		if ( $template && file_exists( $template ) ) {
			load_template( $template, false );
		}
	}
}

}
