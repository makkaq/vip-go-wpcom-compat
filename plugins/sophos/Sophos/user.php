<?php
/**
 * User namespace
 *
 * @package Sophos
 */

namespace Sophos\User;


/**
 * WordPress capability
 *
 * Capability used for checking if a user has admin-like powers. This exists
 * because Sophos uses custom roles so checking role names isn't robust; using
 * a single capability name means that bugs will be easier to spot and to fix.
 * The chosen capability needs to work in single AND multisite installations.
 */
const ADMIN_CAPABILITY = 'import';


/**
 * WordPress capability
 *
 * Capability used for checking if a user has editor-like powers. This exists
 * because Sophos uses custom roles so checking role names isn't robust; using
 * a single capability name means that bugs will be easier to spot and to fix.
 * The chosen capability needs to work in single AND multisite installations.
 */
const EDITOR_CAPABILITY = 'edit_others_posts';


/**
 * Platform agnostic role creation
 *
 * @param string $role
 * @param string $name
 * @param array $capabilities
 *
 * @return WP_Role
 */
function add_role( $role, $name, $capabilities ) {

	$existing = get_role( $role );

	if ( $existing instanceof \WP_Role ) {
		$diff = array_diff( $capabilities, $existing->capabilities );

		if ( empty( $diff ) ) {
			return null;
		}
	}

	$function = ( function_exists( 'wpcom_vip_add_role' ) )
		? 'wpcom_vip_add_role'
		: 'add_role';

	return $function( $role, $name, $capabilities );
}


/**
 * Platform agnostic capabilities getter
 *
 * @param string $role Role name
 *
 * @return array
 */
function get_capabilities( $role ) {
	$function = ( function_exists( 'wpcom_vip_get_role_caps' ) )
		? 'wpcom_vip_get_role_caps'
		: 'get_role';

	$response = $function( $role );

	if ( $response instanceof \WP_Role && property_exists( $response, 'capabilities' ) ) {
		return $response->capabilities;
	} else {
		return $response;
	}
}


/**
 * Get data from user attributes or user meta
 *
 * @param  int $user_id User ID
 * @param  string $meta_key User meta key
 *
 * @return string|bool
 */
function get_attribute_or_meta( $user_id, $meta_key ) {
	$function = ( function_exists( 'get_user_attribute' ) )
			  ? 'get_user_attribute'
			  : 'get_user_meta';

	return $function( $user_id, $meta_key, true );
}


/**
 * Delete data from user attributes or user meta
 *
 * @param  int $user_id User ID
 * @param  string $meta_key User meta key
 * @param  mixed (Optional) $meta_value Metadata value.
 *
 * @return string|bool
 */
function delete_attribute_or_meta( $user_id, $meta_key, $meta_value = '' ) {
	$function = ( function_exists( 'delete_user_attribute' ) )
			  ? 'delete_user_attribute'
			  : 'delete_user_meta';

	return $function( $user_id, $meta_key, $meta_value );
}


/**
 * Platform agnostic user meta getter
 *
 * Get data from Guest Author fields, user attributes or user meta
 *
 * @param  int $user_id User ID
 * @param  string $meta_key User meta key
 *
 * @return string|bool
 */
function get_meta( $user_id, $meta_key ) {
	$meta_value = false;

	if ( class_exists( 'CoAuthors_Plus' ) ) {
		$meta_value = \Sophos\UI\CoAuthors\get_coauthor_user_meta( $user_id, $meta_key );
	}

	return $meta_value ?: \Sophos\User\get_attribute_or_meta( $user_id, $meta_key );
}


/**
 * Platform agnostic user meta setter
 *
 * @param  int $user_id User ID
 * @param  string $meta_key User meta key
 * @param  mixed $meta_value User meta value
 *
 * @return int|boolean
 */
function update_meta( $user_id, $meta_key, $meta_value ) {
	$return_value = false;

	if ( class_exists( 'CoAuthors_Plus' ) ) {
		$return_value = \Sophos\UI\CoAuthors\update_coauthor_user_meta( $user_id, $meta_key, $meta_value );
	}

	if ( ! $return_value ) {
		$function = ( function_exists( 'update_user_attribute' ) )
			? 'update_user_attribute'
			: 'update_user_meta';

		$return_value = $function( $user_id, $meta_key, $meta_value );
	}

	return $return_value;
}


/**
 * Can the current user edit everything in a post?
 *
 * Roles that do not have editor or higher capabilities or the Editor (regional)
 * role should be restricted to posts only in their region.
 *
 * @param int|WP_Post|null $post Optional. Post ID or post object. Defaults to global $post.
 * @return bool true if restricted, false if not.
 */
function has_edit_restrictions( $post = null ) {
	$user_to_check = wp_get_current_user();
	$post_to_check = get_post( $post );
	$wp_user_roles = wp_roles()->role_objects;

	// Get a list of roles that don't have editor-like powers
	$wp_user_roles_restricted = array_filter(
		$wp_user_roles, function ( $role ) {
			return ! $role->has_cap( \Sophos\User\EDITOR_CAPABILITY );
		}
	);

	// Only specific roles have restrictions applied to them
	$restricted_roles = array_merge( [ 'sophite_regional_editor' ], array_keys( $wp_user_roles_restricted ) );
	if ( 0 === count( array_intersect( $user_to_check->roles, $restricted_roles ) ) ) {
		return false;
	}

	$user_region = \Sophos\User\get_meta( $user_to_check->ID, \Sophos\Language::USER_META_KEY );
	$post_region = \Sophos\Utils\get_post_region( $post_to_check );

	return (bool) ( $user_region !== $post_region->slug );
}
