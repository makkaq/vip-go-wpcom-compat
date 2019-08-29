<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Forward
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="google-site-verification" content="RHQeasQqjasbRt5asY6atZYbs5tr10gFBevDt2HRhW0" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<script type='text/javascript'>
var _vwo_code=(function(){
var account_id=25349,
settings_tolerance=2000,
library_tolerance=2500,
use_existing_jquery=false,
/* DO NOT EDIT BELOW THIS LINE */
f=false,d=document;return{use_existing_jquery:function(){return use_existing_jquery;},library_tolerance:function(){return library_tolerance;},finish:function(){if(!f){f=true;var a=d.getElementById('_vis_opt_path_hides');if(a)a.parentNode.removeChild(a);}},finished:function(){return f;},load:function(a){var b=d.createElement('script');b.src=a;b.type='text/javascript';b.innerText;b.onerror=function(){_vwo_code.finish();};d.getElementsByTagName('head')[0].appendChild(b);},init:function(){settings_timer=setTimeout('_vwo_code.finish()',settings_tolerance);var a=d.createElement('style'),b='body{opacity:0 !important;filter:alpha(opacity=0) !important;background:none !important;}',h=d.getElementsByTagName('head')[0];a.setAttribute('id','_vis_opt_path_hides');a.setAttribute('type','text/css');if(a.styleSheet)a.styleSheet.cssText=b;else a.appendChild(d.createTextNode(b));h.appendChild(a);this.load('//dev.visualwebsiteoptimizer.com/j.php?a='+account_id+'&u='+encodeURIComponent(d.URL)+'&r='+Math.random());return settings_timer;}};}());_vwo_settings_timer=_vwo_code.init();
</script>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> <?php sophos_takeover_bg(); ?>>
<div style="display: none">
<?php
	echo wp_kses( file_get_contents( sprintf( '%s/img/sprite.svg', get_stylesheet_directory() ) ), [
		// Note that attributes are LOWERCASE regardless of how they appear in
		// the SVG code itself, e.g. viewBox has to be viewbox.
		'svg' 	 => [
			'style' => []
		],
		'symbol' => [
			'id'  => [],
			'viewbox' => []
		],
		'path'	 => [
			'd' => []
		]
	]);
