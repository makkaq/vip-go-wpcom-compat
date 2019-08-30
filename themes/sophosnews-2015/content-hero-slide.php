<article class="slide-article">
	<div class="slide-image">
		<div class="image-pointer"></div>
		<a href="<?php echo esc_url( get_permalink() ); ?>"><?php sophos_get_featured_image_as_background( 'hero-thumbnail' ); ?></a>
	</div>
	<div class="slide-content">
		<div class="content-frame">
			<div class="meta-box">
				<div class="date-box"><span class="month"><?php the_time( 'M' ); ?></span><span class="day"><?php the_time( 'd' ); ?></span></div>
 				<div class="author-box"><?php sophos_content_card_entry_authors(); ?></div>
				<div class="comment-box">
					<span class="count"><?php echo esc_html( get_comments_number() ); ?></span>
				</div>
			</div>
			<?php the_title( sprintf( '<h2 class="slide-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<div class="slide-excerpt"><?php the_excerpt(); ?></div>
		</div>
	</div>
</article> <!-- .slide-article -->
