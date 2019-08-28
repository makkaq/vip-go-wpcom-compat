<article id="post-<?php the_ID(); ?>" <?php post_class( 'second-featured' ); ?>>
	<div class="second-featured__image" style="background-image: url('<?php echo esc_url( sophos_get_the_post_thumbnail() ); ?>');">
		<div class="featured-entry-meta">
			<div class="featured-entry-date">
				<span class="day-num"><?php echo esc_html( date_i18n( 'd', get_the_time( 'U' ) ) ); ?></span>
				<span class="month"><?php echo esc_html( date_i18n( 'M', get_the_time( 'U' ) ) ); ?></span>
			</div>
		</div>
	</div>
	<div class="second-featured__content">
		<header class="featured-entry-header">
			<div class="featured-entry-categories">
					<?php echo wp_kses( sophos_term_listing( ' &bull; ', false, true ), [
						'a' => [
						    'href' => 1,
						    'rel' => 1,
						],
					] ); ?>
			</div>
			<?php the_title( sprintf( '<h1 class="featured-entry-title dot-ellipsis dot-resize-update dot-load-update dot-height-130"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
		</header> <!-- featured-entry-header -->
	</div>
</article> <!-- post -->
