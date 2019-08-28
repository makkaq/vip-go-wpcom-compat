<?php
/**
 * Changes to Post edit UI
 *
 * @package Sophos
 * @subpackage UI
 */

namespace Sophos\UI\Post;


/**
 * Add regions to the HTML of the sample permalink slug editor.
 *
 * @param  string $return
 * @param  int $post_id
 * @param  string $new_title
 * @param  string $new_slug
 * @param  WP_Post $post
 * @return string
 */
function sample_permalink_html( $return, $post_id, $new_title, $new_slug, $post ) {

	if ( ! \Sophos\Utils\is_regionalised() ) {
		return;
	}

	$links = '';

	// Create a permalink editor without an edit button
	$clone = preg_replace( '|<button[^>]*>.*?</button>|', '', $return );

	// Disable the main permalink and reflect original author's region
	$html = preg_replace_callback( '|<a([^>]+)href="(https?.*?)"([^>]*)>(https?[^<]+)(.*?)</a>|', function ( $m ) use ( $post_id ) {
		$href     = $m[2];
		$slugless = $m[4];
		$slug     = $m[5];
		$term     = \Sophos\Utils\get_post_region( $post_id );

		// If the author has not set a region $iso will be false. We could just
		// test to see if false === $iso but since \Sophos\Language will do its
		// own more thorough check let's just let that happen. If it fails we
		// simply haven't re-regionalised the URL and the default is fine.
		try {
			if ( $term instanceof \WP_Term ) {
				$language = new \Sophos\Language( $term->slug );
				$href     = \Sophos\URL\regionalize( $href, $language->format_for_sophos() );
				$slugless = \Sophos\URL\regionalize( $slugless, $language->format_for_sophos() );
			}
		} catch ( \Sophos\Exception\InvalidLanguageCode $e ) {
			// do nothing, it's OK
		}

		$link = sprintf( '<a href="%s" style="text-decoration: inherit; color: inherit; pointer-events: none;">%s%s</a>', $href, $slugless, $slug );

		// Allow pointer-events in wp_kses
		add_filter( 'safe_style_css', function( $styles ) {
			$styles[] = 'pointer-events';
			return $styles;
		});

		return wp_kses( $link, [
			'a' => [
				'style' => 1,
				'href'  => 1,
			],
			'span' => [
				'id' => 1,
			],
		]);
	}, $return );

	$post_regions = get_the_terms( $post_id, \Sophos\Region\Taxonomy::NAME ) ?: [];

	if ( is_wp_error( $post_regions ) ) {
		return;
	}

	// Create a permalink editor for each region
	foreach ( $post_regions as $term ) {
		$next = $clone;
		$next = str_replace( 'Permalink:', $term->name . ':', $next );
		$next = preg_replace_callback( '|<a[^>]+href="(https?.*?)"[^>]*>(https?.*?)</a>|', function ( $m ) use ( $term ) {
			$href = \Sophos\URL\regionalize( $m[1], $term->slug );
			$text = \Sophos\URL\regionalize( strip_tags( $m[2] ), $term->slug );
			$link = sprintf( '<a href="%s">%s</a>', $href, $text );
			return wp_kses( $link, [
				'a' => [
					'href' => 1,
				],
			]);
		}, $next );
		$links = $links . '<br>' . $next;
	}

	return $html . $links;
}


/**
 * Render a list of region checkboxes
 *
 * @param  [type] $post [description]
 * @return [type]       [description]
 */
function region_select_markup( $post ) {

	if ( ! \Sophos\Utils\is_regionalised() ) {
		return;
	}

	$all_regions  = \Sophos\Region::regions();
	$post_regions = get_the_terms( $post->ID, \Sophos\Region\Taxonomy::NAME ) ?: [];

	if ( is_wp_error( $post_regions ) ) {
		return;
	}

	// Disable checkboxes for regions the user is not a part of
	$user_restricted = \Sophos\User\has_edit_restrictions();
	$user_region     = \Sophos\User\get_meta( wp_get_current_user()->ID, \Sophos\Language::USER_META_KEY );

	?>
		<input type="hidden" name="<?php echo esc_attr( \Sophos\Region\Taxonomy::NONCE_KEY ); ?>" id="<?php echo esc_attr( \Sophos\Region\Taxonomy::NONCE_KEY ); ?>" value="<?php echo esc_attr( wp_create_nonce( \Sophos\Region\Taxonomy::NONCE_VALUE ) ); ?>">
		<ul class="categorychecklist">
		<?php foreach ( $all_regions as $index => $region ) : ?>
			<?php
				$id       = sprintf( 'post_region-%d', $index );
				$assigned = array_filter( $post_regions, function ( $t ) use ( $region ) {
					return $t->slug === $region->slug;
				});
				$checked           = ( is_array( $assigned ) && count( $assigned ) > 0 ) ? 'checked' : '';
				$restricted_click  = ( $user_restricted && $region->slug !== $user_region ) ? 'pointer-events:none;' : '';
				$restricted_cursor = ( $user_restricted && $region->slug !== $user_region ) ? 'cursor:not-allowed;' : '';
			?>
			<li style="<?php echo esc_attr( $restricted_cursor ); ?>">
				<label for="<?php echo esc_attr( $id ); ?>" style="<?php echo esc_attr( $restricted_click ); ?>">
					<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="post_region[]" value="<?php echo esc_attr( $region->slug ); ?>" <?php echo esc_attr( $checked ); ?>> <?php echo esc_attr( trim( $region->name ) ); ?>
				</label>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php
}


