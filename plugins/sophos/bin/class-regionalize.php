<?php

namespace Sophos\CLI;


/**
 * Sophos Regionalisation commands
 *
 * This class contains commands for regionalising a Wordpress blog and
 * manipulating a site that has already been regionalised.
 *
 * ## How to upgrade blogs.sophos.com
 *
 * ### 1. Activate the theme
 *
 * If the theme is activated but no region hierarchy has been created the theme
 * will not add regional prefixes to URLs and content creators will not be able
 * to add regions to new posts or user profiles. To activate the theme on the
 * command line execute the following command:
 *
 * `$ wp theme install vip/sophosnews-2017 --activate --url=sophos.wordpress.com`
 *
 * On the VIP platform activating the theme will flush the rewrite rules. Until
 * existing content is allocated to a region the site will continue to use
 * standard, un-regionalized URLs but menus will be missing. The site should be
 * in this "activated but unregionalised" state for as short a time as possible.
 *
 * ### 2. Regionalize the site and allocate existing content to en-us
 *
 * For the theme to operate correctly it needs the site to be regionalized:
 *
 * * A region taxonomy is created
 * * Existing content is allocated to a region
 * * Regionalised menus are created
 *
 * Since everything in the blogs.sophos.com site is in English we will allocate
 * existing content to the `en-us` region.
 *
 * `$ wp sophos regionalize --language=en-us --url=sophos.wordpress.com`
 *
 * This will create English menus, URLs should now have language code prefixes
 * and any old URLs should be redirected to a prefixed version of the same URL.
 *
 * Note that the regionalize command creates the region taxonomy and will not
 * run if a region taxonomy already exists. If something goes wrong and it needs
 * to be run twice you'll need to delete the region taxonomy.
 *
 * ### 3. Add non-Engish content and assign it to a region
 *
 * Now that the site has been regionalised it can accept non-English content. To
 * add content from ONE other region import it as you would any other content.
 * The new content will not appear on the site until it has been allocated to a
 * region.
 *
 * Use the allocate command to add any posts and pages that don't have a region
 * to one. The resgion is specified by its ISO code using the `--language`
 * parameter.
 *
 * Because the allocate command will regionalise any post and page that isn't
 * assigned a term from the region taxonomy you MUST add one language at a time
 * using`import` and then `allocate`.
 *
 * For each xml file to be imported run:
 *
 * `$ wp import <file> --authors=create --url=sophos.wordpress.com`
 * `$ wp sophos allocate --language="<language>" --url=sophos.wordpress.com`
 *
 */
class Regionalize extends \WPCOM_VIP_CLI_Command {


	/**
	 * Regionalize command for people in the UK ;)
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
		 * : Perform a trial run without changing any data.
	 *
	 * --language=<iso>
	 * : Allocate region-less posts to this region.
	 *
	 * ## EXAMPLES
	 *
	 *     wp sophos regionalise --language=en-us --url=example.org
	 *     wp sophos regionalise --language=en-us --url=example.org --dry-run
	 *
	 * @when init
	 */
	function regionalise( $args, $assoc_args ) {
		return $this->regionalize( $args, $assoc_args );
	}


	/**
	 * Regionalize a site
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
	 * : Perform a trial run without changing any data.
	 *
	 * --language=<iso>
	 * : Allocate region-less posts to this region
	 *
	 * ## EXAMPLES
	 *
	 *     wp sophos regionalize --language=en-us --url=example.org
	 *     wp sophos regionalize --language=en-us --url=example.org --dry-run
	 *
	 * @when init
	 */
	function regionalize( $args, $assoc_args ) {

		if ( \Sophos\Utils\is_regionalised() ) {
			\WP_CLI::error( 'This site has already been regionalized. Use other commands to update your site' );
		}

		// Create the region taxonomy
		$this->regions( $args, $assoc_args );

		// Allocate posts to regions
		$this->allocate( $args, $assoc_args );

		// Add regional menus
		$this->menus( $args, $assoc_args );
	}


