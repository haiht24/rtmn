<?php get_header(); ?>

	<div class="container">
		<div class="row">

			<section id="content" class="col-md-9">

				<?php if ( have_posts() ) : ?>

					<header>
						<h3 class="section-title"><?php printf( __( 'Search Results for: %s', 'gomedia' ), '<span>' . get_search_query() . '</span>' ); ?></h3>
					</header><!-- .page-header -->
					
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
