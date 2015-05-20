<?php get_header(); ?>

	<div class="container">
		<div class="row">

			<section id="content" class="col-md-9">

				<?php if ( have_posts() ) : ?>

					<header>
						<h3 class="section-title">
							<?php
								if ( is_category() ) :
									single_cat_title();

								elseif ( is_tag() ) :
									single_tag_title();

								elseif ( is_author() ) :
									printf( __( 'Author: %s', 'gomedia' ), '<span class="vcard">' . get_the_author() . '</span>' );

								elseif ( is_day() ) :
									printf( __( 'Day: %s', 'gomedia' ), '<span>' . get_the_date() . '</span>' );

								elseif ( is_month() ) :
									printf( __( 'Month: %s', 'gomedia' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'gomedia' ) ) . '</span>' );

								elseif ( is_year() ) :
									printf( __( 'Year: %s', 'gomedia' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'gomedia' ) ) . '</span>' );

								elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
									_e( 'Galleries', 'gomedia' );

								elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
									_e( 'Images', 'gomedia' );

								elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
									_e( 'Videos', 'gomedia' );

								else :
									_e( 'Archives', 'gomedia' );

								endif;
							?>
						</h3>
					</header>

					<main id="recent-content-1" class="site-main content-loop" role="main">

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'content', get_post_format() ); ?>

					<?php endwhile; ?>

					<?php get_template_part( 'loop', 'nav' ); // Loads the loop-nav.php template ?>

				<?php else : ?>

					<?php get_template_part( 'content', 'none' ); ?>

				<?php endif; ?>

				</main><!-- #main -->
			</section><!-- #primary -->

			<?php get_sidebar(); ?>

		</div><!-- .row -->
	</div><!-- .container -->
<?php get_footer(); ?>