	/**
	 * Adds a region taxonomy
	 *
	 * Takes data from \Sophos\Region\Taxonomy and turns it into a region taxonomy
	 *
	 * ## OPTIONS
	 *
	 * [--language=<iso>]
	 * : Create menus for this region.
	 *
	 * [--dry-run]
	 * : Perform a trial run without changing any data.
	 *
	 * [--force]
	 * : Delete any region taxonomy terms if it exists.
	 *
	 * ## EXAMPLES
	 *
	 *     wp sophos regions --url=example.org
	 *     wp sophos regions --url=example.org --language=en-us
	 *     wp sophos regions --url=example.org --language=en-us --force
	 *     wp sophos regions --url=example.org --language=en-us --force --dry-run
	 *
	 * @when init
	 */
	function regions( $args, $assoc_args ) {

		$live     = empty( $assoc_args['dry-run'] );
		$taxonomy = new \Sophos\Region\Taxonomy( $live );
		\WP_CLI::log( sprintf( 'Conducting a %s run of regions command', $live ? 'live' : 'dry' ) );

		$deleted  = ( array_key_exists( 'force', $assoc_args ) )
				  ? $deleted = $taxonomy->remove_terms()
				  : 0;
		$language = array_key_exists( 'language', $assoc_args )
				  ? new \Sophos\Language( $assoc_args['language'] )
				  : false;
		$added    = $taxonomy->create_terms( $language );

		\WP_CLI::log( sprintf( 'Success: %d terms deleted, %d terms created', $deleted, $added ) );

		// Attempt to cache the regions so that they're available to functions triggered
		// by hooks like locale that run before the taxonomy is registered
		try {
			$regions = \Sophos\Region\Taxonomy\cache();

			if ( $live && (count( $regions ) < $added ) ) {
				\WP_CLI::error( sprintf( 'count of cached terms (%d) is less than the number of terms created (%d)', count( $regions ), $added ) );
			}
		} catch ( \Exception $e ) {
			\WP_CLI::error( $e->getMessage() );
		}
	}


