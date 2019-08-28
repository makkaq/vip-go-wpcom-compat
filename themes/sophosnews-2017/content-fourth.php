<article id="post-<?php the_ID(); ?>" <?php post_class( 'fourth-featured' ); ?> style="background-image: url('<?php echo esc_url( sophos_get_the_post_thumbnail() ); ?>');">
	<header class="featured-entry-header">
		<?php the_title( sprintf( '<h1 class="featured-entry-title dot-ellipsis dot-resize-update dot-load-update dot-height-130"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
		<a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn-outline-white">
			<?php esc_html_e( 'View Article', 'sophos-news' ); ?>
		</a>
	</header> <!-- featured-entry-header -->
</article> <!-- post -->
