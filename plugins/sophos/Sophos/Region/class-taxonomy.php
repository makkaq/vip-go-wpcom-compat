<?php

namespace Sophos\Region {

	/**
	 * Manipulate the region taxonomy
	 */
	class Taxonomy {


		/**
		 * Taxonomy post types
		 */
		const POST_TYPES = [ 'post', 'page' ]; // PHP >= 5.6


		/**
		 * Region taxonomy name
		 */
		const NAME = 'region';


		/**
		 * Region permastruct tag name
		 */
		const TAG = '%region%';


		/**
		 * Taxonomy nonce name
		 */
		const NONCE_KEY = 'sophos-regions-nonce';


		/**
		 * Taxonomy nonce name
		 */
		const NONCE_VALUE = 'sophos-Z5EvP7yp8ojK8FNHpY0qzhXZ';


		/**
		 * Taxonomy cache option name
		 */
		const OPTION_KEY = 'sophos-regions';


		/**
		 * Magic query var representing all regions
		 */
		const ALL_REGIONS = 'all';


		/**
		 * The canonical list of regions and their Accept-Language headers
		 *
		 * @var array
		 */
		private $regions = [
			// Entry names represent Sophos regions, accept-language lists browser locales they deal with
			'Anglosphere'    => [
				'slug' => 'en-us',
				'accept-language' => [ 'en', 'en-gb', 'en-au', 'en-nz', 'en-ca', 'en-za', 'en-in' ],
			],
			'France'         => [
				'slug' => 'fr-fr',
				'accept-language' => [ 'fr' ],
			],
			'Deutschland'    => [
				'slug' => 'de-de',
				'accept-language' => [ 'de', 'de-at', 'de-li', 'de-ch' ],
			],
			'Iberia'         => [
				'slug' => 'es-es',
				'accept-language' => [ 'es', 'pt', 'pt-pt' ],
			],
			'Italia'         => [
				'slug' => 'it-it',
				'accept-language' => [ 'it' ],
			],
			'Benelux'        => [
				'slug' => 'nl-nl',
				'accept-language' => [ 'nl', 'nl-be', 'fr-be', 'fr-lu' ],
			],
			'Brasil'         => [
				'slug' => 'pt-br',
				'accept-language' => [ 'es', 'pt', 'pt-pt' ],
			],
			'日本'            => [
				'slug' => 'jp-jp',
			], // Partner site only
			'América Latina' => [
				'slug' => 'es-419',
				'accept-language' => [
					// Spanish as spoken in...
					'es-ar', // Argentina
					'es-bo', // Bolivia
					'es-cl', // Chile
					'es-co', // Columbia
					'es-cr', // Costa Rica
					'es-do', // Dominican Republic
					'es-ec', // Ecuador
					'es-gt', // Guatemala
					'es-hn', // Honduras
					'es-mx', // Mexico
					'es-ni', // Nicaragua
					'es-pa', // Panama
					'es-pe', // Peru
					'es-pr', // Puerto Rico
					'es-py', // Paraguay
					'es-sv', // El Salvador
					'es-us', // USA
					'es-uy', // Uruguay
					'es-ve',  // Venezuela
				],
			],
			'中文'        => [
				'slug' => 'zh-tw',
				'accept-language' => [ 'zh-hk' ],
			],
		];


		/**
		 * Are we conducting a live run?
		 *
		 * @var boolean
		 */
		private $live = true;


		/**
		 * Create a new \Sophos\Region\Taxonomy object
		 *
		 * @throws \Exception
		 * @param bool $live Live or trial run?
		 */
		public function __construct( $live = true ) {

			if ( 'cli' !== php_sapi_name() ) {
				// This class contains raw taxonomy data that WP-CLI scripts and
				// unit tests can use to build the basisc region taxonomy. Since
				// the region data can be changed by users as soon as its
				// converted into a taxonomy in almost all cases the right way
				// to access region data is via the region taxonomy.
				throw new \Exception( 'Please use \Sophos\Region::regions()' );
			}

			if ( is_bool( $live ) ) {
				$this->live = $live;
			} else {
				throw new \Exception( 'Constructor argument must be a boolean' );
			}
		}


		/**
		 * Delete all the terms in the region taxonomy
		 *
		 * @return int The number of terms deleted from the taxonomy
		 */
		public function remove_terms() {

			$count = 0;
			$terms = get_terms([
				'taxonomy'   => self::NAME,
				'hide_empty' => false,
				'fields'     => 'ids',
			]);

			if ( is_wp_error( $terms ) ) {
				\WP_CLI::error( $terms->get_error_message() );
			} else {
				foreach ( $terms as $id ) {
					if ( $this->live ) {
						$result = wp_delete_term( $id, self::NAME );
						if ( is_wp_error( $result ) ) {
							\WP_CLI::error( $result->get_error_message() );
						} elseif ( false === $result ) {
							\WP_CLI::error( sprintf( 'wp_delete_term retured false when deleting %s', $term->name ) );
						}
					}

					$count++;
				}
				delete_option( self::OPTION_KEY );
			}

			return $count;
		}