	/**
	 * Allocate posts to regions
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
		 * : Perform a trial run without changing any data.
	 *
	 * --language=<iso>
	 * : Allocate region-less posts to this region.
	 *
	 * ## EXAMPLES
	 *
	 *     wp sophos allocate --language="en-us" --url=example.org
	 *     wp sophos allocate --language="en-us" --url=example.org --dry-run
	 *
	 * @when init
	 */
	function allocate( $args, $assoc_args ) {

		$live = empty( $assoc_args['dry-run'] );

		if ( $live && ! count( \Sophos\Region::regions() ) ) {
			\WP_CLI::error( 'No region taxonomy found, try running the regions command first' );
		}

		\WP_CLI::log( sprintf( 'Conducting a %s run of the allocate command', $live ? 'live' : 'dry' ) );

		if ( taxonomy_exists( \Sophos\Region\Taxonomy::NAME ) ) {
			if ( array_key_exists( 'language', $assoc_args ) ) {
				try {
					$language = new \Sophos\Language( $assoc_args['language'] );

					// If we're doing a dry-run the region taxonomy won't exist
					// and \Sophos\Region::from_language will throw an exception
					// so we skip it.
					$term = ( $live )
						  ? \Sophos\Region::from_language( $language )
						  : false;

					$iso = ( ! empty( $term ) && $term instanceof \WP_Term )
						 ? $term->slug
						 : $language->format_for_sophos();

					\WP_CLI::log( sprintf( 'Allocating posts without a region to %s', $iso ) );
				} catch ( \Exception $e ) {
					\WP_CLI::error( $e->getMessage() );
				}
			} else {
				\WP_CLI::error( 'The --langage parameter is required' );
			}

			$this->start_bulk_operation();

			$added  = 0;
			$count  = 0;
			$offset = 0;
			$limit  = 100;
			$args   = [
				'post_type'      => \Sophos\Region\Taxonomy::POST_TYPES,
				'post_status'    => 'any',
				'posts_per_page' => $limit,
				'offset'         => $offset,
			];
			$query = new \WP_Query( $args );

			while ( $query->have_posts() ) {

				$query->the_post();
			    $terms   = get_the_terms( get_the_ID(), \Sophos\Region\Taxonomy::NAME );
				$post_id = get_the_ID();
			    $count++;

				if ( false === $terms ) {
					if ( $live ) {
						$term = wp_set_object_terms( $post_id, $iso, \Sophos\Region\Taxonomy::NAME, true );

						if ( is_wp_error( $term ) ) {
							\WP_CLI::error( $terms->get_error_message() );
						}

						if ( is_string( $term ) ) {
							\WP_CLI::error( sprintf( 'Cannot add incorrectly named term %s', $term ) );
						}
					}

					\WP_CLI::log( sprintf( 'Allocated post %d to region %s', $post_id, $iso ) );
					$added++;
				} elseif ( is_array( $terms ) ) {
					\WP_CLI::log( sprintf( 'Skipping post %d - it already has terms from %s', $post_id, \Sophos\Region\Taxonomy::NAME ) );
				} elseif ( is_wp_error( $terms ) ) {
					\WP_CLI::error( $terms->get_error_message() );
				} else {
					\WP_CLI::error( 'An unknown error occurred' );
				}

				if ( ! $query->have_posts() ) {
					\WP_CLI::log( "Pausing after processing $count posts" );
					$this->stop_the_insanity(); // clear memory
					sleep( 2 );

					$args['offset'] = $args['offset'] + $limit;
					$query = new \WP_Query( $args );

					if ( $query->have_posts() ) {
						wp_reset_postdata();
						continue;
					}
				}// End if().
			}// End while().

			$this->end_bulk_operation();

		} else {
			\WP_CLI::error( sprintf( 'The %s taxonomy does not exist', \Sophos\Region\Taxonomy::NAME ) );
		}// End if().

		\WP_CLI::log( sprintf( 'Success: %d posts allocated to %s', $added, $assoc_args['language'] ) );
	}


