<?php

/*
 Plugin Name: Newsletter
 Plugin URI: https://nakedsecurity.sophos.com
 Description: Naked Security newsletter signup
 Version: 0.1
 Author: Mark Stockley (Compound Eye Ltd)
 Author URI:
 License: GPL3

 Copyright 2013  Mark Stockley  (email : mark@compoundeye.co.uk)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 2, as
 published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * @package   newsletter
 * @author    Mark Stockley
 * @version   0.1
 * @copyright Copyright (c) 2014, Mark Stockley
 * @license   https://opensource.org/licenses/gpl-3.0.html
 */

namespace Sophos;

/**
 * Sign up to Naked Security's Mailchimp-driven daily news
 */
class Newsletter {
	/**
	 * Array key for email address
	 */
	const EMAIL = "email";

	/**
	 * Array key for nonce
	 */
	const NONCE = "nonce";

	/**
	 * Action for wp_verify_nonce
	 */
	const ACTION = 'newsletter-nonce';

	/**
	 * Singleton instance of \Sophos\Newsletter
	 *
	 * @var $_instance
	 */
	private static $_instance = null;

	/**
	 * Singleton constructor
	 *
	 * @return \Sophos\Newsletter
	 */
	public static function run() {
		return ( self::$_instance instanceof \Sophos\Newsletter ) ? self::$_instance : new self();
	}

	/**
	 * Private constructor
	 *
	 * Setup ajax handlers
	 */
	private function __construct() {
		add_action( 'wp_ajax_newsletter_subscribe', [ $this, 'subscribe' ] );
		add_action(
			'wp_ajax_nopriv_newsletter_subscribe', [
			$this,
			'subscribe'
		]
		);
	}

	/**
	 * Ajax handler
	 *
	 * @throws Exception
	 */
	public function subscribe() {
		if ( isset( $_POST[ self::EMAIL ] ) && isset( $_POST[ self::NONCE ] ) ) {
			$nonce    = filter_input( INPUT_POST, self::NONCE, FILTER_SANITIZE_STRING );
			$email    = filter_input( INPUT_POST, self::EMAIL, FILTER_VALIDATE_EMAIL );
			$response = '';

			if ( ! wp_verify_nonce( $nonce, self::ACTION ) ) {
				$response = - 1;
			}

			if ( ! is_email( $email ) ) {
				$response = 0;
			}

			try {
				$dc   = 'us2';
				$list = '31623bb782';
				$key  = 'a535f8250292e4307fabc8ab8f202939-us2';
				$body = wp_json_encode([
					'email_address' => $email,
				    'status'		=> 'pending',
					'merge_fields'	=> [
						'TGROUP' => wp_rand(1,4)
					]
				]);

				if ( false === $body ) {
					throw new \Exception( 'Unable to JSON encode data' );
				}

				$chimp = wp_remote_post( "https://$dc.api.mailchimp.com/3.0/lists/$list/members/", array(
								'body' => $body,
								'headers' => [
									'Authorization' => 'Basic ' . base64_encode( uniqid() . ':' . $key )
								]
							));

				if ( is_wp_error( $chimp ) ) {
					throw new \Exception( $chimp->get_error_message() );
				}

				if ( 200 !== $chimp[ 'response' ][ 'code' ] ) {
					throw new \Exception( $chimp[ 'response' ][ 'code' ] . ' ' . $chimp[ 'response' ][ 'message' ] );
				}

				$response = 1;
				$this->setCookie();

			} catch ( \Exception $e ) {
				header( sprintf( 'HTTP/1.1 500 %s', $e->getMessage() ) );
			}

			esc_html_e( $response );
		}

		exit;
	}

	/**
	 * Set a cookie so we don't ask subscribers to sign up again
	 */
	public function setCookie() {
		$ten_years = 60 * 60 * 24 * 3652;
		$expires   = time() + $ten_years;
		$cookie    = setcookie( "newsletter", 1, $expires, "/" );
	}

	/* This function exist because of this advice from Yoav at VIP: Typically the
	 * ajaxurl js variable would work the way you've set it up, but in an environment
	 * where you're dealing with domain mapping and possibly forced ssl admin,
	 * there's room for issues. Function based on https://gist.github.com/1155395
	 */
	public static function safeAdminURL( $file ) {
		$path = '/wp-admin/' . $file;
		if ( is_admin() ) {
			$url = site_url( $path ); // admin uses non-mapped urls like abc.wordpress.com
		} else {
			$url = home_url( $path ); // for domain-mapped sites like mysite.com
		}

		return $url;
	}
}

\Sophos\Newsletter::run();
