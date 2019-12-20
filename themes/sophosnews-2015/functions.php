<?php

define( 'SOPHOS_CACHE_BUSTER', 1 );

/**
 * If we're not a VIP environment, complain.
 */
wpcom_vip_load_plugin( 'co-authors-plus' );
wpcom_vip_load_plugin( 'fieldmanager' );
wpcom_vip_load_plugin( 'wpcom-thumbnail-editor' );
wpcom_vip_load_plugin( 'add-meta-tags-mod' );
wpcom_vip_load_plugin( 'msm-sitemap' );

/**
 * Stop swfobject, stop Flash.
 */
add_action( 'wp_print_scripts', function(){
	wp_dequeue_script( 'swfobject' );
	wp_dequeue_script( 'jetpack-facebook-embed' );
});


/**
 * "This function ... can be a common source of frustration for VIP devs". Yup.
 * https://vip.wordpress.com/functions/widont/
 */
remove_filter( 'the_title', 'widont', 10 );


/**
 * Disable automatic link creation
 */
remove_filter( 'comment_text', 'make_clickable', 9 );
remove_filter( 'the_content', 'wpcom_make_content_clickable', 120 );
remove_filter( 'the_excerpt', 'wpcom_make_content_clickable', 120 );


/**
 * Disable emojis
 */
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'embed_head', 'print_emoji_detection_script' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );


/**
 * Sophos Naked Security functions and definitions
 *
 * @package Forward
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 775; /* pixels */
}

/**
 * Get images directory
 */
function sophos_image_asset( $filename = '' ) {
	return get_template_directory_uri() . '/img/' . $filename;
}

/**
 * Helper functions to keep panel markup consistent
 *
 * @param  string $section_class
 * @param  string $content_class
 */
function sophos_panel_open( $section_class = '', $content_class = '' ) {
	$class = '';

	if ( $section_class ) {
		$class .= $section_class;
	}
	?>

	<section class="<?php esc_attr_e( $class ); ?>">
	<div class="container">
	<div class="panel-content <?php esc_attr_e( $content_class ); ?>">

	<?php
}

function sophos_panel_close() {
	?>

	</div> <!-- .panel-container -->
	</div> <!-- .container -->
	</section> <!-- .panel -->

	<?php
}

/**
 * Closes the .site-content div early in the template to allow full width panel
 * usage.
 */
function sophos_is_custom_layout() {

	$page_slugs    = [ ];
	$custom_layout = false;

	// is_archive should only be true on these specific archive pages.
	$is_archive = is_date() || is_year() || is_month() || is_time() || is_category() || is_tag() || is_tax();

	if ( (!empty($page_slugs) && is_page( $page_slugs )) || is_single() || is_front_page() || is_author() || $is_archive || is_single() ) {
		$custom_layout = true;
	}

	return $custom_layout;
}

/**
 * Custom template tags for this child theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Add helper dev functions (temporary).
 */
require get_template_directory() . '/inc/dev-functions.php';

/**
 * Load up our custom post types and Fieldmanager meta box definitions.
 */
require get_template_directory() . '/inc/custom-post-types.php';
require get_template_directory() . '/inc/field-manager.php';

/**
 * AMP customisations.
 */
require get_template_directory() . '/inc/amp.php';

/**
 * Add theme-specific plugins.
 */
require get_template_directory() . '/inc/short-codes/twitter-follow.php';
require get_template_directory() . '/inc/plugins/sophites/sophite.php';
require get_template_directory() . '/inc/plugins/newsletter/newsletter.php';
require get_template_directory() . '/inc/plugins/translate/translate.php';
require get_template_directory() . '/inc/plugins/summary/Controller.php';
require get_template_directory() . '/inc/plugins/ad/ad.php';
require get_template_directory() . '/inc/plugins/comment/moderation.php';
require get_template_directory() . '/inc/plugins/campaign/campaign.php';

