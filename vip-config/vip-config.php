<?php
/**
 * Hi there, VIP dev!
 *
 * vip-config.php is where you put things you'd usually put in wp-config.php. Don't worry about database settings
 * and such, we've taken care of that for you. This is just for if you need to define an API key or something
 * of that nature.
 *
 * WARNING: This file is loaded very early (immediately after `wp-config.php`), which means that most WordPress APIs,
 *   classes, and functions are not available. The code below should be limited to pure PHP.
 *
 * @see https://vip.wordpress.com/documentation/vip-go/understanding-your-vip-go-codebase/
 *
 * Happy Coding!
 *
 * - The WordPress.com VIP Team
 **/

namespace Sophos\URL {

    /**
     * Redirect legacy Sophos News domains
     */
    function redirect_legacy_domains () {

        $canonical_host = 'news.sophos.com';
        $request_uri    = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL );
        $language_root  = filter_input( INPUT_SERVER, 'HTTP_HOST', FILTER_CALLBACK, [
            'options' => function ( $domain ) {
                $domain_mapping = [
                    'blogs.sophos.com'        => '/en-us',
                    'sophosbenelux.com'       => '/nl-nl',
                    'www.sophosbenelux.com'   => '/nl-nl',
                    'sophosbenelux.be'        => '/nl-nl',
                    'www.sophosbenelux.be'    => '/nl-nl',
                    'blog.sophos.be'          => '/nl-nl',
                    'sophosblog.de'           => '/de-de',
                    'www.sophosblog.de'       => '/de-de',
                    'blog.sophos.de'          => '/de-de',
                    'sophosfranceblog.fr'     => '/fr-fr',
                    'www.sophosfranceblog.fr' => '/fr-fr',
                    'blog.sophos.fr'          => '/fr-fr',
                    'sophositalia.it'         => '/it-it',
                    'www.sophositalia.it'     => '/it-it',
                    'sophositalia.com'        => '/it-it',
                    'www.sophositalia.com'    => '/it-it',
                    'blog.sophos.it'          => '/it-it',
                    'sophosiberia.es'         => '/es-es',
                    'www.sophosiberia.es'     => '/es-es'
                ];

                if ( array_key_exists( $domain, $domain_mapping ) ) {
                    return $domain_mapping[ $domain ];
                }

                return false;
            }
        ]);

        // Don't redirect in WP CLI context
        if ( defined( 'WP_CLI' ) && WP_CLI ) {
            return false;
        }

        // Don't redirect if the host check failed
        if ( false === $language_root ) {
            return false;
        }

        // Don't redirect for '/cache-healthcheck?' or monitoring will break
        if ( '/cache-healthcheck?' === $request_uri ) {
            return false;
        }

        // If there's no path, use the language root
        if ( '/' === $request_uri || empty( $request_uri ) ) {
            $request_uri = $language_root;
        }

        header( 'Location: https://' . $canonical_host . $request_uri, true, 301 );
        exit;
    }

    redirect_legacy_domains();
};
