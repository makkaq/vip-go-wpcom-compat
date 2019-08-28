<?php

namespace Sophos;

/**
 * Class for representing a language
 *
 * This is a class for handling languages - an instance of the class represents a single language.
 * Languages can be created with a number of different constructors and language codes can be formatted
 * in patterns used by Sophos (e.g. en-us) or Wordpress (e.g. en_US).
 */
class Language {


	/**
	 * Meta key for storing user language preference
	 */
	const USER_META_KEY = 'sophos-language';


	/**
	 * The very last resort if we MUST have a language
	 */
	const DEFAULT_LANGUAGE = 'en-us';


	/**
	 * Matches 2 (en), 4 character (en-us) and numeric (es-419) codes without matching Wordpress wp-* slugs
	 */
	const LOCALE_PATTERN = '/^\w\w([-_](\w\w|\d{1,3}))?$/';


	/**
	 * ISO languge or locale in lowercase with a dash as the separator e.g. en-us
	 *
	 * @var string
	 */
	private $code = null;


	/**
	 * Constructor
	 *
	 * @constructor
	 * @throws \Sophos\Exception\InvalidLanguageCode
	 * @param string $iso Language or locale in a recognisible ISO format
	 * @return \Sophos\Language|false
	 */
	public function __construct( $iso ) {
		if ( preg_match( self::LOCALE_PATTERN, $iso ) ) {
			$this->code = str_replace( '_', '-', strtolower( $iso ) );
		} else {
			throw new \Sophos\Exception\InvalidLanguageCode( "$iso is not a valid language code" );
		}
	}


	/**
	 * Construct a \Sophos\Language object from the Accept-Language header
	 *
	 * @constructor
	 * @throws \Exception
	 * @return \Sophos\Language|false
	 */
	public static function from_http_headers() {
		if ( \Sophos\Utils\is_wp_cli() ) {
			return false;
		}

		if ( function_exists( 'locale_accept_from_http' ) ) {
			$iso = filter_input( INPUT_SERVER, 'HTTP_ACCEPT_LANGUAGE', FILTER_CALLBACK, [
				'options' => function ( $value ) {
					return locale_accept_from_http( $value );
				},
			]);

			try {
				return new self( $iso );
			} catch ( \Sophos\Exception\InvalidLanguageCode $e ) {
				return false;
			}
		} else {
			throw new \Exception( 'Could not find function locale_accept_from_http' );
		}
	}


	/**
	 * Get a language from a URL path
	 *
	 * @constructor
	 * @param  string $url
	 * @return \Sophos\Language|false
	 */
	public static function from_url( $url = null ) {
		$u    = new \Sophos\URL( $url );
		$path = explode( '/', trim( wp_parse_url( $u->to_string(), PHP_URL_PATH ), '/' ) );

		try {
			return new self( array_shift( $path ) );
		} catch ( \Sophos\Exception\InvalidLanguageCode $e ) {
			return false;
		}
	}


	/**
	 * Get a language from user meta
	 *
	 * @constructor
	 * @return \Sophos\Language|false
	 */
	public static function from_user_setting() {
		try {
			$setting = \Sophos\User\get_meta( get_current_user_id(), self::USER_META_KEY );
			return new self( $setting );
		} catch ( \Sophos\Exception\InvalidLanguageCode $e ) {
			return false;
		}
	}


	/**
	 * Get the default locale
	 *
	 * @constructor
	 * @param  string $url
	 * @return \Sophos\Language|false
	 */
	public static function from_default() {
		$iso = ( defined( 'WPLANG' ) && WPLANG ) ? WPLANG : self::DEFAULT_LANGUAGE;

		try {
			return new self( $iso );
		} catch ( \Sophos\Exception\InvalidLanguageCode $e ) {
			return false;
		}
	}


	/**
	 * The the language code in its default format
	 *
	 * Language codes are returned in the format [a-z]{2}(-[a-z]{2})?, e.g. en or en-us
	 *
	 * @return string ISO language code
	 */
	public function format_for_sophos() {
		return $this->code;
	}


	/**
	 * The the language code in a Wordpress-friendly format
	 *
	 * Language codes are returned in the format [a-z]{2}(_[A-Z]{2})?, e.g. en or en_US
	 *
	 * @return string ISO language code
	 */
	public function format_for_wordpress() {
		if ( strpos( $this->code, '-' ) ) {
			list( $language, $territory ) = explode( '-', $this->code );
			return sprintf( '%s_%s', $language, strtoupper( $territory ) );
		}

		return $this->code;
	}


	/**
	 * The the language code in a Wordpress VIP-friendly format
	 *
	 * Language codes are returned in the format [a-z]{2}(_[A-Z]{2})?, e.g. en or en_US
	 *
	 * @return string ISO language code
	 */
	public function format_for_wordpress_vip() {
		$region = \Sophos\Region\Data::instance( $this );

		return ( $region instanceof \Sophos\Region\Data )
			? $region->wordpress_vip_locale()
			: $this->code;
	}


	/**
	 * The the language code in an appropriate format for a News Sitemap
	 *
	 * Language codes for News Sitemaps, in line with Google documentation.
	 * See: https://www.google.com/schemas/sitemap-news/0.9/sitemap-news.xsd
	 *
	 * Language codes are returned in the format [a-z]{2}, e.g. en, APART FROM CHINESE
	 *
	 * @return string ISO language code
	 */
	public function format_for_news_sitemap() {
		if ( strpos( $this->code, '-' ) ) {
			list( $language, $territory ) = explode( '-', $this->code );

			if ( 'zh' === $language ) {
				return $this->format_for_sophos();
			}

			return $language;
		}

		return $this->code;
	}
}