if ( ! function_exists( 'sophos_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function sophos_setup() {

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Forward, use a find and replace
		 * to change 'forward-child' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'forward', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			[
				'primary'    => esc_html__( 'Primary Menu', 'nakedsecurity' ),
				'main'       => esc_html__( 'Main Menu', 'nakedsecurity' ),
				'test-one'   => esc_html__( 'Test One', 'nakedsecurity' ),
				'test-two'   => esc_html__( 'Test Two', 'nakedsecurity' ),
			]
		);



		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		]
		);

        // Featured Image thumbnail size for "hero" display
        add_image_size( 'hero-thumbnail', 780, 408, true );

        // Featured Image thumbnail size for "card" display
        add_image_size( 'card-thumbnail', 412, 200, true );

        // Featured Image thumbnail size for sophos_video archive display
        add_image_size( 'video-thumbnail', 150, 150, true );

        // Featured Image thumbnail size for This Week's Best Advice display
        add_image_size( 'this-weeks-best-advice-thumbnail', 225, 225, true );

        // Featured Image thumbnail size for the Video of the Week display
        add_image_size( 'video-of-the-week-thumbnail', 843, 450, true );

        // Banner Full Width size for front-page banner ad
        add_image_size( 'banner-full-width', 1024, 150, true );
        add_image_size( 'banner-card', 330, 400, true );

        // Free Tools thumbnail size
        add_image_size( 'free-tools-thumbnail', 100, 70, false );

		// Add additional wp_nav_menu()'s.
		register_nav_menus(
			[
				'footer-primary'			=> esc_html__( 'Footer: Primary', 'nakedsecurity' ),
				'footer-network-protection' => esc_html__( 'Footer: Network Protection', 'nakedsecurity' ),
				'footer-enduser-protection' => esc_html__( 'Footer: Enduser Protection', 'nakedsecurity' ),
				'footer-server-protection'  => esc_html__( 'Footer: Server Protection', 'nakedsecurity' )
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Search Results Sidebar', 'forward' ),
				'id'            => 'search-sidebar',
				'description'   => '',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h1 class="widget-title">',
				'after_title'   => '</h1>',
			]
		);

		/**
 		 * Create a sidebar we can put ads in to
		 */
    	register_sidebar(
        	[
				'name'          => esc_html__('Inline Advert', 'nakedsecurity'),
        		'id'            => 'sophos-inline-ad',
        		'description'   => esc_html__('Widgets in this area will be shown as inline adverts.', 'nakedsecurity'),
        		'before_widget' => '<aside id="%1$s" class="widget sophos-inline-ad %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<header><h2 class="widgettitle">',
				'after_title'   => '</h2></header>'
			]
		);

		// Don't ever show comment author links
		add_filter( 'get_comment_author_url', function ( $url, $comment_id, \WP_Comment $comment ) {
			return '';
		}, 10, 3);

		// Remove avatar from RSS feed
		add_filter( 'mrss_avatar_user', '__return_false' );

		// Use large images in RSS feed
		add_filter( 'mrss_media', function ($medias) {
    		foreach ( $medias as $key => $media ) {

        		// Skip items without a thumbnail, i.e. Gravatars and such
        		if ( empty( $media['thumbnail'] ) )
            		continue;

       	 		// Make sure we have the required data
        		if ( empty( $media['content'] ) || empty( $media['content']['attr'] ) || empty( $media['content']['attr']['url'] ) )
            		continue;

        		// Replace thumbnail URL with fullsize URL
        		$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
        		$medias[$key]['content']['attr']['url'] = sprintf( '%s?w=170&h=90&crop=1', $image[0] );
    		}

			return $medias;
		}, 15);

		/* Enable Open Graph tags */
		if ( function_exists( 'wpcom_vip_enable_opengraph' )) {
			wpcom_vip_enable_opengraph();

			add_filter('jetpack_open_graph_tags', function ($tags) {

				if ( !array_key_exists('fb:admins', $tags) ) {
					$tags['fb:admins'] = 28552295016;
				}

				if ( has_post_thumbnail(get_the_ID()) ) {
					$src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'opengraph' ) ?: array();
					$tags['og:image'] = array_shift( $src );

					if ( substr( $tags['og:image'], 0, strlen('https://') ) === 'https://' ) {
						$tags['og:image:secure_url'] = $tags['og:image'];
					}
				}

				return $tags;
			});
		}
	}
