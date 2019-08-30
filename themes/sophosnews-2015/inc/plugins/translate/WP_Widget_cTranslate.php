<?php
class cTranslate_Widget extends WP_Widget
{
	public function __construct()
	{
		parent::__construct(
	 		'ctranslate_widget',                                // ID
			'Translations',                                     // Name
			array( 'description' => __( 'Translations' ), )     // Args
		);
	}

	public function widget( $args, $instance )
	{
	    $post_id = get_the_ID();

	    if ( is_numeric( $post_id ) )
	    {
	    	if ( is_single() )
	        {
	            $master_id = ( get_post_type( $post_id ) === C_POST_TYPE ) ? get_post_meta( $post_id, 'cet_has_master_with_id', true ) : $post_id ;

	            if ( !empty( $master_id ) and ( get_post_type( $master_id ) === 'post' ) )
	            {
	                $ids = get_post_meta( $master_id, '_cet_has_translation_with_id', false );
	                $ids = array_unique( array_filter( $ids ) ); // make sure we have an array of unique valid IDs

	                if ( count( $ids ) )
	                {
                        $li = null;

                        foreach ( $ids as $id ) {
	                        $translation = get_post( $id );

	                        if ( !is_null( $translation ) && is_object( $translation ) && ('manual' !== get_post_meta( $id, 'cet_translator', true )))
	                        {
	                            if ( get_post_type( $translation->ID ) === C_POST_TYPE )
	                            {
	                                if ( $translation->post_status === 'publish' )
	                                {
	                                    $title = $translation->post_title;
	                                    $href  = get_permalink( $translation->ID );

	                                    $li .= "<li><a href=\"$href\">$title</a></li>";
	                                }
	                            }
	                        }
	                    }

	                    if ( !is_null( $li ) )
	                    {
	                        $heading = __( 'Translations', 'sophosnews' );
	                        echo "<div class=\"ctranslate\"><h3 class=\"widget-title\">$heading</h3><ul>$li</ul></div>";
	                    }
	                }
	            }
	        }
	    }
	}
}

add_action( 'widgets_init', function() {
	register_widget( 'cTranslate_Widget' );
} );
?>
