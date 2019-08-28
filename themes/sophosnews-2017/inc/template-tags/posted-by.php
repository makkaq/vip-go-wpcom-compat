<?php

if ( ! function_exists( 'sophos_posted_by' ) ) :
	/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
	function sophos_posted_by() {

		?><span class="byline"><?php

		/* translators: author name */
		echo _x( 'By ', 'post author', 'sophos-news' );

		if ( function_exists( 'get_coauthors' ) ) :
			$coauthors = get_coauthors();
			foreach ( $coauthors as $co ) :
				?><span class="author vcard"><?php
					echo wp_kses( coauthors_posts_links_single( $co ), [
						'a' => [
							'href' 	=> [],
							'title' => [],
							'class' => [],
							'rel' 	=> [],
						],
					]);
				?></span><?php
			endforeach;
		else:
			?><span class="author vcard"><a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html( get_the_author() ); ?></a></span><?php
		endif;
		?></span><?php
	}
endif;