endif; // sophos_setup
add_action( 'after_setup_theme', 'sophos_setup' );

if ( ! function_exists( 'sophos_widgets_init' ) ) :
	/**
	 * Register widget area.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
	 */
	function sophos_widgets_init() {
		register_sidebar(
			[
				'name'          => esc_html__( 'Sidebar', 'forward' ),
				'id'            => 'sidebar-1',
				'description'   => '',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h1 class="widget-title">',
				'after_title'   => '</h1>',
			]
		);
	}
endif; // sophos_widgets_init
add_action( 'widgets_init', 'sophos_widgets_init' );

/**
 * Enqueue child theme scripts and styles.
 */
function sophos_scripts() {
	global $wp_query;

	// Main stylesheet
	wp_enqueue_style( 'sophos-stylesheet', get_stylesheet_uri(), [], SOPHOS_CACHE_BUSTER );

	// Front-end scripts
	if ( ! is_admin() ) {

        // Load theme-specific JavaScript with versioning based on last modified time; http://www.ericmmartin.com/5-tips-for-using-jquery-with-wordpress/
		wp_enqueue_script( 'child-js-plugins', get_template_directory_uri() . '/js/plugins.js', [ 'jquery' ], SOPHOS_CACHE_BUSTER, true );
		wp_enqueue_script( 'child-js-core', get_template_directory_uri() . '/js/core.js', [ 'child-js-plugins' ], SOPHOS_CACHE_BUSTER, true );

		// Setup javascript variables for the Ajax URL and nonce
		if ( class_exists( '\Sophos\Newsletter' ) ) {
			wp_localize_script(
				'child-js-core', 'Sophos', [
				'ajaxurl' => \Sophos\Newsletter::safeAdminURL( 'admin-ajax.php' ),
				// URL handling ajax requests
				'nonce'   => wp_create_nonce( 'newsletter-nonce' )
			]
			);
		}

		// setup JavaScript variables for wp-ajax-page-loader.js
		$max                      = $wp_query->max_num_pages;
		$paged                    = ( get_query_var( 'paged' ) > 1 ) ? get_query_var( 'paged' ) : 1;
		$wp_ajax_page_loader_vars = [
			'startPage' => $paged,
			'maxPages'  => apply_filters( 'sophos_ajax_loader_max_pages', $max ),
			'nextLink'  => next_posts( $max, false )
		];
		wp_localize_script( 'child-js-plugins', 'PG8Data', $wp_ajax_page_loader_vars );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'sophos_scripts' );

/**
 * Modify the home page main loop
 *
 * @param bool|false $query
 */
function sophos_home_posts( $query = false ) {
	if ( !( is_home() || is_author() || is_archive() ) || !is_a( $query, 'WP_Query' ) || !$query->is_main_query() ) {
		return;
	}

	$ppp = 9;

	if ( is_author() ) {
		$query->set( 'posts_per_page', $ppp );
	} else if ( ( is_home() || is_archive() ) and is_paged() ) {
		// The home page needs nine items for each query after the first. Offset for page 2 is 10, page 3 is 19 etc.
		$query->set( 'posts_per_page', $ppp );
		$query->set( 'offset', ( $ppp + 1 ) + ( abs( get_query_var( 'paged', 0 ) - 2 ) * $ppp ) );
	} else {
		// The home page has 10 items (because it has a hero)
		$query->set( 'posts_per_page', 10 );
	}
}
add_action( 'pre_get_posts', 'sophos_home_posts', 15, 1 );

/**
 * Filter non-posts from search results
 */
function sophos_get_outta_my_search_results( $query ) {
	if ( ! is_admin() ) {
		if ( $query->is_search ) {
			$query->set( 'post_type', 'post' );
		}

		return $query;
	}
}
add_filter( 'pre_get_posts', 'sophos_get_outta_my_search_results' );

/**
 * Alter the archive title for the Author archive
 */
function sophos_get_the_archive_title( $title ) {
	if ( is_author() ) {

		if ( defined( 'WPCOM_IS_VIP_ENV' ) && true === WPCOM_IS_VIP_ENV ) {
			$count_user_posts = wpcom_vip_count_user_posts( get_the_author_meta( 'ID' ) );
		} else {
			$count_user_posts = count_user_posts( get_the_author_meta( 'ID' ) );
		}

		$title = sprintf( __( '%d Articles by %s' ), $count_user_posts, '<span class="vcard">' . get_the_author() . '</span>' );
	}

	return $title;
}

add_filter( 'get_the_archive_title', 'sophos_get_the_archive_title', 10, 1 );

/**
 * Add a class attribute to the Next/Previous posts links.
 *
 * @return string
 */
function sophos_posts_pagination_link_attributes() {
	return 'class="button"';
}

add_filter( 'next_posts_link_attributes', 'sophos_posts_pagination_link_attributes' );
add_filter( 'previous_posts_link_attributes', 'sophos_posts_pagination_link_attributes' );

/**
 * Returns the featured image html. If one does not exist we pull the first
 * image from the post and return that as the featured image.
 *
 * Must be used in the loop!
 *
 * @param string $size   Any image size that exists in WordPress via
 *                       add_image_size()
 *
 * @see add_image_size()
 *
 * @param string $return Passing a value of "array" will return the result of
 *                       wp_get_attachment_image_src()
 *
 * @see wp_get_attachment_image_src()
 *
 * @return string
 */
function sophos_get_featured_image( $size = 'card-thumbnail', $return = 'default' ) {
	if ( ! has_post_thumbnail() ) {

		// Looking for an "attached" image first (One that was uploaded directly to this post).
		$post_attachments = get_children(
			[
				'numberposts'    => 1,
				'order'          => 'ASC',
				'post_parent'    => get_the_ID(),
				'post_type'      => 'attachment',
				'post_mime_type' => 'image'
			]
		);

		/**
		 * TODO: See if we can try and pull an img tag from the content. Right now we can only seem to pull an image directly from the "Featured Image" feature or images uploaded and attached directly to the post
		 */
		if ( ! $post_attachments ) {
			return '';
		}

		// Return the first "attached" image
		$attachment_image = array_pop( $post_attachments );

		if ( 'array' == $return ) {
			return wp_get_attachment_image_src( $attachment_image->ID, $size );
		}

		return wp_get_attachment_image( $attachment_image->ID, $size );
	}

	// Return the Featured Image
	if ( 'array' == $return ) {
		return wp_get_attachment_image_src( get_post_thumbnail_id(), $size );
	}

	return wp_get_attachment_image( get_post_thumbnail_id(), $size );
}

/**
 * Returns the featured image as a <div> with a background image.
 *
 * @param string $size   Any image size that exists in WordPress via
 *                       add_image_size()
 *
 * @see sophos_get_featured_image()
 *
 * @return string
 */
function sophos_get_featured_image_as_background( $size = 'card-thumbnail' ) {
	$image_uri  = sophos_get_featured_image( $size, 'array' );
	$background = ! empty( $image_uri )
				  ? sprintf( 'background-image: url(%s);', esc_url( $image_uri[0] ) )
				  : '';

	?><div class="dynamic-image-frame">
		<div class="dynamic-image" style="<?php echo esc_attr( $background ); ?>"></div>
	</div><?php
}

/**
 * Returns the specified attachment as a <div> wiht a background image.
 *
 * @param        $attachment_id
 * @param string $size   Any image size that exists in WordPress via
 *                       add_image_size()
 *
 * @see sophos_get_featured_image()
 *
 * @return string
 */
function sophos_get_attachment_image_as_background( $attachment_id, $size = 'card-thumbnail' ) {
	$image_uri = wp_get_attachment_image_src( $attachment_id, $size );
	if ( empty( $image_uri ) ) {
		return '';
	}
	?>

	<div class="dynamic-image-frame">
		<div class="dynamic-image" style="background-image: url('<?php echo esc_url( $image_uri[0] ); ?>');"></div>
	</div>

	<?php
}

/**
 * Returns the featured image url.
 *
 * @param string $size   Any image size that exists in WordPress via
 *                       add_image_size()
 *
 * @see sophos_get_featured_image()
 *
 * @return string
 */
function sophos_get_featured_image_url( $size = 'hero-thumbnail' ) {
	$image_uri = sophos_get_featured_image( $size, 'array' );
	if ( empty( $image_uri ) ) {
		return '';
	}

	return $image_uri[0];
}

/**
 * A very basic random advert generator
 * Todo: Needs logic for handling duplicates & ultimately content manage
 * adverts in WP.
 */
function sophos_get_random_advert() {
	$adverts = [
		[
			'url' => 'https://www.sophos.com/en-us/lp/sevendeadlysins.aspx',
			'img' => 'sin-all-336x280.jpg'
		],
		[
			'url' => 'https://www.sophos.com/en-us/lp/sevendeadlysins.aspx#web',
			'img' => 'sin-delinquent-web-filtering-336x280.png'
		],
		[
			'url' => 'https://www.sophos.com/en-us/lp/sevendeadlysins.aspx#firewall',
			'img' => 'sin-faulty-firewall-336x280.png'
		],
		[
			'url' => 'https://www.sophos.com/en-us/lp/sevendeadlysins.aspx#mac',
			'img' => 'sin-mac-malice-336x280.png'
		],
		[
			'url' => 'https://www.sophos.com/en-us/lp/sevendeadlysins.aspx#mobile',
			'img' => 'sin-mobile-negligence-336x280.png'
		],
		[
			'url' => 'https://www.sophos.com/en-us/lp/sevendeadlysins.aspx#email',
			'img' => 'sin-un-encrypted-email-336x280.png'
		],
		[
			'url' => 'https://www.sophos.com/en-us/lp/sevendeadlysins.aspx#files',
			'img' => 'sin-un-encrypted-files-336x280.png'
		]
	];

	$advert_count  = count( $adverts ) - 1;
	$random_advert = rand( 0, $advert_count );
	$advert_img    = sophos_image_asset( 'promos/' . $adverts[ $random_advert ]['img'] );
	$advert_url    = $adverts[ $random_advert ]['url'];

	$html = '';
	$html .= '<a class="advert-link" href="' . $advert_url . '">';
	$html .= '	<img class="advert-image" src="' . $advert_img . '">';
	$html .= '</a>';

	return $html;
}

/**
 * Comment form default overrides
 * See comment-template.php:2251 for additional fields.
 */
function sophos_comment_form_defaults( $defaults ) {
	$defaults['title_reply']          = esc_html__( 'What do you think?' );
	$defaults['comment_notes_before'] = '';

	return $defaults;
}
add_filter( 'comment_form_defaults', 'sophos_comment_form_defaults' );


/**
 * Remove URL data from comments
 * @param  array $commentdata Comment data array
 * @return array Comment data array
 */
function sophos_preprocess_comments ( $commentdata ) {
    if ( array_key_exists( 'comment_author_url', $commentdata ) && isset( $commentdata[ 'comment_author_url' ] ) ) {
        $commentdata[ 'comment_author_url' ] = null;
    }
    return $commentdata;
}
add_filter( 'preprocess_comment' , 'sophos_preprocess_comments' );


/**
 * Add favicons to wp_head
 */
function sophos_favicons() {

	$favicon_path = sophos_image_asset() . 'favicons/';
	?>

	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo esc_url( $favicon_path ); ?>apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo esc_url( $favicon_path ); ?>apple-touch-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo esc_url( $favicon_path ); ?>apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo esc_url( $favicon_path ); ?>apple-touch-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo esc_url( $favicon_path ); ?>apple-touch-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo esc_url( $favicon_path ); ?>apple-touch-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo esc_url( $favicon_path ); ?>apple-touch-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo esc_url( $favicon_path ); ?>apple-touch-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( $favicon_path ); ?>apple-touch-icon-180x180.png">
	<link rel="icon" type="image/png" href="<?php echo esc_url( $favicon_path ); ?>favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="<?php echo esc_url( $favicon_path ); ?>favicon-194x194.png" sizes="194x194">
	<link rel="icon" type="image/png" href="<?php echo esc_url( $favicon_path ); ?>favicon-96x96.png" sizes="96x96">
	<link rel="icon" type="image/png" href="<?php echo esc_url( $favicon_path ); ?>android-chrome-192x192.png" sizes="192x192">
	<link rel="icon" type="image/png" href="<?php echo esc_url( $favicon_path ); ?>favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="<?php echo esc_url( $favicon_path ); ?>manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/mstile-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<?php
}

/**
 * Create canonical URLs for URLs that aren't posts and pages
 */
add_action( 'wp_head', function () {

	// Fall back on the standard canonical URL generation for posts and pages
	if ( is_singular() ) {
		return;
	}

	// Sanitise the path and chop off the query string
	$path = wp_parse_url( filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL ),  PHP_URL_PATH );
	$url  = get_site_url() . $path;

	/**
	 * Filters the canonical URL for a post.
	 *
	 * @param string  $canonical_url The post's canonical URL.
	 * @param WP_Post $post          Post object.
	 */
	$canonical = apply_filters( 'get_canonical_url', $url, null );

	?><link rel="canonical" href="<?php echo esc_url( $canonical ); ?>"><?php
});

