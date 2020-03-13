<?php
/**
 * Custom template tags for Naked Security
 */

/**
 * Display the entry author byline.
 */
function sophos_entry_authors() {

	?>

	<?php if ( sophos_coauthors_enabled() ) : ?>

		<?php if ( sophos_is_coauthored() ) : ?>
			<span class="co-authored-by"><?php esc_html_e( 'Co-authored by', 'forward' ) ?></span>
			<?php coauthors_posts_links( null, null, '<div class="co-authors-list">', '</div>', true ); ?>
		<?php else : ?>
			<span class="by"><?php esc_html_e( 'by', 'forward' ) ?></span>
			<?php coauthors_posts_links(); ?>
		<?php endif; ?>

	<?php else : ?>
		<span class="by"><?php esc_html_e( 'by', 'forward' ) ?></span>
		<?php the_author_posts_link(); ?>
	<?php endif; ?>

	<?php
}

/**
 * Display the entry author byline for content cards.
 */
function sophos_content_card_entry_authors() {

	?>

	<?php if ( sophos_coauthors_enabled() ) : ?>
		<span class="by"><?php esc_html_e( 'by', 'forward' ) ?></span>
		<?php coauthors(); ?>
	<?php else : ?>
		<span class="by"><?php esc_html_e( 'by', 'forward' ) ?></span>
		<?php the_author(); ?>
	<?php endif; ?>

	<?php
}

/**
 * Format and display the post date.
 */
function sophos_entry_published_date() {
	?>

	<span class="entry-published">
		<time class="entry-date published" datetime="<?php esc_attr_e( get_the_date( 'c' ) ); ?>"><?php esc_html_e( get_the_date( 'd M Y' ) ); ?></time>
	</span>

	<?php
}

/**
 * Format and display the post categories.
 */
function sophos_entry_categories() {
	?>
		
	<span class="entry-categories">
	<?php
		$categories = array_filter(get_the_category(), function ($cat) {
			return strtolower( $cat->name ) !== 'uncategorized';
		});
	
		echo implode( ', ', array_map( function ($cat) {
			return sprintf('<a href="%s">%s</a>', esc_url(get_category_link($cat->term_id)), esc_html($cat->name));			
		}, $categories) );
	?>
	</span>

	<?php
}

/**
 * Format and display the post tags.
 */
function sophos_entry_tags() {
	?>

	<div class="entry-tags">
		<?php the_tags( '<ul><li>', '</li><li>', '</li></ul>' ); ?>
	</div>

	<?php
}

/**
 * Format and display tags based on count.
 */
function sophos_popular_tags() {

	$tags = get_tags(
		[
			'orderby' => 'count',
			'order'   => 'DESC',
			'number'  => 30
		]
	);
	?>

	<div class="entry-tags">
	<ul>

	<?php

	$queried_object = get_queried_object();
	$page_term_id   = false;

	if ( isset( $queried_object ) ) {
		$page_term_id = get_queried_object()->term_id;
	}

	foreach ( $tags as $tag ) {
		$this_term_id = $tag->term_id;
		$tag_link     = get_tag_link( $this_term_id );
		$list_class   = '';

		// Apply a special class if the current term is being viewed.
		if ( $page_term_id == $this_term_id ) {
			$list_class = 'current-tag';
		}
		?>

		<li class="<?php esc_attr_e( $list_class ); ?>">
			<a href="<?php echo esc_url( $tag_link ); ?>" title="<?php esc_attr_e( $tag->name ); ?> tag" class="<?php esc_attr_e( $tag->slug ); ?>"><?php esc_html_e( $tag->name ); ?></a>
		</li>

		<?php
	}

	?>

	</ul>
	</div>

	<?php
}

/**
 * Post navigation is just a single "Load More" button.
 *
 * @param null $label Override the default starting label
 */
function sophos_posts_navigation( $label = null ) {
	global $wp_query;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 ) {
		return;
	}

	if ( ! $label ) {
		$label = esc_html__( 'Show more articles', 'forward' );
	}

	/**
	 * Load more button should be "Show More Articles By {user_firstname}"
	 * If user_firstname has not been set, we will default to the user's display name.
	 */
	if ( is_author() ) {
		$output_name = get_the_author_meta( 'user_firstname' );
		if ( empty( $output_name ) ) {
			$output_name = get_the_author();
		}
		$label = sprintf( esc_html__( 'Show more articles by %s', 'forward' ), $output_name );
	}

	?>
	<section class="load-more">
		<div class="container">
			<?php next_posts_link( $label ); ?>
		</div>
	</section>
	<?php
}

