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

        $canonical_host = 'news-sophos-develop.go-vip.net';
        $request_uri    = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL );
        $http_host      = filter_input( INPUT_SERVER, 'HTTP_HOST', FILTER_CALLBACK, [
            'options' => function ( $domain ) {
                if ( in_array( $domain, [
                    'sophosbenelux.com',
                    'www.sophosbenelux.com',
                    'sophosbenelux.be',
                    'www.sophosbenelux.be',
                    'blog.sophos.be',
                    'sophosblog.de',
                    'www.sophosblog.de',
                    'blog.sophos.de',
                    'sophosfranceblog.fr',
                    'www.sophosfranceblog.fr',
                    'blog.sophos.fr',
                    'sophositalia.it',
                    'www.sophositalia.it',
                    'sophositalia.com',
                    'www.sophositalia.com',
                    'blog.sophos.it',
                    'sophosiberia.es',
                    'www.sophosiberia.es'
                ], true ) ) {
                    return $domain;
                }

                return false;
            }
        ]);

        // Don't redirect if the host check failed
        if ( false === $http_host ) {
            return false;
        }

        // Don't redirect for '/cache-healthcheck?' or monitoring will break
        if ( '/cache-healthcheck?' === $request_uri ) {
            return false;
        }

        // Don't redirect in WP CLI context
        if ( ( defined( 'WP_CLI' ) && WP_CLI ) ) {
            return false;
        }

        header( 'Location: https://' . $canonical_host . $request_uri, true, 301 );
        exit;
    }

    redirect_legacy_domains();
};
