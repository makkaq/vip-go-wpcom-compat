<?php

/*
 Plugin Name: Sophos
 Plugin URI: https://news.sophos.com
 Description: The world according to Sophos
 Version: 1.1.34
 Author: Mark Stockley (Compound Eye Ltd)
 Author URI: compoundeye.co.uk
 License: GPL3

 Copyright 2017  Mark Stockley  (email : mark@compoundeye.co.uk)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 2, as
 published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * @package   Sophos
 * @author    Mark Stockley
 * @version   1.1.34
 * @copyright Copyright (c) 2016, Mark Stockley
 * @license   https://opensource.org/licenses/gpl-3.0.html
 */

global $coauthors_plus;

wpcom_vip_load_plugin( 'co-authors-plus', 'plugins', '3.2' );

// If we're running from the CLI (e.g. unit testing) use a relative path
define( 'SOPHOS_LIB', dirname( __FILE__ ) );
define( 'SOPHOS_BIN', dirname( __FILE__ ) . '/bin' );

require_once( SOPHOS_LIB . '/Sophos/Exception/class-invalidlanguagecode.php' );
require_once( SOPHOS_LIB . '/Sophos/Exception/class-taxonomyerror.php' );
require_once( SOPHOS_LIB . '/Sophos/Exception/class-invalidurl.php' );
require_once( SOPHOS_LIB . '/Sophos/utils.php' );
require_once( SOPHOS_LIB . '/Sophos/class-language.php' );
require_once( SOPHOS_LIB . '/Sophos/class-region.php' );
require_once( SOPHOS_LIB . '/Sophos/Region/class-data.php' );
require_once( SOPHOS_LIB . '/Sophos/Region/class-taxonomy.php' );
require_once( SOPHOS_LIB . '/Sophos/Region/Menu/class-data.php' );
require_once( SOPHOS_LIB . '/Sophos/class-url.php' );
require_once( SOPHOS_LIB . '/Sophos/user.php' );
require_once( SOPHOS_LIB . '/Sophos/comment.php' );
require_once( SOPHOS_LIB . '/Sophos/UI/coauthors.php' );
require_once( SOPHOS_LIB . '/Sophos/UI/profile.php' );
require_once( SOPHOS_LIB . '/Sophos/UI/post.php' );
require_once( SOPHOS_LIB . '/Sophos/UI/table.php' );

if ( function_exists( '\Sophos\Utils\is_wp_cli' ) && \Sophos\Utils\is_wp_cli() ) {

	// Menu Data classes
	require_once( SOPHOS_LIB . '/Sophos/Region/class-menu.php' );
	require_once( SOPHOS_LIB . '/Sophos/Region/Menu/class-primary.php' );
	require_once( SOPHOS_LIB . '/Sophos/Region/Menu/class-secondary.php' );
	require_once( SOPHOS_LIB . '/Sophos/Region/Menu/class-tertiary.php' );
	require_once( SOPHOS_LIB . '/Sophos/Region/Menu/class-about.php' );
	require_once( SOPHOS_LIB . '/Sophos/Region/Menu/class-popular.php' );
	require_once( SOPHOS_LIB . '/Sophos/Region/Menu/class-community.php' );
	require_once( SOPHOS_LIB . '/Sophos/Region/Menu/class-social.php' );
	require_once( SOPHOS_LIB . '/Sophos/Region/Menu/class-support.php' );
	require_once( SOPHOS_LIB . '/Sophos/Region/Menu/class-work.php' );
	require_once( SOPHOS_LIB . '/Sophos/Region/Menu/class-legal.php' );

	// CLI commands
	require_once( SOPHOS_BIN . '/class-regionalize.php' );
	require_once( SOPHOS_BIN . '/class-user.php' );
}

/*
 * Switch locale and add a locale filter as early as possible
 */
