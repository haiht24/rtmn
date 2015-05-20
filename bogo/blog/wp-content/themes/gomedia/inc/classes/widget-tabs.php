<?php
/**
 * Tabbed widget.
 *
 * @package    GoMedia
 * @author     Theme Junkie
 * @copyright  Copyright (c) 2014, Theme Junkie
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 * @since      1.0.0
 */
class GoMedia_Tabs_Widget extends WP_Widget {

	/**
	 * Sets up the widgets.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		// Set up the widget options.
		$widget_options = array(
			'classname'   => 'widget-gomedia-tabs tabs-widget',
			'description' => __( 'Display popular posts, recent posts and tags in tabs.', 'gomedia' )
		);

		// Create the widget.
		$this->WP_Widget(
			'gomedia-tabs',                  // $this->id_base
			__( '&raquo; Tabs', 'gomedia' ), // $this->name
			$widget_options                  // $this->widget_options
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
		?>
		
		<ul class="tabs-nav">
			<li class="active"><a href="#tab1"><i class="fa fa-thumbs-o-up"></i> <?php echo esc_attr( $instance['popular'] ); ?></a></li>
			<li><a href="#tab2"><i class="fa fa-clock-o"></i> <?php echo esc_attr( $instance['recent'] ); ?></a></a></li>
			<li><a href="#tab3"><i class="fa fa-tag"></i> <?php echo esc_attr( $instance['tags'] ); ?></a></li>
		</ul>

		<div class="tabs-container">
			<div class="tab-content" id="tab1">
				<?php echo gomedia_popular_posts(); ?>
			</div>

			<div class="tab-content" id="tab2">
				<?php echo gomedia_latest_posts(); ?>
			</div>

			<div class="tab-content" id="tab3">
				<?php the_widget( 'WP_Widget_Tag_Cloud' ); ?>				
			</div>
		</div>

		<?php
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

		$instance['popular'] = strip_tags( $new_instance['popular'] );
		$instance['recent']  = strip_tags( $new_instance['recent'] );
		$instance['tags']    = strip_tags( $new_instance['tags'] );

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
			'popular' => esc_html__( 'Popular', 'gomedia' ),
			'recent'  => esc_html__( 'Latest', 'gomedia' ),
			'tags'    => esc_html__( 'Tags', 'gomedia' )
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
	?>

		<p>
			<label for="<?php echo $this->get_field_id( 'popular' ); ?>">
				<?php _e( 'Popular posts title:', 'gomedia' ); ?>
			</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'popular' ); ?>" name="<?php echo $this->get_field_name( 'popular' ); ?>" value="<?php echo esc_attr( $instance['popular'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'recent' ); ?>">
				<?php _e( 'Recent posts title:', 'gomedia' ); ?>
			</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'recent' ); ?>" name="<?php echo $this->get_field_name( 'recent' ); ?>" value="<?php echo esc_attr( $instance['recent'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'tags' ); ?>">
				<?php _e( 'Tags title:', 'gomedia' ); ?>
			</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ); ?>" value="<?php echo esc_attr( $instance['tags'] ); ?>" />
		</p>

	<?php

	}

}

/**
 * Register the widget.
 *
 * @since  1.0.0
 */
function gomedia_register_tabs_widget() {
	register_widget( 'GoMedia_Tabs_Widget' );
}
add_action( 'widgets_init', 'gomedia_register_tabs_widget' );

/**
 * Popular Posts by comment
 *
 * @since 1.0.0
 */
function gomedia_popular_posts() {

	// Posts query arguments.
	$args = array(
		'posts_per_page' => 3,
		'orderby'        => 'comment_count',
		'post_type'      => 'post'
	);

	// The post query
	$popular = get_posts( $args );

	global $post;

	if ( $popular ) {
		$html = '<ul>';

			foreach ( $popular as $post ) :
				setup_postdata( $post );

				$html .= '<li class="clearfix">';
					$html .= '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" rel="bookmark">' . get_the_post_thumbnail( $post->ID, 'gomedia-widget-thumb', array( 'class' => 'entry-thumb', 'alt' => esc_attr( get_the_title( $post->ID ) ) ) ) . '</a>';
					$html .= '<h2 class="entry-title"><a href="' . esc_url( get_permalink( $post->ID ) ) . '" rel="bookmark">' . esc_attr( get_the_title( $post->ID ) ) . '</a></h2>';
					$html .= '<div class="entry-meta">' . get_the_date() . '</div>';
				$html .= '</li>';

			endforeach;

		$html .= '</ul>';
	}

	// Reset the query.
	wp_reset_postdata();

	if ( isset( $html ) ) {
		return $html;
	}

}

/**
 * Recent Posts
 *
 * @since 1.0.0
 */
function gomedia_latest_posts() {

	// Posts query arguments.
	$args = array(
		'post_type'      => 'post',
		'posts_per_page' => 3
	);

	// The post query
	$recent = get_posts( $args );

	global $post;

	if ( $recent ) {
		$html = '<ul>';

			foreach ( $recent as $post ) :
				setup_postdata( $post );

				$html .= '<li class="clearfix">';
					$html .= '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" rel="bookmark">' . get_the_post_thumbnail( $post->ID, 'gomedia-widget-thumb', array( 'class' => 'entry-thumb', 'alt' => esc_attr( get_the_title( $post->ID ) ) ) ) . '</a>';
					$html .= '<h2 class="entry-title"><a href="' . esc_url( get_permalink( $post->ID ) ) . '" rel="bookmark">' . esc_attr( get_the_title( $post->ID ) ) . '</a></h2>';
					$html .= '<div class="entry-meta">' . get_the_date() . '</div>';
				$html .= '</li>';

			endforeach;

		$html .= '</ul>';
	}

	// Reset the query.
	wp_reset_postdata();

	if ( isset( $html ) ) {
		return $html;
	}

}