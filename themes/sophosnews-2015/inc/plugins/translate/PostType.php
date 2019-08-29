<?php

namespace Sophos\Translation;

class PostType {
    
    const DOMAIN    = 'translation_plugin';
    const POST_TYPE = 'translation';
    const TAXONOMY  = 'languages';
    const QUERY_VAR = 'language_iso_code'; // Was this supposed to be translation_iso_code?
    
    /**
     * Singleton instance
     * 
     * @var $_instance
     */
    private static $_instance = null;
    
    /**
     * Register Translation post type and Languages taxonomy
     * 
     * @return object Singleton instance of \Sophos\Translation\PostType
     */
    public static function register () {
        register_taxonomy( self::TAXONOMY, array('post', self::POST_TYPE), array(
            'hierarchical'          => false,
            'labels'                => array(
                'name'              => _x( 'Languages', 'taxonomy general name', self::DOMAIN ),
                'singular_name'     => _x( 'Language', 'taxonomy singular name', self::DOMAIN),
                'search_items'      => __( 'Search Languages', self::DOMAIN ),
                'all_items'         => __( 'All Languages', self::DOMAIN ),
                'parent_item'       => __( 'Parent Language', self::DOMAIN ),
                'parent_item_colon' => __( 'Parent Language:', self::DOMAIN ),
                'edit_item'         => __( 'Edit Language', self::DOMAIN ),
                'update_item'       => __( 'Update Language', self::DOMAIN ),
                'add_new_item'      => __( 'Add New Language', self::DOMAIN ),
                'new_item_name'     => __( 'New Language Name', self::DOMAIN ),
                'menu_name'         => __( 'Languages', self::DOMAIN ),
            ),
            'show_ui'               => true,
            'show_admin_column'     => false,
            'query_var'             => 'translation_iso_code', // is this actually used?
            'rewrite'               => false
	));
        
        register_post_type( self::POST_TYPE, array(
            'menu_position' => 5, // positions this just below posts in the menu
            'public'        => true,
            'has_archive'   => true,
            'taxonomies'    => array( 'category', 'post_tag', self::TAXONOMY ),
            'supports'      => array('title','editor','author','thumbnail','excerpt','revisions'),
            'labels'        => array(
                'name'          => __( 'Translations', 'sophos_translation' ),
                'singular_name' => __( 'Translation', 'sophos_translation' )
            ),
        ));
        
        $singleton = ( self::$_instance instanceof \Sophos\Translation\PostType ) ? self::$_instance : new self();
        $singleton->_addLanguagesToTaxonomy();

        return $singleton;
    }

    /**
     * Populate the Languages taxonomy
     * 
     * @throws \Exception
     */
    private static function _addLanguagesToTaxonomy () {
        $languages = get_terms( self::TAXONOMY );
        if ( empty($languages) ) {
            try {
				$api       = new \Google();
                $languages = $api->languages(get_bloginfo('language'));

                foreach ($languages as $language) {
                    $iso  = $language['language'];
                    $name = $language['name'];
                    if ( !term_exists($iso, self::TAXONOMY) ) {
                        wp_insert_term($iso, self::TAXONOMY, array('description' => $name, 'slug' => sprintf('iso-639-1-%s', $iso) ));
                    }
                }			
			} catch (\Exception $e) {
				if ( false === WPCOM_IS_VIP_ENV )
					trigger_error( $e->getMessage(), E_USER_WARNING );
			}
        }
    }
}
