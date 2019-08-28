<?php

/**
 * Add Sophos-specific user roles
 */
function sophos_add_user_roles() {
	if ( function_exists( '\Sophos\User\add_role' ) ) {

		$editor 	 = \Sophos\User\get_capabilities( 'editor' );
		$contributor = \Sophos\User\get_capabilities( 'contributor' );

		\Sophos\User\add_role(
			'sophite_regional_editor',
			_x( 'Sophos Editor (regional)', 'Wordpress user role description that appears on the Users page', 'sophos-news' ),
			$editor
		);

		\Sophos\User\add_role(
			'sophite_sub_editor',
			_x( 'Sophos Editor (without publish)', 'Wordpress user role description that appears on the Users page', 'sophos-news' ),
			array_merge( $editor, [
				'moderate_comments'		 => false,
				'delete_others_pages' 	 => true,
				'delete_others_posts' 	 => true,
				'delete_private_pages' 	 => true,
				'delete_private_posts' 	 => true,
				'delete_published_pages' => true,
				'delete_published_posts' => true,
				'publish_pages' 		 => true,
				'publish_posts' 		 => true,
			])
		);

		\Sophos\User\add_role(
			'sophite_edit_after_publish_contributor',
			_x( 'Sophos Contributor (with edit after publish)', 'Wordpress user role description that appears on the Users page', 'sophos-news' ),
			array_merge( $contributor, [
				'upload_files' 		   => true,
				'edit_published_posts' => true,
			])
		);

		\Sophos\User\add_role(
			'sophite_image_contributor',
			_x( 'Sophos Contributor (with image upload)', 'Wordpress user role description that appears on the Users page', 'sophos-news' ),
			array_merge( $contributor, [
				'upload_files'	 	 => true,
			])
		);

		\Sophos\User\add_role(
			'sophite_enhanced_contributor',
			_x( 'Sophos Contributor (upload files and read private posts)', 'Wordpress user role description that appears on the Users page', 'sophos-news' ),
			array_merge( $contributor, [
				'upload_files'	 	 => true,
				'read_private_posts' => true,
			])
		);

		\Sophos\User\add_role(
			'disabled',
			_x( 'Sophos Disabled (user with no rights)', 'Wordpress user role description that appears on the Users page', 'sophos-news' ),
			[]
		);
	}// End if().
}
add_action( 'after_setup_theme', 'sophos_add_user_roles' );
