<?php

if ( ! function_exists( 'sophos_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function sophos_setup() {

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Sophos, use a find and replace
		 * to change 'sophos-news' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'sophos-news', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => __( 'Primary Header Menu', 'sophos-news' ),
			'secondary' => __( 'Secondary Header Menu', 'sophos-news' ),
			'tertiary' => __( 'News Header Menu', 'sophos-news' ),
			'footer-social' => __( 'Footer - Social', 'sophos-news' ),
			'footer-popular' => __( 'Footer - Popular', 'sophos-news' ),
			'footer-community' => __( 'Footer - Community', 'sophos-news' ),
			'footer-work' => __( 'Footer - Work With Us', 'sophos-news' ),
			'footer-about' => __( 'Footer - About Sophos', 'sophos-news' ),
			'footer-support' => __( 'Footer - Support', 'sophos-news' ),
			'footer-legal' => __( 'Footer - Legal', 'sophos-news' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'sophos_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		/**
		 * Stop swfobject, stop Flash.
		 */
		add_action( 'wp_print_scripts', function() {
			wp_dequeue_script( 'swfobject' );
		});

		/**
		 * "This function ... can be a common source of frustration for VIP devs". Yup.
		 * https://vip.wordpress.com/functions/widont/
		 */
		remove_filter( 'the_title', 'widont', 10 );
	}
endif; // sophos_setup
add_action( 'after_setup_theme', 'sophos_setup' );
