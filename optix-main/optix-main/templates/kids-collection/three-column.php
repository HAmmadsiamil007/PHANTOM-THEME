<?php
/**
 * Kids Collection - Three Column
 *
 * @package optix
 */

$kc_img = kc_img_base();
?>
<!-- SUB BANNER SECTION -->
<section
	class="sub-banner-con position-relative float-left w-100 gradient-overlay d-flex align-items-center justify-content-center">
	<div class="container">
		<div class="col-xl-12 col-lg-12 mr-auto ml-auto">
			<div class="sub-banner-inner-con text-center">
				<h1 class=""><?php echo esc_html( optix_get_option( 'three_column_title', 'Three Column' ) ); ?></h1>
				<div class="breadcrumb-con d-inline-block">
					<ol class="breadcrumb mb-0">
						<li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'optix' ); ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( optix_get_option( 'three_column_title', 'Three Column' ) ); ?></li>
					</ol>
				</div>
				<!-- banner inner con -->
			</div>
			<!-- col -->
		</div>
		<!-- container -->
	</div>
	<!-- banner con -->
</section>
<!-- MAIN SECTION -->
<section class="blog-posts blogpage-section three-column-con float-left w-100">
	<div class="container">
		<div class="row wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
			<div id="blog" class="col-xl-12">
				<!-- threecolumn-blog  -->
				<div class="row wow fadeInDown" data-wow-duration="2s" data-wow-delay="0.4s">
					<?php
					$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
					$args  = array(
						'post_type'      => 'post',
						'posts_per_page' => get_option( 'posts_per_page', 6 ),
						'paged'          => $paged,
					);
					$query = new WP_Query( $args );

					if ( $query->have_posts() ) :
						$post_count = 0;
						while ( $query->have_posts() ) :
							$query->the_post();
							$post_count++;
							$box_class = 'blog-box';
							if ( 1 === $post_count ) {
								$box_class .= ' blog-box1';
							} elseif ( 2 === $post_count ) {
								$box_class .= ' blog-box2';
							}
							?>
							<div class="col-lg-4 col-md-6 col-sm-6 col-12">
								<div class="<?php echo esc_attr( $box_class ); ?>">
									<figure class="blog-image mb-0">
										<?php
										if ( has_post_thumbnail() ) {
											the_post_thumbnail( 'full', array( 'class' => 'img-fluid', 'loading' => 'lazy' ) );
										} else {
											?>
											<img loading="lazy" src="<?php echo esc_url( $kc_img . '/blog-image1.jpg' ); ?>" alt="" class="img-fluid"
												loading="lazy">
											<?php
										}
										?>
									</figure>
									<div class="lower-portion">
										<div class="span-i-con">
											<i class="fa-solid fa-user" aria-hidden="true"></i>
											<span class="text-size-14 text-mr"><?php printf( esc_html__( 'By : %s', 'optix' ), get_the_author_posts_link() ); ?></span>
											<i class="tag-mb fa-solid fa-tag" aria-hidden="true"></i>
											<span class="text-size-14"><?php
											$categories = get_the_category();
											if ( ! empty( $categories ) ) {
												echo esc_html( $categories[0]->name );
											}
											?></span>
										</div>
										<a href="<?php echo esc_url( get_permalink() ); ?>">
											<h5><?php the_title(); ?></h5>
										</a>
									</div>
									<div class="button-portion">
										<div class="date">
											<i class="mb-0 calendar-ml fa-solid fa-calendar-days" aria-hidden="true"></i>
											<span class="mb-0 text-size-14"><?php echo esc_html( get_the_date() ); ?></span>
										</div>
										<div class="button">
											<a class="mb-0 read_more text-decoration-none" href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'Read More', 'optix' ); ?></a>
										</div>
									</div>
								</div>
							</div>
							<?php
						endwhile;
						wp_reset_postdata();
					endif;
					?>
				</div>
				<?php
				$total_pages = $query->max_num_pages;
				if ( $total_pages > 1 ) :
					$big = 999999999;
					?>
					<div class="pagination-con">
						<?php
						echo paginate_links(
							array(
								'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
								'format'    => '?paged=%#%',
								'current'   => max( 1, $paged ),
								'total'     => $total_pages,
								'prev_text' => '<i class="fa-solid fa-angle-left" aria-hidden="true"></i>',
								'next_text' => '<i class="fa-solid fa-angle-right" aria-hidden="true"></i>',
								'type'      => 'list',
							)
						);
						?>
					</div>
					<?php
				endif;
				?>
			</div>
		</div>
	</div>
</section>
