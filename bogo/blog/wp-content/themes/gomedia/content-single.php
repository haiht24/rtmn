<div class="entry-info entry-header col-md-2">

	<?php gomedia_post_author(); // Get the post author info. ?>

	<?php gomedia_social_sharing(); // Get the social sharing. ?>

	<?php
		$tags_list = get_the_tag_list( '<ul><li>','</li><li>','</li></ul>' );
		if ( $tags_list ) :
	?>
		<div class="entry-tags">
			<h3><?php _e( 'Tags', 'gomedia' ); ?></h3>
			<?php echo $tags_list; ?>
		</div>
	<?php endif; // End if $tags_list ?>

</div><!-- .col-md-2 -->

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<header class="entry-header">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">
			<time class="entry-date published" datetime="<?php echo get_the_date( 'c' ); ?>"><?php printf( __( 'Posted on %s', 'gomedia' ), esc_html( get_the_date() ) ); ?></time>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'gomedia' ) );
				if ( $categories_list && gomedia_categorized_blog() ) :
			?>
			<span class="cat-links">
				<?php printf( __( 'in %1$s', 'gomedia' ), $categories_list ); ?>
			</span>
			<?php endif; // End if categories ?>
			<?php edit_post_link( __( '&middot; Edit', 'gomedia' ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'gomedia' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php gomedia_related_posts(); // Get the related posts. ?>

	<?php get_template_part( 'loop', 'nav' ); // Loads the loop-nav.php template  ?>

	<?php
		// If comments are open or we have at least one comment, load up the comment template
		if ( comments_open() || '0' != get_comments_number() ) :
			comments_template();
		endif;
	?>
	
</article><!-- #post-## -->
