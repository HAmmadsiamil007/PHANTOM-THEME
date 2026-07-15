<?php
/**
 * Kids Collection - Single Blog
 *
 * @package optix
 */

$kc_img = kc_img_base();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		?>
		<!-- SUB BANNER SECTION -->
		<section class="sub-banner-con position-relative float-left w-100 gradient-overlay d-flex align-items-center justify-content-center">
			<div class="container">
				<div class="col-xl-12 col-lg-12 mr-auto ml-auto">
					<div class="sub-banner-inner-con text-center">
						<h1 class=""><?php the_title(); ?></h1>
						<div class="breadcrumb-con d-inline-block">
							<ol class="breadcrumb mb-0">
								<li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
							</ol>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Single Blog -->
		<section class="singleblog-section blogpage-section float-left w-100">
			<div class="container ">
				<div class="row">
					<div class="col-lg-8 col-md-12 col-sm-12 col-12 wow fadeInLeft" data-wow-duration="2s"
						data-wow-delay="0.3s">
						<div class="main-box">
							<figure class="image1 mb-3">
								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail( 'full', array( 'class' => 'img-fluid', 'loading' => 'lazy' ) );
								} else {
									?>
									<img loading="lazy" src="<?php echo esc_url( $kc_img . '/singleblog-image1.jpg' ); ?>" alt="" class="img-fluid"
										loading="lazy">
									<?php
								}
								?>
							</figure>
							<div class="content1">
								<h4><?php the_title(); ?></h4>
								<div class="span-fa-outer-con">
									<i class="fa-solid fa-user" aria-hidden="true"></i>
									<span class="text-size-14 text-mr"><?php printf( esc_html__( 'By : %s', 'optix' ), get_the_author_posts_link() ); ?></span>
									<i class="mb-0 calendar fa-solid fa-calendar-days" aria-hidden="true"></i>
									<span class="mb-0 text-size-14"><?php echo esc_html( get_the_date() ); ?></span>
								</div>
								<div class="text-size-16">
									<?php the_content(); ?>
								</div>
							</div>
							<div class="content2">
								<figure class="singleblog-quoteimage">
									<img loading="lazy" src="<?php echo esc_url( optix_img( optix_get_option( 'single_blog_quote_image' ), $kc_img . '/singleblog-quoteimage.png' ) ); ?>" alt="" class="img-fluid"
										loading="lazy">
								</figure>
								<p class="mb-0 text-white text-size-18"><?php echo wp_kses_post( optix_get_option( 'single_blog_quote_text', '&ldquo;Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.xcepteur sint occaecat&rdquo;' ) ); ?></p>
							</div>
							<div class="content4">
								<div class="row">
									<div class="col-lg-8 col-md-8 col-sm-7 col-12">
										<div class="tag">
											<h4><?php echo esc_html( optix_get_option( 'single_blog_tags_heading', 'Releted Tags' ) ); ?></h4>
											<ul class="mb-0 list-unstyled ">
												<?php
												$posttags = get_the_tags();
												if ( $posttags ) {
													foreach ( $posttags as $tag ) {
														echo '<li><a class="button text-decoration-none" href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . esc_html( $tag->name ) . '</a></li>';
													}
												}
												?>
											</ul>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-5 col-12">
										<div class="icon">
											<h4><?php echo esc_html( optix_get_option( 'single_blog_social_heading', 'Social Share' ) ); ?></h4>
											<div class="social-icons position-absolute">
												<ul class="mb-0 list-unstyled ">
													<li><a href="https://www.linkedin.com/login"
															class="text-decoration-none"><i
																class="fa-brands fa-linkedin-in social-networks" aria-hidden="true"></i></a>
													</li>
													<li><a href="https://www.instagram.com/" class="text-decoration-none"><i
																class="fa-brands fa-instagram social-networks" aria-hidden="true"></i></a></li>
													<li><a href="https://www.facebook.com/login/"
															class="text-decoration-none"><i
																class="fa-brands fa-facebook-f social-networks" aria-hidden="true"></i></a>
													</li>
													<li><a href="https://twitter.com/i/flow/login"
															class="text-decoration-none"><i
																class="fa-brands fa-x-twitter social-networks" aria-hidden="true"></i></a></li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="buttons aos-init aos-animate">
								<div class="prev">
									<?php previous_post_link( '%link', '<span class="prev-text">' . esc_html( optix_get_option( 'single_blog_prev_text', 'Prev' ) ) . '</span>' ); ?>
								</div>
								<div class="next">
									<?php next_post_link( '%link', '<span class="next-text">' . esc_html( optix_get_option( 'single_blog_next_text', 'Next' ) ) . '</span>' ); ?>
								</div>
							</div>
							<div class="content5">
								<figure class="singleblog-review1 mb-0">
									<img loading="lazy" src="<?php echo esc_url( optix_img( optix_get_option( 'single_blog_author_image' ), $kc_img . '/singleblog-review1.jpg' ) ); ?>" alt="" class="img-fluid"
										loading="lazy">
								</figure>
								<div class="content">
									<h4><?php echo esc_html( optix_get_option( 'single_blog_author_name', 'Billy wallson' ) ); ?></h4>
									<span class="text-size-16"><?php echo esc_html( optix_get_option( 'single_blog_author_role', 'Senior Director' ) ); ?></span>
									<p class="text-size-16"><?php echo esc_html( optix_get_option( 'single_blog_author_bio', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut ali,' ) ); ?></p>
								</div>
							</div>
							<?php
							if ( comments_open() || get_comments_number() ) :
								?>
								<div class="content6">
									<h4><?php
										$comment_count = get_comments_number();
										printf(
											esc_html( _n( '%s Comment', '%s Comments', $comment_count, 'optix' ) ),
											number_format_i18n( $comment_count )
										);
										?></h4>
									<?php
									wp_list_comments(
										array(
											'style'       => 'div',
											'callback'    => function ( $comment, $args, $depth ) use ( $kc_img ) {
												?>
												<div class="comment">
													<div class="image" data-aos="flip-left">
														<?php echo get_avatar( $comment, 70, '', '', array( 'class' => 'avatar', 'loading' => 'lazy' ) ); ?>
													</div>
													<div class="content">
														<h5><?php comment_author_link(); ?></h5>
														<span class="text-size-14"><?php echo get_comment_date(); ?></span>
														<?php comment_reply_link(
															array_merge(
																$args,
																array(
																	'reply_text' => '<a class="reply text-decoration-none" href="#">' . esc_html( optix_get_option( 'single_blog_reply_text', 'Reply' ) ) . '</a>',
																	'depth'      => $depth,
																	'max_depth'  => $args['max_depth'],
																)
															)
														); ?>
														<div class="text_holder">
															<p class="text-size-16"><?php comment_text(); ?></p>
														</div>
													</div>
												</div>
												<?php
											},
										)
									);
									?>
								</div>
								<div class="content7">
									<h4><?php echo esc_html( optix_get_option( 'single_blog_comment_heading', 'Leave a Comment' ) ); ?></h4>
									<?php
									comment_form(
										array(
											'class_submit'  => 'post_comment',
											'comment_field' => '<div class="row"><div class="col-12"><div class="form-group mb-0"><textarea class="form_style" placeholder="' . esc_attr( optix_get_option( 'single_blog_comment_placeholder', 'Enter your comment here...' ) ) . '" rows="3" name="comment"></textarea></div></div></div>',
											'fields'        => array(
												'author' => '<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-12"><div class="form-group mb-0"><input type="text" class="form_style" placeholder="' . esc_attr( optix_get_option( 'single_blog_comment_name_placeholder', 'Your name' ) ) . '" name="author"></div></div></div>',
												'email'  => '<div class="col-lg-6 col-md-6 col-sm-6 col-12"><div class="form-group mb-0"><input type="email" class="form_style" placeholder="' . esc_attr( optix_get_option( 'single_blog_comment_email_placeholder', 'Your e-mail' ) ) . '" name="email"></div></div></div>',
											),
											'submit_button' => '<div class="button text-center"><button type="submit" class="post_comment">' . esc_html( optix_get_option( 'single_blog_comment_btn', 'Post Comment' ) ) . '</button></div>',
										)
									);
									?>
								</div>
								<?php
							endif;
							?>
						</div>
					</div>
					<div class="col-lg-4 col-md-12 col-sm-12 col-12 column wow fadeInRight" data-wow-duration="2s"
						data-wow-delay="0.4s">
						<div class="box1">
							<h5><?php echo esc_html( optix_get_option( 'single_blog_search_heading', 'Search News' ) ); ?></h5>
							<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<div class="form-row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<input type="text" name="s" id="searchblog" class="form-control upper_layer"
											placeholder="<?php echo esc_attr( optix_get_option( 'single_blog_search_placeholder', 'Search Here...' ) ); ?>" aria-label="<?php esc_attr_e( 'Search blog posts', 'optix' ); ?>">
										<div class="input-group-append form-button">
											<button class="btn search" name="btnsearch" id="searchbtn" type="submit" aria-label="<?php esc_attr_e( 'Search', 'optix' ); ?>"><i
													class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></button>
										</div>
									</div>
								</div>
							</form>
						</div>
						<div class="box1 box2">
							<h5><?php echo esc_html( optix_get_option( 'single_blog_categories_heading', 'Popular Category' ) ); ?></h5>
							<ul class="list-unstyled mb-0">
								<?php
								wp_list_categories(
									array(
										'title_li' => '',
										'style'    => 'list',
									)
								);
								?>
							</ul>
						</div>
						<div class="box1 box3">
							<h5><?php echo esc_html( optix_get_option( 'single_blog_follow_heading', 'Follow Us' ) ); ?></h5>
							<div class="social-icons">
								<ul class="mb-0 list-unstyled ">
									<li><a href="https://www.linkedin.com/login" class="text-decoration-none"><i
												class="fa-brands fa-linkedin-in social-networks" aria-hidden="true"></i></a>
									</li>
									<li><a href="https://www.instagram.com/" class="text-decoration-none"><i
												class="fa-brands fa-instagram social-networks" aria-hidden="true"></i></a></li>
									<li><a href="https://www.facebook.com/login/" class="text-decoration-none"><i
												class="fa-brands fa-facebook-f social-networks" aria-hidden="true"></i></a>
									</li>
									<li><a href="https://twitter.com/" class="text-decoration-none"><i
												class="fa-brands fa-x-twitter social-networks" aria-hidden="true"></i></a></li>
								</ul>
							</div>
						</div>
						<div class="box1 box4">
							<h5><?php echo esc_html( optix_get_option( 'single_blog_tags_sidebar_heading', 'Tags' ) ); ?></h5>
							<ul class="tag mb-0 list-unstyled">
								<?php
								$tags = get_tags();
								if ( $tags ) {
									foreach ( $tags as $tag ) {
										echo '<li><a class="button text-decoration-none" href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . esc_html( $tag->name ) . '</a></li>';
									}
								}
								?>
							</ul>
						</div>
						<div class="box1 box5">
							<h5><?php echo esc_html( optix_get_option( 'single_blog_feeds_heading', 'Feeds' ) ); ?></h5>
							<?php
							$recent_posts = wp_get_recent_posts(
								array(
									'numberposts' => 4,
									'post_status' => 'publish',
								)
							);
							foreach ( $recent_posts as $recent ) :
								?>
								<div class="feed">
									<figure class="feed-image mb-0">
										<?php
										if ( has_post_thumbnail( $recent['ID'] ) ) {
											echo get_the_post_thumbnail( $recent['ID'], 'thumbnail', array( 'class' => 'img-fluid', 'loading' => 'lazy' ) );
										} else {
											?>
											<img loading="lazy" src="<?php echo esc_url( $kc_img . '/singleblog-feed1.jpg' ); ?>" alt="" class="img-fluid"
												loading="lazy">
											<?php
										}
										?>
									</figure>
									<a href="<?php echo esc_url( get_permalink( $recent['ID'] ) ); ?>" class="mb-0"><?php echo esc_html( $recent['post_title'] ); ?></a>
								</div>
								<?php
							endforeach;
							wp_reset_postdata();
							?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
	endwhile;
endif;
?>
