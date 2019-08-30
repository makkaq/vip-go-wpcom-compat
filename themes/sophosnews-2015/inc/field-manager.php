<?php

/**
 * Generate Custom Fields for Advertisements (sophos_adverts post type)
 *
 * - Destination URL
 * - Banner Image (000x000)
 * - Card Image (000x000)
 */
function sophos_advert_field_manager_definitions() {

	$fm = new Fieldmanager_Group(
		[
			'name'     => 'sophos_advert_fields',
			'children' => [
				'destination_url' => new Fieldmanager_TextField( esc_html__( 'Destination URL', 'forward' ) ),
				'banner_image'    => new Fieldmanager_Media( esc_html__( 'Banner Image', 'forward' ) ),
				'card_image'      => new Fieldmanager_Media( esc_html__( 'Card Image', 'forward' ) ),
			]
		]
	);
	$fm->add_meta_box( esc_html__( 'Advertisement', 'forward' ), 'sophos_advert' );
}

add_action( 'fm_post_sophos_advert', 'sophos_advert_field_manager_definitions' );

/**
 * Generate Custom Fields for the Video of the Week (sophos_video post type)
 *
 * - YouTube Video URL
 * - Video Length
 * - Static Display Image
 */
function sophos_video_field_manager_definitions() {
	$fm = new Fieldmanager_Group(
		[
			'name'     => 'sophos_video_fields',
			'children' => [
				'youtube_video_url'    => new Fieldmanager_TextField( esc_html__( 'YouTube Video URL', 'forward' ) ),
				'video_length'         => new Fieldmanager_TextField( esc_html__( 'Video Length', 'forward' ) ),
				'static_display_image' => new Fieldmanager_Media( esc_html__( 'Static Display Image', 'forward' ) )
			]
		]
	);
	$fm->add_meta_box( esc_html__( 'Advertisement', 'forward' ), 'sophos_video' );
}

add_action( 'fm_post_sophos_video', 'sophos_video_field_manager_definitions' );
