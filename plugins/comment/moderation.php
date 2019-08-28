<?php

/*
 Plugin Name: Moderation
 Plugin URI: https://nakedsecurity.sophos.com
 Description: Enforce comment moderation for all users
 Version: 0.1
 Author: Mark Stockley (Compound Eye Ltd)
 Author URI:
 License: GPL3

 Copyright 2018  Mark Stockley  (email : mark@compoundeye.co.uk)

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
 * @package   Sophos
 * @author    Mark Stockley
 * @version   0.1
 * @copyright Copyright (c) 2014, Mark Stockley
 * @license   https://opensource.org/licenses/gpl-3.0.html
 */

namespace Sophos\Comment;


/**
 * Enforce comment moderation on Contributors
 *
 * By default Wordpress allows writers to comment on their own posts without
 * moderation REGARDLESS OF THE MODERATION RULES. This just won't do.
 *
 * See https://core.trac.wordpress.org/ticket/6907
 *
 * @param  [mixed] $approved    Preliminary comment approval status: 0, 1, 'trash', or 'spam'.
 * @param  [array] $commentdata Comment data array
 * @return [mixed]              Comment approval status
 */
function enforce_moderation( $approved, $commentdata ) {

	if ( array_key_exists( 'user_ID', $commentdata ) && ! empty( $commentdata['user_ID'] ) ) {
		$user_id   = (int) $commentdata['user_ID'];
		$author_id = (int) get_post_field( 'post_author', $commentdata['comment_post_ID'] );

		$data = get_userdata( $user_id );

		if ( $author_id === $user_id && ! user_can( $user_id, 'moderate_comments' ) ) {
			return 0;
		}
	}

	return $approved;
}

add_filter( 'pre_comment_approved', '\Sophos\Comment\enforce_moderation' , '99', 2 );