/**
 * Force shortlinks to HTTPS
 *
 * @param string $shortlink   Shortlink URL.
 * @param int    $id          Post ID, or 0 for the current post.
 * @param string $context     The context for the link. One of 'post' or 'query',
 * @param bool   $allow_slugs Whether to allow post slugs in the shortlink. Not used by default.
 * @return string
 */
add_filter( 'get_shortlink', function ( $shortlink, $id, $context, $allow_slugs ) {
	return str_replace( 'http://', "https://", $shortlink );
}, 10, 4);

/**
 * Force media links to HTTPS
 *
 * @param string $content	HTML content
 * @return string
 */
add_filter( 'the_content', function ( $content ) {
	return str_replace(
		array(
			'http://feeds.soundcloud.com',
			'http://itunes.apple.com'
		),
		array(
			'https://feeds.soundcloud.com',
			'https://itunes.apple.com'
		),
	$content );
});

/**
 * Display featured image & caption (when available).
 */
function sophos_featured_image() {
	if ( has_post_thumbnail() ) {

		$attr = [ ];

		// See if there's a caption.
		$caption = get_post( get_post_thumbnail_id() )->post_excerpt;

		// Add a class for images with captions.
		if ( $caption ) {
			$attr['class'] = 'attachment-post-thumbnail with-caption';
		}

		?>
		<div class="entry-featured-image">
			<?php the_post_thumbnail( 'hero-thumbnail', $attr ); ?>
			<?php if ( $caption ) : ?>
				<figcaption class="wp-caption-text"><?php wp_kses_post( $caption ); ?></figcaption>
			<?php endif; ?>
		</div> <!-- .entry-featured-image -->
		<?php
	}
}

