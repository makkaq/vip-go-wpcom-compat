<?php
/**
 * Jetpack Compatibility File
 * See: https://jetpack.me/
 *
 * @package nakedsecurity
 */

/**
 * Add theme support for Infinite Scroll.
 * See: https://jetpack.me/support/infinite-scroll/
 */
function sophos_jetpack_setup() {
	add_theme_support(
		'infinite-scroll',
		[
			'container' => 'main',
			'footer'    => 'page',
		]
	);
}

add_action( 'after_setup_theme', 'sophos_jetpack_setup' );
