<?php
/**
 * Custom template tags for this theme.
 * Eventually, some of the functionality here could be replaced by core features.
 * 
 * @package    GoMedia
 * @author     Theme Junkie
 * @copyright  Copyright (c) 2014, Theme Junkie
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 * @since      1.0.0
 */

if ( ! function_exists( 'gomedia_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since 1.0.0
 */
function gomedia_posted_on() {
	global $post;

	$time_string = __( '<time class="entry-date published" datetime="%1$s">%2$s ago</time>', 'gomedia' );

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) )
	);

	printf( __( '<span class="posted-on">%1$s</span><span class="byline"> by %2$s</span>', 'gomedia' ),
		sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>',
			esc_url( get_permalink() ),
			$time_string
		),
		sprintf( '<span class="author vcard entry-author"><a class="url fn n" href="%1$s">%2$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		)
	);

	$category = get_the_category( $post->ID );
	if ( $category && gomedia_categorized_blog() && ! is_single() ) :
		echo '<span class="entry-category">';
			echo '<a href="' . get_category_link( $category[0]->term_id ) . '">' . $category[0]->name . '</a>';
		echo '</span>';
	endif; // End if categories
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @since  1.0.0
 * @return bool
 */
function gomedia_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'gomedia_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'gomedia_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so gomedia_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so gomedia_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in gomedia_categorized_blog.
 *
 * @since 1.0.0
 */
function gomedia_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'gomedia_categories' );
}
add_action( 'edit_category', 'gomedia_category_transient_flusher' );
add_action( 'save_post',     'gomedia_category_transient_flusher' );

if ( ! function_exists( 'gomedia_site_branding' ) ) :
/**
 * Site branding for the site.
 * 
 * Display site title by default, but user can change it with their custom logo.
 * They can upload it on Customizer page.
 * 
 * @since  1.0.0
 */
