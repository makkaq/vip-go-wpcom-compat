<?php

namespace Sophos\Translation;

class Utils {
    
    /**
     * Validate a string as a language code we can work with
     * 
     * @param string $iso
     * @return bool
     */
    public static function validateLanguageISOCode ($iso) {
        return preg_match( '([a-z][a-z](?:-[A-Z][A-Z])?)', $iso );
    }
    
    /**
     * Get the site's Google API key
     * 
     * @return string
     */
    public static function getGoogleAPIKey() {
        return get_option('cet_google_api_key');
    }
}