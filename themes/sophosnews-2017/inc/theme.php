<?php

/**
 * Increment this to manually bust the cache.
 */
define( 'SOPHOS_CACHE_BUSTER', 4 );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'sophos_scripts' ) ) :
	/**
	 * Enqueue scripts and styles.
	 */
	function sophos_scripts() {
		wp_enqueue_style(
			'sophos-style',
			get_stylesheet_uri(),
			[],
			SOPHOS_CACHE_BUSTER
		);

			// Front-end scripts
		if ( ! is_admin() ) {
            wp_enqueue_script(
				'sophos-vendor-js-cookie',
				get_template_directory_uri() . '/js/js-cookie-v2.2.1/js.cookie.js',
				[ 'jquery' ],
				SOPHOS_CACHE_BUSTER,
				true
			);

			// Load theme-specific JavaScript
			wp_enqueue_script(
                'sophos-js-core',
				get_template_directory_uri() . '/js/core.js',
				[ 'jquery' ],
				SOPHOS_CACHE_BUSTER,
				true
			);

			wp_enqueue_script(
				'sophos-js-extras',
				get_template_directory_uri() . '/js/extras.js',
				[ 'jquery' ],
				SOPHOS_CACHE_BUSTER,
				true
			);

            wp_enqueue_script(
				'sophos-js-utils',
				get_template_directory_uri() . '/js/sophos.utils.js',
				[ 'jquery' ],
				SOPHOS_CACHE_BUSTER,
				true
			);

            wp_enqueue_script(
				'sophos-js-campaign',
				get_template_directory_uri() . '/js/sophos.campaign.js',
				[ 'jquery', 'sophos-js-utils', 'sophos-vendor-js-cookie' ],
				SOPHOS_CACHE_BUSTER,
				true
			);

            wp_enqueue_script(
                'sophos-js-partner',
                get_template_directory_uri() . '/js/sophos.partner.js',
                [ 'jquery', 'sophos-js-utils', 'sophos-vendor-js-cookie' ],
                SOPHOS_CACHE_BUSTER,
                true
            );

            wp_enqueue_script(
                'sophos-js-ga',
                get_template_directory_uri() . '/js/sophos.ga.js',
                [ 'jquery', 'sophos-js-utils', 'sophos-vendor-js-cookie' ],
                SOPHOS_CACHE_BUSTER,
                true
            );

			wp_enqueue_script(
				'sophos-js-news',
				get_template_directory_uri() . '/js/sophos.news.js',
				[ 'jquery', 'sophos-js-utils', 'sophos-vendor-js-cookie', 'sophos-js-campaign', 'sophos-js-partner', 'sophos-js-ga' ],
				SOPHOS_CACHE_BUSTER,
				true
			);
		}

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Setup wp-ajax-page-loader
		global $wp_query;

		$max = $wp_query->max_num_pages;
		$paged = ( get_query_var( 'paged' ) > 1 ) ? get_query_var( 'paged' ) : 1;

		$wp_ajax_page_loader_vars = [
			'startPage' => $paged,
			'maxPages' => $max,
			'nextLink' => next_posts( $max, false ),
		];

		wp_localize_script( 'sophos-js-core', 'PG8Data', $wp_ajax_page_loader_vars );

		$iso = \Sophos\Region::guess();
		$rd  = \Sophos\Region\Data::instance( $iso );

		if ( $rd instanceof \Sophos\Region\Data ) {
			$ua = $rd->google_analytics_id();
			if ( preg_match( '/^UA\-\d+\-\d+$/', $ua ) ) {
				wp_localize_script( 'sophos-js-core', '_sophosLocalAnalytics', esc_attr( $ua ) );
			}
		}
	}
endif; // sophos_scripts
add_action( 'wp_enqueue_scripts', 'sophos_scripts' );

/**
 * Add stylesheet to the visual editor.
 */
function sophos_add_editor_styles() {

	add_editor_style( get_stylesheet_uri() );

}
add_action( 'init', 'sophos_add_editor_styles' );

/**
 * Exclude pages from the loop on the external UI
 */
function sophos_exclude_pages_from_loop( $query ) {
	if ( ! is_admin() && $query->is_main_query() && ! $query->is_page() ) {
		$query->set( 'post_type', 'post' );
	}
}
add_action( 'pre_get_posts', 'sophos_exclude_pages_from_loop' );


/**
 * We're using the "sidebar" tag slug to pull featured articles into the sidebar.
 * Hide the tag from displaying elsewhere on the site.
 */
