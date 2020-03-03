<?php
/**
 * Wordpress utility functions
 *
 * @package Sophos
 * @subpackage Utils
 */

namespace Sophos\Utils;


/**
 * This Conditional Tag checks if Wordpress is being run from wp-cli
 *
 * @return boolean
 */
function is_wp_cli() {
	return defined( 'WP_CLI' ) && WP_CLI;
}


/**
 * This Conditional Tag checks if the current request is an AJAX request
 *
 * @return boolean
 */
function is_ajax() {
	return defined( 'DOING_AJAX' ) && DOING_AJAX;
}


/**
 * This Conditional Tag checks if the current view is a regionalised wp-admin/edit.php
 *
 * @return boolean
 */
function is_edit_page() {

	// get_current_screen only works in admin
	if ( ! is_admin() || ! function_exists( 'get_current_screen' ) ) {
		return;
	}

	$screen = get_current_screen();

    if ( $screen instanceof \WP_Screen ) {
	    $types  = \Sophos\Region\Taxonomy::POST_TYPES;

    	if ( 'edit' === $screen->base && in_array( $screen->post_type, $types, true ) ) {
    		return true;
    	}
    }

	return false;
}


/**
 * This Conditional Tag checks if the current view is wp-admin/edit-comments.php
 *
 * @param int|WP_Post $post Post ID or object
 * @return boolean
 */
function is_edit_comments_page( $post ) {

	// get_current_screen only works in admin
	if ( ! is_admin() || ! function_exists( 'get_current_screen' ) ) {
		return;
	}

	$post   = ( $post instanceof \WP_Post ) ? $post : get_post( (int) $post );
	$screen = get_current_screen();

    if ( $screen instanceof \WP_Screen ) {
        $types  = \Sophos\Region\Taxonomy::POST_TYPES;

        if ( 'edit-comments' === $screen->base && in_array( $post->post_type, $types, true ) ) {
            return true;
        }
    }

	return false;
}


/**
 * This Conditional Tag checks if the current view is the login screen
 *
 * @return boolean
 */
function is_login_page() {
	if ( 'wp-login.php' === $GLOBALS['pagenow'] ) {
		return true;
	}

	return false;
}


/**
 * This Conditional Tag checks if the current view's URL is for comment submission
 *
 * @return boolean
 */
function is_comment_page() {
	if ( 'wp-comments-post.php' === $GLOBALS['pagenow'] ) {
		return true;
	}

	return false;
}


/**
 * This Conditional Tag checks if an iso code belongs to a Sophos region
 *
 * @param  string $iso ISO language code
 * @return boolean
 */
function is_region( $iso ) {

	$iso = str_replace( '_', '-', strtolower( trim( $iso ) ) );

	foreach ( \Sophos\Region::regions() as $term ) {
		if ( $term->slug === $iso ) {
			return true;
		}
	}

	return false;
}


/**
 * This Conditional Tag checks if an iso code belongs to a Sophos language
 *
 * @param  string $iso ISO language code
 * @return boolean
 */
function is_language( $iso ) {

	$iso = str_replace( '_', '-', strtolower( trim( $iso ) ) );

	foreach ( \Sophos\Region\Taxonomy\cache() as $term ) {
		if ( $term->slug === $iso ) {
			return true;
		}
	}

	return false;
}


/**
 * This Conditional Tag checks if a URL is a preview URL
 *
 * @param  string $url
 * @return boolean
 */
function is_preview( $url ) {

	$components = wp_parse_url( $url );
	parse_str( array_key_exists( 'query', $components ) ? $components['query'] : '', $query );
	$preview = array_key_exists( 'preview', $query ) ? $query['preview'] : '';

	if ( 'true' === $preview ) {
		return true;
	}

	$post_id = array_key_exists( 'p', $query ) ? $query['p'] : false;

	if ( is_numeric( $post_id ) && get_post_status( $post_id ) === 'draft' ) {
		return true;
	}

	return false;
}


/**
 * This Conditional Tag checks if the current site is regionalised
 *
 * @param  string $url
 * @return boolean
 */
function is_regionalised() {
	return get_option( 'sophos_rewrites_flushed' )
		&& taxonomy_exists( \Sophos\Region\Taxonomy::NAME )
		&& \Sophos\Region::has_terms();
}


/**
 * Platform agnostic term_exists function
 *
 * @param  integer|string $term The term to check
 * @param  string $taxonomy The taxonomy name to use
 * @param  integer $parent Parent term ID
 * @return null|0|integer|array
 */
function term_exists( $term, $taxonomy = '', $parent = null ) {
	$term_exists = ( function_exists( 'wpcom_vip_term_exists' ) )
	             ? 'wpcom_vip_term_exists'
				 : 'term_exists';

	return $term_exists( $term, $taxonomy, $parent );
}


/**
 * Take a good guess at the default region for a post
 *
 * @param int|WP_Post $post Post ID or object
 * @return WP_Term or false
 */
function get_post_region( $post ) {

	$terms = get_the_terms( $post, \Sophos\Region\Taxonomy::NAME );

	// No terms
	if ( false === $terms ) {
		return false;
	}

	// Failure
	if ( is_wp_error( $terms ) ) {
		return false;
	}

	switch ( count( $terms ) ) {
		case 0: // Post has no regions
			return false;
		case 1: // Post has one region, easy choice.
			return $terms[0];
		default: // Post has mutliple regions
			$post = ( is_int( $post ) ) ? get_post( $post ) : $post;
			$iso  = \Sophos\User\get_meta( $post->post_author, \Sophos\Language::USER_META_KEY );

			try {
				// If the post author doesn't have a region $iso
				// is empty and this throws an exception.
				$language = new \Sophos\Language( $iso );

				// If the post author has a region and it matches one of the
				// regions assinged to the post, use that.
				foreach ( $terms as $term ) {
					if ( $language->format_for_sophos() === $term->slug ) {
						return $term;
					}
				}
			} catch ( \Sophos\Exception\InvalidLanguageCode $e ) {
				// It's OK
			}

			// IF all else fails, first off the list
			return array_shift( $terms );
	}
}
