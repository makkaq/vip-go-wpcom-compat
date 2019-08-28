<?php

namespace Sophos {

	/**
	 * Regionalised URL manipulation
	 */
	class URL {


		/**
		 * URL
		 *
		 * @var string
		 */
		private $url = null;


		/**
		 * Constructor
		 *
		 * @constructor
		 * @deprecated
		 * @throws \Sophos\Exception\InvalidURL
		 * @param [type] $url [description]
		 */
		public function __construct( $url = null ) {
			try {
				// If no URL is supplied, use the current one
				$valid_url = is_null( $url )
					? filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_CALLBACK, array( 'options' => '\Sophos\URL::validate' ) )
					: self::validate( $url );

				if ( false === $valid_url ) {
					throw new \Sophos\Exception\InvalidURL( $url );
				}
				$this->url = $valid_url;
			} catch ( \Sophos\Exception\InvalidURL $e ) {
				// catch and do something.
			}
		}


		/**
		 * Return the URL as a string
		 *
		 * @return [string] URL
		 */
		public function to_string () {
			return $this->url;
		}


		/**
		 * URL validation for full or partial URLs
		 *
		 * @param  string $url URL
		 * @return string|false Returns url on success, false on failure
		 */
		public static function validate( $url ) {
			return ( filter_var( $url, FILTER_VALIDATE_URL ) !== false || wp_parse_url( $url, PHP_URL_PATH ) )
				? $url
				: false;
		}


		/**
		 * Add or change the region code in a URL
		 *
		 * @deprecated
		 * @param  string $iso Language or locale in a recognisible ISO format
		 * @return string URL
		 */
		public function regionalise( $new_iso = null ) {

			// If no regions have been created, don't regionalise the URL
			if ( ! \Sophos\Utils\is_regionalised() ) {
				return $this->url;
			}

			$new_iso = $new_iso ?: \Sophos\Region::guess();

			if ( \Sophos\Utils\is_region( $new_iso ) ) {
		        $url_parts = wp_parse_url( $this->url );

				$old_path = array_key_exists( 'path', $url_parts ) ? $url_parts['path'] : '/';
				$old_iso  = \Sophos\Language::from_url( $this->url );
				$new_path = ( $old_iso instanceof \Sophos\Language )
						  ? str_replace( $old_iso->format_for_sophos(), $new_iso, $old_path )
						  : '/' . $new_iso . $old_path;

				$scheme = empty( $url_parts['scheme'] ) ? '' : $url_parts['scheme'] . '://';
				$host   = empty( $url_parts['host'] )   ? '' : $url_parts['host'];
				$query  = empty( $url_parts['query'] )  ? '' : '?' . $url_parts['query'];

				return $scheme . $host . $new_path . $query;
			} else {
				// There are some situations, such as a call to home_url during
				// the creation of the admin bar that seem to cause is_region to
				// fail (a call to get_option returns an empty array even though
				// the database appears to have the region values in it) so we
				// return an unregionalised URL rather than throwing an error.
				return $this->url;
			}
		}
	}
}


namespace Sophos\URL {


	/**
	 * Platform agnostic URL to post ID conversion
	 *
	 * @param  string $url URL
	 * @return Post ID or 0 on failure
	 */
	function to_postid( $url ) {
		$url_to_postid = ( function_exists( 'wpcom_vip_url_to_postid' ) )
			   ? 'wpcom_vip_url_to_postid'
			   : 'url to postid';

		return $url_to_postid( $url );
	}


	/**
	 * Regionalise a home URL
	 *
	 * @deprecated
	 * @param  string $url The complete home URL including scheme and path.
	 * @param  string $path Path relative to the home URL. Blank string if no path is specified.
	 * @param  string|null $orig_scheme Scheme to give the home URL context. Accepts 'http', 'https', 'relative', 'rest', or null.
	 * @param  int|null $blog_id Site ID, or null for the current site.
	 * @return string Home URL
	 */
	function regionalize_home_url( $url, $path, $orig_scheme, $blog_id ) {

		// This will guess the correct language root. That's not suitable in all
		// cases (such as sitemap URLs and article URLs in sitemaps), so we'll
		// restrict guesswork to the actual home page.
		if (
			''           === $path ||
			'/'          === $path ||
			'/tag/'      === substr( $path, 0, strlen( '/tag/' ) ) ||
			'/author/'   === substr( $path, 0, strlen( '/author/' ) ) ||
			'/category/' === substr( $path, 0, strlen( '/category/' ) )
		) {
			return \Sophos\URL\regionalize( $url );
		}

		return $url;
	}