/**
 * Alter the title value on archive pages.
 * developer.wordpress.org/reference/functions/get_the_archive_title
 */
function sophos_alter_the_archive_title( $title ) {
	if ( is_category() ) {
		return $title = sprintf( __( '<span class="uppercase">%s</span> articles' ), single_cat_title( '', false ) );
	}
	if ( is_tag() ) {
		$title = sprintf( __( 'articles <span class="lighten">tagged</span> <span class="uppercase">%s</span>' ), single_tag_title( '', false ) );
	}

	return $title;
}

add_filter( 'get_the_archive_title', 'sophos_alter_the_archive_title' );

/**
 * Display Facebook and comment counts
 *
 * @param bool $class	add a class to the list element
 *
 * @return string
 */
function sophos_article_counts( $class = false ) {
	// Remove non-breaking spaces
	$title = str_replace( "\xc2\xa0", "\x20", html_entity_decode( get_the_title(), ENT_HTML5 ) );
	?>

	<ul class="social-links <?php esc_attr_e( $class ); ?>">
		<li class="facebook"><a href="https://www.facebook.com/share.php?u=<?php echo urlencode( get_permalink() ); ?>&title=<?php echo urlencode( $title ); ?>" data-title="<?php esc_attr_e( $title ); ?>" title="Share on Facebook">:/sShare on Facebook</a></li>
	</ul>

	<?php
}

