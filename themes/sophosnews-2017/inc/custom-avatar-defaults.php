<?php

/**
 * Override avatar with custom theme avatars if the user/commenter does not
 * have a Gravatar setup.
 *
 * @param $url
 * @param $id_or_email
 *
 * @return string
 */
function sophos_get_avatar_url( $url, $id_or_email ) {
	if ( is_admin() ) {
		return $url;
	}

	if ( sophos_validate_gravatar( $id_or_email ) ) {
		return $url;
	}

	$avatar_directory = get_template_directory_uri() . '/img/avatars/';
	$avatars          = [
		'avatar-one.png',
		'avatar-two.png',
		'avatar-three.png',
	];

	$random_avatar_url = $avatar_directory . $avatars[ array_rand( $avatars, 1 ) ];

	return $random_avatar_url;
}
add_filter( 'get_avatar_url', 'sophos_get_avatar_url', 10, 3 );

/**
 * Utility function to check if a Gravatar exists for a given email or id
 *
 * @param $id_or_email
 *
 * @return bool
 */
function sophos_validate_gravatar( $id_or_email ) {
	$email = '';
	if ( is_numeric( $id_or_email ) ) {
		$id   = (int) $id_or_email;
		$user = get_userdata( $id );
		if ( $user ) {
			$email = $user->user_email;
		}
	} elseif ( is_object( $id_or_email ) ) {
		// No avatar for pingbacks or trackbacks
		$allowed_comment_types = apply_filters( 'get_avatar_comment_types', [ 'comment' ] );
		if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types, true ) ) {
			return false;
		}

		if ( ! empty( $id_or_email->user_id ) ) {
			$id   = (int) $id_or_email->user_id;
			$user = get_userdata( $id );
			if ( $user ) {
				$email = $user->user_email;
			}
		} elseif ( ! empty( $id_or_email->comment_author_email ) ) {
			$email = $id_or_email->comment_author_email;
		}
	} else {
		$email = $id_or_email;
	}

	$hashkey = md5( strtolower( trim( $email ) ) );
	$uri     = 'http://www.gravatar.com/avatar/' . $hashkey . '?d=404';

	$data = wp_cache_get( $hashkey );
	if ( false === $data ) {
		$response = wp_remote_head( $uri );
		if ( is_wp_error( $response ) ) {
			$data = 'not200';
		} else {
			$data = $response['response']['code'];
		}
		$group = 'has_gravatar';
		$expires = 60 * 5;
		wp_cache_set( $hashkey, $data, $group, $expires );
	}
	if ( 200 === $data ) {
		return true;
	} else {
		return false;
	}
}
