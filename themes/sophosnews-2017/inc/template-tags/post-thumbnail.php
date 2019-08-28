<?php

if ( ! function_exists( 'sophos_get_the_post_thumbnail' ) ) :
	/**
 * Display the post thumbnail
 */
	function sophos_get_the_post_thumbnail( $id = null ) {
		$post = get_post( $id );
		if ( ! $post ) {
			return '';
		}
		$post_thumbnail_id = get_post_thumbnail_id( $post );
		if ( ! $post_thumbnail_id ) {
			return '';
		}

		$image_data = wp_get_attachment_image_src( $post_thumbnail_id, 'post-thumbnail' );
		return is_array( $image_data ) ? $image_data[0] : '';
	}
endif;
