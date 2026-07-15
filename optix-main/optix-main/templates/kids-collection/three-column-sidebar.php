<?php
/**
 * Kids Collection - Three Column Sidbar
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
				<h1 class=""><?php echo esc_html( optix_get_option( 'three_colum_sidbar_title', 'Three Column Sidbar' ) ); ?></h1>
				<div class="breadcrumb-con d-inline-block">
					<ol class="breadcrumb mb-0">
						<li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'optix' ); ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( optix_get_option( 'three_colum_sidbar_title', 'Three Column Sidbar' ) ); ?></li>
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
<!--  -->
<!--End Slider Section-->
<section class="blog-posts blogpage-section light-violet-bg float-left w-100">
	<div class="container">
		<div class="row m-0">
			<div class="col-xl-9 col-lg-9 wow fadeInLeft" data-wow-duration="2s" data-wow-delay="0.3s">
				<div id="blog" class="three-column col-xl-12">
					<div class="row">
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
								<div class="col-xl-4 col-lg-4 col-md-6">
									<div class="blog-box threecolumn-blog">
										<div class="post-image">
											<a href="<?php echo esc_url( get_permalink() ); ?>">
												<?php
												if ( has_post_thumbnail() ) {
													the_post_thumbnail( 'full', array( 'alt' => get_the_title(), 'loading' => 'lazy' ) );
												} else {
													?>
													<img loading="lazy" alt="" src="<?php echo esc_url( $kc_img . '/standard_post_img01.jpg' ); ?>" loading="lazy">
													<?php
												}
												?>
											</a>
											<!--post-image-->
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
									<!--col-->
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
					<!--blog-->
				</div>
			</div>
			<div class="sidebar sticky-sidebar col-lg-3 wow fadeInRight" data-wow-duration="2s"
				data-wow-delay="0.4s">
				<div class="theiaStickySidebar">
					<div class="widget widget-newsletter">
						<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" id="widget-search-form-sidebar" class="form-inline">
							<div class="input-group">
								<input type="text" aria-required="true" name="s"
									class="form-control widget-search-form" placeholder="<?php esc_attr_e( 'Search for pages...', 'optix' ); ?>" aria-label="<?php esc_attr_e( 'Search for pages', 'optix' ); ?>">
								<div class="input-group-append">
									<span class="input-group-btn">
										<button type="submit" id="widget-widget-search-form-button" class="btn" aria-label="<?php esc_attr_e( 'Search', 'optix' ); ?>"><i
												class="fa fa-search" aria-hidden="true"></i></button>
									</span>
									<!--input-group-append-->
								</div>
								<!--input-group-->
							</div>
							<!--form-inline-->
						</form>
						<!--widget-->
					</div>
					<div class="widget">
						<div class="tabs">
							<ul class="nav nav-tabs" id="tabs-posts" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#popular"
										role="tab" aria-controls="popular" aria-selected="true"><?php esc_html_e( 'Popular', 'optix' ); ?></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#featured"
										role="tab" aria-controls="featured" aria-selected="false"><?php esc_html_e( 'Featured', 'optix' ); ?></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#recent" role="tab"
										aria-controls="recent" aria-selected="false"><?php esc_html_e( 'Recent', 'optix' ); ?></a>
								</li>
								<!--nav-tabs-->
							</ul>
							<div class="tab-content" id="tabs-posts-content">
								<div class="tab-pane fade show active" id="popular" role="tabpanel">
									<div class="post-thumbnail-list">
										<?php
										$popular_posts = new WP_Query(
											array(
												'posts_per_page' => 3,
												'orderby'        => 'comment_count',
												'post_status'    => 'publish',
											)
										);
										while ( $popular_posts->have_posts() ) :
											$popular_posts->the_post();
											?>
											<div class="post-thumbnail-entry">
												<?php
												if ( has_post_thumbnail() ) {
													the_post_thumbnail( 'thumbnail', array( 'alt' => get_the_title(), 'loading' => 'lazy' ) );
												} else {
													?>
													<img loading="lazy" alt="" src="<?php echo esc_url( $kc_img . '/side_post_img01.jpg' ); ?>" loading="lazy">
													<?php
												}
												?>
												<div class="post-thumbnail-content">
													<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
													<span class="post-date"><i class="far fa-clock" aria-hidden="true"></i> <?php echo esc_html( sprintf( __( '%s ago', 'optix' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ) ); ?></span>
													<span class="post-category"><i class="fa fa-tag" aria-hidden="true"></i>
														<?php
														$cat = get_the_category();
														if ( ! empty( $cat ) ) {
															echo esc_html( $cat[0]->name );
														}
														?>
													</span>
													<!--post-thumbnail-content-->
												</div>
												<!--post-thumbnail-entry-->
											</div>
											<?php
										endwhile;
										wp_reset_postdata();
										?>
										<!--post-thumbnail-list-->
									</div>
									<!--tab-pane-->
								</div>
								<div class="tab-pane fade" id="featured" role="tabpanel">
									<div class="post-thumbnail-list">
										<?php
										$featured_posts = new WP_Query(
											array(
												'posts_per_page' => 3,
												'orderby'        => 'rand',
												'post_status'    => 'publish',
											)
										);
										while ( $featured_posts->have_posts() ) :
											$featured_posts->the_post();
											?>
											<div class="post-thumbnail-entry">
												<?php
												if ( has_post_thumbnail() ) {
													the_post_thumbnail( 'thumbnail', array( 'alt' => get_the_title(), 'loading' => 'lazy' ) );
												} else {
													?>
													<img loading="lazy" alt="" src="<?php echo esc_url( $kc_img . '/side_post_img03.jpg' ); ?>" loading="lazy">
													<?php
												}
												?>
												<div class="post-thumbnail-content">
													<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
													<span class="post-date"><i class="far fa-clock" aria-hidden="true"></i> <?php echo esc_html( sprintf( __( '%s ago', 'optix' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ) ); ?></span>
													<span class="post-category"><i class="fa fa-tag" aria-hidden="true"></i>
														<?php
														$cat = get_the_category();
														if ( ! empty( $cat ) ) {
															echo esc_html( $cat[0]->name );
														}
														?>
													</span>
													<!--post-thumbnail-content-->
												</div>
												<!--post-thumbnail-entry-->
											</div>
											<?php
										endwhile;
										wp_reset_postdata();
										?>
										<!--post-thumbnail-list-->
									</div>
									<!--tab-pane-->
								</div>
								<div class="tab-pane fade" id="recent" role="tabpanel">
									<div class="post-thumbnail-list">
										<?php
										$recent_sidebar = new WP_Query(
											array(
												'posts_per_page' => 3,
												'post_status'    => 'publish',
											)
										);
										while ( $recent_sidebar->have_posts() ) :
											$recent_sidebar->the_post();
											?>
											<div class="post-thumbnail-entry">
												<?php
												if ( has_post_thumbnail() ) {
													the_post_thumbnail( 'thumbnail', array( 'alt' => get_the_title(), 'loading' => 'lazy' ) );
												} else {
													?>
													<img loading="lazy" alt="" src="<?php echo esc_url( $kc_img . '/side_post_img02.jpg' ); ?>" loading="lazy">
													<?php
												}
												?>
												<div class="post-thumbnail-content">
													<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
													<span class="post-date"><i class="far fa-clock" aria-hidden="true"></i> <?php echo esc_html( sprintf( __( '%s ago', 'optix' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ) ); ?></span>
													<span class="post-category"><i class="fa fa-tag" aria-hidden="true"></i>
														<?php
														$cat = get_the_category();
														if ( ! empty( $cat ) ) {
															echo esc_html( $cat[0]->name );
														}
														?>
													</span>
													<!--post-thumbnail-content-->
												</div>
												<!--post-thumbnail-entry-->
											</div>
											<?php
										endwhile;
										wp_reset_postdata();
										?>
										<!--post-thumbnail-list-->
									</div>
									<!--tab-pane-->
								</div>
								<!--tab-content-->
							</div>
							<!--tabs-->
						</div>
						<!--widget-->
					</div>
					<div class="widget widget-categories">
						<div class="widget-title font_weight_600">
							<?php esc_html_e( 'Categories', 'optix' ); ?></div>
						<ul>
							<?php
							wp_list_categories(
								array(
									'title_li'     => '',
									'show_count'   => true,
									'style'        => 'list',
									'li_class'     => 'cat-item',
								)
							);
							?>
						</ul>
					</div>
					<div class="widget widget-tweeter">
						<h4 class="widget-title font_weight_600"><?php esc_html_e( 'Recent Tweets', 'optix' ); ?></h4>
						<div id="twitter-cnt">
							<ul>
								<li>Rule number 1: Don't overthink it
									<a href="https://t.co/T9Vg7b9XuI" target="_blank"
										title="Visit this link">https://t.co/T9Vg7b9XuI</a>
									<small>Sep/12/2019</small>
								</li>
								<li>Smart OR Stylish? How do you balance design principles with design trends? <a
										href="https://t.co/yBb0HKiksq" target="_blank"
										title="Visit this link">https://t.co/yBb0HKiksq</a>
									<a href="https://t.co/kR5EhraUuK" target="_blank"
										title="Visit this link">https://t.co/kR5EhraUuK</a>
									<small>Sep/10/2019</small>
								</li>
							</ul>
							<!--twitter-cnt-->
						</div>
						<!--widget-->
					</div>
					<div class="widget widget-tags">
						<h4 class="widget-title font_weight_600"><?php esc_html_e( 'Tags', 'optix' ); ?></h4>
						<div class="tags">
							<?php
							wp_tag_cloud(
								array(
									'unit'     => 'px',
									'smallest' => 14,
									'largest'  => 14,
									'format'   => 'flat',
									'orderby'  => 'name',
								)
							);
							?>
							<!--tags-->
						</div>
						<!--widget-->
					</div>
					<!--theiaStickySidebar-->
				</div>
				<!--sidebar-->
			</div>
			<!--row-->
		</div>
	</div>
	<!--container-->
</section>
