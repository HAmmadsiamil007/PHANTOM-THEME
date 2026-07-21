<?php
/**
 * Template Name: Four Columns
 *
 * @package Phantom_Theme
 */

get_header(); ?>

<div class="container four-column-page">
	<div class="row">
		<div class="col-lg-3 col-md-6 col-sm-12">
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
			?>
		</div>
	</div>
</div>

<?php get_footer();
