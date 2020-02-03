<?php

if ( ! function_exists( 'sophos_term_listing' ) ) :
	/**
 * Show categories and tags as a single list.
 * If an identical tag and category exists give preference to the category.
 */
	function sophos_term_listing( $separator = ' ', $output_list_items = false, $exclude_tags = false ) {

        $terms = [];

        foreach ( [ 'category', 'post_tag' ] as $taxonomy ) {

            if ( 'post_tag' === $taxonomy && true === $exclude_tags ) {
                continue;
            }

            $tax = get_the_terms( false, $taxonomy );

            if ( is_wp_error( $tax ) ) {
                continue;
            }

            if ( false === $tax ) {
                continue;
            }

            // Filter tags and categories we don't want to display
            $term_objects = array_filter ( $tax, function ( \WP_Term $term ) {
                return ! in_array( strtolower( $term->name ), [ 'sidebar', 'uncategorized' ], true );
            });

            // Remove duplicates by assigning to $terms based on name
            foreach ( $term_objects as $term ) {
                $terms[ $term->name ] = $term;
            }
        }

        // The keys were for de-duping, we can discard them now
        $terms  = array_values( $terms );
		$links  = array();
        $output = '';

		foreach ( $terms as $term ) {
			$link = sophos_get_term_link( $term->term_id );
			if ( is_wp_error( $link ) ) {
				return $link;
			}
			$links[] = '<a href="' . esc_url( $link ) . '">' . $term->name . '</a>';
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
