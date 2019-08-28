<?php

if ( ! function_exists( 'sophos_post_navigation' ) ) :
	/**
	 * Display navigation to next/previous post when applicable.
	 */
	function sophos_post_navigation() {

		$post       = get_post();
		$post_terms = get_the_terms( $post->ID, \Sophos\Region\Taxonomy::NAME ) ?: [];
		$guess 		= \Sophos\Region::guess();

		if ( is_wp_error( $post_terms ) ) {
			return;
		}

		$post_terms_not_in_current_region = array_filter(
			$post_terms, function ( \WP_Term $term ) use ( $guess ) {
				return $term->slug !== $guess;
			}
		);

		$exclude_terms = wp_list_pluck( $post_terms_not_in_current_region, 'term_id' );
		$prev = ( is_attachment() )
		   	  ? get_post( get_post()->post_parent )
		   	  : sophos_get_adjacent_post( true, $exclude_terms, true, \Sophos\Region\Taxonomy::NAME );
		$next = sophos_get_adjacent_post( true, $exclude_terms, false, \Sophos\Region\Taxonomy::NAME );

		// Don't print empty markup if there's nowhere to navigate.
		if ( ! $next && ! $prev ) {
			return;
		}

		?>
		<nav class="navigation post-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'sophos-news' ); ?></h2>
			<div class="nav-links">
	   			<div class="nav-previous">
					<?php if ( $prev instanceof WP_Post ) : ?>
						<span class="nav-label"><?php echo esc_html_x( 'Prev', 'Shown at the bottom of each article alongside the title of the previous article.', 'sophos-news' ) ?></span>
						<a href="<?php echo esc_url( get_permalink( $prev->ID ) ); ?>" rel="prev"><?php echo esc_html( $prev->post_title ); ?></a>
					<?php endif; ?>
	   			</div>
	   			<div class="nav-next">
					<?php if ( $next instanceof WP_Post ) : ?>
						<span class="nav-label"><?php echo esc_html_x( 'Next', 'Shown at the bottom of each article alongside the title of the next article.', 'sophos-news' ) ?></span>
						<a href="<?php echo esc_url( get_permalink( $next->ID ) ); ?>" rel="next"><?php echo esc_html( $next->post_title ); ?></a>
					<?php endif; ?>
	   			</div>
		 	</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}
endif;