	/**
	 * Adds regional menus
	 *
	 * If a language parameter is supplied then only the menus for that region
	 * will be created. Without it all menus for all regions are created.
	 *
	 * To ensure that menus are created with the correct translations the code
	 * will attempt to load a .mo file for each language.
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
	 * : Perform a trial run without changing any data.
	 *
	 * [--language=<iso>]
	 * : Create menus for this region.
	 *
	 * ## EXAMPLES
	 *
	 *     wp sophos menus --url=example.org
	 *     wp sophos menus --url=example.org --dry-run
	 *     wp sophos menus --language=en-us --url=example.org
	 *     wp sophos menus --language=en-us --url=example.org --dry-run
	 *
	 * @when init
	 */
	function menus( $args, $assoc_args ) {

		$live = empty( $assoc_args['dry-run'] );

		if ( $live && ! count( \Sophos\Region::regions() ) ) {
			\WP_CLI::error( 'No region taxonomy found, try running the regions command first' );
		}

		\WP_CLI::log( sprintf( 'Conducting a %s run of the menus command', $live ? 'live' : 'dry' ) );

		try {
			if ( empty( $assoc_args['language'] ) ) {
				// Do all regions

				if ( $live ) {
					$regions = array_map( function ( $a ) {
						return $a->slug;
					}, \Sophos\Region::regions() );
				} else {
					// On a dry-run the region taxonomy hasn't been created so
					// we have to fetch the raw data and use that.
					$taxonomy = new \Sophos\Region\Taxonomy( $live );
					$regions  = [];
					foreach ( $taxonomy->raw() as $key => $val ) {
						$regions[] = $val['slug'];
					}
				}
			} else {
				// Do one region
				// =============
				// If we're doing a dry-run the region taxonomy won't exist
				// and \Sophos\Region::from_language will throw an exception
				// so we skip it and use the language data itself for dry runs.
				// The reason that we use \Sophos\Region on live is that it will
				// ONLY work with language codes that represent a top-level item
				// in the region taxonomy where as \Sophos\Language will work
				// with any valid ISO code so it isn't as robust to errors.
				$language = new \Sophos\Language( $assoc_args['language'] );

				if ( $live ) {
					$term    = \Sophos\Region::from_language( $language );
					$regions = [ $term->slug ];
				} else {
					$regions = [ $language->format_for_sophos() ];
				}
			}// End if().
		} catch ( \Exception $e ) {
			\WP_CLI::error( $e->getMessage() );
		}// End try().

		$menus = 0;
		$items = 0;
		$exist = 0;

		foreach ( $regions as $region ) :

			$language = new \Sophos\Language( $region );

			if ( $language->format_for_wordpress() !== get_locale() ) {

				$mo = sprintf( '%s/languages/%s.mo', get_stylesheet_directory(), $language->format_for_wordpress() );

				if ( load_textdomain( 'sophos-news', $mo ) === false ) {
					\WP_CLI::log( sprintf( 'Skipping %s, unable to load textdomain from %s', $region, $mo ) );
					continue;
				} else {
					\WP_CLI::log( sprintf( 'Loaded translations for %s', $region ) );
				}
			}

			foreach ( \Sophos\Region\Menu\slugs() as $location ) :

				$data = \Sophos\Region\Menu::instance( $region, $location );

				if ( false === $data ) {
					\WP_CLI::log( sprintf( 'Could not load menu data class for %s in %s, skipping', $region, $location ) );
					continue;
				}

				if ( $live ) {
					try {
						$count = $data->into_menu();

						if ( false === $count ) {
							$exist++;
						} else {
							$menus++;
							$items = $items + $count;
						}
					} catch ( \Exception $e ) {
						\WP_CLI::error( $e->getMessage() );
					}
				}

				\WP_CLI::log( sprintf( 'Created menu %s', $data->slug ) );

			endforeach;
		endforeach;

		if ( $live ) {
			\WP_CLI::log( sprintf( 'Success: %d new menus were created with %d items. %d menus already existed', $menus, $items, $exist ) );
		} else {
			\WP_CLI::log( 'Success: your dry-run completed successfully' );
		}
	}


	private function attributes_to_array( $element ) {
		preg_match_all( '/([^\s]+="[^"]*")/', $element, $pairs, PREG_PATTERN_ORDER );

		$attr = [];

		foreach ( $pairs[1] as $pair ) {
			$kv = explode( '=', $pair );
			$attr[ $kv[0] ] = trim( $kv[1], '"' );
		}

		return $attr;
	}


	/**
	 * Is a domain on our allow list for images?
	 *
	 * @param string $domain Hostname
	 * @return boolean
	 */
	private function domain_on_allow_list( $domain ) {
		return in_array( $domain, [
			'blog.sophos.be',
			'sophosbenelux.com',
			'www.sophosbenelux.com',
			'sophosbenelux.be',
			'www.sophosbenelux.be',
			'blog.sophos.fr',
			'sophosfranceblog.fr',
			'www.sophosfranceblog.fr',
			'sophositalia.it',
			'www.sophositalia.it',
			'sophositalia.com',
			'www.sophositalia.com',
			'blog.sophos.de',
			'www.sophosblog.de',
			'sophosblog.de',
			'www.sophosiberia.es',
			'sophosiberia.es',
		], true );
	}


