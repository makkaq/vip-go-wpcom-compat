<?php
/**
 * Custom translation template tags for Naked Security
 */

/**
 * Test if the current post is a translation
 * 
 * @param int $id Post ID
 * @return boolean
 */
function sophos_is_translation ($id = null) {
    return ( is_single($id) && ( get_post_type($id) === \Sophos\Translation\PostType::POST_TYPE ) ) ? 1 : 0;
}

/**
 * Test if the current post is a machine translation
 * 
 * @param int $id Post ID
 * @return boolean
 */
function sophos_is_automated_translation ($id = null) {
    return ( is_single($id) && ( get_post_type($id) === \Sophos\Translation\PostType::POST_TYPE ) && ('manual' !== get_post_meta($id ?: get_the_ID(), 'cet_translator', true )) ) ? 1 : 0;
}

/**
 * Output a credit for machine translations
 * 
 * @param int $id Post ID
 */
function sophos_translation_attribution ($id = null) {
    $by = get_post_meta($id ?: get_the_ID(), 'cet_translator', true );
    if (strtolower($by) === 'google') :
        ?><a id="attribution" class="Google" href="https://translate.google.com/"></a><?php
    endif;
}

/**
 * Return the ISO language code for the current URL
 * 
 * @return string ISO language code or false
 */
function sophos_get_language_iso () {
    if ( class_exists('\Sophos\Translation\Utils') ) {
        $path = trim($_SERVER['REQUEST_URI'], '/');
        $iso  = array_shift(explode('/', $path));

        if (\Sophos\Translation\Utils::validateLanguageISOCode($iso)) {
            return $iso;
        }
    }
  
    return false;
}

/**
 * Get keys and values to make WP_Query language sensitive
 * 
 * @return array query_vars or empty array
 */
function sophos_make_query_translatable () {
    if ( function_exists('sophos_get_language_iso') && ($iso = sophos_get_language_iso()) ) {
        return array( \Sophos\Translation\PostType::QUERY_VAR => $iso );
    }
    
    return array();
}