/**
 * Save regions
 *
 * @param  int $post_id
 */
function save_region_selection( $post_id ) {

	$nonce = filter_input( INPUT_POST, \Sophos\Region\Taxonomy::NONCE_KEY, FILTER_CALLBACK, [
		'options' => function ( $value ) {
			return wp_verify_nonce( $value, \Sophos\Region\Taxonomy::NONCE_VALUE );
		},
	]);

	// Verify the nonce
	if ( false === $nonce ) {
		return;
	}

	// Check for auto save
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	$post = get_post( $post_id );

	// Check this is a post type that can have a region
	if ( ! in_array( $post->post_type, \Sophos\Region\Taxonomy::POST_TYPES, true ) ) {
		return;
	}

	// Check user can edit this post type
	if ( ! current_user_can( sprintf( 'edit_%s', $post->post_type ), $post_id ) ) {
		return;
	}

	// Check for bulk edit.
	if ( isset( $_REQUEST['bulk_edit'] ) ) {
		return;
	}

	// Validate that we have known language IDs, filter out invalid ones
	$regions = filter_input( INPUT_POST, 'post_region', FILTER_CALLBACK, [
		'options' => function ( $value ) {
			return \Sophos\Utils\is_region( $value ) ? $value : false;
		},
	]);

	// If an error occurred or a user forgot to set a region, use their region
	if ( empty( $regions ) ) {
		$default = \Sophos\User\get_meta( get_current_user_id(), \Sophos\Language::USER_META_KEY );

		if ( false === $default ) {
			return;
		}
	}

	// If we didn't get a region code in the POST data use the default
	wp_set_object_terms( $post_id, $regions ?: $default, \Sophos\Region\Taxonomy::NAME, false );
}


/**
 * Add single region select box to post edit sidebar
 */
function add_region_select() {
	if ( ! is_admin() ) {
		return;
	}

	if ( ! \Sophos\Utils\is_regionalised() ) {
		return;
	}

	add_meta_box( 'regiondiv', __( 'Region', 'sophos-news' ), '\Sophos\UI\Post\region_select_markup', \Sophos\Region\Taxonomy::POST_TYPES, 'side', 'high' );
}

/**
 * Disable all fields except for the editable taxonomy from being updated.
 *
 * We assume the author's region is the primary region and only users in the same region as the author should be able to edit the entire post.
 */
