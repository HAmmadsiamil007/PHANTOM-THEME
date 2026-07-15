<?php
declare(strict_types=1);

namespace OptixCore\Engine;

defined( 'ABSPATH' ) || exit;

class Search_Engine {

	private static ?self $instance = null;

	private function __construct() {}

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'wp_ajax_optix_live_search', array( $this, 'handle_live_search' ) );
		add_action( 'wp_ajax_nopriv_optix_live_search', array( $this, 'handle_live_search' ) );
		add_filter( 'pre_get_posts', array( $this, 'modify_search_query' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_search_assets' ) );
	}

	public function enqueue_search_assets(): void {
		wp_enqueue_script( 'optix-frontend' );
		wp_localize_script(
			'optix-frontend',
			'optixSearch',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'optix_live_search' ),
			)
		);
	}

	public function handle_live_search(): void {
		if ( ! wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) ),
			'optix_live_search'
		) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'optix-core' ) ) );
		}

		$term = sanitize_text_field( wp_unslash( $_POST['search'] ?? '' ) );
		if ( strlen( $term ) < 2 ) {
			wp_send_json_error( array( 'message' => __( 'Search term too short.', 'optix-core' ) ) );
		}

		$post_types = get_option( 'optix_search_post_types', array( 'post', 'product' ) );
		if ( ! is_array( $post_types ) ) {
			$post_types = array( 'post', 'product' );
		}

		$args = array(
			'post_type'      => $post_types,
			's'              => $term,
			'posts_per_page' => (int) get_option( 'optix_search_max_results', 8 ),
			'no_found_rows'  => true,
		);

		$query   = new \WP_Query( $args );
		$results = array();

		foreach ( $query->posts as $post ) {
			$results[] = array(
				'title' => get_the_title( $post ),
				'url'   => get_permalink( $post ),
				'type'  => get_post_type_object( $post->post_type )->labels->singular_name ?? $post->post_type,
				'image' => get_the_post_thumbnail_url( $post, 'thumbnail' ) ? get_the_post_thumbnail_url( $post, 'thumbnail' ) : '',
			);
		}

		wp_send_json_success(
			array(
				'results'     => $results,
				'total'       => $query->found_posts,
				'result_text' => sprintf(
					_n( '%d result found', '%d results found', count( $results ), 'optix-core' ),
					count( $results )
				),
			)
		);
	}

	public function modify_search_query( \WP_Query $query ): void {
		if ( ! $query->is_search() || ! $query->is_main_query() ) {
			return;
		}

		$post_types = get_option( 'optix_search_post_types', array( 'post', 'product' ) );
		if ( is_array( $post_types ) && ! empty( $post_types ) ) {
			$query->set( 'post_type', $post_types );
		}

		$posts_per_page = (int) get_option( 'optix_search_per_page', 10 );
		if ( $posts_per_page > 0 ) {
			$query->set( 'posts_per_page', $posts_per_page );
		}
	}

	public function get_no_results_message(): string {
		$message = get_option( 'optix_search_no_results', __( 'No results found. Try a different search term.', 'optix-core' ) );
		return $message ? $message : __( 'No results found. Try a different search term.', 'optix-core' );
	}
}
