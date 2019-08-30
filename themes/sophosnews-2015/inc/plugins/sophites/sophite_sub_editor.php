<?php

namespace Sophos\Role;

/**
 * Create an editor who can edit others' work but can't publish or delete it
 */
class SubEditor extends Sophite {
	/**
	 * Role name
	 *
	 * @var string
	 */
	protected $_name = 'sophite_sub_editor';

	/**
	 * Role description
	 *
	 * @var string
	 */
	protected $_description = 'Sophos Editor (without publish)';

	/**
	 * Create the role
	 *
	 * @return WP_Role
	 */
	public function create() {
		$capabilities = $this->get_capabilities( 'editor' );
		$caps         = [
			'moderate_comments' 	 => false,
			'delete_others_pages' 	 => true,
			'delete_others_posts' 	 => true,
			'delete_private_pages' 	 => true,
			'delete_private_posts' 	 => true,
			'delete_published_pages' => true,
			'delete_published_posts' => true,
			'publish_pages'	 		 => true,
			'publish_posts' 		 => true,
		];

		foreach ( $caps as $cap => $value ) {
			// Unused capabilities must be set to false or existing roles won't
			// be updated properly (see wpcom_vip_merge_role_caps)
			$capabilities[ $cap ] = $value;
		}

		return $this->add_role( $this->name(), $this->description(), $capabilities );
	}
}