function allow_adding_regions_to_a_post() {

	if ( ! is_admin() ) {
		return;
	}

	if ( ! \Sophos\Utils\is_regionalised() ) {
		return;
	}

	$post_id = filter_input( INPUT_POST, 'post_ID', FILTER_VALIDATE_INT );
	$action  = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

	// Don't proceed if we've somehow got a non-numeric ID
	if ( ! is_int( $post_id ) ) {
		return;
	}

	// If post type is unregionalised restrictions don't apply
	if ( ! in_array( get_post_type( $post_id ), \Sophos\Region\Taxonomy::POST_TYPES, true ) ) {
		return;
	}

	if ( ! in_array( $action, [ 'editpost', 'inline-save' ], true ) ) {
		return;
	}

	// If user is Admin-like or Editor-like so no restrictions apply
	if ( ! \Sophos\User\has_edit_restrictions( $post_id ) ) {
		return;
	}

	$author_iso = \Sophos\User\get_meta( get_current_user_id(), \Sophos\Language::USER_META_KEY );

	// If the restricted user's region is in the list of regions we can add
	// it. Anything else is removed (array_filter removes false entries).
	$isos = array_filter( filter_input( INPUT_POST, sprintf( 'post_%s', \Sophos\Region\Taxonomy::NAME ), FILTER_CALLBACK, [
		'options' => function ( $iso ) use ( $author_iso ) {
			return ( $author_iso === $iso ) ? $author_iso : false;
		},
	]));

	$tax = get_taxonomy( \Sophos\Region\Taxonomy::NAME );

	if ( current_user_can( $tax->cap->assign_terms ) ) {
		// Restricted user can add or remove their own region only
		$terms = ( count( $isos ) === 1 )
		       ? wp_add_object_terms( $post_id, $author_iso, \Sophos\Region\Taxonomy::NAME )
			   : wp_remove_object_terms( $post_id, $author_iso, \Sophos\Region\Taxonomy::NAME );

		if ( is_wp_error( $terms ) ) {
			wp_die( esc_html( $terms->get_error_message() ) );
		}
	}

	// Remove all fields from $_POST except for the editable taxonomy when editing inline on the post list screen.
	if ( 'inline-save' === $action ) {

		// This conditional statement is left as a placeholder. So long as
		// show_in_quick_edit is false for the region taxonomy we don't
		// need to worry about restricting access to editing here. It's set
		// to false because of a Wordpress core bug but there's no rush to
		// reinstate Quick Edit for this taxonomy. If we ever do turn on
		// show_in_quick_edit we'll need code here to clear everything apart
		// from the region taxonomy from the POST array. For code see the
		// feature-user-post-editing-role branch for starter code.

		// Return for WP to continue processing this request and return the new post listing via ajax.
		return;
	}

	// Redirect back to the post edit screen so we short-circuit the request.
	wp_safe_redirect( get_edit_post_link( $post_id, 'url' ) );
	exit;
}


/**
 * Disable autosave on posts outside your region
 *
 * If a user's region does not match a post author's region disable autosave. We
 * assume the author's region is the primary region and only users in the same
 * region as the author should be able to edit the entire post.
 */
function disable_autosave_when_not_users_region() {

	if ( ! is_admin() ) {
		return;
	}

	if ( ! \Sophos\Utils\is_regionalised() ) {
		return;
	}

	$screen = get_current_screen();

	if ( ! in_array( $screen->post_type, \Sophos\Region\Taxonomy::POST_TYPES, true ) ) {
		return;
	}

	if ( 'post' !== $screen->base ) {
		return;
	}

	if ( \Sophos\User\has_edit_restrictions() ) {
		wp_deregister_script( 'autosave' );
	}
}


/**
 * If a language has been set and it's not English, disable email notification
 *
 * @param  int     $post_id Post ID
 * @param  WP_Post $post    Post object
 * @return bool
 */
function disable_subscriber_email( int $post_id, \WP_Post $post ) {

	if ( ! \Sophos\Utils\is_regionalised() ) {
		return;
	}

	// Check this is a post type that can have a region
	if ( ! in_array( $post->post_type, \Sophos\Region\Taxonomy::POST_TYPES, true ) ) {
		return;
	}

	// Check user can publish this post type
	if ( ! current_user_can( sprintf( 'publish_%s', $post->post_type ), $post->ID ) ) {
		return;
	}

	$nonce = filter_input( INPUT_POST, \Sophos\Region\Taxonomy::NONCE_KEY, FILTER_CALLBACK, [
		'options' => function ( $value ) {
			return wp_verify_nonce( $value, \Sophos\Region\Taxonomy::NONCE_VALUE );
		},
	]);

	// Verify the nonce
	if ( false === $nonce ) {
		return;
	}

	// If the post has been saved before publishing and a region value was set
	// then it can be guessed at by get_post_region. If it hasn't then we have
	// to fetch a list of region values from the POST data.
	$term = \Sophos\Utils\get_post_region( $post_id );
	$isos = ( $term instanceof \WP_Term )
	      ? [ $term->slug ] // Post has previously been saved
		  : array_filter(   // Post has never been saved
			  	filter_input( INPUT_POST, sprintf( 'post_%s', \Sophos\Region\Taxonomy::NAME ), FILTER_CALLBACK,
			  		[
						'options' => function ( $iso ) {
							try {
								$term = \Sophos\Region::from_slug( $iso );
								return $term->slug;
							} catch ( \Exception $e ) {
								return false;
							}
						},
					]
				)
				?: [] // If filter_input returns nothing give array_filter something.
			);

	// If we have one or more language codes, and English isn't one of them, try
	// to stop the default wordpress.com email notification.
	if ( count( $isos ) > 0 && ! in_array( \Sophos\Language::DEFAULT_LANGUAGE, $isos, true ) ) {
		update_post_meta( $post->ID, 'email_notification', time() );
	}
}
