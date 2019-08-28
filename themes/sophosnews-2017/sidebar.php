<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Sophos
 */

?>

<div id="secondary" class="widget-area" role="complementary">
	<?php if ( is_front_page() || is_tax( 'region' ) || is_search() ) : ?>

	    <?php
		// Get posts tagged sidebar
		$the_query = new WP_Query([
			'post_type' => 'post',
			'posts_per_page' => '4',
			'tag' => 'sidebar',
			'tax_query' => [ sophos_get_region_tax_query() ],
		]);

		// If no results, grab 4 random posts
		if ( ! $the_query->have_posts() ) {
			$the_query = new WP_Query([
				'post_type' => 'post',
				'post__in' => sophos_get_random_posts(),
				'posts_per_page' => '4',
				'tax_query' => [ sophos_get_region_tax_query() ],
			]);
		}
	    ?>

		<div id="fourth-article-wrapper" class="article-wrapper">
			<?php // Fourth article style
			while ( $the_query->have_posts() ) { $the_query->the_post();
				get_template_part( 'content', 'fourth' );
			} wp_reset_postdata(); ?>
		</div>

	<?php elseif ( is_single() || is_page() ) : ?>
		<?php

		// 'related_posts' may be populated with 4 null items
		$related  = get_post_meta( get_the_ID(), 'related_posts' );
		$post_ids = ( is_array( $related ) && 0 !== count( $related ) )
				  ?	array_filter( $related[0], function ( $i ) {
		 		   		return empty( $i ) ? false : true;
		 		  })
				  : [];

		if ( 0 === count( $post_ids ) ) {
			// Get posts tagged sidebar
			$the_query = new WP_Query([
				'post_type' => 'post',
				'posts_per_page' => '4',
				'tag' => 'sidebar',
				'tax_query' => [ sophos_get_region_tax_query() ],
			]);

			// If no results, grab 4 random posts
			if ( ! $the_query->have_posts() ) {
				$the_query = new WP_Query([
					'post_type' => 'post',
					'post__in' => sophos_get_random_posts(),
					'posts_per_page' => '4',
					'tax_query' => [ sophos_get_region_tax_query() ],
				]);
			}
		} else {
			$the_query = new WP_Query([
				'post_type' => 'post',
				'post__in' => array_values( $post_ids ),
				'posts_per_page' => '4',
				'tax_query' => [ sophos_get_region_tax_query() ],
			]);
		}
	    ?>

		<div id="second-article-wrapper" class="article-wrapper">
			<h3 class="widget-title"><?php echo esc_html_x('You might also enjoy...', 'Article sidebar title', 'sophos-news') ?></h3>
			<?php // Fourth article style
			while ( $the_query->have_posts() ) { $the_query->the_post();
				get_template_part( 'content', 'second' );
			} wp_reset_postdata(); ?>
		</div>

	<?php else : ?>
		<?php
		echo 'here';
		if ( is_active_sidebar( 'sidebar-1' ) ) {
			dynamic_sidebar( 'sidebar-1' );
		} ?>
	<?php endif; ?>
</div> <!-- #secondary -->
