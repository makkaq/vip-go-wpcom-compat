<?php
/**
 * Registering Custom Post Types
 */
function sophos_custom_post_types() {

	// Advertisement - Custom Post Type.
	$labels = [
		'name'               => esc_html_x( 'Advertisements', 'Post Type General Name', 'forward' ),
		'singular_name'      => esc_html_x( 'Advertisement', 'Post Type Singular Name', 'forward' ),
		'menu_name'          => esc_html__( 'Ads', 'forward' ),
		'name_admin_bar'     => esc_html__( 'Ad', 'forward' ),
		'parent_item_colon'  => esc_html__( 'Parent Item:', 'forward' ),
		'all_items'          => esc_html__( 'All Items', 'forward' ),
		'add_new_item'       => esc_html__( 'Add New Item', 'forward' ),
		'add_new'            => esc_html__( 'Add New', 'forward' ),
		'new_item'           => esc_html__( 'New Item', 'forward' ),
		'edit_item'          => esc_html__( 'Edit Item', 'forward' ),
		'update_item'        => esc_html__( 'Update Item', 'forward' ),
		'view_item'          => esc_html__( 'View Item', 'forward' ),
		'search_items'       => esc_html__( 'Search Item', 'forward' ),
		'not_found'          => esc_html__( 'Not found', 'forward' ),
		'not_found_in_trash' => esc_html__( 'Not found in Trash', 'forward' ),
	];
	$args   = [
		'label'               => esc_html__( 'Advertisement', 'forward' ),
		'labels'              => $labels,
		'supports'            => [ 'title', 'custom-fields', ],
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'rewrite'             => false,
		'capability_type'     => 'post',
	];
	register_post_type( 'sophos_advert', $args );

	// Video of the Week Custom Post Type.
	$labels = [
		'name'               => esc_html_x( 'Videos', 'Post Type General Name', 'forward' ),
		'singular_name'      => esc_html_x( 'Video', 'Post Type Singular Name', 'forward' ),
		'menu_name'          => esc_html__( 'Videos', 'forward' ),
		'name_admin_bar'     => esc_html__( 'Video', 'forward' ),
		'parent_item_colon'  => esc_html__( 'Parent Item:', 'forward' ),
		'all_items'          => esc_html__( 'All Items', 'forward' ),
		'add_new_item'       => esc_html__( 'Add New Item', 'forward' ),
		'add_new'            => esc_html__( 'Add New', 'forward' ),
		'new_item'           => esc_html__( 'New Item', 'forward' ),
		'edit_item'          => esc_html__( 'Edit Item', 'forward' ),
		'update_item'        => esc_html__( 'Update Item', 'forward' ),
		'view_item'          => esc_html__( 'View Item', 'forward' ),
		'search_items'       => esc_html__( 'Search Item', 'forward' ),
		'not_found'          => esc_html__( 'Not found', 'forward' ),
		'not_found_in_trash' => esc_html__( 'Not found in Trash', 'forward' ),
	];
	$args   = [
		'label'               => esc_html__( 'Video', 'forward' ),
		'labels'              => $labels,
		'supports'            => [ 'title', 'custom-fields', ],
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => 'videos',
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'rewrite'             => true,
		'capability_type'     => 'post',
	];
	register_post_type( 'sophos_video', $args );
}

add_action( 'init', 'sophos_custom_post_types', 0 );

function sophos_rewrite_flush() {
	sophos_custom_post_types();
	flush_rewrite_rules();
}

add_action( 'after_switch_theme', 'sophos_rewrite_flush' );
