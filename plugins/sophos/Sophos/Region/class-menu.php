<?php

namespace Sophos\Region;


/**
 * Regional menu data factory
 *
 * The menu data factory provides access to classes encapsulating menu data for
 * each region. The Menu Data classes are here to provide a repeatable menu
 * creation process. After the site has been regionalised menus will be managed
 * normally via the admin interface and these classes can be removed entirely.
 */
class Menu {


	/**
	 * Private constructor
	 */
	private function __construct() { }


	/**
	 * Create a new menu data object
	 *
	 * @param [\Sophos\Language|string] $language \Sophos\Language object or ISO language code
	 * @param [string] $location Theme location for menu
	 */
	public static function instance( $language, $location ) {
		$lang  = ( ! $language instanceof \Sophos\Language )
			   ? new \Sophos\Language( $language )
			   : $language;

		$items = explode( '-', $location );
		$iso   = $lang->format_for_wordpress();
		$class = sprintf( '\Sophos\Region\Menu\%s\%s', ucfirst( array_shift( $items ) ), strtoupper( $iso ) );

		return class_exists( $class ) ? new $class( $lang ) : false;
	}
}
