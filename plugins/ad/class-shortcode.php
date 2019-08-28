<?php

namespace Sophos\Ad;


/**
 * Sophos Ad shortcode
 */
class Shortcode {

	/**
	 * Sidebar name
	 * @var string
	 */
	public static $sidebar = 'sophos-inline-ad';


	/**
	 * Index of widget shown
	 * @var int
	 */
	public $index;


	/**
	 * Setup the shortcode
	 * @return [type] [description]
	 */
	public function __construct() {

		// Register the sidebar
		add_action( 'after_setup_theme', array( '\Sophos\Ad\Shortcode', 'register' ) );

		// Add the shortcode
		add_shortcode('ad', function ( $atts ) {
			if ( is_active_sidebar( self::$sidebar ) ) :
				ob_start();
					// Load the ads sidebar. Obviously the sidebar can contain
					// any number of ads, which one is used depends on the
					// \Sophos\Ad\Widget\widget method.
					dynamic_sidebar( self::$sidebar );
				return ob_get_clean();
			endif;

			return '';
		});

		// Only allow inline ad widgets in this sidebar
		add_filter( 'sidebars_widgets', function ( $sidebars_widgets ) {
			if ( ! is_admin() && array_key_exists( self::$sidebar, $sidebars_widgets ) ) {
				$sidebars_widgets[ self::$sidebar ] = array_filter( $sidebars_widgets[ self::$sidebar ], function ( $a ) {
					return preg_match( '/^sophos_ad(-\d+)?$/', $a );
				});
			}

			return $sidebars_widgets;
		});
	}


	/**
	 * Register the sidebar
	 */
	public static function register() {
		register_sidebar( array(
			'name'          => esc_html__( 'Inline Advert', 'nakedsecurity' ),
			'id'            => self::$sidebar,
			'description'   => esc_html__( 'Widgets in this area will be shown as inline adverts.', 'nakedsecurity' ),
			'before_widget' => '<aside id="%1$s" class="widget sophos-inline-ad %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<header><h2 class="widgettitle">',
			'after_title'   => '</h2></header>',
		));
	}
}

new \Sophos\Ad\Shortcode();
