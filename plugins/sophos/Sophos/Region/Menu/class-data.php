<?php

namespace Sophos\Region\Menu;


/**
 * Get a list of unregionalised menu slugs
 *
 * @return array
 */
function slugs() {
	return [ 'primary', 'secondary', 'tertiary', 'about', 'popular', 'community', 'social', 'work', 'support', 'legal' ];
}


/**
 * Create a regionalised menu slug
 *
 * @param  string $slug Menu slug
 * @return string|WP_Error
 */
function slug( $slug, $language = null ) {

	if ( ! \Sophos\Utils\is_regionalised() && ! \Sophos\Utils\is_wp_cli() ) {
		return $slug;
	}

	if ( is_null( $language ) ) {
		$language = new \Sophos\Language( \Sophos\Region::guess() );
	}

	if ( ! $language instanceof \Sophos\Language ) {
		return new \WP_Error( 'invalid_language_object', 'A language parameter was supplied but it was not a \Sophos\Language object' );
	}

	return sprintf( '%s-%s', $language->format_for_sophos(), $slug );
}


/**
 * Test if a regionalised version of a menu exists
 *
 * @param  [string] $slug Menu slug
 * @return [bool]
 */
function exists( $slug ) {

	$slug = \Sophos\Region\Menu\slug( $slug );
	$menu = wp_get_nav_menu_object( $slug );

	return (bool) $menu;
}


/**
 * Regional menu data base class
 */
abstract class Data {


	/**
	 * The menu name
	 *
	 * The NAME constant needs to defined in each sub class
	 */
	const NAME = '';


	/**
	 * Menu language
	 *
	 * @var [\Sophos\Language]
	 */
	public $language = '';


	/**
	 * Menu slug
	 *
	 * @var [string]
	 */
	public $slug = '';


	/**
	 * Constructor
	 *
	 * @param \Sophos\Language $language
	 */
	public function __construct( \Sophos\Language $language ) {
		if ( ! defined( 'static::NAME' ) ) {
			throw new \Exception( sprintf( 'Constant NAME is not defined in the class %s', get_class( $this ) ) );
		}

		$this->language = $language;
		$this->slug     = \Sophos\Region\Menu\slug( static::NAME, $language );
	}


	/**
	 * Create a menu from the data in this menu data class
	 *
	 * @return [int|false] Number of items added or false if menu already exists
	 */
	public function into_menu() {

		if ( wp_get_nav_menu_object( $this->slug ) ) {
			return false;
		}

		$n  = 0;
		$id = wp_create_nav_menu( $this->slug );

		if ( ! is_wp_error( $id ) ) {
			foreach ( $this->items() as $item ) {
				$n = $n + $this->add_menu_item( $id, $item );
			}
		} else {
			throw new \Exception( 'Menu creation failed: ' . $id->get_error_message() );
		}

		return $n;
	}


	/**
	 * Add an item and its children to a menu
	 *
	 * @param int $menu Menu ID
	 * @param array $item Array representing a menu item
	 * @param int $parent Parent menu item ID
	 * @return int Number of items added
	 */
	private function add_menu_item( $menu, $item, $parent = 0 ) {
		$item['menu-item-status']    = 'publish';
		$item['menu-item-parent-id'] = $parent;

		$n  = 0;
		$id = wp_update_nav_menu_item( $menu, 0, $item ); // 0 triggers creation of new menu item

		if ( ! is_wp_error( $id ) ) {
			$n++;

			if ( array_key_exists( 'menu-item-children', $item ) ) {
				foreach ( $item['menu-item-children'] as $child ) {
					$i = $this->add_menu_item( $menu, $child, $id );
					$n = $n + $i;
				}
			}
		} else {
			throw new \Exception( 'Menu item creation failed: ' . $id->get_error_message() );
		}

		return $n;
	}
}
