<?php get_header(); ?>

	<div class="container">
		<div class="row">

			<div id="content" class="col-md-9">
				
				<?php if ( of_get_option( 'gomedia_featured_posts', '1' ) ) { ?>
					<section id="featured-content" class="row clearfix">
						
						<?php $tag = of_get_option( 'gomedia_featured_tag' ); ?>

						<?php $featured = get_posts( array( 'posts_per_page' => 3, 'tag_id' => $tag ) ); ?>

						<?php if ( $tag && $featured ) : ?>

							<div id="featured-left" class="col-md-6">

								<div id="carousel-0" class="jcarousel">

									<ul>
										<?php foreach ( $featured as $post ) : setup_postdata( $post ); ?>

											<li>
												<a href="<?php the_permalink(); ?>" rel="bookmark">
												
													<?php if ( has_post_thumbnail() ) : ?>
														<?php the_post_thumbnail( 'gomedia-featured', array( 'class' => 'entry-thumb', 'alt' => esc_attr( get_the_title() ) ) ); ?>
													<?php endif; ?>

													<div class="carousel-caption">
														<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
														<div class="entry-meta"><?php printf( __( 'by %s', 'gomedia' ), get_the_author() ) ?></div><!-- .entry-meta -->
													</div><!-- .carousel-caption -->

												</a>
											</li>

										<?php endforeach; ?>
									</ul>
									<p class="jcarousel-pagination"></p>

								</div>

							</div>

						<?php endif; wp_reset_postdata(); ?>

						<?php $featured2 = get_posts( array( 'posts_per_page' => 3, 'offset' => 3, 'tag_id' => $tag ) ); ?>

						<?php if ( $tag && $featured2 ) : ?>
							<?php $i = 0; ?>

							<div id="featured-right" class="col-md-6">

								<ul>
									<?php foreach ( $featured2 as $post ) : setup_postdata( $post ); ?>

										<li class="plain-item <?php if ( ++$i == 3 ) { echo 'last-item'; } ?> clearfix">
											<a href="<?php the_permalink(); ?>" rel="bookmark">
											
												<?php if ( has_post_thumbnail() ) : ?>
													<?php the_post_thumbnail( 'gomedia-featured-small', array( 'class' => 'entry-thumb', 'alt' => esc_attr( get_the_title() ) ) ); ?>
												<?php endif; ?>

												<div class="plain-title">
													<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
													<div class="entry-meta"><?php printf( __( 'by %s', 'gomedia' ), esc_attr( get_the_author() ) ) ?></div><!-- .entry-meta -->
												</div><!-- .carousel-caption -->

											</a>
										</li>

									<?php endforeach; ?>
								</ul>

							</div>

						<?php endif; wp_reset_postdata(); ?>
						
					</section><!-- #featured-content -->
				<?php } ?>
				
				<?php $ad = of_get_option( 'gomedia_home_ads' ); ?>
				<?php if ( $ad ) { ?>
					<div class="home-ad">
						<?php echo stripslashes( $ad ); ?>
					</div>
				<?php } ?>

				<main id="recent-content-1" class="site-main content-loop" role="main">

					<h3 class="section-title"><?php _e( 'News', 'gomedia' ); ?></h3>

					<?php $latest = get_posts( array( 'posts_per_page' => 3 ) ); ?>

					<?php if ( $latest ) : ?>
						<?php $i = 0; ?>
						<?php foreach ( $latest as $post ) : setup_postdata( $post ); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php if ( ++$i == 3 ) { echo 'style="border-bottom: none;"'; } ?>>
								
								<?php if ( has_post_thumbnail() ) : ?>
									<a class="hidden-xs" href="<?php the_permalink(); ?>" rel="bookmark">
										<div class="entry-thumb-wrapper">
											<?php the_post_thumbnail( 'gomedia-post-thumb', array( 'class' => 'entry-thumb', 'alt' => esc_attr( get_the_title() ) ) ); ?>
											<?php echo gomedia_get_post_format_icons(); // get the post format icons. ?>
										</div><!-- .entry-thumb-wrapper -->
									</a>
								<?php endif; ?>

								<header class="entry-header">
									<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

									<div class="entry-meta">
										<?php gomedia_posted_on(); ?>
									</div><!-- .entry-meta -->
								</header><!-- .entry-header -->

								<div class="entry-excerpt">
									<?php the_excerpt(); ?>
								</div><!-- .entry-excerpt -->

							</article><!-- #post-## -->

						<?php endforeach; ?>
					<?php endif; wp_reset_postdata(); ?>

				</main><!-- .site-main -->
			</div><!-- #content -->

			<?php get_sidebar( 'home-top' ); // Loads the sidebar-home-top.php templates. ?>

		</div><!-- .row -->
	</div><!-- .container -->
	
	<?php if ( of_get_option( 'gomedia_latest_videos', '1' ) ) { ?>
		<div id="carousel-1" class="carousel-loop">
			<div class="container">

				<?php
				$limit = (int) of_get_option( 'gomedia_latest_videos_num', 10 );
				$args = array(
					'tax_query' => array(
						array(
							'taxonomy' => 'post_format',
							'field'    => 'slug',
							'terms'    => array( 'post-format-video' )
						)
					),
					'posts_per_page' => $limit
				);

				$video = get_posts( $args );
				?>

				<?php if ( $video ) : ?>
				
					<h2 class="section-title"><?php _e( 'Latest Videos', 'gomedia' ); ?></h2>
				
					<div class="jcarousel jcarousel-carousel">
						<ul>
							<?php foreach ( $video as $post ) : setup_postdata( $post ); ?>
								<li>
									<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
										<?php if ( has_post_thumbnail() ) : ?>
											<a href="<?php the_permalink(); ?>" rel="bookmark">
												<?php the_post_thumbnail( 'gomedia-carousel-thumb', array( 'class' => 'entry-thumb', 'alt' => esc_attr( get_the_title() ) ) ); ?>
												<h2 class="entry-title"><?php echo wp_trim_words( get_the_title(), 8 ); ?></h2>
												<span class="icon-mark"><i class="fa fa-play"></i></span>
											</a>
										<?php endif; ?>
									</article>
								</li>
							<?php endforeach; ?>
						</ul>
					</div><!-- .jcarousel -->

					<?php if ( count( $video ) > 6 ) { ?>
						<a href="#" class="jcarousel-control-prev"><i class="fa fa-chevron-left"></i></a>
						<a href="#" class="jcarousel-control-next"><i class="fa fa-chevron-right"></i></a>
					<?php } ?>

				<?php endif; wp_reset_postdata(); ?>

			</div><!-- .container -->
		</div><!-- .carousel-loop -->
	<?php } ?>

	<div class="container">
		<div class="row">

			<div id="content-2" class="col-md-9 clearfix">
				<main id="recent-content-2" class="content-loop">

					<?php $latest2 = get_posts( array( 'posts_per_page' => 3, 'offset' => 3 ) ); ?>

					<?php if ( $latest2 ) : ?>
						<?php foreach ( $latest2 as $post ) : setup_postdata( $post ); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

								<?php if ( has_post_thumbnail() ) : ?>
									<a class="hidden-xs" href="<?php the_permalink(); ?>" rel="bookmark">
										<div class="entry-thumb-wrapper">
											<?php the_post_thumbnail( 'gomedia-post-thumb', array( 'class' => 'entry-thumb', 'alt' => esc_attr( get_the_title() ) ) ); ?>
											<?php echo gomedia_get_post_format_icons(); // get the post format icons. ?>
										</div><!-- .entry-thumb-wrapper -->
									</a>
								<?php endif; ?>

								<header class="entry-header">
									<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

									<div class="entry-meta">
										<?php gomedia_posted_on(); ?>
									</div><!-- .entry-meta -->
								</header><!-- .entry-header -->

								<div class="entry-excerpt">
									<?php the_excerpt(); ?>
								</div><!-- .entry-excerpt -->

							</article><!-- #post-## -->

						<?php endforeach; ?>

						<?php gomedia_next_posts_link(); // Get the page/1/ link. ?>
						
					<?php endif; wp_reset_postdata(); ?>

				</main><!-- .site-main -->
			</div><!-- #content -->

			<?php get_sidebar( 'home-bottom' ); // Loads the sidebar-home-top.php templates. ?>

		</div><!-- .row -->
	</div><!-- .container -->
	
	<?php if ( of_get_option( 'gomedia_editor_picks', '1' ) ) { ?>
		<div id="carousel-2" class="carousel-loop">
			<div class="container">
				<h2 class="section-title"><?php _e( 'Editor\'s Picks', 'gomedia' ); ?></h2>
				
				<?php $editor_num = (int) of_get_option( 'gomedia_editor_picks_num', 10 ); ?>
				<?php $editor_tag = of_get_option( 'gomedia_editor_picks_tag' ); ?>

				<?php $picks = get_posts( array( 'posts_per_page' => $editor_num, 'tag_id' => $editor_tag ) ); ?>

				<?php if ( $editor_tag && $picks ) : ?>
				
					<div class="jcarousel jcarousel-carousel">
						<ul>
							<?php foreach ( $picks as $post ) : setup_postdata( $post ); ?>
								<li>
									<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
										<?php if ( has_post_thumbnail() ) : ?>
											<a href="<?php the_permalink(); ?>" rel="bookmark">
												<?php the_post_thumbnail( 'gomedia-carousel-thumb', array( 'class' => 'entry-thumb', 'alt' => esc_attr( get_the_title() ) ) ); ?>
												<h2 class="entry-title"><?php echo wp_trim_words( get_the_title(), 8 ); ?></h2>
												<?php echo gomedia_get_post_format_icons(); // get the post format icons. ?>
											</a>
										<?php endif; ?>
									</article>
								</li>
							<?php endforeach; ?>
						</ul>
					</div><!-- .jcarousel -->
					
					<?php if ( count( $picks ) > 6 ) { ?>
						<a href="#" class="jcarousel-control-prev"><i class="fa fa-chevron-left"></i></a>
						<a href="#" class="jcarousel-control-next"><i class="fa fa-chevron-right"></i></a>
					<?php } ?>

				<?php endif; wp_reset_postdata(); ?>

			</div><!-- .container -->
		</div><!-- .carousel-loop -->
	<?php } ?>

<?php get_footer(); ?>