	/**
	 * Regionalise a URL
	 *
	 * @param  int|string $url URL or post ID
	 * @param  string $iso Language or locale in a recognisible ISO format
	 * @return string URL
	 */
	function regionalize( $url, $iso = null ) {
		if ( is_numeric( $url ) ) {
			$url = get_permalink( (int) $url );
		}

		$u = new \Sophos\URL( $url );
		return $u->regionalise( $iso );
	}


	/**
	 * Add a language code to permalinks (post, post types & pages)
	 *
	 * @param  string  $permalink The post URL
	 * @param  WP_Post  $post The post object
	 * @param  boolean $structural Does the URL contain structural elements?
	 * @return string
	 */
	function add_language( $permalink, $post, $structural = true ) {

		// If it's a structural URL and it doesn't contain %region% move along...
		if ( $structural && ( false === strpos( $permalink, \Sophos\Region\Taxonomy::TAG ) ) ) {
			return $permalink;
		}

		// If we're previewing a post don't regionalise
		if ( \Sophos\Utils\is_preview( $permalink ) ) {
			return $permalink;
		}

		$iso = null;

		if ( \Sophos\Utils\is_edit_comments_page( $post ) ) {
			$term = \Sophos\Utils\get_post_region( $post );
			$iso  = $term->slug;
		}

		// If we're on the edit page then use the region filter to set the iso
		// code in the link. If the region filter isn't set use the region of
		// the post's author.
		if ( \Sophos\Utils\is_edit_page() ) {
			$term = get_query_var( \Sophos\Region\Taxonomy::NAME, false ) ?: \Sophos\Utils\get_post_region( $post );
			$iso  = ( $term instanceof \WP_Term ) ? $term->slug : $term;
		}

		return \Sophos\URL\regionalize( $permalink, $iso );
	}


	/**
	 * Redirect URLs so they have a regional root
	 */
	function redirect() {

		if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
			return;
		}

		if ( defined( 'REST_API_REQUEST' ) && REST_API_REQUEST ) {
			return;
		}

		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return;
		}

		if ( ! \Sophos\Utils\is_regionalised() ) {
			return;
		}

		if ( is_admin() || is_robots() || \Sophos\Utils\is_comment_page() || \Sophos\Utils\is_ajax() || \Sophos\Utils\is_wp_cli() || \Sophos\Utils\is_login_page() ) {
			return;
		}

		$u = new \Sophos\URL();

		if ( false === $u->to_string() ) {
			return;
		}

		if ( '/sitemap.xml' === $u->to_string()) {
			return;
		}

		if ( \Sophos\Utils\is_preview( $u->to_string() ) ) {
			return;
		}

		// Redirect if the URL does not have a region code already
		if ( \Sophos\Region::has_terms() && ! \Sophos\Language::from_url( $u->to_string() ) instanceof \Sophos\Language ) {
			wp_safe_redirect( $u->regionalise(), 302 );
			exit;
		}
	}


	/**
	 * Get a post's URL with its canonical region
	 *
	 * @param string $redirect Requested URL or post ID
	 * @param string $url New URL (for compatibility with redirect_canonical)
	 * @return string
	 */
	function canonical( $redirect, $url = null ) {
		$post_id = is_numeric( $redirect ) ? $redirect : \Sophos\URL\to_postid( $redirect );
		$term    = \Sophos\Utils\get_post_region( $post_id );

		if ( $term instanceof \WP_Term ) {
			return \Sophos\URL\regionalize( $redirect, $term->slug );
		}

		return $redirect;
	}


	/**
	 * Correctly regionalise URLs in entries created by msm-sitemap plugin
	 *
	 * @param  SimpleXMLElement $el Object representing a sitemap URL element
	 * @return array Array of child elements
	 */
	function msm_sitemap( \SimpleXMLElement $el ) {
		if ( property_exists( $el, 'loc' ) ) {
			$el->loc[ 0 ] = \Sophos\URL\canonical( $el->loc->__toString() );
		}

		return $el;
	}
}
