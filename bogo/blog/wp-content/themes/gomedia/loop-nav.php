<?php if ( is_singular( 'post' ) ) : // If viewing a single post page. ?>

	<div class="loop-nav">
		<?php previous_post_link( '<div class="prev">' . __( 'Previous Post: %link', 'gomedia' ) . '</div>', '%title' ); ?>
		<?php next_post_link(     '<div class="next">' . __( 'Next Post: %link',     'gomedia' ) . '</div>', '%title' ); ?>
	</div><!-- .loop-nav -->

<?php elseif ( is_home() || is_archive() || is_search() ) : // If viewing the blog, an archive, or search results. ?>

	<?php loop_pagination(
		array( 
			'prev_text' => _x( 'Previous', 'posts navigation', 'gomedia' ), 
			'next_text' => _x( 'Next',     'posts navigation', 'gomedia' ),
			'before'    => '<nav class="loop-pagination"><ul class="pagination">',
			'after'     => '</ul></nav>',
		) 
	); ?>

<?php endif; // End check for type of page being viewed. ?>