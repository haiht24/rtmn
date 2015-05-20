<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 *
 * @package    GoMedia
 * @author     Theme Junkie
 * @copyright  Copyright (c) 2014, Theme Junkie
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 * @since      1.0.0
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet
	$themename = wp_get_theme();
	$themename = preg_replace("/\W/", "_", strtolower( $themename ) );

	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = $themename;
	update_option( 'optionsframework', $optionsframework_settings );

}

/**
 * Defines an array of options that will be used to generate the settings page 
 * and be saved in the database.
 *
 * @since  1.0.0
 * @access public
 */
function optionsframework_options() {

	// Pull all tags into an array.
	$tags = array();
	$tags_obj = get_tags();
	foreach ( $tags_obj as $tag ) {
		$tags[$tag->term_id] = esc_html( $tag->name );
	}

	$options = array();

	$options[] = array(
		'name' => __( 'General', 'gomedia' ),
		'type' => 'heading'
	);

	$options['gomedia_logo'] = array(
		'name' => __( 'Logo Uploader', 'gomedia' ),
		'desc' => __( 'Upload your custom logo, it will automatically replace the Site Title', 'gomedia' ),
		'id'   => 'gomedia_logo',
		'type' => 'upload'
	);

	$options['gomedia_favicon'] = array(
		'name' => __( 'Favicon Uploader', 'gomedia' ),
		'desc' => __( 'Upload your custom favicon.', 'gomedia' ),
		'id'   => 'gomedia_favicon',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => __( 'Display back to top link', 'gomedia' ),
		'desc' => __( 'Enable the back to top link in the footer', 'gomedia' ),
		'id' => 'gomedia_to_top',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __( 'Home Page', 'gomedia' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Display Featured Posts', 'gomedia' ),
		'desc' => __( 'Enable the featured posts area', 'gomedia' ),
		'id' => 'gomedia_featured_posts',
		'std' => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name'    => __( 'Featured Posts Tag', 'gomedia' ),
		'desc'    => __( 'Select a tag to be used as Featured Posts', 'gomedia' ),
		'id'      => 'gomedia_featured_tag',
		'class'   => 'hidden',
		'type'    => 'select',
		'options' => $tags
	);

	$options[] = array(
		'name' => __( 'Display Latest Videos', 'gomedia' ),
		'desc' => __( 'Enable the latest videos area', 'gomedia' ),
		'id'   => 'gomedia_latest_videos',
		'std'  => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name'  => __( 'Latest Videos Number', 'gomedia' ),
		'desc'  => __( 'Number of the latest videos to show. -1 to display all videos', 'gomedia' ),
		'id'    => 'gomedia_latest_videos_num',
		'std'   => 10,
		'class' => 'hidden',
		'type'  => 'text'
	);

	$options[] = array(
		'name' => __( 'Display Editor\'s Picks', 'gomedia' ),
		'desc' => __( 'Enable the editor\'s picks area', 'gomedia' ),
		'id'   => 'gomedia_editor_picks',
		'std'  => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name'    => __( 'Editor\'s Picks Tag', 'gomedia' ),
		'desc'    => __( 'Select a tag to be used as Editor\'s Picks Posts', 'gomedia' ),
		'id'      => 'gomedia_editor_picks_tag',
		'class'   => 'hidden',
		'type'    => 'select',
		'options' => $tags
	);

	$options[] = array(
		'name'  => __( 'Editor\'s Picks Number', 'gomedia' ),
		'desc'  => __( 'Number of the Editor\'s Picks to show. -1 to display all posts', 'gomedia' ),
		'id'    => 'gomedia_editor_picks_num',
		'std'   => 10,
		'class' => 'hidden',
		'type'  => 'text'
	);

	$options[] = array(
		'name' => __( 'Single Posts', 'gomedia' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Display author info ', 'gomedia' ),
		'desc' => __( 'Enable the author biographical info', 'gomedia' ),
		'id'   => 'gomedia_post_author',
		'std'  => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __( 'Display share link', 'gomedia' ),
		'desc' => __( 'Enable the share link on single post', 'gomedia' ),
		'id'   => 'gomedia_post_share',
		'std'  => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __( 'Display related posts', 'gomedia' ),
		'desc' => __( 'Enable the related posts on single post', 'gomedia' ),
		'id'   => 'gomedia_related',
		'std'  => '1',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __( 'Social Links', 'gomedia' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => '',
		'desc' => __( 'The social links will appear at the top of your site.', 'gomedia' ),
		'type' => 'info'
	);

	$options['gomedia_enable_social'] = array(
		'name' => __( 'Enable ', 'gomedia' ),
		'desc' => __( 'Enable social links', 'gomedia' ),
		'id'   => 'gomedia_enable_social',
		'std'  => '1',
		'type' => 'checkbox'
	);

	$options['gomedia_label'] = array(
		'name' => __( 'Label', 'gomedia' ),
		'desc' => __( 'The label will appear beside the social links', 'gomedia' ),
		'id'   => 'gomedia_label',
		'std'  => __( 'Follow Us', 'gomedia' ),
		'type' => 'text'
	);

	$options['gomedia_fb'] = array(
		'name' => __( 'Facebook', 'gomedia' ),
		'desc' => __( 'Facebook profile url', 'gomedia' ),
		'id'   => 'gomedia_fb',
		'placeholder' => 'http://',
		'type' => 'text'
	);

	$options['gomedia_tw'] = array(
		'name' => __( 'Twitter', 'gomedia' ),
		'desc' => __( 'Twitter profile url', 'gomedia' ),
		'id'   => 'gomedia_tw',
		'placeholder' => 'http://',
		'type' => 'text'
	);

	$options['gomedia_gplus'] = array(
		'name' => __( 'Google Plus', 'gomedia' ),
		'desc' => __( 'Google Plus profile url', 'gomedia' ),
		'id'   => 'gomedia_gplus',
		'placeholder' => 'http://',
		'type' => 'text'
	);

	$options['gomedia_pinterest'] = array(
		'name' => __( 'Pinterest', 'gomedia' ),
		'desc' => __( 'Pinterest profile url', 'gomedia' ),
		'id'   => 'gomedia_pinterest',
		'placeholder' => 'http://',
		'type' => 'text'
	);

	$options['gomedia_linkedin'] = array(
		'name' => __( 'LinkedIn', 'gomedia' ),
		'desc' => __( 'LinkedIn profile url', 'gomedia' ),
		'id'   => 'gomedia_linkedin',
		'placeholder' => 'http://',
		'type' => 'text'
	);

	$options['gomedia_feed'] = array(
		'name' => __( 'RSS Feed', 'gomedia' ),
		'desc' => __( 'RSS Feed url', 'gomedia' ),
		'id'   => 'gomedia_feed',
		'placeholder' => 'http://',
		'type' => 'text'
	);

	$options['gomedia_newsletter'] = array(
		'name' => __( 'Newsletter', 'gomedia' ),
		'desc' => __( 'Newsletter url', 'gomedia' ),
		'id'   => 'gomedia_newsletter',
		'placeholder' => 'http://',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( '404 Page', 'gomedia' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Title', 'gomedia' ),
		'desc' => __( '404 page title', 'gomedia' ),
		'id'   => 'gomedia_404_title',
		'std'  => __( 'Page Not Found', 'gomedia' ),
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Description', 'gomedia' ),
		'desc' => __( '404 page description', 'gomedia' ),
		'id'   => 'gomedia_404_desc',
		'std'  => __( 'We\'re sorry — something has gone wrong on our end. We might have removed the page. Or the link you clicked might be old and does not work anymore. Or you might have accidentally typed the wrong URL in the address bar.', 'gomedia' ),
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => __( 'Contact page', 'gomedia' ),
		'desc' => __( 'Contact page url', 'gomedia' ),
		'id'   => 'gomedia_404_contact',
		'placeholder' => 'http://',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Advertisement', 'gomedia' ),
		'type' => 'heading'
	);

	$options['gomedia_header_ads'] = array(
		'name' => __( 'Header Advertisement', 'gomedia' ),
		'desc' => __( 'Add your ad code to the text box. Recommended size 728x90', 'gomedia' ),
		'id'   => 'gomedia_header_ads',
		'type' => 'textarea'
	);

	$options['gomedia_home_ads'] = array(
		'name' => __( 'Home Advertisement', 'gomedia' ),
		'desc' => __( 'The ad will appear at the bottom of featured posts on home page. Recommended size 728x90', 'gomedia' ),
		'id'   => 'gomedia_home_ads',
		'type' => 'textarea'
	);

	$options[] = array(
		'name' => __( 'Custom Code', 'gomedia' ),
		'type' => 'heading'
	);

	$options['gomedia_script_head'] = array(
		'name' => __( 'Header code', 'gomedia' ),
		'desc' => __( 'If you need to add custom scripts to your header (meta tag verification, google fonts url), you should enter them in the box. They will be added before &lt;/head&gt; tag', 'gomedia' ),
		'id'   => 'gomedia_script_head',
		'type' => 'textarea'
	);

	$options['gomedia_script_footer'] = array(
		'name' => __( 'Footer code', 'gomedia' ),
		'desc' => __( 'If you need to add custom scripts to your footer (like google analytic script), you should enter them in the box. They will be added before &lt;/body&gt; tag', 'gomedia' ),
		'id'   => 'gomedia_script_footer',
		'type' => 'textarea'
	);

	/* Return the theme settings data. */
	return $options;
}

/**
 * Custom scripts for the theme settings.
 *
 * @since  1.0.0
 */
function gomedia_options_custom_scripts() { ?>

	<script type="text/javascript">
	jQuery(document).ready(function() {

		jQuery('#gomedia_featured_posts').click(function() {
			jQuery('#section-gomedia_featured_tag').fadeToggle(400);
		});

		if (jQuery('#gomedia_featured_posts:checked').val() !== undefined) {
			jQuery('#section-gomedia_featured_tag').show();
		}

		jQuery('#gomedia_latest_videos').click(function() {
			jQuery('#section-gomedia_latest_videos_num').fadeToggle(400);
		});

		if (jQuery('#gomedia_latest_videos:checked').val() !== undefined) {
			jQuery('#section-gomedia_latest_videos_num').show();
		}

		jQuery('#gomedia_editor_picks').click(function() {
			jQuery('#section-gomedia_editor_picks_tag, #section-gomedia_editor_picks_num').fadeToggle(400);
		});

		if (jQuery('#gomedia_editor_picks:checked').val() !== undefined) {
			jQuery('#section-gomedia_editor_picks_tag, #section-gomedia_editor_picks_num').show();
		}

	});
	</script>

<?php }
add_action( 'optionsframework_custom_scripts', 'gomedia_options_custom_scripts' );

/**
 * Allowed embed, script and meta in textarea.
 *
 * @since  1.0.1
 */
function gomedia_change_santiziation() {
	remove_filter( 'of_sanitize_textarea', 'of_sanitize_textarea' );
	add_filter( 'of_sanitize_textarea', 'gomedia_sanitize_textarea' );
}
add_action( 'admin_init', 'gomedia_change_santiziation', 100 );

/**
 * Custom sanitization for textarea
 *
 * @since  1.0.1
 * @param  array  $input
 * @return array
 */
function gomedia_sanitize_textarea( $input ) {
	$output = stripslashes( $input );
	return $output;
}

/**
 * Header Code
 *
 * @since  1.0.1
 */
function gomedia_header_code() {
	$header_code = of_get_option( 'gomedia_script_head' );

	if ( !empty( $header_code ) ) {
		echo stripslashes( $header_code ) . "\n";
	}

}
add_action( 'wp_head', 'gomedia_header_code', 20 );

/**
 * Footer Code
 *
 * @since  1.0.1
 */
function gomedia_footer_code() {
	$footer_code = of_get_option( 'gomedia_script_footer' );

	if ( !empty( $footer_code ) ) {
		echo stripslashes( $footer_code ) . "\n";
	}

}
add_action( 'wp_footer', 'gomedia_footer_code', 20 );