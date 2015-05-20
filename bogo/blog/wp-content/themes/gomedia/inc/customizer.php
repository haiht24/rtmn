<?php
/**
 * GoMedia Theme Customizer.
 *
 * @package    GoMedia
 * @author     Theme Junkie
 * @copyright  Copyright (c) 2014, Theme Junkie
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 * @since      1.0.0
 */

// Load textarea function for customizer.
add_action( 'customize_register', 'gomedia_textarea_customize_control', 1 );

// postMessage support for site title and description.
add_action( 'customize_register', 'gomedia_customize_register' );

// Load javascript for the Customizer.
add_action( 'customize_preview_init', 'gomedia_customize_preview_js' );

/**
 * Load textarea function for customizer.
 *
 * @since 1.0.0
 */
function gomedia_textarea_customize_control() {
	require trailingslashit( get_template_directory() ) . 'inc/classes/customize-control-textarea.php';
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @since 1.0.0
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function gomedia_customize_register( $wp_customize ) {

	// Live preview
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	// Get the theme settings value.
	$options = optionsframework_options();

	// Adds "Logo Settings" section to the Theme Customization screen.
	$wp_customize->add_section(
		'gomedia_logo_settings',
		array(
			'title'       => esc_html__( 'Logo', 'gomedia' ),
			'description' => $options['gomedia_logo']['desc'],
			'priority'    => 25,
		)
	);

		// Logo setting.
		$wp_customize->add_setting(
			'gomedia[gomedia_logo]',
			array(
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_url'
			)
		);

			// Logo control.
			$wp_customize->add_control(
				new WP_Customize_Image_Control( $wp_customize, 'gomedia_logo_control',
				array(
					'label'    => $options['gomedia_logo']['name'],
					'section'  => 'gomedia_logo_settings',
					'settings' => 'gomedia[gomedia_logo]'
				)
			) );

	// Adds "Favicon Settings" section to the Theme Customization screen.
	$wp_customize->add_section(
		'gomedia_favicon_settings',
		array(
			'title'       => esc_html__( 'Favicon', 'gomedia' ),
			'description' => $options['gomedia_favicon']['desc'],
			'priority'    => 28,
		)
	);

		// Favicon setting.
		$wp_customize->add_setting(
			'gomedia[gomedia_favicon]',
			array(
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_url'
			)
		);

			// Favicon control.
			$wp_customize->add_control(
				new WP_Customize_Image_Control( $wp_customize, 'gomedia_favicon_control',
				array(
					'label'    => $options['gomedia_favicon']['name'],
					'section'  => 'gomedia_favicon_settings',
					'settings' => 'gomedia[gomedia_favicon]'
				)
			) );

	// Adds "Advertisement Settings" section to the Theme Customization screen.
	$wp_customize->add_section(
		'gomedia_ads_settings',
		array(
			'title'       => esc_html__( 'Advertisement', 'gomedia' ),
			'priority'    => 195,
		)
	);

		// Advertisement setting.
		$wp_customize->add_setting(
			'gomedia[gomedia_header_ads]',
			array(
				'type'       => 'option',
				'capability' => 'edit_theme_options'
			)
		);

			// Advertisement control.
			$wp_customize->add_control( new Gomedia_Customize_Control_Textarea( $wp_customize, 'gomedia_header_ads_control',
				array(
					'label'    => $options['gomedia_header_ads']['name'],
					'section'  => 'gomedia_ads_settings',
					'settings' => 'gomedia[gomedia_header_ads]'
				)
			) );

	// Adds "Social Settings" section to the Theme Customization screen.
	$wp_customize->add_section(
		'gomedia_social_setting',
		array(
			'title'       => esc_html__( 'Social Links', 'gomedia' ),
			'description' => esc_html__( 'The social icons will appear at the top of your site.', 'gomedia' ),
			'priority'    => 200,
		)
	);

		// Show hide social links.
		$wp_customize->add_setting( 
			'gomedia[gomedia_enable_social]',
			array(
				'default'    => $options['gomedia_enable_social']['std'],
				'type'       => 'option',
				'capability' => 'edit_theme_options'
			) 
		);

			// Show hide social links control.
			$wp_customize->add_control(
				'gomedia_enable_social_control',
				array(
					'label'    => $options['gomedia_enable_social']['name'],
					'type'     => 'checkbox',
					'section'  => 'gomedia_social_setting',
					'settings' => 'gomedia[gomedia_enable_social]',
					'priority' => 1
				)
			);

		// Label setting.
		$wp_customize->add_setting(
			'gomedia[gomedia_label]',
			array(
				'default'           => $options['gomedia_label']['std'],
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

			// Label control.
			$wp_customize->add_control(
				'gomedia_label_control',
				array(
					'label'    => $options['gomedia_label']['name'],
					'section'  => 'gomedia_social_setting',
					'settings' => 'gomedia[gomedia_label]',
					'priority' => 2
				)
			);

		// Facebook setting.
		$wp_customize->add_setting(
			'gomedia[gomedia_fb]',
			array(
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_url',
			)
		);

			// Facebook control.
			$wp_customize->add_control(
				'gomedia_fb_control',
				array(
					'label'    => $options['gomedia_fb']['name'],
					'section'  => 'gomedia_social_setting',
					'settings' => 'gomedia[gomedia_fb]'
				)
			);

		// Twitter setting.
		$wp_customize->add_setting(
			'gomedia[gomedia_tw]',
			array(
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_url',
			)
		);

			// Twitter control.
			$wp_customize->add_control(
				'gomedia_tw_control',
				array(
					'label'    => $options['gomedia_tw']['name'],
					'section'  => 'gomedia_social_setting',
					'settings' => 'gomedia[gomedia_tw]'
				)
			);

		// Google Plus setting.
		$wp_customize->add_setting(
			'gomedia[gomedia_gplus]',
			array(
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_url',
			)
		);

			// Google Plus control.
			$wp_customize->add_control(
				'gomedia_gplus_control',
				array(
					'label'    => $options['gomedia_gplus']['name'],
					'section'  => 'gomedia_social_setting',
					'settings' => 'gomedia[gomedia_gplus]'
				)
			);

		// Pinterest setting.
		$wp_customize->add_setting(
			'gomedia[gomedia_pinterest]',
			array(
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_url',
			)
		);

			// Pinterest control.
			$wp_customize->add_control(
				'gomedia_pinterest_control',
				array(
					'label'    => $options['gomedia_pinterest']['name'],
					'section'  => 'gomedia_social_setting',
					'settings' => 'gomedia[gomedia_pinterest]'
				)
			);

		// LinkedIn setting.
		$wp_customize->add_setting(
			'gomedia[gomedia_linkedin]',
			array(
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_url',
			)
		);

			// LinkedIn control.
			$wp_customize->add_control(
				'gomedia_linkedin_control',
				array(
					'label'    => $options['gomedia_linkedin']['name'],
					'section'  => 'gomedia_social_setting',
					'settings' => 'gomedia[gomedia_linkedin]'
				)
			);

		// Feed setting.
		$wp_customize->add_setting(
			'gomedia[gomedia_feed]',
			array(
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_url',
			)
		);

			// Feed control.
			$wp_customize->add_control(
				'gomedia_feed_control',
				array(
					'label'    => $options['gomedia_feed']['name'],
					'section'  => 'gomedia_social_setting',
					'settings' => 'gomedia[gomedia_feed]'
				)
			);

		// Newsletter setting.
		$wp_customize->add_setting(
			'gomedia[gomedia_newsletter]',
			array(
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_url',
			)
		);

			// Newsletter control.
			$wp_customize->add_control(
				'gomedia_newsletter_control',
				array(
					'label'    => $options['gomedia_newsletter']['name'],
					'section'  => 'gomedia_social_setting',
					'settings' => 'gomedia[gomedia_newsletter]'
				)
			);

}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since 1.0.0
 */
function gomedia_customize_preview_js() {
	wp_enqueue_script( 'gomedia_customizer', trailingslashit( get_template_directory_uri() ) . 'assets/js/customizer.js', array( 'customize-preview' ), null, true );
}
