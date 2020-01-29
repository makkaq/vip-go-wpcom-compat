<?php

namespace Sophos\Ad\Widget;


/**
 * Show ads at random
 */
class Random extends \Sophos\Ad\Widget {
	// Nothing to see here
}


// Filter the list of ads to one, chosen at pseudo-random
add_filter( 'sidebars_widgets', function ( $sidebars_widgets ) {

	$sidebar = \Sophos\Ad\Shortcode::$sidebar;
	if ( ! is_admin() && is_array( $sidebars_widgets ) && array_key_exists( $sidebar, $sidebars_widgets ) ) {
		$ads 	 = $sidebars_widgets[ $sidebar ];

		if ( ! empty( $ads ) ) {
			$index	 = array_rand( $ads, 1 );
			$sidebars_widgets[ $sidebar ] = [ $ads[ $index ] ];
		}
	}

	return $sidebars_widgets;
}, 100);
