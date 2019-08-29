<?php
/**
 * The main front page.
 */

get_header(); ?>

</div> <!-- .container -->
</div> <!-- #content -->

<?php
/**
 * Hide all of the "extra" elements when the home page is being paginated. We
 * do this so the ajax autoloader loads a full page of articles using the while
 * loop at the bottom of this template.
 */
if ( !is_paged() ) : ?>

<?php sophos_panel_open( 'hero-panel' ); ?>
	<?php // Show the first post from the main query (handles sticky posts automatically).
	if ( have_posts() ) : the_post();
	    get_template_part( 'content', 'hero-slide' );
	endif;
	?>
<?php sophos_panel_close(); ?>

<?php sophos_panel_open( 'cards-panel attach-top' ); ?>
	<div class="card-collection">
		<?php // Show the next three posts from the main query
		for ( $i = 0; $i < 3; $i ++ ) : the_post();
			get_template_part( 'content', 'card' );
		endfor; ?>
	</div>
<?php sophos_panel_close(); ?>

<div id="newsletter-signup" class="container"></div>

<?php sophos_panel_open( 'cards-panel zero-bottom' ); ?>
	<div class="card-collection">
		<?php // Show the first three posts from the main query.
		for ( $i = 0; $i < 3; $i ++ ) : the_post();
			get_template_part( 'content', 'card' );
		endfor; ?>
	</div>
<?php sophos_panel_close(); ?>

<?php sophos_panel_open( 'advice-video-panel' ); ?>
	<?php // This Week's Best Advice.
	$best_advice_of_the_week = new WP_Query( [ 'posts_per_page' => 1, 'tag' => 'best-advice', ] );
	if ( $best_advice_of_the_week->have_posts() ) : $best_advice_of_the_week->the_post(); ?>
		<div class="advice-block">
			<div class="top-row">
				<h2 class="advice-block-title"><?php esc_html_e( 'This week&rsquo;s best advice', 'forward' ); ?></h2>
				<div class="advice-image"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php sophos_get_featured_image_as_background( 'this-weeks-best-advice-thumbnail' ); ?></a></div>
				<h3 class="advice-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a></h3>
			</div>
			<div class="bottom-row"><?php

				$advice = get_term_link( 'best-advice', 'post_tag' );

				if ( ! is_wp_error( $advice ) ) :
					?><div class="more-advice"><a href="<?php echo esc_url( $advice ); ?>"><?php esc_html_e( 'See more security advice', 'nakedsecurity' ); ?></a></div><?php
				endif;

			?></div>
		</div>
	<?php endif; wp_reset_postdata(); ?>

	<?php // Video of the Week.
	if ( !($video_of_the_week = wp_cache_get('sophos_video_of_the_week')) instanceof \WP_Query ) {
		$video_of_the_week = new WP_Query([
			'no_found_rows'  => true, // improve performance by avoiding SQL_CALC_FOUND_ROWS
			'posts_status'   => 'publish',
			'post_type'      => 'sophos_video',
			'posts_per_page' => 1,
			'meta_query'     => [[ 'key' => 'sophos_video_fields' ]]
		]);
		wp_cache_set('sophos_video_of_the_week', $video_of_the_week, '', 300);
	}

	if ( $video_of_the_week->have_posts() ) : $video_of_the_week->the_post();
		$sophos_video_meta = get_post_meta( get_the_ID(), 'sophos_video_fields' ); ?>
		<div class="weekly-video-block">
			<div class="top-row">
				<h2 class="video-label"><?php esc_html_e( 'Video of the Week', 'forward' ); ?></h2>
				<div class="video-length"><?php esc_html_e( $sophos_video_meta[0]['video_length'] ); ?></div>
				<div class="video-image">
					<a href="<?php echo esc_url( $sophos_video_meta[0]['youtube_video_url'] ); ?>"><?php sophos_get_attachment_image_as_background( $sophos_video_meta[0]['static_display_image'], 'video-of-the-week-thumbnail' ); ?></a>
				</div>
			</div>
			<div class="bottom-row">
				<h3 class="video-title">
					<a href="<?php echo esc_url( $sophos_video_meta[0]['youtube_video_url'] ); ?>"><?php the_title(); ?></a>
				</h3>

				<div class="video-date"><?php esc_html_e( sophos_get_week_range_from_date( get_post_time( 'U', true ) ) ); ?></div>
				<div class="more-videos"><a href="<?php echo esc_url( get_post_type_archive_link( 'sophos_video' ) ); ?>"><?php esc_html_e( 'View the video archive', 'forward' ); ?></a></div>
			</div>
		</div>
	<?php endif; wp_reset_postdata(); ?>
