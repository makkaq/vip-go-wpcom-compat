<?php

if ( ! function_exists( 'sophos_entry_footer' ) ) :
	/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
	function sophos_entry_footer() {

		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			$categories_list = get_the_category_list( __( ', ', 'sophos-news' ) );
			if ( $categories_list && sophos_categorized_blog() ) {
				/* translators: used between list items, there is a space after the comma */
				printf( '<span class="cat-links">' . wp_kses( 'Posted in %1$s', 'sophos-news' ) . '</span>', $categories_list // WPCS: XSS ok, sanitization ok.
				);
			}

			$tags_list = get_the_tag_list( '', __( ', ', 'sophos-news' ) );
			if ( $tags_list ) {
				/* translators: used between list items, there is a space after the comma */
				printf( '<span class="tags-links">' . wp_kses( 'Tagged %1$s', 'sophos-news' ) . '</span>', $tags_list // WPCS: XSS ok, sanitization ok.
				);
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( __( 'Leave a comment', 'sophos-news' ), __( '1 Comment', 'sophos-news' ), __( '% Comments', 'sophos-news' ) );
			echo '</span>';
		}

		edit_post_link( __( 'Edit', 'sophos-news' ), '<span class="edit-link">', '</span>' );
	}

endif;
