<?php
/**
 * Sets up custom filters and actions for the theme.
 *
 * @package    GoMedia
 * @author     Theme Junkie
 * @copyright  Copyright (c) 2014, Theme Junkie
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 * @since      1.0.0
 */

// Register custom image sizes.
add_action( 'init', 'gomedia_register_image_sizes', 5 );

// Add custom image sizes custom name.
add_filter( 'image_size_names_choose', 'gomedia_custom_name_image_sizes', 11, 1 );

// Register sidebars.
add_action( 'widgets_init', 'gomedia_register_sidebars' );

/**
 * Registers custom image sizes for the theme.
 *
 * @since 1.0.0
 * @link  http://codex.wordpress.org/Function_Reference/add_image_size
 */
function gomedia_register_image_sizes() {
	add_image_size( 'gomedia-post-thumb', 210, 158, true );
	add_image_size( 'gomedia-carousel-thumb', 285, 195, true );
	add_image_size( 'gomedia-related-thumb', 176, 133, true );
	add_image_size( 'gomedia-widget-thumb', 60, 60, true );
	add_image_size( 'gomedia-featured', 720, 338, true );
	add_image_size( 'gomedia-featured-small', 145, 90, true );
}

/**
 * Adds custom image sizes custom name.
 *
 * @since 1.0.0
 */
function gomedia_custom_name_image_sizes( $sizes ) {
	$sizes['gomedia-post-thumb'] = __( 'Post Thumbnail', 'gomedia' );
	$sizes['gomedia-carousel-thumb'] = __( 'Carousel Thumbnail', 'gomedia' );
	$sizes['gomedia-related-thumb'] = __( 'Related Thumbnail', 'gomedia' );
	$sizes['gomedia-widget-thumb'] = __( 'Widget Thumbnail', 'gomedia' );
	$sizes['gomedia-featured'] = __( 'Featured Posts Thumbnail', 'gomedia' );
	$sizes['gomedia-featured-small'] = __( 'Small Featured Posts Thumbnail', 'gomedia' );
	return $sizes;
}

/**
 * Registers sidebars.
 *
 * @since 1.0.0
 * @link  http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function gomedia_register_sidebars() {

	register_sidebar(
		array(
			'name'          => _x( 'Primary', 'sidebar', 'gomedia' ),
			'id'            => 'primary',
			'description'   => __( 'The main sidebar.', 'gomedia' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Home Top', 'gomedia' ),
			'id'            => 'home-top',
			'description'   => __( 'This sidebar will appear only on home page.', 'gomedia' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Home Bottom', 'gomedia' ),
			'id'            => 'home-bottom',
			'description'   => __( 'This sidebar will appear only on home page.', 'gomedia' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 1', 'gomedia' ),
			'id'            => 'footer-1',
			'description'   => __( 'The footer sidebar.', 'gomedia' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 2', 'gomedia' ),
			'id'            => 'footer-2',
			'description'   => __( 'The footer sidebar.', 'gomedia' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 3', 'gomedia' ),
			'id'            => 'footer-3',
			'description'   => __( 'The footer sidebar.', 'gomedia' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 4', 'gomedia' ),
			'id'            => 'footer-4',
			'description'   => __( 'The footer sidebar.', 'gomedia' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
	
}