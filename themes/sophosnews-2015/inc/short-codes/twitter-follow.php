<?php

namespace Sophos\Shortcode\Twitter;


add_action( 'init', function () {
	// Try to remove wordpress.com's twitter-follow shortcode
	remove_shortcode( 'twitter-follow' );

	// Add our own that renders nothing
	add_shortcode( 'twitter-follow', function ( $atts ) {
		return '';
	});
}, 999);