/**
 * Display social sharing links above article & callout.
 *
 * @param bool $show_feed show the rss feed link
 * @param bool $class     add a class to the list element
 *
 * @return string
 */
function sophos_social_links( $show_feed = false, $class = false ) {
	// Remove non-breaking spaces
	$title = str_replace( "\xc2\xa0", "\x20", html_entity_decode( get_the_title(), ENT_HTML5 ) );
	?>

	<ul class="block social share">
		<li class="facebook"><a href="https://www.facebook.com/share.php?u=<?php echo urlencode( get_permalink() ); ?>&title=<?php echo urlencode( $title ); ?>" data-title="<?php esc_attr_e( $title ); ?>" title="Share on Facebook"><svg style="height: 20px;" viewBox="0 0 100 100" class="icon facebook"><use xlink:href="#facebook"></use></svg></a></li>
		<li class="twitter"><a href="https://twitter.com/intent/tweet?text=<?php echo urlencode( str_replace( "\xc2\xa0", "\x20", html_entity_decode( get_the_title(), ENT_HTML5 ) ) ); ?>+<?php echo urlencode( wp_get_shortlink() ); ?>" data-title="<?php esc_attr_e( get_the_title() ); ?>" title="Share on Twitter"><svg style="height: 20px;" viewBox="0 0 100 100" class="icon twitter"><use xlink:href="#twitter"></use></svg></a></li>
		<li class="linkedin"><a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( wp_get_shortlink() ); ?>&title=<?php echo urlencode( $title ); ?>" data-title="<?php esc_attr_e( $title ); ?>" title="Share on LinkedIn"><svg style="height: 20px;"  viewBox="0 0 100 100" class="icon linkedin"><use xlink:href="#linkedin"></use></svg></a></li>
		<li class="reddit"><a href="https://reddit.com/submit/?url=<?php echo urlencode( wp_get_shortlink() ); ?>&title=<?php echo urlencode( $title ); ?>" title="Share on Reddit"><svg style="height: 20px;"  viewBox="0 0 100 100" class="icon reddit"><use xlink:href="#reddit"></use></svg></a></li>

		<?php if ( $show_feed ) : ?>
			<li class="rss"><a href="<?php echo esc_url( get_bloginfo( 'rss_url' ) ); ?>"><svg style="height: 20px;" viewBox="0 0 100 100" class="icon rss"><use xlink:href="#rss"></use></svg></a></li>
		<?php endif; ?>
	</ul>

	<?php
}


