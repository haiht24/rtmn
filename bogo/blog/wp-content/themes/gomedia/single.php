<?php get_header(); ?>

	<div class="container">
		<div class="row">

			<div id="content" class="col-md-9">
				<main class="site-main row" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'single' ); ?>

				<?php endwhile; // end of the loop. ?>

				</main><!-- #main -->
			</div><!-- #primary -->

			<?php get_sidebar(); ?>

		</div><!-- .row -->
	</div><!-- .container -->
	
<?php get_footer(); ?>