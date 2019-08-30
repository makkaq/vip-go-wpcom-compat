<?php sophos_panel_open( 'recommended-panel' );
	$recommended_posts_limit = 3;
?>
	<h3 class="block-title"><?php esc_html_e( 'Recommended reads', 'nakedsecurity' ); ?></h3>
	<div class="blocks">
		<?php // Recommended Posts
		if ( class_exists( 'Jetpack_RelatedPosts' ) && method_exists( 'Jetpack_RelatedPosts', 'init_raw' ) ) {
			$jetpack_recommends = Jetpack_RelatedPosts::init_raw()->get_for_post_id( get_the_ID(), [ 'size' => $recommended_posts_limit ] );
			$recommended_posts  = new WP_Query([
				'no_found_rows'  => true, // avoid SQL_CALC_FOUND_ROWS
				'post__in'       => array_column( $jetpack_recommends, 'id' ),
				'posts_per_page' => $recommended_posts_limit
			]);
		} else {
			// Pull random posts that were posted recently if Jetpack is not available.
			$recommended_posts = new WP_Query([
				'no_found_rows'       => true, // avoid SQL_CALC_FOUND_ROWS
				'posts_per_page'      => $recommended_posts_limit,
				'orderby'             => 'rand',
				'ignore_sticky_posts' => 1,
				'date_query'          => [[
					'column' => 'post_date_gmt',
					'after'  => '3 months ago',
				],],
			]);
		}
		while ( $recommended_posts->have_posts() ) : $recommended_posts->the_post();
			set_query_var( 'article_class', 'card-slide' );
			get_template_part( 'content', 'card' );
		endwhile;
		wp_reset_postdata();
		?>
	</div>
<?php sophos_panel_close();