?>
</div>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'forward' ); ?></a>

	<?php $ad_takeover = sophos_get_add_takeover();
	if ( $ad_takeover ) {
		// Sophos product ad takes priority.
		get_template_part( 'panel', 'takeover' );
	} else {
		if ( is_home() ) {
			get_template_part( 'panel', 'breaking-news' );
		}
	} ?>

	<header id="masthead" class="site-header variation variation-3" role="banner">
		<div class="header-container">
			<div class="site-branding">
				<div class="site-title">
					<a title="Naked Security" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo esc_url( get_template_directory_uri() . '/img/naked-security-logo-white@2x.png' ); ?>"></a>
					by
					<a title="sophos.com" href="https://www.sophos.com"><svg viewBox="0 0 132 24" class="icon sophos"><use xlink:href="#sophos"></use></svg></a>
				</div>
			</div><!-- .site-branding -->
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<div class="container">
					<?php wp_nav_menu(
						[
							'theme_location' => 'test-two',
							'container'      => false,
							'menu_class'     => 'exit-links menu',
							'depth'          => - 1,
						]
					); ?>
				</div>
			</nav><!-- #site-navigation -->
			<?php get_search_form(); ?>
		</div><!-- .container -->
	</header>

	<div class="responsive-flyout-panel small-12 column active" style="opacity: 1; display: none;">
		<div class="modal-filler"></div>
	    <div class="productNavBox">
	        <div class="productNavContainer">
	            <div class="row prodRow">
	                <div class="prodListBox column large-9">
	                    <div class="row margin-0 highlightProduct">
	                        <div class="column medium-6">
	                            <a href="https://www.sophos.com/products/next-gen-firewall.aspx"><img src="https://www.sophos.com/medialibrary/2F4785DE8C3F43ECBD699A18F332FEEB.ashx">
	                                <div class="menu-section-header">
										<div class="menu-section-title">XG Firewall</div>
	                                	<div class="menu-section-subtitle">Next-Gen Firewall</div>
									</div>
	                            </a>
	                        </div>
	                        <div class="column medium-6">
	                            <a href="https://www.sophos.com/products/intercept-x.aspx"><img src="https://www.sophos.com/medialibrary/27111765D65A45D49A232925D2CE428B.ashx">
	                                <div class="menu-section-header">
										<div class="menu-section-title">Intercept X</div>
	                                	<div class="menu-section-subtitle">Next-Gen Endpoint</div>
									</div>
	                            </a>
	                        </div>
	                    </div>
	                    <div class="productList">
	                        <ul>
								<li>
									<a href="https://www.sophos.com/en-us/products/cloud-optix.aspx"><img src="https://www.sophos.com/medialibrary/SophosNext/Images/Products/Icons/sophos-cloud-optix-icon.svg">Sophos Cloud Optix</a>
								</li>
	                            <li>
	                                <a href="https://www.sophos.com/products/sophos-central.aspx"><img src="https://www.sophos.com/medialibrary/08EA66123F1B4DDE8FE5C9B578A7FAA7.ashx">Sophos Central</a>
	                            </li>
	                            <li>
	                                <a href="https://www.sophos.com/products/mobile-control.aspx"><img src="https://www.sophos.com/medialibrary/678FE994855047739F50D6C411184FA6.ashx">Sophos Mobile</a>
	                            </li>
	                            <li>
	                                <a href="https://www.sophos.com/products/server-security.aspx"><img src="https://www.sophos.com/medialibrary/01B80E7D7CD54EF8AC2E51C9C6EC0236.ashx">Intercept X for Server</a>
	                            </li>
	                            <li>
	                                <a href="https://www.sophos.com/products/secure-wifi.aspx"><img src="https://www.sophos.com/medialibrary/BEBD9C41BB4E480498514B29B92A145A.ashx">Secure Wi-Fi</a>
	                            </li>
	                            <li>
	                                <a href="https://www.sophos.com/products/phish-threat.aspx"><img src="https://www.sophos.com/medialibrary/DC9D1A7BA3964B0C9DAF1F1E68806B5A.ashx">Phish Threat</a>
	                            </li>
	                            <li>
	                                <a href="https://www.sophos.com/products/safeguard-encryption.aspx"><img src="https://www.sophos.com/medialibrary/8F51F06B62814B108D3B2763FF018E37.ashx">SafeGuard Encryption</a>
	                            </li>
	                            <li>
	                                <a href="https://www.sophos.com/products/sophos-email.aspx"><img src="https://www.sophos.com/medialibrary/597ADA0AF01A4A12B2B27239D0F9992C.ashx">Secure Email</a>
	                            </li>
	                            <li>
	                                <a href="https://www.sophos.com/products/unified-threat-management.aspx"><img src="https://www.sophos.com/medialibrary/8CB72BF5E1AD41F7894CE5E42101B0F3.ashx">SG UTM</a>
	                            </li>
	                            <li>
	                                <a href="https://www.sophos.com/products/secure-web-gateway.aspx"><img src="https://www.sophos.com/medialibrary/C586E372694547A1B207A61D87246BA7.ashx">Secure Web Gateway</a>
	                            </li>
	                        </ul>
	                    </div>
	                </div>
	                <div class="homeUserBox column large-3">
						<img src="https://www.sophos.com/medialibrary/3376DF5742A64AEE93FDB9DCA7291239.ashx">
						<div class="menu-section-title">For Home Users</div>
	                    <p>Sophos Home protects every Mac and PC in your home </p><a href="https://home.sophos.com/" target="_blank" class="greenSmallMenuBtn">Learn More</a>
					</div>
	            </div>
	            <div class="row blueRow">
	                <div class="column large-3 medium-6">
	                    <a href="https://www.sophos.com/products/free-tools.aspx"><img width="19" src="https://www.sophos.com/medialibrary/A1FF440D2BB84511B8C94D771D44E207.ashx">Free Security Tools</a>
	                </div>
	                <div class="column large-3 medium-6">
	                    <a href="https://www.sophos.com/products/free-trials.aspx"><img width="20" src="https://www.sophos.com/medialibrary/FCD3410ED2C347808D9DB8AC12C62DBD.ashx">Free Trials</a>
	                </div>
	                <div class="column large-3 medium-6">
	                    <a href="https://www.sophos.com/products/demos.aspx"><img width="18" src="https://www.sophos.com/medialibrary/50985180E5F54C94917A2A6508E24287.ashx">Product Demos</a>
	                </div>
	                <!-- <div class="column large-3 medium-6">
	                    <a href="javascript:fairfax.openLiveChatFromFlyoutLink();" class="flyout-live-chat-link"><img width="20" src="https://www.sophos.com/medialibrary/E24C5050837145F6A5C592E299BA2A0D.ashx">Live Sales Chat</a>
	                </div> -->
	            </div>
	        </div>
	    </div>
	</div>
	<script>

	jQuery(document).ready( function () {
		var productButton   = jQuery('nav .menu-item.products');
		var modalBackground = jQuery('.modal-filler');
		var flyoutMenu		= jQuery('.responsive-flyout-panel');
		var Sophos			= Sophos || {};

		Sophos.MenuTest = {};

		Sophos.MenuTest.open = function () {
				productButton.addClass('open');
				flyoutMenu.show();
				modalBackground.show();
		};

		Sophos.MenuTest.close = function () {
			productButton.removeClass('open');
			flyoutMenu.hide();
			modalBackground.hide();
		};

		Sophos.MenuTest.toggle = function () {
			if ( productButton.hasClass('open') ) {
				Sophos.MenuTest.close();
			} else {
				Sophos.MenuTest.open();
			}
		};

		productButton.click( Sophos.MenuTest.toggle );
		modalBackground.click( Sophos.MenuTest.close );
	});

	</script>

	<?php get_template_part( 'panel', 'about' ); ?>

	<div id="content" class="site-content">
		<div class="content-container">