/**
 * Display navigation to next/previous post when applicable.
 */
function sophos_post_navigation() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'forward' ); ?></h2>

		<div class="nav-links">
			<?php
			previous_post_link( '<div class="nav-previous">%link</div>', '<span class="nav-label">Previous</span><span class="nav-seperator">:</span><span class="nav-title"> %title</span>' );
			next_post_link( '<div class="nav-next">%link</div>', '<span class="nav-label">Next</span><span class="nav-seperator">:</span><span class="nav-title"> %title</span>' );
			?>
		</div>
		<!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}

/**
 * News From Sophos
 * Articles displayed on the front page are being pulled from
 * https://blogs.sophos.com
 */
function sophos_blog_feed_articles() {
	$feed = fetch_feed( 'https://news.sophos.com/en-us/feed/' );
	if ( ! is_wp_error( $feed ) ) {
		$max_items = $feed->get_item_quantity( 7 );
		$rss_items = $feed->get_items( 0, $max_items );

		foreach ( $rss_items as $item ) {
			echo '<article class="feed-article">';
			echo '<div class="feed-content">';
			echo '<h4 class="feed-title"><a href="' . esc_url( $item->get_permalink() ) . '" rel="bookmark">' . esc_html( $item->get_title() ) . '</a></h4>';
			echo '</div>';
			echo '</article> <!-- .feed-article -->';
		}
	}
}

/**
 * Pull a random advert post and display it using the specified template part.
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template.
 *
 * @see get_template_part()
 */
function sophos_random_advert( $slug, $name = null ) {
	$random_advert = new WP_Query(
		[
			'post_type'      => 'sophos_advert',
			'meta_key'       => 'sophos_advert_fields',
			'orderby'        => 'rand',
			'posts_per_page' => 1
		]
	);

	if ( ! $random_advert->have_posts() ) {
		return;
	}

	$random_advert->the_post();
	get_template_part( $slug, $name );
	wp_reset_postdata();
}

/**
 * Generate a week range string based on the $time argument
 *
 * Output shown when start and end month's are the same:
 * "{month} {start_day}-{end_day}, {year}"
 *
 * Output shown when the start and end month's are different but the year is
 * the same:
 * "{month} {start_day} - {end_month} {end_day}, {year}"
 *
 * Output shown when the start and end year's are different:
 * "{month} {start_day}, {start_year} - {end_month} {end_day}, {end_year}"
 *
 * @param $time a timestamp
 *
 * @return string
 */
function sophos_get_week_range_from_date( $time ) {
	// Figure out what Monday's timestamp should be
	if ( 1 == date( 'w', $time ) ) {
		$monday = $time;
	} else {
		$monday = strtotime( 'last monday', $time );
	}

	// Figure out what Sunday's timestamp should be
	if ( 0 == date( 'w', $time ) ) {
		$sunday = $time;
	} else {
		$sunday = strtotime( 'next sunday', $time );
	}

	$start_date = [
		'month' => date( 'F', $monday ),
		'day'   => date( 'j', $monday ),
		'year'  => date( 'Y', $monday ),
	];

	$end_date = [
		'month' => date( 'F', $sunday ),
		'day'   => date( 'j', $sunday ),
		'year'  => date( 'Y', $sunday ),
	];

	if ( $start_date['month'] == $end_date['month'] ) {
		return sprintf(
			'%s %d-%d, %d', $start_date['month'], $start_date['day'], $end_date['day'], $start_date['year']
		);
	}

	if ( $start_date['year'] != $end_date['year'] ) {
		return sprintf(
			'%s %d, %d - %s %d, %d', $start_date['month'], $start_date['day'], $start_date['year'], $end_date['month'], $end_date['day'], $end_date['year']
		);
	}

	if ( $start_date['month'] != $end_date['month'] ) {
		return sprintf(
			'%s %d - %s %d, %d', $start_date['month'], $start_date['day'], $end_date['month'], $end_date['day'], $start_date['year']
		);
	}
}

/**
 * Flush out the transients used in forward_categorized_blog.
 */
function sophos_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'forward_categories' );
}

add_action( 'edit_category', 'sophos_category_transient_flusher' );
add_action( 'save_post', 'sophos_category_transient_flusher' );
