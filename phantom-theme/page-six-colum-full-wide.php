<?php
/**
 * Template Name: Six Columns Full Width
 *
 * @package Phantom_Theme
 */

get_header(); ?>

<div class="container-fluid six-column-page">
	<div class="row">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</div>
</div>

<?php get_footer();
