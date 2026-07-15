<?php
/**
 * Kids Collection - Two Column
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
				<h1 class=""><?php echo esc_html( optix_get_option( 'two_column_title', 'Two Column' ) ); ?></h1>
				<div class="breadcrumb-con d-inline-block">
					<ol class="breadcrumb mb-0">
						<li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'optix' ); ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( optix_get_option( 'two_column_title', 'Two Column' ) ); ?></li>
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
<div class="blog-posts blogpage-section float-left w-100">
	<div class="container">
		<div class="row wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
			<div id="blog" class="col-xl-12">
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
						while ( $query->have_posts() ) :
							$query->the_post();
							?>
							<div class="col-xl-6 col-lg-6 col-md-12">
								<div class="blog-box twocolumn-blog float-left w-100 post-item mb-4">
									<div class="post-item-wrap position-relative">
										<div class="post-image">
											<a href="<?php echo esc_url( get_permalink() ); ?>">
												<?php
												if ( has_post_thumbnail() ) {
													the_post_thumbnail( 'full', array( 'alt' => get_the_title(), 'loading' => 'lazy' ) );
												} else {
													?>
													<img loading="lazy" alt="" src="<?php echo esc_url( $kc_img . '/post-featured.jpg' ); ?>" loading="lazy">
													<?php
												}
												?>
											</a>
										</div>
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
											<p class="mb-0 text-size-16"><?php echo esc_html( get_the_excerpt() ); ?></p>
										</div>
										<div class="button-portion loadone_twocol">
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
</div>