add_action( 'after_setup_theme', function () {
	// Register the region taxonomy before we start switching locales
	\Sophos\Region\Taxonomy\register();

	// The switch_to_locale() method starts by checking its argument against the
	// current locale using get_locale(). If we set a filter on locale before we
	// run switch then the filtered get_locale() actually prevents the switch!
	$language = new \Sophos\Language( \Sophos\Region::guess() );
	$locale   = $language->format_for_wordpress();

	trigger_error( "Locale is $locale", E_USER_WARNING );

	if ( ! is_admin() ) {
		global $wp_locale_switcher;

		$wp_locale_switcher->switch_to_locale( $locale );
	}

	/**
	 * Change the locale based on regionalisation rules
	 */
	add_filter( 'locale', function ( $wp_locale ) use ( $locale ) {
		return $locale;
	});
}, 1);


/**
 * Setup regionalised permalinks
 */
add_action( 'init', function () {

	global $wp_rewrite;

	// The redirect rule for robots.txt in wp-includes/class-wp-rewrite.php uses
	// home_url(), which we always run through a regionalisation filter. The WP
	// code tests to see if the URL is empty, or /, which it never is because
	// we've added a language code to the root with the filter. This means the
	// rule for robots.txt IS NEVER SET. Since there's no effective way to
	// isolate the use of the filter in that one context, we have to reinstate
	// the rule.
	$wp_rewrite->add_rule( 'robots\.txt$', $wp_rewrite->index . '?robots=1', 'top' );

	// Create a %region% tag for use in rewrite rules
	$wp_rewrite->add_rewrite_tag( \Sophos\Region\Taxonomy::TAG, '(\w\w(?:[-_](?:\w\w|\d{1,3}))?)', sprintf( '%s=', \Sophos\Region\Taxonomy::NAME ) );

	// THE ORDER OF THESE RULES MATTERS. The sophos-page-with-region pattern will match post URLs
	// so it has to go second so that it's used when sophos-post-with-region doesn't match
	$wp_rewrite->add_permastruct( 'sophos-post-with-region', '/' . \Sophos\Region\Taxonomy::TAG . '/%year%/%monthnum%/%day%/%postname%/', [
		'with_front' => false,
	] );
	$wp_rewrite->add_permastruct( 'sophos-category-with-region', '/' . \Sophos\Region\Taxonomy::TAG . '/category/%category%/', [
		'with_front' => false,
	] );
	$wp_rewrite->add_permastruct( 'sophos-author-with-region', '/' . \Sophos\Region\Taxonomy::TAG . '/author/%author%/', [
		'with_front' => false,
	] );
	$wp_rewrite->add_permastruct( 'sophos-page-with-region', '/' . \Sophos\Region\Taxonomy::TAG . '/%pagename%/', [
		'with_front' => false,
	] );

	if ( function_exists( 'wpcom_vip_load_permastruct' ) ) {
		wpcom_vip_load_permastruct( '/%year%/%monthnum%/%day%/%postname%/' );
	}
});


/**
 * Set a variable if the rewrite rules have been flushed
 */
add_action( 'generate_rewrite_rules', function () {
	add_option( 'sophos_rewrites_flushed', true );
});


/**
 * Filter queries by region
 */
