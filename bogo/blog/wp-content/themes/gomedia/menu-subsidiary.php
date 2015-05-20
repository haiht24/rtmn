<?php if ( has_nav_menu( 'subsidiary' ) ) : // Check if there's a menu assigned to the 'subsidiary' location. ?>

	<nav id="subsidiary-nav" class="bottom-left col-xs-12 col-md-7" role="navigation">

		<?php wp_nav_menu(
			array(
				'theme_location'  => 'subsidiary',
				'container'       => '',
				'menu_id'         => 'subsidiary-menu',
				'menu_class'      => 'subsidiary-menu-items',
				'depth'           => 1,
				'fallback_cb'     => ''
			)
		); ?>

	</nav><!-- #subsidiary-nav -->

<?php endif; // End check for menu. ?>