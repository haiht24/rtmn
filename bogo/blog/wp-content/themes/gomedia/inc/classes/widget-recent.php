<?php
/**
 * Recent Posts with Thumbnail widget.
 *
 * @package    GoMedia
 * @author     Theme Junkie
 * @copyright  Copyright (c) 2014, Theme Junkie
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 * @since      1.0.0
 */
class GoMedia_Recent_Widget extends WP_Widget {

	/**
	 * Sets up the widgets.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		// Set up the widget options.
		$widget_options = array(
			'classname'   => 'widget-gomedia-recent recent-posts-widget',
			'description' => __( 'Display recent posts with thumbnails.', 'gomedia' )
		);

		// Create the widget.
		$this->WP_Widget(
			'gomedia-recent',                                   // $this->id_base
			__( '&raquo; Recent Posts Thumbnails', 'gomedia' ), // $this->name
			$widget_options                                     // $this->widget_options
		);

		// Flush the transient.
		add_action( 'save_post'   , array($this, 'flush_widget_transient') );
		add_action( 'deleted_post', array($this, 'flush_widget_transient') );
		add_action( 'switch_theme', array($this, 'flush_widget_transient') );

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
			echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $after_title;
		}

		// Display the recent posts.
		if ( false === ( $recent = get_transient( 'gomedia_recent_widget_' . $this->id ) ) ) {

			// Posts query arguments.
			$args = array(
				'post_type'      => 'post',
				'posts_per_page' => $instance['limit']
			);

			// The post query
			$recent = get_posts( $args );

			// Store the transient.
			set_transient( 'gomedia_recent_widget_' . $this->id, $recent );

		}

		global $post;
		if ( $recent ) {
			echo '<div class="gomedia-recent-widget">';
				echo '<ul>';

					foreach ( $recent as $post ) :
						setup_postdata( $post );

						echo '<li class="clearfix">';
							echo '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" rel="bookmark">' . get_the_post_thumbnail( $post->ID, 'gomedia-widget-thumb', array( 'class' => 'entry-thumb', 'alt' => esc_attr( get_the_title( $post->ID ) ) ) ) . '</a>';
							echo '<h2 class="entry-title"><a href="' . esc_url( get_permalink( $post->ID ) ) . '" rel="bookmark">' . esc_attr( get_the_title( $post->ID ) ) . '</a></h2>';
						echo '</li>';

					endforeach;

				echo '</ul>';
			echo '</div>';
		}

		// Reset the query.
		wp_reset_postdata();
		
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
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['limit'] = (int) $new_instance['limit'];

		// Delete our transient.
		$this->flush_widget_transient();

		return $instance;
	}

	/**
	 * Flush the transient.
	 *
	 * @since  1.0.0
	 */
	function flush_widget_transient() {
		delete_transient( 'gomedia_recent_widget_' . $this->id );
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @since 1.0.0
	 */
	function form( $instance ) {

		// Default value.
		$defaults = array(
			'title' => esc_html__( 'Recent Posts', 'gomedia' ),
			'limit' => 5
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
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>">
				<?php _e( 'Number of posts to show:', 'gomedia' ); ?>
			</label>
			<input type="text" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" value="<?php echo absint( $instance['limit'] ); ?>" size="3" />
		</p>

	<?php

	}

}

/**
 * Register the widget.
 *
 * @since  1.0.0
 */
function gomedia_register_recent_widget() {
	register_widget( 'GoMedia_Recent_Widget' );
}
add_action( 'widgets_init', 'gomedia_register_recent_widget' );