/**
 * Custom Shortcodes
 */

// Callout shortcode
function sophos_callout_shortcode( $atts, $content = null ) {

	return '<div class="callout"><p class="quote">' . $content . '</p>' . sophos_social_links() . '</div>';
}

add_shortcode( 'callout', 'sophos_callout_shortcode' );

// Learn more shortcode
function sophos_learn_more_shortcode( $atts, $content = null ) {

	// Attributes
	$atts = shortcode_atts(
		[
			'href'  => '#',
			'label' => 'Learn More:',
			'class' => '',
		], $atts, 'learn-more'
	);

	$html = '';
	$html .= '<div class="learn-more ' . $atts['class'] . '">';
	$html .= '<a href="' . $atts['href'] . '">';
	$html .= '<span class="label">' . $atts['label'] . ' </span>';
	$html .= $content;
	$html .= '</a>';
	$html .= '</div>' . PHP_EOL;

	return $html;
}

add_shortcode( 'learn-more', 'sophos_learn_more_shortcode' );

/**
 * Return an array of available site takeover adverts.
 *
 * @return array
 */
function sophos_get_takeover_ads() {
	return [
		[
			'slug'   => 'takeover-mac-malice',
			'name'   => esc_html__( 'Mac Malice', 'forward' ),
			'url'    => 'https://www.sophos.com/en-us/lp/sevendeadlysins.aspx#mac',
			'img'    => 'sin-mac-malice-336x280.png',
			'bg-img' => 'takeover-mac-malice.png',
		],
		[
			'slug'   => 'takeover-delinquent-web-filtering',
			'name'   => esc_html__( 'Delinquent Web Filtering', 'forward' ),
			'url'    => 'https://www.sophos.com/en-us/lp/sevendeadlysins.aspx#web',
			'img'    => 'sin-delinquent-web-filtering-336x280.png',
			'bg-img' => 'takeover-delinquent-web-filtering.png',
		],
		[
			'slug'   => 'takeover-faulty-firewall',
			'name'   => esc_html__( 'Faulty Firewall', 'forward' ),
			'url'    => 'https://www.sophos.com/en-us/lp/sevendeadlysins.aspx#firewall',
			'img'    => 'sin-faulty-firewall-336x280.png',
			'bg-img' => 'takeover-faulty-firewall.png',
		],
		[
			'slug'   => 'takeover-mobile-negligence',
			'name'   => esc_html__( 'Mobile Negligence', 'forward' ),
			'url'    => 'https://www.sophos.com/en-us/lp/sevendeadlysins.aspx#mobile',
			'img'    => 'sin-mobile-negligence-336x280.png',
			'bg-img' => 'takeover-mobile-negligence.png',
		],
		[
			'slug'   => 'takeover-un-encrypted-email',
			'name'   => esc_html__( 'Un-Encrypted Email', 'forward' ),
			'url'    => 'https://www.sophos.com/en-us/lp/sevendeadlysins.aspx#email',
			'img'    => 'sin-un-encrypted-email-336x280.png',
			'bg-img' => 'takeover-un-encrypted-email.png',
		],
		[
			'slug'   => 'takeover-un-encrypted-files',
			'name'   => esc_html__( 'Un-Encrypted Files', 'forward' ),
			'url'    => 'https://www.sophos.com/en-us/lp/sevendeadlysins.aspx#files',
			'img'    => 'sin-un-encrypted-files-336x280.png',
			'bg-img' => 'takeover-un-encrypted-files.png',
		],
	];
}

