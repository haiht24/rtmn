<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="hidden-xs" href="<?php the_permalink(); ?>" rel="bookmark">
			<div class="entry-thumb-wrapper">
				<?php the_post_thumbnail( 'gomedia-post-thumb', array( 'class' => 'entry-thumb', 'alt' => esc_attr( get_the_title() ) ) ); ?>
				<span class="icon-mark"><i class="fa fa-play"></i></span>
			</div><!-- .entry-thumb-wrapper -->
		</a>
	<?php endif; ?>

	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta">
				<?php gomedia_posted_on(); ?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-excerpt">
		<?php the_excerpt(); ?>
	</div><!-- .entry-excerpt -->

</article><!-- #post-## -->
