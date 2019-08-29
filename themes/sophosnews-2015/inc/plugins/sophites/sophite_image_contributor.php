<?php

namespace Sophos\Role;

/**
 * Create a Contributor that can upload images
 */
class ImageContributor extends Sophite {

	/**
	 * Role name
	 *
	 * @var string
	 */
	protected $_name = 'sophite_image_contributor';

	/**
	 * Role description
	 *
	 * @var string
	 */
	protected $_description = 'Sophos Contributor (with image upload)';

	/**
	 * Create the role
	 *
	 * @return WP_Role
	 */
	public function create() {
		$contributor  = $this->get_capabilities( 'contributor' );
		$capabilities = array_merge( $contributor, [ 'upload_files' => true ] );

		return $this->add_role( $this->name(), $this->description(), $capabilities );
	}
}  