	/**
	 * Take an image tag, sideload non-local images and return the new tag
	 *
	 * @param  array Array of arguments passed to CLI script
	 * @param  array $matches Array of regex matches
	 * @return string HTML image element
	 */
	private function download_image( $assoc_args, $matches ) {

		$live = empty( $assoc_args['dry-run'] );
		$img  = $matches[0];
		$attr = $this->attributes_to_array( $img );

		if ( array_key_exists( 'src', $attr ) ) {
			$src    = trim( $attr['src'], '\'' );
			$local  = wp_parse_url( get_site_url(), PHP_URL_HOST );
			$remote = wp_parse_url( $src, PHP_URL_HOST );

			if ( $this->domain_on_allow_list( $remote ) ) {

				\WP_CLI::log( sprintf( '%d: Found a reference to an image.', $assoc_args['post']->ID ) );

				if ( preg_match( '/\.(jpg|png|gif)$/', $src ) ) {
					\WP_CLI::log( sprintf( '%d: Downloading: %s', $assoc_args['post']->ID, $src ) );

					if ( $live ) {

						$url = media_sideload_image( $src, null, null, 'src' );

						if ( is_wp_error( $url ) ) {
							\WP_CLI::error( $url->get_error_message() );
						}

						$attr['src'] = $url;

						return sprintf( '<img %s />', implode( ' ', array_map( function ( $key, $value ) {
							return sprintf( '%s="%s"', $key, $value );
						}, array_keys( $attr ), $attr ) ) );
					}
				} else {
					\WP_CLI::log( sprintf( '%d: Skipping image %s, not a .jpg, .gif or .png', $assoc_args['post']->ID, $src ) );
				}
			} else {
				\WP_CLI::log( sprintf( '%d: Skipping image from %s it\'s not in the allow list of domains', $assoc_args['post']->ID, $remote ) );
			}
		}// End if().

		return $img;
	}


