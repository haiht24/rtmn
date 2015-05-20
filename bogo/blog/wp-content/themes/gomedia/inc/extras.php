<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package    GoMedia
 * @author     Theme Junkie
 * @copyright  Copyright (c) 2014, Theme Junkie
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 * @since      1.0.0
 */

// wp_nav_menu() fallback.
add_filter( 'wp_page_menu_args', 'gomedia_page_menu_args' );

// Custom classes to the body classes.
add_filter( 'body_class', 'gomedia_body_classes' );

// Custom classes to the post classes.
add_filter( 'post_class', 'gomedia_post_classes' );

// Filters wp_title.
add_filter( 'wp_title', 'gomedia_wp_title', 10, 2 );

// Sets the authordata global when viewing an author archive.
add_action( 'wp', 'gomedia_setup_author' );

// Generates the relevant template info.
add_action( 'wp_head', 'gomedia_meta_template', 10 );

// Change the excerpt length.
add_filter( 'excerpt_length', 'gomedia_excerpt_length', 999 );

// Change the excerpt more string.
add_filter( 'excerpt_more', 'gomedia_excerpt_more' );

// Matched the pagination markup with bootstrap.
add_filter( 'loop_pagination', 'gomedia_bootstrap_pagination' );

// Register custom contact info fields.
add_filter( 'user_contactmethods', 'gomedia_contact_info_fields' );

// Removes default styles set by WordPress recent comments widget.
add_action( 'widgets_init', 'gomedia_remove_recent_comments_style' );

// Override the default options.php location.
add_filter( 'options_framework_location', 'gomedia_location_override' );

// Change the theme option text.
add_filter( 'optionsframework_menu', 'gomedia_theme_options_text' );

// Custom comment form fields.
add_filter( 'comment_form_default_fields', 'gomedia_comment_form_fields' );

// Custom comment form submit field.
add_action( 'comment_form', 'gomedia_comment_button' );

// Filter to fix first page redirect.
add_filter( 'redirect_canonical', 'gomedia_page_one_redirect' );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since  1.0.0
 * @param  array $args Configuration arguments.
 * @return array
 */
function gomedia_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @since  1.0.0
 * @param  array $classes Classes for the body element.
 * @return array
 */
function gomedia_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}

/**
 * Adds custom classes to the array of post classes.
 *
 * @since  1.0.0
 * @param  array $classes Classes for the post element.
 * @return array
 */
function gomedia_post_classes( $classes ) {

	// Adds a clearing the float class.
	if ( ! is_single() ) {
		$classes[] = 'clearfix';
	}

	// Adds a grid class to the single post.
	if ( is_single() ) {
		$classes[] = 'col-md-10';
	}

	return $classes;
}

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @since  1.0.0
 * @param  string $title Default title text for current view.
 * @param  string $sep Optional separator.
 * @return string The filtered title.
 */
function gomedia_wp_title( $title, $sep ) {

	if ( is_feed() ) {
		return $title;
	}
	
	global $page, $paged;

	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'gomedia' ), max( $paged, $page ) );
	}

	return $title;
}

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @since  1.0.0
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function gomedia_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}

/**
 * Generates the relevant template info.  Adds template meta with theme version.  Uses the theme 
 * name and version from style.css.
 *
 * @since 1.0.0
 */
function gomedia_meta_template() {
	$theme    = wp_get_theme( get_template() );
	$template = sprintf( '<meta name="template" content="%1$s %2$s" />' . "\n", esc_attr( $theme->get( 'Name' ) ), esc_attr( $theme->get( 'Version' ) ) );

	echo apply_filters( 'gomedia_meta_template', $template );
}

/**
 * Control the excerpt length.
 *
 * @since  1.0.0
 * @param  $length
 */
function gomedia_excerpt_length( $length ) {
	return 45;
}

/**
 * Change the excerpt more string.
 *
 * @since  1.0.0
 * @param  string  $more
 */
function gomedia_excerpt_more( $more ) {
	return '&hellip; <a href="'. esc_url( get_permalink() ) . '">' . __( 'Read More', 'gomedia' ) . '</a>';
}

/**
 * Matched the pagination markup with bootstrap.
 *
 * @since  1.0.0
 * @param  string  $page_links
 */
function gomedia_bootstrap_pagination( $page_links ) {
	// Wrap page_link anchors in li
	$page_links = str_replace( '<a', '<li><a', $page_links );
	$page_links = str_replace( '</a>', '</a></li>', $page_links );
	// add active class to current li
	$page_links = str_replace( '<span', '<li class="active"><span', $page_links );
	$page_links = str_replace( '</span>', '</span></li>', $page_links );

	return $page_links;
}

/**
 * Register custom contact info fields.
 *
 * @since  1.0.0
 * @param  array $contactmethods
 * @return array
 */
function gomedia_contact_info_fields( $contactmethods ) {
	$contactmethods['twitter']  = 'Twitter';

	return $contactmethods;
}

/**
 * Removes default styles set by WordPress recent comments widget.
 *
 * @since 1.0.0
 */
function gomedia_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}

/**
 * Override the default options.php location.
 *
 * @since  1.0.0
 */
function gomedia_location_override() {
	return array( 'admin/options.php' );
}

/**
 * Change the theme options text.
 *
 * @since  1.0.0
 * @param  array $menu
 */
function gomedia_theme_options_text( $menu ) {
	$menu['page_title'] = __( 'GoMedia Settings', 'gomedia' );
	$menu['menu_title'] = __( 'Theme Settings', 'gomedia' );

	return $menu;
}

/**
 * Custom comment form fields.
 *
 * @since  1.0.0
 * @param  array $fields
 * @return array
 */
function gomedia_comment_form_fields( $fields ) {

	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? " aria-required='true'" : '' );

	$fields['author'] = '<div class="row"><div class="form-group col-md-4 comment-form-author"><label for="author">' . __( 'Name (required)', 'gomedia' ) . '</label><input class="form-control" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>';

	$fields['email'] = '<div class="form-group col-md-4 comment-form-email"><label for="email">' . __( 'Email (required)', 'gomedia' ) . '</label><input class="form-control" id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></div>';

	$fields['url'] = '<div class="form-group col-md-4 comment-form-url"><label for="url">' . __( 'Website (optional)', 'gomedia' ) . '</label><input class="form-control" id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></div></div><!-- .row -->';


	return $fields;

}

/**
 * Custom comment form submit field.
 * 
 * @since  1.0.0
 */
function gomedia_comment_button() {
	echo '<button type="submit" class="btn btn-muted">' . __( 'Submit Comment', 'gomedia' ) . '</button>';
}

/**
 * Filter to fix first page redirect.
 *
 * @since  1.0.0
 */
function gomedia_page_one_redirect( $redirect_url ) {
	if ( get_query_var('paged') == 1 ) {
		$redirect_url = '';
	}

	return $redirect_url;
}