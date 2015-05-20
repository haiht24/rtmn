<?php if ( has_nav_menu( 'secondary' ) ) : // Check if there's a menu assigned to the 'secondary' location. ?>

	<nav id="secondary-nav" class="main-navigation" role="navigation">
		<div class="container">
			<div class="row">

				<?php wp_nav_menu(
					array(
						'theme_location'  => 'secondary',
						'container'       => '',
						'menu_id'         => 'secondary-menu',
						'menu_class'      => 'sf-menu col-md-9',
						'fallback_cb'     => ''
					)
				); ?>

				<?php get_search_form(); // Loads the searchform.php template. ?>

			</div>
		</div><!-- .container -->
	</nav><!-- #secondary-nav -->

<?php endif; // End check for menu. ?>