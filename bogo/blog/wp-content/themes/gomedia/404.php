<?php get_header(); ?>

	<div class="container">
		<div class="row">

			<div id="content" class="col-md-9">
				<main class="site-main" role="main">

					<section class="error-404 not-found">

						<div class="entry-content">

							<div class="row">       
								<div class="col-md-5">
									<h1 class="error-title"><?php _e( '404!', 'gomedia' ); ?></h1>
								</div>
								<div class="col-md-7">
									<h2><?php echo strip_tags( of_get_option( 'gomedia_404_title', __( 'Page Not Found', 'gomedia' ) ) ); ?></h2>
									<p><?php echo stripslashes( strip_tags( of_get_option( 'gomedia_404_desc', __( 'We\'re sorry — something has gone wrong on our end. We might have removed the page. Or the link you clicked might be old and does not work anymore. Or you might have accidentally typed the wrong URL in the address bar.', 'gomedia' ) ) ) ); ?>           
								</div>
							</div>

							<h1 class="error-msg"><?php _e( 'In the mean time, you can...', 'gomedia' ); ?></h1>

							<p><?php _e( 'While we work on resolving the problem, here are couple of things you can do:', 'gomedia' ); ?></p>
							<ul>
								<li><?php _e( 'You might try retyping the URL and trying again.', 'gomedia' ) ?></li>
								<li><?php printf( __( 'Go to the <a href="%s">main page</a>, and check out some of our latest posts.', 'gomedia' ), esc_url( home_url() ) ); ?></li>
								<li><?php _e( '<strong>Do a search</strong>. The search bar in on the top-right corner.', 'gomedia' ); ?></li>
								<li>
									<?php printf( __( '<a href="%1$s">Visit us on Facebook</a>, <a href="%2$s">follow our tweets</a> or <a href="%3$s">sign-up our newsletter</a> (it\'s free!).', 'gomedia' ), esc_url( of_get_option( 'gomedia_fb' ) ), esc_url( of_get_option( 'gomedia_tw' ) ), esc_url( of_get_option( 'gomedia_newsletter' ) ) ); ?>
								</li>

								<?php if ( of_get_option( 'gomedia_404_contact' ) ) { ?>
									<li><?php printf( __( 'If you think we need to look into this urgently, <a href="%s">contact us</a> via our online form.', 'gomedia' ), esc_url( of_get_option( 'gomedia_404_contact' ) ) ); ?></li>
								<?php } ?>

							</ul>

						</div><!-- .entry-content -->

					</section><!-- .error-404 -->

				</main><!-- #main -->
			</div><!-- #primary -->

			<?php get_sidebar(); ?>

		</div><!-- .row -->
	</div><!-- .container -->
	
<?php get_footer(); ?>