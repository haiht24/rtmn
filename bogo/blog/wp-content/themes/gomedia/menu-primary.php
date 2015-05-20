<?php if ( has_nav_menu( 'primary' ) ) : // Check if there's a menu assigned to the 'primary' location. ?>
	
	<!-- Mobile navigation -->
	<a href="#primary-nav" class="mobile-menu"><i class="fa fa-bars"></i></a>

	<nav id="primary-nav" class="main-navigation" role="navigation">
		<div class="container clearfix">

			<?php wp_nav_menu(
				array(
					'theme_location'  => 'primary',
					'container'       => '',
					'menu_id'         => 'primary-menu',
					'menu_class'      => 'sf-menu left',
					'fallback_cb'     => ''
				)
			); ?>

			<?php gomedia_social_links(); // Get the social links. ?>

		</div><!-- .container -->
	</nav><!-- #primary-nav -->

<?php endif; // End check for menu. ?>