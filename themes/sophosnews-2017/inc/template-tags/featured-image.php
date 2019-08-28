<?php

if ( ! function_exists( 'sophos_featured_image' ) ) :
	/**
 * Display featured image when available.
 */
	function sophos_featured_image() {
		if ( has_post_thumbnail() ) {
		?>
	<div class="entry-featured-image">
		<?php the_post_thumbnail(); ?>
	</div> <!-- .featured-image -->
	<?php
		}
	}
endif;
