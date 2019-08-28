<article id="post-<?php the_ID(); ?>" <?php post_class( 'third-featured' ); ?>>
	<header class="featured-entry-header">
		<?php the_title( sprintf( '<h1 class="featured-entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
	</header> <!-- featured-entry-header -->
	<div class="featured-entry-content">
		<div class="dot-ellipsis dot-resize-update dot-load-update dot-height-130">
			<?php the_excerpt(); ?>
		</div>
		<p><a href="<?php echo esc_url( get_permalink() ); ?>" class="more-link">More &gt;</a></p>
	</div><!-- .entry-content -->
</article> <!-- post -->