<?php sophos_panel_close(); ?>

<?php sophos_panel_open( 'tools-panel' ); ?>
	<?php get_template_part( 'panel', 'tools' ); ?>
<?php sophos_panel_close(); ?>

<?php sophos_panel_open( 'stories-panel' ); ?>
<div class="popular-stories-block">
	<h2 class="block-title"><?php esc_html_e( 'Popular stories', 'forward' ); ?></h2>
	<div class="stories-collection">
		<?php // Popular Posts.

			if ( function_exists( 'wpcom_vip_top_posts_array' ) && true === WPCOM_IS_VIP_ENV ) :
				// wpcom_vip_top_posts_array does not limit by post type and
				// will happily return the ids of translations, which are
				// excluded by the WP_Query that follows, leading to a truncated
				// list of top stories. To overcome this we fetch the top 100,
				// ids - the maximum allowed - rather than 8.
				$ids = array_map( function ($a) {
					return $a[ 'post_id' ];
				}, wpcom_vip_top_posts_array( 30, 100 ) );
			else:
				$query = new WP_Query([
					'fields'         => 'ids',
					'posts_per_page' => 8,
				]);

				$ids = $query->get_posts();
			endif;

			$popular_posts = new WP_Query( [
				'post_type'           => 'post',
				'post__in'            => $ids,
				'posts_per_page'      => 0,
				'ignore_sticky_posts' => 1,
			]);

			while ( $popular_posts->have_posts() ) : $popular_posts->the_post();
				get_template_part( 'content', 'snippet' );
			endwhile;
			wp_reset_postdata();
		?>
	</div>
</div>
<div class="sophos-news-block">
	<h2 class="block-title"><?php esc_html_e( 'News from Sophos', 'forward' ); ?></h2>
	<div class="feed-collection">
		<?php sophos_blog_feed_articles(); ?>
	</div>
	<a href="https://news.sophos.com" class="visit-sophos"><?php esc_html_e( 'SOPHOS', 'forward' ); ?></a>
</div>
<?php sophos_panel_close(); ?>

<?php
/**
 * We're finished hiding the "extra" elements now. Render the rest of the page.
 */
endif; ?>

<?php sophos_panel_open( 'cards-panel' ); ?>
	<div class="content-wrapper">
		<?php // Output the rest of the the main query.
		$post_count = 0;
		while ( have_posts() ) : the_post(); ?>

			<?php // Start a new collection (row) before the 1st, 4th, and 7th post.
			if ( 0 === $post_count % 3 ) : ?>
				<div class="card-collection">
			<?php endif; ?>

			<?php get_template_part( 'content', 'card' );?>

			<?php // End the collection (row) after the 3rd, 6th, and 9th post.
			if ( 0 === ($post_count + 1) % 3 ) : ?>
				</div> <!-- card-collection-->
			<?php endif; ?>

		<?php $post_count++; endwhile; ?>
	</div>
<?php sophos_panel_close(); ?>

<?php sophos_posts_navigation( esc_html__( 'Load more articles', 'forward' ) ); ?>

<?php sophos_panel_open( 'banner-panel' ); ?>
	<?php sophos_random_advert( 'content-advert', 'banner' ); ?>
<?php sophos_panel_close(); ?>

<?php get_footer();
