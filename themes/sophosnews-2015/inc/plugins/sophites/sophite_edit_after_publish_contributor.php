<?php

namespace Sophos\Role;

/**
 * Create a Contributor that can upload images and edit posts after they've
 * been published
 */
class EditAfterPublishContributor extends \Sophos\Role\ImageContributor {

	/**
	 * Role name
	 *
	 * @var string
	 */
	protected $_name = 'sophite_edit_after_publish_contributor';

	/**
	 * Role description
	 *
	 * @var string
	 */
	protected $_description = 'Sophos Contributor (with edit after publish)';

	/**
	 * Create the role
	 *
	 * @return WP_Role
	 */
	public function create() {
		$sophite_image_contributor = $this->get_capabilities( 'sophite_image_contributor' );
		$capabilities              = array_merge( $sophite_image_contributor, [ 'edit_published_posts' => true ] );

		return $this->add_role( $this->name(), $this->description(), $capabilities );
	}
}  