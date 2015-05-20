<?php get_header(); ?>

	<div class="container">
		<div class="row">

			<div id="content" class="col-md-9">

				<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
				
				<main class="site-main" role="main">

					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'content', 'page' ); ?>

						<?php
							// If comments are open or we have at least one comment, load up the comment template
							if ( comments_open() || '0' != get_comments_number() ) :
								comments_template();
							endif;
						?>

					<?php endwhile; // end of the loop. ?>

				</main><!-- #main -->
			</div><!-- #primary -->

			<?php get_sidebar(); ?>

		</div><!-- .row -->
	</div><!-- .container -->
<?php get_footer(); ?>
