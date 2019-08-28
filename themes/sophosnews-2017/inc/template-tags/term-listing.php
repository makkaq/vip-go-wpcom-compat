<?php

if ( ! function_exists( 'sophos_term_listing' ) ) :
	/**
 * Show categories and tags as a single list.
 * If an identical tag and category exists give preference to the category.
 */
	function sophos_term_listing( $separator = ' ', $output_list_items = false, $exclude_tags = false ) {
		$category_terms = get_the_terms( false, 'category' );
		$tag_terms = get_the_terms( false, 'post_tag' );

		// cast both data sets as arrays
		$category_terms = json_decode( wp_json_encode( $category_terms ), true );
		$tag_terms = json_decode( wp_json_encode( $tag_terms ), true );

		$category_names = array_column( $category_terms, 'name' );
		$terms = [];
		$output = '';

		if ( ! empty( $category_terms ) ) {
			foreach ( $category_terms as $cat_term ) {
				// skip specified categories
				if ( in_array( $cat_term['slug'], [ 'sidebar', 'uncategorized' ], true ) ) {
					continue;
				}
				$terms[] = $cat_term;
			}
		}

		if ( ! empty( $tag_terms ) && ! $exclude_tags ) {
			foreach ( $tag_terms as $term ) {
				if ( in_array( $term['name'], $category_names, true ) ) {
					continue;
				}
				// skip specified tags
				if ( in_array( $term['slug'], [ 'sidebar' ], true ) ) {
					continue;
				}
				$terms[] = $term;
			}
		}

		if ( empty( $terms ) ) {
			return false;
		}

		$links = array();

		foreach ( $terms as $term ) {
			$link = sophos_get_term_link( $term['term_id'] );
			if ( is_wp_error( $link ) ) {
				return $link;
			}
			$links[] = '<a href="' . esc_url( $link ) . '">' . $term['name'] . '</a>';
		}

		if ( $output_list_items ) {
			foreach ( $links as $link ) {
				$output .= '<li>' . $link . '</li>' . PHP_EOL;
			}
		} else {
			$output = join( $separator, $links );
		}

		return $output;
	}
endif;
