<?php

if ( ! function_exists( 'sophos_google_fonts' ) ) :
	/**
 * Adds google font support.
 */
	function sophos_google_fonts() {
		$query_args = array(
		'family' => 'Source+Sans+Pro:200,300,400,600',

		/**
			* An example for changing fonts:
			* 'family' => 'Open+Sans:400,700|Oswald:700',
			* 'subset' => 'latin,latin-ext',
			*/

			);

			wp_register_style( 'source-sans', add_query_arg( $query_args, '//fonts.googleapis.com/css' ), array(), null );

			wp_enqueue_style( 'source-sans' );
	}
endif; // sophos_google_fonts

/**
 * Enable google fonts using:
 * add_action('wp_enqueue_scripts', 'sophos_google_fonts');
 */
