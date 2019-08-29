<?php


/**
 * Add the AMP Analytics library to the head of AMP documents
 *
 * @param AMP_Post_Template $amp_template
 */
add_action( 'amp_post_template_head', function ( $amp_template ) {
	/*
	 * Add a meta element to opt AMP pages into using the Google AMP Client ID
	 * so that individuals are tracked as the same user on the site and on
	 * Google's AMP caches.
	 */
	?><meta name="amp-google-client-id-api" content="googleanalytics"><?php
	/*
	 * Add the AMP analytics and AMP Javascript library (in that order).
	 */
	?><script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
	<script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script><?php
});


/**
 * Add Google Analytics tracking code to AMP documents
 *
 * @param Array $data
 */
add_filter( 'amp_post_template_data', function ( $data ) {

	if ( array_key_exists( 'post_amp_content' , $data ) ) {
		ob_start();
		?><amp-analytics type="googleanalytics" id="nakedsecurity.sophos.com">
			<script type="application/json">
			{
				"vars": {
					"account": "UA-737537-25"
				},
				"triggers": {
					"trackPageview": {
						"on": "visible",
						"request": "pageview"
					}
				},
				"extraUrlParams": {
					"cd4": "${ampdocHost}"
				}
			}
			</script>
		</amp-analytics>
		<amp-analytics type="googleanalytics" id="sophos.com">
			<script type="application/json">
			{
				"vars": {
					"account": "UA-737537-1"
				},
				"triggers": {
					"trackPageview": {
						"on": "visible",
						"request": "pageview"
					}
				}
			}
			</script>
		</amp-analytics><?php

		$data[ 'post_amp_content' ] = $data[ 'post_amp_content' ] . ob_get_clean();
	}

	return $data;
});


/**
 * Add an Eloqua tracking pixel to the footer
 *
 * Our best guess at what Eloqua wants, based on this appalling document:
 * https://docs.oracle.com/cloud/latest/marketingcs_gs/OMCAA/Help/EloquaAsynchronousTrackingScripts/Tasks/TrackingImageTag.htm?Highlight=pps
 *
 * @param AMP_Post_Template $amp_template
 */
add_action( 'amp_post_template_footer', function ( $amp_template ) {
	?><amp-pixel
		src="https://s1777052651.t.eloqua.com/visitor/v200/svrGP?pps=3&siteid=1777052651&ref=AMPDOC_URL"
    	layout="nodisplay"
	></amp-pixel><?php
});


/**
 * Add the SVG sprite to the footer
 *
 * @param AMP_Post_Template $amp_template
 */
add_action( 'amp_post_template_footer', function ( $amp_template ) {
	?><div style="display: none">
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
	</div><?php
});


/**
 * Remove the broken template Co-Authors Plus uses to override AMP's meta-author.php
 * template https://github.com/Automattic/Co-Authors-Plus/issues/360.
 */
add_action( 'pre_amp_render_post', function () {
	if ( function_exists( 'get_coauthors' ) ) {
		// Remove the borken Co-Authors Plus template
		remove_filter( 'amp_post_template_file', 'cap_set_amp_author_meta_template', 10 );

		// Add our own Co-Authors compatible template
		add_filter( 'amp_post_template_file', function ( $file, $template_type, $post ) {
			if ( function_exists( 'get_coauthors' ) && ( 'meta-author.php' === array_pop( explode('/', $file ) ) ) ) {
				return sprintf( '%s/inc/amp/meta-author.php', get_template_directory() );
			}

			return $file;
		}, 10, 3);

		// Override the CSS for better styling of multiple authors
		// FIXME abstract this to a stylesheet
		add_action( 'amp_post_template_css', function ( $amp_template ) {

			// Relative font URLs don't work as they do in style.css because the
			// stylesheet is inline, and its location changes with each page.
			$fonts = get_stylesheet_directory_uri() . '/fonts';
			?>
			@font-face{
				font-family:flamamedium;
				font-weight:400;
				font-style:normal;
				src:url(<?php echo esc_html( $fonts ); ?>/flama-medium-webfont.eot);
				src:url(<?php echo esc_html( $fonts ); ?>/flama-medium-webfont.eot?#iefix) format("embedded-opentype"),
				url(<?php echo esc_html( $fonts ); ?>/flama-medium-webfont.woff) format("woff"),
				url(<?php echo esc_html( $fonts ); ?>/flama-medium-webfont.ttf) format("truetype"),
				url(<?php echo esc_html( $fonts ); ?>/flama-medium-webfont.svg#flamamedium) format("svg")
		   	}
			@font-face {
				font-family:"SophosSansLight";
				font-weight:300;
				font-style:normal;
				src:url(<?php echo esc_html( $fonts ); ?>/sophossans-light.woff2) format("woff2"),
					url(<?php echo esc_html( $fonts ); ?>/sophossans-light.woff) format("woff"),
					url(<?php echo esc_html( $fonts ); ?>/sophossans-light.ttf) format("truetype");
			}

			@font-face {
				font-family:"SophosSansRegular";
				font-weight:300;
				font-style:normal;
				src:url(<?php echo esc_html( $fonts ); ?>/sophossans-regular.woff2) format("woff2"),
					url(<?php echo esc_html( $fonts ); ?>/sophossans-regular.woff) format("woff"),
					url(<?php echo esc_html( $fonts ); ?>/sophossans-regular.ttf) format("truetype");
			}

			@font-face {
				font-family:"SophosSansMedium";
				font-weight:300;
				font-style:normal;
				src:url(<?php echo esc_html( $fonts ); ?>/sophossans-medium.woff2) format("woff2"),
					url(<?php echo esc_html( $fonts ); ?>/sophossans-medium.woff) format("woff"),
					url(<?php echo esc_html( $fonts ); ?>/sophossans-medium.ttf) format("truetype");
			}

			<?php
			echo strip_tags( file_get_contents( sprintf( '%s/css/header.amp.css', get_stylesheet_directory() ) ) );
			echo strip_tags( file_get_contents( sprintf( '%s/css/ad.amp.css', get_stylesheet_directory() ) ) );

			# Temporary override to set header width
			?>
			header.site-header .header-container {
				max-width: 840px;
			}

			<?php
		});
	}
});
