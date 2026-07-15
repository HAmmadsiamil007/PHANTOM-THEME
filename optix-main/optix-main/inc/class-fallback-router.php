<?php
/**
 * Emergency Fallback Router — activates when optix-core plugin is deactivated.
 *
 * @package optix
 */

namespace Optix;

class Fallback_Router {
  private static ?self $instance = null;

  private string $active_profile = 'default';

  public static function get_instance(): self {
    if ( null === self::$instance ) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function init(): void {
    $this->active_profile = get_option( 'optix_active_profile', 'default' );

    add_filter( 'template_include', [ $this, 'route_template' ], 99 );
    add_filter( 'get_template_part', [ $this, 'route_template_part' ], 10, 3 );
    add_filter( 'wc_get_template', [ $this, 'route_wc_template' ], 10, 5 );
    add_filter( 'stylesheet_uri', [ $this, 'route_stylesheet' ], 99 );
    add_filter( 'template_directory_uri', [ $this, 'route_template_directory_uri' ], 99 );
    add_filter( 'theme_mod_header_image', [ $this, 'route_theme_mod' ], 99 );
    add_filter( 'theme_mod_background_image', [ $this, 'route_theme_mod' ], 99 );
  }

  public function get_active_profile(): string {
    return $this->active_profile;
  }

  public function get_profile_path(): string {
    $profile_dir = get_template_directory() . '/profiles/' . $this->active_profile;
    if ( is_dir( $profile_dir ) ) {
      return $profile_dir;
    }
    return get_template_directory() . '/profiles/default';
  }

  public function route_template( string $template ): string {
    $profile_path = get_template_directory() . '/profiles/' . $this->active_profile;
    $default_path = get_template_directory() . '/profiles/default';

    if ( ! is_dir( $default_path ) ) {
      return $template;
    }

    $slug = $this->get_template_slug();

    if ( $slug && $this->active_profile !== 'default' ) {
      $override = $profile_path . '/' . $slug;
      if ( file_exists( $override ) ) {
        return $override;
      }
    }

    $fallback = $default_path . '/' . $slug;
    if ( $slug && file_exists( $fallback ) ) {
      return $fallback;
    }

    return $template;
  }

  public function route_template_part( string $template, string $slug, $name = null ): string {
    $profile_path = get_template_directory() . '/profiles/' . $this->active_profile;
    $default_path = get_template_directory() . '/profiles/default';
    $name_str     = is_array( $name ) ? ( $name[0] ?? null ) : $name;

    $candidates = [];
    if ( $name_str ) {
      $candidates[] = $profile_path . '/' . $slug . '-' . $name_str . '.php';
    }
    $candidates[] = $profile_path . '/' . $slug . '.php';
    if ( $name_str ) {
      $candidates[] = $default_path . '/' . $slug . '-' . $name_str . '.php';
    }
    $candidates[] = $default_path . '/' . $slug . '.php';

    foreach ( $candidates as $candidate ) {
      if ( file_exists( $candidate ) ) {
        return $candidate;
      }
    }

    return $template;
  }

  public function route_wc_template( string $template, string $template_name, array $args, string $template_path, string $default_path ): string {
    $profile_wc = get_template_directory() . '/profiles/' . $this->active_profile . '/woocommerce/' . $template_name;
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
    $profile_style = get_template_directory() . '/profiles/' . $this->active_profile . '/assets/css/style.css';
    if ( file_exists( $profile_style ) ) {
      return get_template_directory_uri() . '/profiles/' . $this->active_profile . '/assets/css/style.css';
    }

    $default_style = get_template_directory() . '/profiles/default/assets/css/style.css';
    if ( file_exists( $default_style ) ) {
      return get_template_directory_uri() . '/profiles/default/assets/css/style.css';
    }

    return $uri;
  }

  public function route_template_directory_uri( string $uri ): string {
    $profile_dir = get_template_directory() . '/profiles/' . $this->active_profile;
    if ( is_dir( $profile_dir ) ) {
      return get_template_directory_uri() . '/profiles/' . $this->active_profile;
    }

    return $uri;
  }

  public function route_theme_mod(): string {
    return '';
  }

  private function get_template_slug(): ?string {
    if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
      if ( function_exists( 'is_shop' ) && is_shop() ) {
        return $this->resolve_template( [ 'woocommerce/archive-product.php', 'archive.php' ] );
      }
      if ( function_exists( 'is_product' ) && is_product() ) {
        return $this->resolve_template( [ 'woocommerce/single-product.php', 'single.php' ] );
      }
      if ( function_exists( 'is_cart' ) && is_cart() ) {
        return $this->resolve_template( [ 'woocommerce/cart.php', 'page.php' ] );
      }
      if ( function_exists( 'is_checkout' ) && is_checkout() ) {
        return $this->resolve_template( [ 'woocommerce/checkout.php', 'page.php' ] );
      }
      if ( function_exists( 'is_account_page' ) && is_account_page() ) {
        return $this->resolve_template( [ 'woocommerce/my-account.php', 'page.php' ] );
      }
    }

    if ( is_singular() ) {
      global $post;
      $type = get_post_type();

      $templates = [];
      $templates[] = "single-{$type}.php";

      $slug = get_page_template_slug();
      if ( $slug ) {
        array_unshift( $templates, $slug );
      }

      $templates[] = 'single.php';
      $templates[] = 'singular.php';

      return $this->resolve_template( $templates );
    }

    if ( is_404() ) {
      return $this->resolve_template( [ '404.php' ] );
    }

    if ( is_search() ) {
      return $this->resolve_template( [ 'search.php' ] );
    }

    if ( is_front_page() || is_home() ) {
      $front = get_option( 'page_on_front' );
      if ( $front && 'page' === get_option( 'show_on_front' ) ) {
        $slug = get_page_template_slug( $front );
        if ( $slug ) {
          return $slug;
        }
        return 'front-page.php';
      }
      return 'home.php';
    }

    if ( is_home() ) {
      return $this->resolve_template( [ 'home.php', 'index.php' ] );
    }

    if ( is_page() ) {
      global $post;
      $slug = get_page_template_slug();
      if ( $slug ) {
        return $slug;
      }
      return $this->resolve_template( [ 'page.php' ] );
    }

    if ( is_single() ) {
      return $this->resolve_template( [ 'single.php', 'singular.php' ] );
    }

    if ( is_category() || is_tag() || is_tax() ) {
      $term = get_queried_object();
      $tax  = $term->taxonomy ?? '';
      $type = $this->get_post_type_from_taxonomy( $tax );

      $templates = [];
      if ( $type ) {
        $templates[] = "taxonomy-{$tax}-{$type}.php";
      }
      $templates[] = "taxonomy-{$tax}.php";
      $templates[] = 'archive.php';

      return $this->resolve_template( $templates );
    }

    if ( is_post_type_archive() ) {
      $type = get_query_var( 'post_type' );
      return $this->resolve_template( [ "archive-{$type}.php", 'archive.php' ] );
    }

    if ( is_archive() ) {
      return $this->resolve_template( [ 'archive.php' ] );
    }

    return null;
  }

  private function resolve_template( array $candidates ): ?string {
    $search_dirs = [];
    if ( $this->active_profile !== 'default' ) {
      $search_dirs[] = get_template_directory() . '/profiles/' . $this->active_profile;
    }
    $search_dirs[] = get_template_directory() . '/profiles/default';

    foreach ( $candidates as $candidate ) {
      foreach ( $search_dirs as $dir ) {
        $path = $dir . '/' . ltrim( $candidate, '/' );
        if ( file_exists( $path ) ) {
          return $path;
        }
      }
    }

    return null;
  }

  private function get_post_type_from_taxonomy( string $taxonomy ): ?string {
    $types = get_post_types_by_support( 'custom-fields' );
    foreach ( $types as $type ) {
      $taxonomies = get_object_taxonomies( $type );
      if ( in_array( $taxonomy, $taxonomies, true ) ) {
        return $type;
      }
    }
    return null;
  }
}
