<?php

/*
 Plugin Name: Sophites
 Plugin URI: https://nakedsecurity.sophos.com
 Description: Sophos custom user types
 Version: 0.5
 Author: Mark Stockley (Compound Eye Ltd)
 Author URI:
 License: GPL3

 Copyright 2013  Mark Stockley  (email : mark@compoundeye.co.uk)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 2, as
 published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * @package   sophos
 * @author    Mark Stockley
 * @version   0.5
 * @copyright Copyright (c) 2013, Mark Stockley
 * @license   https://opensource.org/licenses/gpl-3.0.html
 */

namespace Sophos\Role;

const VERSION = 0.5;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'sophite_disabled.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'sophite_sub_editor.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'sophite_image_contributor.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'sophite_edit_after_publish_contributor.php';

/**
 * Base class for Sophos-specific roles
 */
abstract class Sophite {

	/**
	 * Name of option holding version number
	 */
	const VERSION_KEY = 'sophos_sophite_version';

	/**
	 * Role name
	 *
	 * @var string
	 */
	protected $_name;

	/**
	 * Role description
	 *
	 * @var string
	 */
	protected $_description;

	/**
	 * Create the role if it doesn't exist or it's out of date
	 */
	public function __construct() {
		$role = $this->name();

		if ( ! empty( $role ) ) {
			$version = get_transient( self::VERSION_KEY );

			// If the transient doesn't exist or is out of date, or the role doesn't exist, create it.
            // if ( false === $version || \Sophos\Role\VERSION > (float) $version || null === get_role( $role ) ) {
            if ( 1 ) {
            	$this->create();
				set_transient( self::VERSION_KEY, \Sophos\Role\VERSION, 10 );
			}
		}
	}

	/**
	 * Accessor for role name
	 *
	 * @return string
	 */
	public function name() {
		return $this->_name;
	}

	/**
	 * Accessor for role description
	 *
	 * @return string
	 */
	public function description() {
		return $this->_description;
	}

	abstract function create();

	/**
	 * Add a role in a platform agnostic way
	 *
	 * @param string $role
	 * @param string $name
	 * @param array  $capabilities
	 *
	 * @return WP_Role
	 */
	protected function add_role( $role, $name, $capabilities ) {
		$add = function_exists( 'wpcom_vip_add_role' ) ? 'wpcom_vip_add_role' : 'add_role';
		return $add( $role, $name, $capabilities );
	}

	/**
	 * Get a role's capabilities in a platform agnostic way
	 *
	 * @param string $name
	 *
	 * @return array
	 */
	protected function get_capabilities( $name ) {
		if ( function_exists( 'wpcom_vip_get_role_caps' ) ) {
			return wpcom_vip_get_role_caps( $name );
		} else {
			$role = get_role( $name );

			return $role->capabilities;
		}
	}
}


add_action( 'init', function () {
	new \Sophos\Role\SubEditor();
	new \Sophos\Role\ImageContributor();
	new \Sophos\Role\EditAfterPublishContributor();
	new \Sophos\Role\Disabled();
});
