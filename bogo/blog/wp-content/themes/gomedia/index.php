<?php get_header(); ?>
		
	<div class="container">
		<div class="row">

			<div id="content" class="col-md-9">

				<header>
					<h3 class="section-title">
						<?php printf( __( 'Page %s', '' ), $paged ); ?>
					</h3>
				</header>

				<main id="recent-content-1" class="site-main content-loop" role="main">

				<?php if ( have_posts() ) : ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'content', get_post_format() ); ?>

					<?php endwhile; ?>

					<?php get_template_part( 'loop', 'nav' ); // Loads the loop-nav.php template ?>

				<?php else : ?>

					<?php get_template_part( 'content', 'none' ); ?>

				<?php endif; ?>

				</main><!-- .site-main -->
			</div><!-- #content -->

			<?php get_sidebar(); ?>

		</div><!-- .row -->
	</div><!-- .container -->

<?php get_footer(); ?>
