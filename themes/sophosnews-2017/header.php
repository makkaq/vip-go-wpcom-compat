<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Sophos
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php

$iso = \Sophos\Region::guess();
$rd  = \Sophos\Region\Data::instance( $iso );

if ( $rd instanceof \Sophos\Region\Data ) :
	$verification = $rd->google_site_verification();

	if ( preg_match( '/^[a-zA-Z0-9]+$/', $verification ) ) :
		?><meta name="google-site-verification" content="<?php echo esc_attr( $verification ); ?>" /><?php
	endif;
endif; ?>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php echo esc_attr( get_bloginfo( 'pingback_url' ) ); ?>">
<?php

$langs = [];

if ( is_singular() ) :
	foreach ( get_the_terms( get_the_ID(), \Sophos\Region\Taxonomy::NAME ) as $term ) :
		array_push($langs, (object) [
			'iso'  => $term->slug,
			'href' => \Sophos\URL\regionalize( get_permalink( $term->ID ), $term->slug ),
		]);
	endforeach;
else:
	global $wp;
	$url = home_url( add_query_arg( [], $wp->request ) );

	if ( is_home() ) :
		// Get all languages
		foreach ( \Sophos\Region::regions() as $term ) :
			array_push($langs, (object) [
				'iso'  => $term->slug,
				'href' => \Sophos\URL\regionalize( $url, $term->slug ),
			]);
		endforeach;
	else :
		// Get languages where a given category or tag is used
		$qo  = get_queried_object();

		if ( $qo instanceof \WP_Term ) :
			foreach ( \Sophos\Region::regions() as $term ) :
				$query = new WP_Query( [
					'post_type' => 'post',
					'tax_query' => [
						'relation' => 'AND',
					    [
							'taxonomy' => $qo->taxonomy,
							'field'    => 'id',
							'terms'    => $qo->term_taxonomy_id,
						],
						[
							'taxonomy' => \Sophos\Region\Taxonomy::NAME,
							'field'    => 'slug',
							'terms'    => $term->slug,
						],
					],
				]);

				if ( $query->found_posts > 0 ) :
					array_push($langs, (object) [
						'iso'  => $term->slug,
						'href' => \Sophos\URL\regionalize( $url, $term->slug ),
					]);
				endif;
			endforeach;
		endif;
	endif;
endif;

foreach ( $langs as $lang ) :
	?><link rel="alternate" hreflang="<?php echo esc_attr( $lang->iso ); ?>" href="<?php echo esc_url( $lang->href ); ?>" />
	<?php
endforeach; ?>
<?php wp_head(); ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-737537-1"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}

	gtag('js', new Date());

	// Sophos profile
	gtag('config', 'UA-737537-1', {
		'linker': {'accept_incoming': true},
		'cookie_domain': '.sophos.com',
		'page_path': '/corpblog' + window.location.pathname + window.location.search,
		'custom_map': {
			'dimension1': 'CampaignID',
			'dimension2': 'SessionID'
		}
	});

	// Sophos News profile
	gtag('config', 'UA-737537-53');

	/**
	 * Add data to local profiles
	 */
	jQuery(function () {
		// Send the campaign ID after we've loaded Sophos code
		gtag('event', 'cid', {
			'CampaignID': Sophos.Campaign.getCampaignId(),
			'SessionID': Sophos.Session.getSessionId()
		});

		// Local profiles
		// ==============
		// The window._sophosLocalAnalytics variable is included in the <body>
		// element by Wordpress, so we have to delay establishing the trackers
		// that depend upon it until the document has loaded.
		if (window._sophosLocalAnalytics && window._sophosLocalAnalytics.substring(0, 3) === 'UA-') {
			gtag('config', window._sophosLocalAnalytics);
		}
	});
</script>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5L6H3LN');</script>
<!-- End Google Tag Manager -->
</head>

<body <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5L6H3LN" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'sophos-news' ); ?></a>

	<header id="masthead" class="site-header">

		<div class="header-container">

			<div class="site-branding">
				<h1 class="site-title">
					<?php $home = ( $rd instanceof \Sophos\Region\Data ) ? $rd->sophos_homepage_url() : 'https://www.sophos.com/'; ?>
					<a class="site-logo" href="<?php echo esc_url( strip_tags( $home ), 'https' ); ?>" rel="home">
						<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
					</a>
				</h1>
			</div> <!-- site-branding -->

			<nav class="primary-header-navigation" role="navigation">
				<?php wp_nav_menu([
					'menu'           => \Sophos\Region\Menu\slug( 'primary' ),
					'theme_location' => \Sophos\Region\Menu\slug( 'primary' ),
					'fallback_cb'    => false,
					'container'      => 'ul',
				]); ?>
			</nav>

		</div>
	</header> <!-- site-header -->

	<div class="secondary-header">
		<div class="secondary-header-container container">
			<nav class="secondary-header-navigation" role="navigation">
				<?php wp_nav_menu([
					'menu'           => \Sophos\Region\Menu\slug( 'secondary' ),
					'theme_location' => \Sophos\Region\Menu\slug( 'secondary' ),
					'fallback_cb'    => false,
					'container'      => 'ul',
				]); ?>
			</nav>
		</div>
	</div> <!-- secondary-header -->

	<div class="news-header">
		<div class="news-header-container container">

			<div class="news-branding">
				<h1 class="news-title">
					<a class="news-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<?php esc_html_e( 'Sophos News', 'sophos-news' ); ?>
					</a>
				</h1>
			</div> <!-- news-branding -->

			<div class="mobile-menu-toggle">
				<a href="#newsmenu">
					<?php if ( is_archive() ) : ?>
						<div class="tablet">
							<?php esc_html_e( 'Menu', 'sophos-news' ); ?>
						</div>
						<div class="mobile">
							<?php esc_html_e( 'Viewing', 'sophos-news' ); ?>: <span><?php single_cat_title( '' ); ?></span>
						</div>
					<?php else : ?>
						<?php esc_html_e( 'Menu', 'sophos-news' ); ?>
					<?php endif; ?>
				</a>
			</div>

			<div class="mobile-search-toggle">
				<a class="icon icon-search-secondary" href="#search">
					<?php esc_html_e( 'Search', 'sophos-news' ); ?>
				</a>
			</div>

			<nav class="news-header-navigation" role="navigation">
				<?php wp_nav_menu([
					'menu'           => \Sophos\Region\Menu\slug( 'tertiary' ),
					'theme_location' => \Sophos\Region\Menu\slug( 'tertiary' ),
					'fallback_cb'    => false,
					'container'      => 'ul',
				]); ?>
			</nav>

			<div class="site-search-block" style="display: none;">
				<form role="search" class="search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<fieldset>
						<div class="field">
							<input type="text" value="" name="s" class="search-field" placeholder="<?php esc_html_e( 'Search', 'sophos-news' ); ?>">
						</div>
						<div class="submit">
							<button type="submit" class="search-submit icon icon-search-secondary">
								<?php esc_html_e( 'Go', 'sophos-news' ); ?>
							</button>
						</div>
					</fieldset>
				</form>
				<a class="icon" href="#search-close">
					<?php esc_html_e( 'Close', 'sophos-news' ); ?>
				</a>
			</div>
		</div>
	</div> <!-- news-header -->

	<div id="content" class="site-content">
		<div class="content-container">
