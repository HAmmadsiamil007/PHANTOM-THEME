<?php
declare(strict_types=1);

namespace OptixCore\Engine;

defined( 'ABSPATH' ) || exit;

class Blog_Engine {

	private static ?self $instance = null;

	private function __construct() {}

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_filter( 'excerpt_length', array( $this, 'custom_excerpt_length' ) );
		add_filter( 'excerpt_more', array( $this, 'custom_excerpt_more' ) );
		add_filter( 'body_class', array( $this, 'add_blog_body_classes' ) );
	}

	public function custom_excerpt_length( int $length ): int {
		$custom = (int) get_option( 'optix_blog_excerpt_length', 55 );
		return $custom > 0 ? $custom : 55;
	}

	public function custom_excerpt_more(): string {
		$more = get_option( 'optix_blog_excerpt_more', '...' );
		return $more ? $more : '...';
	}

	public function add_blog_body_classes( array $classes ): array {
		if ( is_singular( 'post' ) ) {
			$layout    = get_option( 'optix_blog_single_layout', 'full' );
			$classes[] = 'optix-single-' . $layout;
		} elseif ( is_archive() || is_home() ) {
			$layout    = get_option( 'optix_blog_archive_layout', 'grid' );
			$classes[] = 'optix-archive-' . $layout;
		}
		return $classes;
	}

	public function get_reading_time( int $post_id = 0 ): int {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		$content = get_post_field( 'post_content', $post_id );
		$words   = str_word_count( wp_strip_all_tags( $content ) );
		$wpm     = (int) get_option( 'optix_blog_wpm', 200 );
		$minutes = (int) ceil( $words / max( $wpm, 1 ) );
		return max( 1, $minutes );
	}

	public function get_reading_time_html( int $post_id = 0 ): string {
		$minutes = $this->get_reading_time( $post_id );
		return sprintf(
			'<span class="optix-reading-time">%s %s</span>',
			esc_html( $minutes ),
			esc_html( _n( 'min read', 'mins read', $minutes, 'optix-core' ) )
		);
	}

	public function get_related_posts( int $post_id = 0, int $count = 3 ): array {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		$categories = wp_get_post_categories( $post_id );
		if ( empty( $categories ) ) {
			return array();
		}

		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => $count,
			'post__not_in'   => array( $post_id ),
			'category__in'   => $categories,
			'no_found_rows'  => true,
		);

		$query = new \WP_Query( $args );
		return $query->posts;
	}

	public function get_archive_layout(): string {
		return get_option( 'optix_blog_archive_layout', 'grid' );
	}

	public function get_single_layout(): string {
		return get_option( 'optix_blog_single_layout', 'full' );
	}
}
