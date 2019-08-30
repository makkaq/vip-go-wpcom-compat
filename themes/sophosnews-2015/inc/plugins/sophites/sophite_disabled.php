<?php

namespace Sophos\Role;

/**
 * Create user role with no rights
 */
class Disabled extends Sophite {

	/**
	 * Role name
	 *
	 * @var string
	 */
	protected $_name = 'disabled';

	/**
	 * Role description
	 *
	 * @var string
	 */
	protected $_description = 'Sophos Disabled (user with no rights)';

	/**
	 * Create the role
	 *
	 * @return WP_Role
	 */
	public function create() {
		return $this->add_role( $this->name(), $this->description(), [ ] );
	}
}
