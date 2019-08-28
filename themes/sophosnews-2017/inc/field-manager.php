<?php

/**
 * Generate Custom Fields for adding specific related posts per post
 */
function sophos_related_posts() {
	$fm = new Fieldmanager_Group([
		'name' => 'related_posts',
		'children' => [
			'related_post_1' => new Fieldmanager_Autocomplete([
				'label' => 'Related Post 1',
				'show_edit_link' => true,
				'datasource' => new Fieldmanager_Datasource_Post( [
					'query_args' => [
						'post_type' => 'post',
					],
				] ),
			]),
			'related_post_2' => new Fieldmanager_Autocomplete([
				'label' => 'Related Post 2',
				'show_edit_link' => true,
				'datasource' => new Fieldmanager_Datasource_Post( [
					'query_args' => [
						'post_type' => 'post',
					],
				] ),
			]),
			'related_post_3' => new Fieldmanager_Autocomplete([
				'label' => 'Related Post 3',
				'show_edit_link' => true,
				'datasource' => new Fieldmanager_Datasource_Post( [
					'query_args' => [
						'post_type' => 'post',
					],
				] ),
			]),
			'related_post_4' => new Fieldmanager_Autocomplete([
				'label' => 'Related Post 4',
				'show_edit_link' => true,
				'datasource' => new Fieldmanager_Datasource_Post( [
					'query_args' => [
						'post_type' => 'post',
					],
				] ),
			]),
		],
	]);
	$fm->add_meta_box( esc_html__( 'Related Posts', 'sophos-news' ), [ 'post' ] );
}

add_action( 'fm_post_post', 'sophos_related_posts' );
