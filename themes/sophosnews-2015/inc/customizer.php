<?php

/**
 * Register a customizer section for this theme
 *
 * Currently the only option being set is the site breaking news panel.
 * Disabled by default
 *
 * @param $wp_customize
 */
function sophos_customize_register( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	$wp_customize->remove_section( 'background_image' );
	$wp_customize->remove_section( 'colors' );

	$wp_customize->add_setting(
		'sophos_breaking_news', [
			'default'   => 'disable',
			'transport' => 'refresh',
		]
	);

	$wp_customize->add_section(
		'sophos_customizer_section', [
			'title'    => esc_html__( 'Sophos Theme Options', 'forward' ),
			'priority' => 30,
		]
	);

	$choices = [ 'disable' => esc_html__( 'None', 'forward' ) ];

	// Build select box options from the last 30 published posts
	$posts_last_30_days = new WP_Query(
		[
			'posts_per_page' => 30,
			'date_query'     => [
				[
					'column' => 'post_date_gmt',
					'after'  => '1 month ago',
				],
			],
		]
	);

	if ( $posts_last_30_days->have_posts() ) {
		while ( $posts_last_30_days->have_posts() ) {
			$posts_last_30_days->the_post();
			$choices[ get_the_ID() ] = esc_html( get_the_title() );
		}
	}
	wp_reset_postdata();

	$wp_customize->add_control(
		'sophos_takeover_advert', [
			'label'    => esc_html__( 'Breaking News Post', 'forward' ),
			'section'  => 'sophos_customizer_section',
			'settings' => 'sophos_breaking_news',
			'type'     => 'select',
			'choices'  => $choices,
		]
	);
}

add_action( 'customize_register', 'sophos_customize_register' );