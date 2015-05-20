<?php
/**
 * Video widget.
 *
 * @package    GoMedia
 * @author     Theme Junkie
 * @copyright  Copyright (c) 2014, Theme Junkie
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 * @since      1.0.0
 */
class GoMedia_Video_Widget extends WP_Widget {

	/**
	 * Sets up the widgets.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		// Set up the widget options.
		$widget_options = array(
			'classname'   => 'widget-gomedia-video video-widget',
			'description' => __( 'Easily to display any type of video.', 'gomedia' )
		);

		// Create the widget.
		$this->WP_Widget(
			'gomedia-video',                    // $this->id_base
			__( '&raquo; Video', 'gomedia' ), // $this->name
			$widget_options                   // $this->widget_options
		);
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @since 1.0.0
	 */
	function widget( $args, $instance ) {
		extract( $args );

		// Output the theme's $before_widget wrapper.
		echo $before_widget;

		// If the title not empty, display it.
		if ( $instance['title'] ) {
			echo '<h4 class="widget-title">' . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . '</h4>';
		}

		// Display the video.
		if ( $instance['video_code'] ) {
			echo '<div class="video-frame">' . stripslashes( $instance['video_code'] ) . '</div>';
		}

		// Close the theme's widget wrapper.
		echo $after_widget;

	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 *
	 * @since 1.0.0
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $new_instance;

		$instance['title']      = strip_tags( $new_instance['title'] );
		$instance['video_code'] = stripslashes( $new_instance['video_code'] );

		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @since 1.0.0
	 */
	function form( $instance ) {

		// Default value.
		$defaults = array(
			'title'      => esc_html__( 'Video', 'gomedia' ),
			'video_code' => '',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
	?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php _e( 'Title:', 'gomedia' ); ?>
			</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'video_code' ); ?>">
				<?php _e( 'Embed Code:', 'gomedia' ); ?>
			</label>
			<textarea class="widefat" name="<?php echo $this->get_field_name( 'video_code' ); ?>" id="<?php echo $this->get_field_id( 'video_code' ); ?>" cols="30" rows="6"><?php echo stripslashes( $instance['video_code'] ); ?></textarea>
		</p>

	<?php

	}

}

/**
 * Register the widget.
 *
 * @since  1.0.0
 */
function gomedia_register_video_widget() {
	register_widget( 'GoMedia_Video_Widget' );
}
add_action( 'widgets_init', 'gomedia_register_video_widget' );