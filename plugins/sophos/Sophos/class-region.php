<?php

namespace Sophos;

/**
 * Class for representing a region
 */
class Region {


	/**
	 * Guess the current user's region
	 *
	 * @return string pseudo-ISO country code or false
	 */
	public static function guess() {
		try {
			$language = \Sophos\Language::from_url();
			# FIXME Note that our switch to using data in guest profiles rather than user profiles probably
			# makes from_user_setting useless for now. The code will fall back to data stored in user profiles
			# regardless of what's in the guest profile because the guest_authors property of the CoAuthors
			# class doesn't exist at the time we want the data. I suspect that co-author data is instantiated
			# after this code is run from init (priority 1). Changing the priority might fix things, but it might break
			# a bunch of other stuff too.
			$language = ( $language instanceof \Sophos\Language ) ? $language : \Sophos\Language::from_user_setting();
			$language = ( $language instanceof \Sophos\Language ) ? $language : \Sophos\Language::from_http_headers();
			$language = ( $language instanceof \Sophos\Language ) ? $language : \Sophos\Language::from_default();
			$region   = self::from_language( $language );

			if ( $region instanceof \WP_Term ) {
				return $region->slug; // TODO clients of guess should use Region object, not slug?
			} else {
				throw new \Exception( '\Sophos\Region::from_language did not return a \WP_Term object' );
			}
		} catch ( \Sophos\Exception\TaxonomyError $e ) {
			$language = \Sophos\Language::from_default();
			return $language->format_for_sophos(); // TODO clients of guess should use Region object, not slug?
		}
	}


	/**
	 * Create a region object based on a language
	 *
	 * @constructor
	 * @param  \Sophos\Language $language Language object
	 * @return \WP_Term
	 */
	public static function from_language( \Sophos\Language $language ) {

		$iso    = $language->format_for_sophos();
		$region = self::from_slug( $iso );

		// FIXME make recursive in case of deep hierarchy
		if ( property_exists( $region, 'parent' ) && 0 !== $region->parent ) {
			$region = self::from_id( $region->parent );
		}

		return $region;
	}


	/**
	 * Get a region from the cache based on its slug
	 *
	 * @param  string $slug
	 * @return \WP_Term
	 */
	public static function from_slug( $slug ) {
		$regions = array_filter( self::terms(), function ( $term ) use ( $slug ) {
			return $term->slug === $slug;
		});

		if ( is_array( $regions ) && count( $regions ) === 1 ) {
			return array_shift( $regions );
		} else {
			throw new \Sophos\Exception\TaxonomyError( sprintf( 'Slug %s matched %d items', $slug, count( $regions ) ) );
		}
	}


	/**
	 * Get a region from the cache based on its ID
	 *
	 * @param  int $id
	 * @return \WP_Term
	 */
	public static function from_id( $id ) {

		$terms = self::terms();
		$regions = array_filter( self::terms(), function ( $term ) use ( $id ) {
			return $term->term_id === $id;
		});

		if ( is_array( $regions ) && count( $regions ) === 1 ) {
			return array_shift( $regions );
		} else {
			throw new \Sophos\Exception\TaxonomyError( sprintf( 'ID %d matched %d items', $id, count( $regions ) ) );
		}
	}


	/**
	 * Get all the terms in the region taxonomy
	 *
	 * The region taxonomy consists of parents that represent regions and children
	 * that represent target languages for that region. This method returns them all.
	 *
	 * @return array Array of WP_Term objects
	 */
	public static function terms() {
		return get_option( \Sophos\Region\Taxonomy::OPTION_KEY ) ?: \Sophos\Region\Taxonomy\cache();
	}


	/**
	 * Are there any terms in the region taxonomy?
	 *
	 * @return boolean
	 */
	public static function has_terms() {
		$terms = self::terms();
		return ! empty( $terms );
	}


	/**
	 * Get all available regions
	 *
	 * The region taxonomy consists of parents that represent regions and children
	 * that represent target languages for that region. This method returns the parents.
	 *
	 * @return array Array of WP_Term objects
	 */
	public static function regions() {
		$terms = self::terms();
		return array_filter( $terms, function ( $term ) {
			return ! ( $term->parent && $term->parent > 0 );
		});
	}
}
