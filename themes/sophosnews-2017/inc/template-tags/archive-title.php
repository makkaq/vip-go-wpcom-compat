<?php

if ( ! function_exists( 'sophos_the_archive_title' ) ) :
	/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
	function sophos_the_archive_title( $before = '', $after = '' ) {
		if ( is_category() ) {
			/* translators: %s category */
			$title = sprintf( __( 'Articles Categorized %s', 'sophos-news' ), '<span>' . single_cat_title( '', false ) . '</span>' );
		} elseif ( is_tag() ) {
			/* translators: %s tag */
			$title = sprintf( __( 'Articles Tagged %s', 'sophos-news' ), '<span>' . single_tag_title( '', false ) . '</span>' );
		} elseif ( is_author() ) {
			/* translators: %s author */
			$title = sprintf( __( 'Articles by %s', 'sophos-news' ), '<span class="vcard">' . get_the_author() . '</span>' );
		} elseif ( is_year() ) {
			/* translators: %s year */
			$title = sprintf( __( 'Articles from %s', 'sophos-news' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'sophos-news' ) ) . '</span>' );
		} elseif ( is_month() ) {
			/* translators: %s month */
			$title = sprintf( __( 'Articles from %s', 'sophos-news' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'sophos-news' ) ) . '</span>' );
		} elseif ( is_day() ) {
			/* translators: %s day */
			$title = sprintf( __( 'Articles from %s', 'sophos-news' ), '<span>' . get_the_date( _x( 'F j, Y', 'daily archives date format', 'sophos-news' ) ) . '</span>' );
		} elseif ( is_tax( 'post_format' ) ) {
			if ( is_tax( 'post_format', 'post-format-aside' ) ) {
				$title = _x( 'Asides', 'post format archive title', 'sophos-news' );
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title = _x( 'Galleries', 'post format archive title', 'sophos-news' );
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title = _x( 'Images', 'post format archive title', 'sophos-news' );
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title = _x( 'Videos', 'post format archive title', 'sophos-news' );
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title = _x( 'Quotes', 'post format archive title', 'sophos-news' );
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title = _x( 'Links', 'post format archive title', 'sophos-news' );
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title = _x( 'Statuses', 'post format archive title', 'sophos-news' );
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title = _x( 'Audio', 'post format archive title', 'sophos-news' );
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title = _x( 'Chats', 'post format archive title', 'sophos-news' );
			}
		} elseif ( is_post_type_archive() ) {
			/* translators: %s title */
			$title = sprintf( __( 'Archives: %s', 'sophos-news' ), post_type_archive_title( '', false ) );
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( get_queried_object()->taxonomy );
			/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
			$title = sprintf( __( '%1$s: %2$s', 'sophos-news' ), $tax->labels->singular_name, single_term_title( '', false ) );
		} else {
			$title = __( 'Archives', 'sophos-news' );
		}// End if().

			/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
			$title = apply_filters( 'get_the_archive_title', $title );

		if ( ! empty( $title ) ) {
			echo wp_kses_post( $before . $title . $after );
		}
	}
endif;
