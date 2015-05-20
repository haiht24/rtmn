<?php
/**
 * Enqueue scripts and styles.
 *
 * @package    GoMedia
 * @author     Theme Junkie
 * @copyright  Copyright (c) 2014, Theme Junkie
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 * @since      1.0.0
 */

/**
 * Loads the theme styles & scripts.
 *
 * @since 1.0.0
 * @link  http://codex.wordpress.org/Function_Reference/wp_enqueue_script
 * @link  http://codex.wordpress.org/Function_Reference/wp_enqueue_style
 */
function gomedia_enqueue() {

	// Load bootstrap styles.
	wp_enqueue_style( 'gomedia-bootstrap-css', trailingslashit( get_template_directory_uri() ) . 'assets/css/bootstrap.min.css', null, null );

	// Load bootstrap theme styles.
	wp_enqueue_style( 'gomedia-bootstrap-theme', trailingslashit( get_template_directory_uri() ) . 'assets/css/bootstrap-theme.min.css', null, null );
	

	// if WP_DEBUG and/or SCRIPT_DEBUG turned on, load the unminified styles & script.
	if ( WP_DEBUG || SCRIPT_DEBUG ) {

		// Load main stylesheet
		wp_enqueue_style( 'gomedia-style', get_stylesheet_uri() );

		// Load custom js plugins.
		wp_enqueue_script( 'gomedia-plugins', trailingslashit( get_template_directory_uri() ) . 'assets/js/plugins.js', array( 'jquery' ), null, true );

		// Load custom js methods.
		wp_enqueue_script( 'gomedia-main', trailingslashit( get_template_directory_uri() ) . 'assets/js/main.js', array( 'jquery' ), null, true );

	} else {

		// Load main stylesheet
		wp_enqueue_style( 'gomedia-style', trailingslashit( get_template_directory_uri() ) . 'style.min.css' );

		// If child theme is active, load the stylesheet.
		if ( is_child_theme() ) {
			wp_enqueue_style( 'gomedia-child-style', get_stylesheet_uri() );
		}

		// Load custom js plugins.
		wp_enqueue_script( 'gomedia-scripts', trailingslashit( get_template_directory_uri() ) . 'assets/js/gomedia.min.js', array( 'jquery' ), null, true );

	}

	// Load bootstrap scripts.
	wp_enqueue_script( 'gomedia-bootstrap', trailingslashit( get_template_directory_uri() ) . 'assets/js/bootstrap.min.js', array( 'jquery' ), null, true );

	// Load comment-reply script.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'gomedia_enqueue' );

/**
 * Loads HTML5 Shiv & Respond js file.
 * 
 * @since  1.0.0
 */
function gomedia_special_scripts() {
?>
<!--[if lt IE 9]>
<script src="<?php echo trailingslashit( get_template_directory_uri() ) . 'assets/js/html5shiv.js'; ?>"></script>
<![endif]-->
<?php
}
add_action( 'wp_head', 'gomedia_special_scripts', 15 );

/**
 * js / no-js script.
 *
 * @since  1.0.0
 */
function gomedia_no_js_script() {
?>
<script>document.documentElement.className = 'js';</script>
<?php
}
add_action( 'wp_footer', 'gomedia_no_js_script' );