<article class="story-snippet">
	<div class="story-image">
		<a href="<?php echo esc_url( get_permalink() ); ?>"><?php sophos_get_featured_image_as_background( 'thumbnail' ); ?></a>
	</div>
	<div class="story-content">
		<div class="story-meta ">
			<div class="story-date"><?php the_time( 'F j, Y' ); ?></div>
		</div>
		<?php the_title( sprintf( '<h3 class="story-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
	</div>
</article> <!-- .story-snippet -->