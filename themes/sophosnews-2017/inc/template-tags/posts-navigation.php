<?php

if ( ! function_exists( 'sophos_posts_navigation' ) ) :
	/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
	function sophos_posts_navigation() {
		// Don't print empty markup if there's only one page.
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
			return;
		}
		?>

		<div class="load-more">
		<a href="#" class="btn btn-outline-blue">
			<?php esc_html_e( 'Load More', 'sophos-news' ); ?>
		</a>
		</div>

		<?php
	}
endif;