function sophos_exclude_tags( $tags ) {
	// VIP: Fixes PHP warning "array_filter() expects parameter 1 to be array"
	if ( ! empty( $tags ) && is_array( $tags ) ) {
		return array_filter( $tags, function ( $var ) {
			$exclude_values = [ 'sidebar' ];

			return ! in_array( $var, $exclude_values, true );
		} );
	}
}
add_filter( 'get_the_tags', 'sophos_exclude_tags' );

/**
 * Add description field to menu items markup
 */
function sophos_menu_item_description( $item_output, $item, $depth, $args ) {
	if ( \Sophos\Region\Menu\slug( 'primary' ) === $args->menu ) {
		$item_output = str_replace( $args->link_after . '</a>', '<div class="menu-item-description">' . $item->description . '</div>' . $args->link_after . '</a>', $item_output );
	}

	return $item_output;

}
add_filter( 'walker_nav_menu_start_el', 'sophos_menu_item_description', 10, 4 );


function sophos_get_region_tax_query() {
	return [
		'taxonomy' => \Sophos\Region\Taxonomy::NAME,
		'field'    => 'slug',
		'terms'    => \Sophos\Region::guess(),
	];
}

function sophos_get_random_posts( $post_count = 50 ) {
	if ( function_exists( 'vip_get_random_posts' ) ) {
		return vip_get_random_posts( $post_count, 'post', true );
	} else {
		return [];
	}
}

function sophos_get_term_link( $term_id ) {
	$function = ( function_exists( 'wpcom_vip_get_term_link' ) )
			  ? 'wpcom_vip_get_term_link'
			  : 'get_term_link';

	return $function( $term_id );
}

/**
 * Platform agnostic adjacent post getter
 *
 * @param  boolean $in_same_term   Optional. Whether post should be in a same taxonomy term. Note – only the first term will be used from wp_get_object_terms().
 * @param  string  $excluded_terms Optional. The term to exclude.
 * @param  boolean $previous       Optional. Whether to retrieve previous post.
 * @param  string  $taxonomy       Optional. Taxonomy, if $in_same_term is true. Default ’category’.
 * @return null|string|WP_Post     Post object if successful. Null if global $post is not set. Empty string if no corresponding post exists.
 */
function sophos_get_adjacent_post( $in_same_term = false, $excluded_terms = '', $previous = true, $taxonomy = 'category' ) {
	if ( function_exists( 'wpcom_vip_get_adjacent_post' ) ) {
		$function       = 'wpcom_vip_get_adjacent_post';
		$excluded_terms = implode( ',', $excluded_terms );
	} else {
		$function = 'get_adjacent_post';
	}

	return $function( $in_same_term, $excluded_terms, $previous, $taxonomy );
}

/**
 * Set the number of posts to display for archive pages.
 */
function sophos_set_posts_per_page( $query = false ) {

	if ( ! is_a( $query, 'WP_Query' ) || ! $query->is_main_query() || is_admin() ) {
		return;
	}

	if ( is_archive() ) {
		$query->set( 'posts_per_page', 6 ); // FIXME make this a constant
	}

	if ( is_home() ) {
		if ( $query->is_paged() ) {
			$query->set( 'posts_per_page', 8 ); // FIXME make this a constant
		} else {
			$query->set( 'posts_per_page', 9 ); // FIXME make this a constant
		}
	}
}
add_action( 'pre_get_posts', 'sophos_set_posts_per_page', 10, 1 );


/**
 * Translate comment form defaults
 */
add_filter( 'comment_form_defaults', function ( $fields ) {

	$fields['title_reply']		= _x( 'Leave a Reply', 'Text that appears above the comment form', 'sophos-news' );
	// translators: %s is replaced by the last commenter's name
	$fields['title_reply_to']	= _x( 'Leave a Reply to %s', 'Text that appears above the the comment form when you reply to a comment.', 'sophos-news' );
	$fields['label_submit']		= _x( 'Post Comment', 'Text for the comment form\'s submit button', 'sophos-news' );

	return $fields;
});


/**
 * Add Open Graph tags
 */
function sophos_opengraph() {
	if ( function_exists( 'wpcom_vip_enable_opengraph' ) ) {
		wpcom_vip_enable_opengraph();

		add_filter('jetpack_open_graph_tags', function ( $tags ) {

			if ( ! array_key_exists( 'fb:admins', $tags ) ) {
				$tags['fb:admins'] = 28552295016;
			}

			if ( has_post_thumbnail( get_the_ID() ) ) {
				$src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'opengraph' ) ?: array();
				$tags['og:image'] = array_shift( $src );

				if ( substr( $tags['og:image'], 0, strlen( 'https://' ) ) === 'https://' ) {
					$tags['og:image:secure_url'] = $tags['og:image'];
				}
			}

			return $tags;
		});
	}
}
add_action( 'after_setup_theme', 'sophos_opengraph' );