add_action( 'parse_query', function ( $query ) {

	// Because the region taxonomy is used in all our regionalised URLs many requests are
	// treated as both a region taxonomy and something else - for example: an author URL
	// causes both is_author() and is_tax() to return true. Templates are chosen according
	// to a series of is_* checks in wp-includes/template-loader.php and is_tax() is quite
	// high in the list which can causes the taxonomy template to be preferred to other
	// templates. Rather than using filters to change template and body class selections
	// it's easier and cleaner to just nix is_tax since, for regionalised queries, the
	// template it triggers is the wrong one.
	if ( $query->is_tax( \Sophos\Region\Taxonomy::NAME ) ) {
		$query->is_tax = false;
	}

	// Let the rewrite rules handle categories and authors from here
	if ( $query->is_author() ) {
		return;
	}

	if ( \Sophos\Utils\is_wp_cli() ) {
		return;
	}

	if ( ! \Sophos\Utils\is_regionalised() ) {
		return;
	}

	if ( ! $query->is_main_query() ) {
		return;
	}

	$iso = null;

	if ( \Sophos\Utils\is_edit_page() ) {
		$iso = get_query_var( \Sophos\Region\Taxonomy::NAME, false );

		// If no query var has been set, look for a user preference
		if ( false === $iso ) {
			$lang = \Sophos\Language::from_user_setting();
			$iso  = ( $lang instanceof \Sophos\Language )
				  ? $lang->format_for_sophos()
				  : \Sophos\Region\Taxonomy::ALL_REGIONS;
		}

		// If user selected 'all regions' OR we've arrived at the page without
		// a filter being set and the user hasn't set a language in their profile
		// then remove the query var and show everything
		if ( \Sophos\Region\Taxonomy::ALL_REGIONS === $iso ) {
			$query->set( \Sophos\Region\Taxonomy::NAME, false );
			return;
		}
	}

	// Because of a bug in the Wordpress core #24819 tax_query doesn't Work
	// on single posts and pages. The bug is marked as wontfix because it's
	// regarded as "Too edge case for such an impactful change to query". Note
	// that this change breaks previews so they're excluded.
	// See: https://core.trac.wordpress.org/ticket/24819?cversion=0&cnum_hist=3
	if ( $query->is_singular && ! $query->is_preview() ) {
		$query->is_singular = false;
	}

	$query->set( 'tax_query', [[
		'taxonomy' => \Sophos\Region\Taxonomy::NAME,
		'field'    => 'slug',
		'terms'    => $iso ?: \Sophos\Region::guess(),
		],
	]);
});

// is_singular may have been made false in parse_query as a workaround for
// Wordpress core issue #24819 so we restore it at the earliest opportunity
add_action( 'posts_selection', function ( $sql ) {
	global $wp_query;
	$wp_query->is_singular = $wp_query->is_single || $wp_query->is_page || $wp_query->is_attachment;
});

// Cache changes to the region taxonomy
add_action( 'create_region', '\Sophos\Region\Taxonomy\cache', 10, 0 );
add_action( 'edited_region', '\Sophos\Region\Taxonomy\cache', 10, 0 );
add_action( 'delete_region', '\Sophos\Region\Taxonomy\cache', 10, 0 );

if ( ! class_exists( 'CoAuthors_Plus' ) ) {
	// Add a region field to user profiles
	add_action( 'show_user_profile', 'Sophos\UI\Profile\add_region_field' );
	add_action( 'edit_user_profile', 'Sophos\UI\Profile\add_region_field' );
	add_action( 'user_new_form', 'Sophos\UI\Profile\add_region_field' );

	// Save region field changes on user profiles
	add_action( 'personal_options_update', 'Sophos\UI\Profile\save_region_field' );
	add_action( 'edit_user_profile_update', 'Sophos\UI\Profile\save_region_field' );
	add_action( 'user_register', 'Sophos\UI\Profile\save_region_field' );
}

// Add regions to the permalink editor
add_filter( 'get_sample_permalink_html', '\Sophos\UI\Post\sample_permalink_html', 10, 5 );

// Add region select boxes to the post edit screen
add_action( 'admin_menu', '\Sophos\UI\Post\add_region_select' );
add_action( 'save_post', '\Sophos\UI\Post\save_region_selection' );

// Add region filter to Posts table in admin
add_action( 'restrict_manage_posts', '\Sophos\UI\Table\add_region_filter' );

// Regionalise permalinks
add_filter( 'post_link', '\Sophos\URL\add_language', 10, 3 );
add_filter( 'post_type_link', '\Sophos\URL\add_language', 10, 3 );
add_filter( 'page_link', '\Sophos\URL\add_language', 10, 3 );

// Regionalise the Preview Changes link
add_filter( 'preview_post_link', function ( $url, \WP_Post $post ) {
	// bypass any custom-post-type
	if ( ! in_array( $post->post_type, [ 'post', 'page' ], true ) ) {
		return;
	}
	$term = \Sophos\Utils\get_post_region( $post );
	return ( $term instanceof \WP_Term ) ?	\Sophos\URL\regionalize( $url, $term->slug ) : $url;
}, 10, 2 );

// Regionalise home URL
add_filter( 'home_url', '\Sophos\URL\regionalize_home_url', 10, 4 );