function gomedia_site_branding() {

	$logo = of_get_option( 'gomedia_logo' );

	// Check if logo available, then display it.
	if ( $logo ) {
		echo '<div id="logo" class="left">' . "\n";
			echo '<a href="' . esc_url( get_home_url() ) . '" rel="home">' . "\n";
				echo '<img class="logo" src="' . esc_url( $logo ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
			echo '</a>' . "\n";
		echo '</div>' . "\n";

	// If not, then display the Site Title and Site Description.
	} else {
		echo '<h1 class="site-title"><a href="' . esc_url( get_home_url() ) . '" rel="home">' . esc_attr( get_bloginfo( 'name' ) ) . '</a></h1>';
		echo '<h2 class="site-description">' . esc_attr( get_bloginfo( 'description' ) ) . '</h2>';
	}

}
endif;

if ( ! function_exists( 'gomedia_social_links' ) ) :
/**
 * Social links
 *
 * @since  1.0.0
 */
function gomedia_social_links() {

	// Get option value.
	$enable     = of_get_option( 'gomedia_enable_social', '1' );
	$label      = of_get_option( 'gomedia_label', __( 'Follow Us', 'gomedia' ) );
	$facebook   = of_get_option( 'gomedia_fb' );
	$twitter    = of_get_option( 'gomedia_tw' );
	$gplus      = of_get_option( 'gomedia_gplus' );
	$pinterest  = of_get_option( 'gomedia_pinterest' );
	$linkedin   = of_get_option( 'gomedia_linkedin' );
	$feed       = of_get_option( 'gomedia_feed' );
	$newsletter = of_get_option( 'gomedia_newsletter' );

	// Check if social links option enabled.
	if ( $enable ) {
		echo '<div class="header-social">';

			if ( $label ) {
				echo '<span>' . strip_tags( $label ) . '</span>';
			}
			if ( $facebook ) {
				echo '<a class="facebook" href="' . esc_url( $facebook ) . '" data-toggle="tooltip" title="Facebook"><i class="fa fa-facebook"></i></a>';
			}
			if ( $twitter ) {
				echo '<a class="twitter" href="' . esc_url( $twitter ) . '" data-toggle="tooltip" title="Twitter"><i class="fa fa-twitter"></i></a>';
			}
			if ( $gplus ) {
				echo '<a class="google-plus" href="' . esc_url( $gplus ) . '" data-toggle="tooltip" title="Google Plus"><i class="fa fa-google-plus"></i></a>';
			}
			if ( $pinterest ) {
				echo '<a class="pinterest" href="' . esc_url( $pinterest ) . '" data-toggle="tooltip" title="Pinterest"><i class="fa fa-pinterest"></i></a>';
			}
			if ( $linkedin ) {
				echo '<a class="linkedin" href="' . esc_url( $linkedin ) . '" data-toggle="tooltip" title="LinkedIn"><i class="fa fa-linkedin"></i></a>';
			}
			if ( $feed ) {
				echo '<a class="rss" href="' . esc_url( $feed ) . '" data-toggle="tooltip" title="RSS"><i class="fa fa-rss"></i></a>';
			}
			if ( $newsletter ) {
				echo '<a class="email" href="' . esc_url( $newsletter ) . '" data-toggle="tooltip" title="Newsletter"><i class="fa fa-envelope-o"></i></a>';
			}

		echo '</div>';
	}

}
endif;

if ( ! function_exists( 'gomedia_post_author' ) ) :
/**
 * Display post author data on single post.
 * 
 * @since  1.0.0
 */
function gomedia_post_author() {

	// Don't display anything if not on the single post.
	if ( ! is_single() ) {
		return;
	}

	// Don't display anything if user hasn't fill the Biographical Info field.
	if ( ! get_the_author_meta( 'description' ) ) {
		return;
	}

	// Check the theme settings value.
	if ( ! of_get_option( 'gomedia_post_author', '1' ) ) {
		return;
	}
?>

	<section class="author-box">
		<?php echo get_avatar( is_email( get_the_author_meta( 'user_email' ) ), apply_filters( 'gomedia_author_bio_avatar_size', 80 ) ); ?>
		<h3 class="author-title">
			<a class="author-name url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
				<?php echo strip_tags( get_the_author() ); ?>
			</a>
		</h3>
		<p><?php echo stripslashes( get_the_author_meta( 'description' ) ); ?></p>
		<p class="follow"><a href="<?php echo esc_url( get_the_author_meta( 'twitter' ) ); ?>" data-toggle="tooltip" title="<?php esc_attr_e( 'Follow on Twitter', 'gomedia' ); ?>"><?php _e( 'Follow', 'gomedia' ); ?></a></p>
	</section><!-- .author-box -->

<?php
}
endif;

if ( ! function_exists( 'gomedia_related_posts' ) ) :
/**
 * Related posts.
 *
 * @since  1.0.0
 */
function gomedia_related_posts() {
	global $post;

	// User selected to display the related posts or not.
	if ( ! of_get_option( 'gomedia_related', '1' ) ) {
		return;
	}

	// Only display related posts on single page.
	if ( ! is_single() ) {
		return;
	}

	// Get the taxonomy terms of the current page for the specified taxonomy.
	$terms = wp_get_post_terms( $post->ID, 'category', array( 'fields' => 'ids' ) );

	// Check if the terms exist.
	if ( empty( $terms ) ) {
		return;
	}

	// Query arguments.
	$query = array(
		'tax_query' => array(
			array(
				'taxonomy' => 'category',
				'field'    => 'id',
				'terms'    => $terms,
				'operator' => 'IN'
			)
		),
		'posts_per_page' => 4,
		'exclude'        => $post->ID,
		'post_type'      => 'post',
	);

	// Allow plugins/themes developer to filter the default query.
	$query = apply_filters( 'gomedia_related_posts_query', $query );

	// Perform the query.
	$related = get_posts( $query );
	
	if ( $related ) {

		$html = '<section class="related-posts">';
			$html .= '<h3 class="section-title">' . __( 'You might also like...', 'gomedia' ) . '</h3>';
			$html .= '<ul class="row">';

				foreach( $related as $post ) {
					setup_postdata( $post );

					$html .= '<li class="col-md-3">';
						$html .= '<a href="' . esc_url( get_permalink() ) . '">';
							$html .= get_the_post_thumbnail( $post->ID, 'gomedia-related-thumb', array( 'class' => 'entry-thumb', 'alt' => esc_attr( get_the_title() ) ) );
							$html .= '<h2 class="entry-title">' . get_the_title() . '</h2>';
							$html .= gomedia_get_post_format_icons();
						$html .= '</a>';
					$html .= '</li>';

				}

			$html .= '</ul>';
		$html .= '</section>';

	}

	// Restore original Post Data.
	wp_reset_postdata();

	if ( isset( $html ) ) {
		echo $html;
	}

}
endif;

if ( ! function_exists( 'gomedia_social_sharing' ) ) :
/**
 * Social sharing on single post.
 *
 * @since  1.0.0
 */
function gomedia_social_sharing() {

	// Check the theme setting.
	if ( ! of_get_option( 'gomedia_post_share', '1' ) ) {
		return;
	}
?>
	<div class="entry-categories">
		<h3><?php _e( 'Share this post', 'gomedia' ); ?></h3>            
		<ul>
			<li id="twitter" data-url="<?php echo esc_url( get_permalink() ); ?>" data-text="<?php echo esc_attr( get_the_title() ); ?>" data-title="<?php echo esc_attr( '<i class="fa fa-twitter"></i> Tweet' ); ?>"></li>
			<li id="facebook" data-url="<?php echo esc_url( get_permalink() ); ?>" data-text="<?php echo esc_attr( get_the_title() ); ?>" data-title="<?php echo esc_attr( '<i class="fa fa-facebook-square"></i> Facebook' ); ?>"></li>
			<li id="google-plus" data-url="<?php echo esc_url( get_permalink() ); ?>" data-text="<?php echo esc_attr( get_the_title() ); ?>" data-title="<?php echo esc_attr( '<i class="fa fa-google-plus-square"></i> Google Plus' ); ?>"></li>
			<li id="linkedin" data-url="<?php echo esc_url( get_permalink() ); ?>" data-text="<?php echo esc_attr( get_the_title() ); ?>" data-title="<?php echo esc_attr( '<i class="fa fa-linkedin-square"></i> LinkedIn' ); ?>"></li>             
		</ul>
	</div>
<?php
}
endif;

if ( ! function_exists( 'gomedia_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since  1.0.0
 */
function gomedia_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'gomedia' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'gomedia' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">

			<?php echo get_avatar( $comment, 60 ); ?>
			
			<div class="comment-des">

				<div class="arrow-comment"></div>

				<div class="comment-by">
					<p class="author"><strong><?php echo get_comment_author_link(); ?></strong></p>
					<?php
						printf( '<p class="date"><a href="%1$s"><time datetime="%2$s">%3$s</time></a></p>',
							esc_url( get_comment_link( $comment->comment_ID ) ),
							get_comment_time( 'c' ),
							/* translators: 1: date, 2: time */
							sprintf( __( '%1$s at %2$s', 'gomedia' ), get_comment_date(), get_comment_time() )
						);
					?>
					<span class="reply">
						<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'gomedia' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</span><!-- .reply -->
				</div><!-- .comment-by -->

				<section class="comment-content comment">
					<?php if ( '0' == $comment->comment_approved ) : ?>
						<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'gomedia' ); ?></p>
					<?php endif; ?>
					<?php comment_text(); ?>
					<?php edit_comment_link( __( 'Edit', 'gomedia' ), '<p class="edit-link">', '</p>' ); ?>
				</section><!-- .comment-content -->

			</div><!-- .comment-des -->

		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

if ( ! function_exists( 'gomedia_get_post_format_icons' ) ) :
/**
 * Post format icons
 *
 * @since  1.0.0
 * @return string The post format icon markup
 */
function gomedia_get_post_format_icons() {

	// Set up empty variable.
	$icon = '';

	// If video format.
	if ( has_post_format( 'video' ) ) {
		$icon = '<span class="icon-mark"><i class="fa fa-play"></i></span>';
		
	// If image or gallery format.
	} elseif ( has_post_format( array( 'image', 'gallery' ) ) ) {
		$icon = '<span class="icon-mark"><i class="fa fa-picture-o"></i></span>';
	}

	return $icon;

}
endif;

if ( ! function_exists( 'gomedia_next_posts_link' ) ) :
/**
 * Create a /page/1/ link.
 *
 * @since  1.0.0
 */
function gomedia_next_posts_link() {

	// Get the permalink structure based on user selected.
	$permalink = get_option( 'permalink_structure' );

	// Check the permalink structure.
	$structure = '';
	if ( $permalink == '' ) {
		$structure = '/?paged=1';
	} else { 
		$structure = '/page/1/';
	}

	// Display the more link.
	echo '<a class="more-news" href="' . esc_url( get_home_url() . $structure ) . '">' . __( 'More Latest News &raquo;', 'gomedia' ) . '</a>';
	
}
endif;

if ( ! function_exists( 'gomedia_header_ad' ) ) :
/**
 * Header ad
 *
 * @since  1.0.1
 */
function gomedia_header_ad() {

	$header_ad = of_get_option( 'gomedia_header_ads' );

	if ( !empty( $header_ad ) ) {
		echo '<div id="header-ad" class="right col-md-8">' . stripslashes( of_get_option( 'gomedia_header_ads' ) ) . '</div>';
	}

}
endif;