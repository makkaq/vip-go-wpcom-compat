<?php

if ( ! function_exists( 'sophos_widgets_init' ) ) :
	/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
	function sophos_widgets_init() {
		register_sidebar( array(
			'name'          => __( 'Sidebar', 'sophos-news' ),
			'id'            => 'sidebar-1',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		) );
	}
endif; // sophos_widgets_init
add_action( 'widgets_init', 'sophos_widgets_init' );