// Redirect URLs to their regionalised equivalent
add_filter( 'template_redirect', '\Sophos\URL\redirect' );

// Redirect incomplete URLs using the post's region rather than the user's
//
// Since URLs without a region code are most like to come from legacy search
// results or bookmarks they're strongly associated with a specific legacy
// site and region. Because of that it's more appropriate to regionalise the
// URL based on our best guess at the post's region rather than the users.
add_filter( 'redirect_canonical', '\Sophos\URL\canonical', 10, 2 );

// Exclude unregionalised tags and categories from robots.txt
add_filter( 'robots_txt', function ( $output, $public ) {
	ob_start();
	foreach ( [ 'category', 'tag' ] as $dir ) {
		echo "Disallow: /$dir/" . PHP_EOL;
	}

	return $output . ob_get_clean();
}, 10, 2);

// Canonicalse URLs in MSM sitemaps so they aren't regionalised to users
add_filter( 'msm_sitemap_entry', '\Sophos\URL\msm_sitemap', 10, 1 );

// Fix news sitemap regionalisation
add_filter( 'wpcom_sitemap_news_sitemap_item', function ( $url, $post ) {

	// Google advises that standard sitemaps should not contain hreflang
	// attributes if they're also used in the documents themselves. News seems
	// to be different though. In a news sitemap the news:language element
	// is required (https://www.google.com/schemas/sitemap-news/0.9/sitemap-news.xsd)
	if ( array_key_exists( 'loc', $url )
	&&	 array_key_exists( 'news:news', $url )
	&&	 array_key_exists( 'news:publication', $url[ 'news:news' ] )
	&&	 array_key_exists( 'news:language', $url[ 'news:news' ][ 'news:publication' ] )) {

		// Don't guess the region from the user, get it from the post
		$canonical = \Sophos\URL\canonical( $url[ 'loc' ] );
		$url[ 'loc' ] = $canonical;

		// FIX Wordpress VIP's broken news:language elements in news sitemaps
		$language  = \Sophos\Language::from_url( $canonical );
		$url[ 'news:news' ][ 'news:publication' ][ 'news:language' ] = $language->format_for_news_sitemap();
	}

	return $url;
},10, 2);

// Set a redirect_to field on comments
//
// If user comments on an article the system redirects the user to an article
// URL with the user's region rather than the URL they were commenting on. If
// the user's region matches the region in the URL that's fine. If the user's
// region does not match the region in the URL then the user is redirected to a
// URL with their region code in it. If that article exists in their region then
// they have been switched unwittingly to a differet region whilst browsing. If
// the article doesn't exist in their region they get a 404.
//
// We can change the comment URL by hooking the comment_post_redirect but by the
// time we do we have no reliable way of knowing the URL of the article they
// were looking at. We can only guess the user's region or the original region
// of the post, not the region the user was actually looking at.
//
// To establish the URL it should redirect to Wordpress first checks if the POST
// array has a redirect_to key (see line 47 of wp-comments-post.php) so we can
// set the correct comment redirect URL by adding this field to the form.
add_action( 'comment_form_logged_in_after', '\Sophos\Comment\add_field_redirect_to' );
add_action( 'comment_form_after_fields', '\Sophos\Comment\add_field_redirect_to' );

// Restrict Users to add posts in their own Region
add_action( 'init', '\Sophos\UI\Post\allow_adding_regions_to_a_post' );
add_action( 'wp_print_scripts', '\Sophos\UI\Post\disable_autosave_when_not_users_region' );

// Co-Authors Plus
add_filter( 'coauthors_guest_author_fields', '\Sophos\UI\CoAuthors\guest_author_sophos_fields', 10, 2 );
add_action( 'add_meta_boxes', '\Sophos\UI\CoAuthors\action_add_meta_boxes', 11, 2 );

// Add region column to Users listing table
add_filter( 'manage_users_columns', '\Sophos\UI\Profile\region_column' );
add_action( 'manage_users_custom_column', '\Sophos\UI\Profile\region_column_value', 10, 3 );

// Don't send subscriber emails for non-English content
add_action( 'publish_post', '\Sophos\UI\Post\disable_subscriber_email', 9, 2 );