	/**
	 * Download remote images
	 *
	 * This command checks posts and pages for images on remote hosts. If it
	 * finds any they are downloaded to the local Media Library and the URL in
	 * the article is updated.
	 *
	 * If a language parameter is supplied then only the images for that region
	 * will be checked and downloaded. Without it all posts are checked.
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
	 * : Perform a trial run without changing any data.
	 *
	 * [--language=<iso>]
	 * : Create menus for this region.
	 *
	 * ## EXAMPLES
	 *
	 *     wp sophos images --url=example.org
	 *     wp sophos images --language=en-us --url=example.org
	 *     wp sophos images --language=en-us --url=example.org --dry-run
	 *
	 * @when init
	 */
	function images( $args, $assoc_args ) {

		// Make sure hooks like email subscriptions don't fire
		// See https://wordpressvip.zendesk.com/hc/en-us/requests/65749
		define( 'WP_IMPORTING', true );

		$live = empty( $assoc_args['dry-run'] );

		\WP_CLI::log( sprintf( 'Conducting a %s run of the images command', $live ? 'live' : 'dry' ) );

		$language = array_key_exists( 'language', $assoc_args )
				  ? new \Sophos\Language( $assoc_args['language'] )
				  : false;

		global $post;

		$count  = 0;
		$offset = 0;
		$limit  = 100;
		$args   = [
			'post_type'      => \Sophos\Region\Taxonomy::POST_TYPES,
			'post_status'    => 'any',
			'posts_per_page' => $limit,
			'offset'         => $offset,
		];

		if ( $language instanceof \Sophos\Language ) {
			$args['tax_query'] = [
				[
					'taxonomy' => 'region',
					'field'    => 'slug',
					'terms'    => $language->format_for_sophos(),
				],
			];
		}

		$query = new \WP_Query( $args );

		$this->start_bulk_operation();

		while ( $query->have_posts() ) {
			$query->the_post();
			$count++;
			$state   = array_merge( [
				'changed' => false,
				'post'    => $post,
			], $assoc_args );
			$content = preg_replace_callback(
				'/<img([^>]+)>/', function ( $matches ) use ( &$state ) {
					$element = $this->download_image( $state, $matches );

					if ( $element === $matches[0] ) {
						return $matches[0];
					} else {
						$state['changed'] = true;
						return $element;
					}
				},
				$post->post_content
			);

			$content = preg_replace_callback(
				'|<a([^>]+)><img([^>]+)></a>|', function ( $matches ) use ( &$state ) {
					$unchanged = $matches[0];

					$a = (object) [
						'string' => $matches[1],
						'attr'   => $this->attributes_to_array( $matches[1] ),
					];

					$a_path  = explode( '/', wp_parse_url( $a->attr['href'], PHP_URL_PATH ) );
					$a->file = array_pop( $a_path );

					$img = (object) [
						'string' => $matches[2],
						'attr'   => $this->attributes_to_array( $matches[2] ),
					];

					$img_path  = explode( '/', wp_parse_url( $img->attr['src'], PHP_URL_PATH ) );
					$img->file = array_pop( $img_path );

					// Does the anchor link to an image?
					if ( array_key_exists( 'href', $a->attr ) && preg_match( '/\.(jpg|png|gif)$/', $a->attr['href'] ) ) {

						$domain = wp_parse_url( $a->attr['href'], PHP_URL_HOST );

						if ( $this->domain_on_allow_list( $domain ) ) {

							// Remove numeric prefix e.g. foo-2.jpg
							$shorn = preg_replace( '/-[0-9]+\.(jpg|gif|png)$/', '.${1}', $img->file );

							// Remove size information e.g. foo-200x100.jpg
							$shorn = preg_replace( '/\-[0-9]+x[0-9]+\.(jpg|gif|png)$/', '.${1}', $shorn );

							// Paths may have changed because the image may have been downloaded
							// to a new location moments ago so compare the filenames of the img
							// src and a href with any size information shorn away.
							if ( $shorn === $a->file ) {

								\WP_CLI::log( sprintf( '%d: Found a link to a full size version of an image we know.', $state['post']->ID ) );

								if ( empty( $state['dry-run'] ) ) {

									\WP_CLI::log( sprintf( '%d: Downloading: %s', $state['post']->ID, $a->attr['href'] ) );
									$url = media_sideload_image( $a->attr['href'], null, null, 'src' );

									if ( is_wp_error( $url ) ) {
										\WP_CLI::error( $url->get_error_message() );
									}

									$state['changed'] = true;
									$changed = str_replace( sprintf( 'href="%s"', $a->attr['href'] ), "href=\"$url\"", $unchanged );

									return str_replace( sprintf( 'href="%s"', $a->attr['href'] ), "href=\"$url\"", $unchanged );
								}
							} else {

								\WP_CLI::log( sprintf( '%d: A manual inspection of a link is required - href %s does not match img src %s', $state['post']->ID, $state['post']->post_title, $a->attr['href'], $img->attr['src'] ) );
							}
						}
					}// End if().

					return $unchanged;
				},
				$content
			);

			if ( true === $state['changed'] ) {
				\WP_CLI::log( sprintf( '%d: Saving changes.', $post->ID ) );

				if ( $live ) {
					$save = wp_update_post( [
						'ID'           => $post->ID,
						'post_content' => trim( $content ),
					]);

					if ( is_wp_error( $save ) ) {
						\WP_CLI::error( $save->get_error_message() );
					}
				}
			}

			if ( ! $query->have_posts() ) {
				\WP_CLI::log( "Pausing after processing $count posts" );
				$this->stop_the_insanity(); // clear memory
				sleep( 2 );

				$args['offset'] = $args['offset'] + $limit;
				$query = new \WP_Query( $args );

				if ( $query->have_posts() ) {
					wp_reset_postdata();
					continue;
				}
			}// End if().
		}// End while().

		$this->end_bulk_operation();

		\WP_CLI::log( sprintf( 'Success: %d posts checked for remote images', $count ) );
	}


