<?php
declare(strict_types=1);

namespace OptixCore;

defined( 'ABSPATH' ) || exit;

class Acf_Blocks {

	private static ?Acf_Blocks $instance = null;

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(): void {
		add_action( 'acf/init', [ $this, 'register_acf_blocks' ] );
	}

	public function register_acf_blocks(): void {
		if ( ! function_exists( 'acf_register_block_type' ) ) {
			return;
		}

		// Skip if the Optix theme already registers these blocks
		if ( function_exists( 'Optix\\render_acf_block' ) ) {
			return;
		}

		acf_register_block_type( [
			'name'            => 'portfolio-grid',
			'title'           => __( 'Portfolio Grid', 'optix-core' ),
			'description'     => __( 'Display portfolio items in a grid layout.', 'optix-core' ),
			'category'        => 'optix',
			'icon'            => 'portfolio',
			'mode'            => 'preview',
			'align'           => 'full',
			'post_types'      => [ 'page', 'portfolio' ],
			'render_callback' => [ $this, 'render_block' ],
			'supports'        => [ 'align' => false, 'anchor' => true ],
		] );

		acf_register_block_type( [
			'name'            => 'project-highlight',
			'title'           => __( 'Project Highlight', 'optix-core' ),
			'description'     => __( 'Highlight a single project.', 'optix-core' ),
			'category'        => 'optix',
			'icon'            => 'star-filled',
			'mode'            => 'preview',
			'align'           => 'full',
			'post_types'      => [ 'page', 'portfolio' ],
			'render_callback' => [ $this, 'render_block' ],
			'supports'        => [ 'align' => false, 'anchor' => true ],
		] );
	}

	public function render_block( array $block, string $content = '', bool $is_preview = false, int $post_id = 0 ): void {
		if ( function_exists( 'optix_profile_template_part' ) ) {
			optix_profile_template_part( 'template-parts/blocks', $block['name'] );
		}
	}
}
