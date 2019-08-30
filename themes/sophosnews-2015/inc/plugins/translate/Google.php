<?php

class Google
{
	const   TRANSLATE_URI = 'https://www.googleapis.com/language/translate/v2';
	const   LANGUAGES_URI = 'https://www.googleapis.com/language/translate/v2/languages';
	
        private $key;

	/**
	 * Indicates that a Google API key looks roughly ok. Note that this doesn't indicate that the API
	 * key will work, only that it looks ok.
	 * @param string $key
	 */
	public static function key_looks_ok ( $key )
	{
		return ( strlen( $key ) > 30 ) && ( strlen( $key ) < 50 ) && ( preg_match('/[a-zA-Z0-9-_]/', $key ) )
		? true
		: false;
	}

	/**
	 * Create a new Google Translate object
	 * @param string $key. Google API key
	 * @throws Exception
	 */
	public function __construct() {
            $key = Sophos\Translation\Utils::getGoogleAPIKey();
            if ( $this->key_looks_ok($key) ) {
                $this->key = $key;
            } else {
                throw new \Exception('The API key supplied is not alphanumeric');
            }
	}

	/**
	 * Get a list of languages supported by Google Translate
	 * @param string $target
	 * @throws Exception
	 */
	public function languages ( $target = null )
	{            
            if ( false === ($languages = get_transient('sophos_languages')) ) {
                $json = $this->get(self::LANGUAGES_URI, array('target' => $this->sanitise_iso_code($target)));                
                $data = json_decode($json, true);

                if ( !is_array($data) )
                    throw new \Exception('$data is not an array');
                if ( !array_key_exists('data', $data) )
                    throw new \Exception('Array key data not found');
                if ( !array_key_exists('languages', $data['data']) ) 
                    throw new \Exception('Array key languages not found');

                // Remove target language from list
                $languages = array_filter($data['data']['languages'], function ($lang) use ($target) {
                    $key = 'language';

                    if ( !is_array($lang) )               return false;
                    if ( !array_key_exists($key, $lang) ) return false;
                    if ( !isset( $lang[ $key ] ) )        return false;

                    $iso = $lang[$key];

                    if ( \Sophos\Translation\Utils::validateLanguageISOCode( $iso ) && (substr($iso,0,2 ) === substr($target,0,2)) ) {
                        return false;
                    } else {
                        return true;
                    }
                });
                
                set_transient('sophos_languages', $languages, 168 * HOUR_IN_SECONDS );
            }

            return $languages;
	}

	/**
	 * Translate some content
	 * @param array $params. See Google Translate API v2 for allowed parameters
	 * @return translated content
	 */
	public function translate (array $params )
	{
		$params   = $this->sanitiseTranslationArguments( $params );
		$json     = $this->post( self::TRANSLATE_URI, $params );
		$response = json_decode( $json, true );

		foreach ( array('data','translations',0,'translatedText') as $key )
		{
			if ( !isset( $response[$key] ) )
			{
				throw new Exception("Could not find json key $key in " . print_r( $response, true ) );
			}

			else
			{
				$response = $response[$key];
			}
		}

		return $response;
	}
    
    private function sanitise_iso_code ( $target )
    {
        /* At the time of writing Google only allowed languages codes with
         * locales for traditional and simplified chinese. If we get anything
         * else that looks like a valid language code we strip the locale
         */
        if ( is_null( $target ) or preg_match( '/^(?:[a-z][a-z]|zh-(?:CN|TW))$/', $target ) )
        {
            return $target;
        }
        elseif ( preg_match( '/^([a-z][a-z])(?:-[A-Z][A-Z])?$/', $target, $matches ) )
        {
            return $matches[1]; // language code without the locale
        }
        else
        {
            throw new Exception( "$target does not look like a valid language target (please check https://developers.google.com/translate/v2/using_rest#language-params)" );
        }
    }

	private function sanitiseTranslationArguments ( $input = array() )
	{
		$params = array();

		foreach ( $input as $key => $value )
		{
			switch ( $key )
			{
				// TODO prettyprint
				case 'source':
					$params['source'] = $this->sanitise_iso_code( $input[$key] );
					break;
				case 'target':
					$params['target'] = $this->sanitise_iso_code( $input[$key] );
					break;
				case 'text':
					$params['q']      = $input[$key];
					break;
				case 'format':
					switch ( $value )
					{
						case 'text':
							$params['format'] = 'text';
							break;
						case 'html':
							$params['format'] = 'html';
							break;
					}
					break;
			}
		}

		return $params;
	}

    private function get ( $url, $params = array() )
    {
        if ( function_exists( 'wpcom_vip_file_get_contents' ) )
        {
            $host     = parse_url( $url, PHP_URL_HOST );
            $query    = array_merge( $params, array( 'key' => $this->key ) ); 
            $url      = add_query_arg( $query, $url );	    
            $response = wpcom_vip_file_get_contents( $url, 5, 60, array(
                'obey_cache_control_header' => true,
                'http_api_args' => array(
                    'compress'    => true,
                    'decompress'  => true,
                    'method'      => 'GET',
                    'timeout'     => 5,
                    'redirection' => 5,
                    'httpversion' => '1.1',
                    'blocking'    => true,
                    'cookies'     => array(),
                    'headers'     => array(
                        'Host'       => $host,
                        'Connection' => 'close'
                    )
                )
            ));

            if ( !is_wp_error( $response ) )
            {
                return $response; // json
            }
            else
            {
                throw new Exception( "wpcom_vip_file_get_contents failed with error " . $response->get_error_message() );
            }
        }
        else
        {
            throw new Exception( "Couldn't find function wpcom_vip_file_get_contents" ); 
        }
    }    

    private function post ( $url, $params = array() )
    {
        if( function_exists( 'wp_remote_post' ) )
        {
            $host     = parse_url( $url, PHP_URL_HOST );
            $query    = array_merge( $params, array( 'key' => $this->key ) );
            $qstring  = http_build_query( $query );
            $response = wp_remote_post( $url, array( 
                            'body'       => $query,
                            'compress'   => true,
                            'decompress' => true,
                            'headers'    => array(
                                'Host'                   => $host,
                                'Connection'             => 'close',
                                'Content-Type'           => 'application/x-www-form-urlencoded',
                                'X-HTTP-Method-Override' => 'GET'
                            )
                        ));

            if( !is_wp_error( $response ) )
            {
                $status = wp_remote_retrieve_response_code( $response );

                if ( (int) $status === 200 )
                {
                    return wp_remote_retrieve_body( $response );
                }
                else
                {
                    $message = wp_remote_retrieve_response_message( $response ); 
                    throw new Exception( "$status $message" );
                } 
            }
            else 
            {
                throw new Exception( 'wp_remote_post() failed with error ' . $response->get_error_message() );
            }
        }
        else
        {
            throw new Exception( "Couldn't find function wp_remote_post" );
        }
    }
    
}

?>