		/**
		 * Create the region taxonomy terms
		 *
		 * @param  \Sophos\Language|boolean $language Language object
		 * @return integer Number of terms added
		 */
		public function create_terms( $language = false ) {

			$count   = 0;
			$regions = ( ! $language instanceof \Sophos\Language )
					 ? $this->regions
			         : // Get an abbreviated region list if there's a region parameter
					 array_filter( $this->regions, function ( $region ) use ( $language ) {
						 return $region['slug'] === $language->format_for_sophos();
					 });

			foreach ( $regions as $name => $data ) {
				if ( $this->live ) {

					# term_exists returns 0 or NULL if term doesn't exist so
					# cast to boolean for type safe evaluation
					$term = \Sophos\Utils\term_exists( $name, self::NAME, 0 );

					if ( false === (bool) $term ) {
						# returns array( 'term_id' => n, 'term_taxonomy_id' => n )
						$term = wp_insert_term( $name, self::NAME, [
							'slug' => $data['slug'],
							'parent' => 0,
						]);
						$count++;
					}
				}

				// If it's a dry-run there will be no $term
				if ( ! $this->live || is_array( $term ) ) {
					// Are there any child languages?
					if ( array_key_exists( 'accept-language', $data ) ) {
						foreach ( $data['accept-language'] as $iso ) {
							if ( $this->live ) {

								$child = \Sophos\Utils\term_exists( "$iso", self::NAME, $term['term_id'] );

								if ( false === (bool) $child ) {
									$child = wp_insert_term( "$iso", self::NAME, [
										'slug' => $iso,
										'parent' => $term['term_id'],
									]);

									if ( is_wp_error( $child ) ) {
										\WP_CLI::error( "$iso " . $child->get_error_message() );
									} else {
										$count++;
									}
								}
							} else {
								$count++;
							}
						}
					}
				} elseif ( is_wp_error( $term ) ) {
					\WP_CLI::error( $term->get_error_message() );
				} else {
					\WP_CLI::error( sprintf( 'wp_insert_term returned %s', $term ) );
				}
			}// End foreach().

			return $count;
		}


		/**
		 * Access raw region data
		 *
		 * @return array Raw region data
		 */
		public function raw() {
			return $this->regions;
		}
	}
}


namespace Sophos\Region\Taxonomy {


	/**
	 * Register the region taxonomy
	 */
	function register() {
		register_taxonomy( \Sophos\Region\Taxonomy::NAME, \Sophos\Region\Taxonomy::POST_TYPES, [
			'hierarchical'       => true,
			'labels'             => [
				'name'              => _x( 'Regions', 'taxonomy general name', 'sophos-news' ),
				'singular_name'     => _x( 'Region', 'taxonomy singular name', 'sophos-news' ),
				'search_items'      => __( 'Search Regions', 'sophos-news' ),
				'all_items'         => __( 'All Regions', 'sophos-news' ),
				'parent_item'       => __( 'Parent Region', 'sophos-news' ),
				'parent_item_colon' => __( 'Parent Region:', 'sophos-news' ),
				'edit_item'         => __( 'Edit Region', 'sophos-news' ),
				'update_item'       => __( 'Update Region', 'sophos-news' ),
				'add_new_item'      => __( 'Add New Region', 'sophos-news' ),
				'new_item_name'     => __( 'New Region Name', 'sophos-news' ),
				'menu_name'         => __( 'Regions', 'sophos-news' ),
			],
			'public'             => true,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_quick_edit' => false,
			'query_var'          => true,
			'rewrite'            => true,
		] );
	}


	/**
	 * Cache the region taxonomy
	 *
	 * @throws \Exception
	 * @return array Array of WP_Term objects
	 */
	function cache() {
		$regions = get_terms( [
			'taxonomy' => \Sophos\Region\Taxonomy::NAME,
			'hide_empty' => false,
		]);

		if ( is_wp_error( $regions ) ) {
			throw new \Sophos\Exception\TaxonomyError( $regions->get_error_message() );
		}

		if ( is_array( $regions ) && count( $regions ) > 0 ) {
			// Returns false if the update has failed OR if nothing was changed. Thanks Wordpress.
			$updated = update_option( \Sophos\Region\Taxonomy::OPTION_KEY, $regions, 'yes' );
		}

		return $regions;
	}
}