	/**
	 * Fix Aweber javascript
	 *
	 * This command checks posts and pages for aweber javascript and replaces it
	 * with a shortcode.
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
	 * : Perform a trial run without changing any data.
	 *
	 * [--language=<iso>]
	 * : Create menus for this region.
	 *
	 * ## EXAMPLES
	 *
	 *     wp sophos aweber --url=example.org
	 *     wp sophos aweber --language=en-us --url=example.org
	 *     wp sophos aweber --language=en-us --url=example.org --dry-run
	 *
	 * @when init
	 */
	function aweber( $args, $assoc_args ) {

		// Make sure hooks like email subscriptions don't fire
		// See https://wordpressvip.zendesk.com/hc/en-us/requests/65749
		define( 'WP_IMPORTING', true );

		$live = empty( $assoc_args['dry-run'] );

		\WP_CLI::log( sprintf( 'Conducting a %s run of the aweber command', $live ? 'live' : 'dry' ) );

		$language = array_key_exists( 'language', $assoc_args )
				  ? new \Sophos\Language( $assoc_args['language'] )
				  : false;

		global $post;

		$count   = 0;
		$changed = 0;
		$offset  = 0;
		$limit   = 100;
		$args    = [
			'post_type'      => \Sophos\Region\Taxonomy::POST_TYPES,
			'post_status'    => 'any',
			'posts_per_page' => $limit,
			'offset'         => $offset,
		];

		if ( $language instanceof \Sophos\Language ) {
			$args['tax_query'] = [
				[
					'taxonomy' => 'region',
					'field'    => 'slug',
					'terms'    => $language->format_for_sophos(),
				],
			];
		}

		$query = new \WP_Query( $args );

		$this->start_bulk_operation();

		while ( $query->have_posts() ) {
			$query->the_post();
			$count++;
			$state   = array_merge( [
				'changed' => false,
				'post'    => $post,
			], $assoc_args );
			$content = preg_replace_callback(
				'{(?:&nbsp;|<p>)?\s*<a href="https?://forms\.aweber\.com/form/10/(\d+)\.js">https?://forms\.aweber\.com/form/10/\d+\.js</a>\s*(?:&nbsp;|</p>|....)?}s', function ( $matches ) use ( &$state ) {

					$post_id   = $state['post']->ID;
					$aweber_id = $matches[1];

					\WP_CLI::log( sprintf( '%d: Found aweber code in post', $post_id ) );

					if ( is_numeric( $aweber_id ) ) {
						$state['changed'] = true;
						$shortcode        = sprintf( '[aweber id=%d]', (int) $aweber_id );

						\WP_CLI::log( sprintf( '%d: Injecting %s', $post_id, $shortcode ) );

						return $shortcode;
					} else {
						return $matches[0];
					}
				},
				$post->post_content
			);

			if ( true === $state['changed'] ) {

				$changed++;

				\WP_CLI::log( sprintf( '%d: Saving changes.', $post->ID ) );

				if ( $live ) {
					$save = wp_update_post( [
						'ID'           => $post->ID,
						'post_content' => trim( $content ),
					]);

					if ( is_wp_error( $save ) ) {
						\WP_CLI::error( $save->get_error_message() );
					}
				}
			}

			if ( ! $query->have_posts() ) {
				\WP_CLI::log( "Pausing after processing $count posts" );
				$this->stop_the_insanity(); // clear memory
				sleep( 2 );

				$args['offset'] = $args['offset'] + $limit;
				$query = new \WP_Query( $args );

				if ( $query->have_posts() ) {
					wp_reset_postdata();
					continue;
				}
			}// End if().
		}// End while().

		$this->end_bulk_operation();

		\WP_CLI::log( sprintf( 'Success: %d posts checked for aweber code, %d changed', $count, $changed ) );
	}
}

\WP_CLI::add_command( 'sophos', '\Sophos\CLI\Regionalize' );
