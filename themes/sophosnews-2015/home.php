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

<?php echo do_shortcode( '[ad]' ); ?>

<?php sophos_panel_open( 'cards-panel' ); ?>
	<div class="card-collection">
		<?php // Show the first three posts from the main query.
		for ( $i = 0; $i < 3; $i ++ ) : the_post();
			get_template_part( 'content', 'card' );
		endfor; ?>
	</div>
<?php sophos_panel_close(); ?>

<div id="newsletter-signup" class="container"></div>

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
	<a href="https://news.sophos.com" class="visit-sophos"><svg style="height: 15px" viewBox="0 0 132 24" class="block icon sophos"><use xlink:href="#sophos"></use></svg></a>
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

<?php get_footer();
