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
	 * Add the AMP analytics library
	 */
	?><script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script><?php
});


/**
 * Add Google Analytics tracking code to AMP documents
 *
 * @param Array $data
 */
add_filter( 'amp_post_template_data', function ( $data ) {

	if ( array_key_exists( 'post_amp_content' , $data ) ) {

		// Note that the sophos.com profile WILL NOT RECORD QUERY STRINGS for
		// AMP URLs because we have to append '/corpblog' to the URL and there
		// is no AMP variable for the query string.
		ob_start();
		?>
		<!-- FIXME regional analytics goes here -->
		<amp-analytics type="gtag" data-credentials="include">
			<script type="application/json">
			{
				"vars": {
					"gtag_id": "UA-737537-1",
					"config": {
						"UA-737537-1": {
							"groups": "default",
							"page_location": "/corpblog/${canonicalPath}"
						},

						"UA-737537-53": {
							"groups": "default"
						}
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