/**
 * Sophos product ad takeover.
 * Disabled by default. See 'Disable takeover' below for details.
 */
function sophos_get_add_takeover() {
	$selected_takeover = get_theme_mod( 'sophos_takeover_advert', 'disable' );
	if ( 'disable' == $selected_takeover ) {
		return false;
	}

	// Pick an ad
	$takeovers         = sophos_get_takeover_ads();
	$selected_takeover = $takeovers[ intval( $selected_takeover ) ];

	if ( $selected_takeover ) {
		add_filter( 'body_class', 'sophos_add_takeover_body_class' );
	}

	return $selected_takeover;
}

add_action( 'wp', 'sophos_get_add_takeover' );

/**
 * Add body classes when ad takeover is active.
 */
function sophos_add_takeover_body_class( $classes ) {
	$takeover      = sophos_get_add_takeover();
	$takeover_slug = $takeover['slug'];
	$classes[]     = 'takeover-active ' . $takeover_slug;

	return $classes;
}

/**
 * Outputs an inline style bg image.
 * Used on <body> in header.php
 */
function sophos_takeover_bg() {
	$takeover = sophos_get_add_takeover();

	if ( $takeover ) {
		echo 'style="background-image:url(\'' . esc_url( sophos_image_asset( 'promos/' ) . $takeover['bg-img'] ) . '\');"';
	}
}

/**
 * Hook for the filter 'get_the_categories' which filters out specific terms
 * from displaying with the_category().
 *
 * @param $categories
 *
 * @return array
 */
function sophos_hide_categories_in_list( $categories ) {
	return array_filter(
		$categories,
		function ( $var ) {
			return ! in_array( $var->slug, [ 'featured' ] );
		}
	);
}
add_filter( 'get_the_categories', 'sophos_hide_categories_in_list' );

/**
 * Checks if the Co-Authors Plus plugin is enabled.
 */
function sophos_coauthors_enabled() {
	if ( function_exists( 'get_coauthors' ) ) {
		return true;
	}

	return false;
}

/**
 * Check if an article is co-authored / has multiple authors.
 */
function sophos_is_coauthored() {

	if ( sophos_coauthors_enabled() ) {

		$coauthors = get_coauthors();

		if (count($coauthors) > 1) {
			return true;
		}

		return false;
	}

	return false;
}
