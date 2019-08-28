<?php


namespace Sophos\Region {


	/**
	 * Access regional metadata
	 *
	 * FIXME reconcile with \Sophos\Region
	 */
	class Data {


		/**
		 * Google Analytics ID
		 * @var string
		 */
		protected $ua = null;


		/**
		 * Google site verification string
		 * @var string
		 */
		protected $sc = 'RHQeasQqjasbRt5asY6atZYbs5tr10gFBevDt2HRhW0';


		/**
		 * Sophos.com homepage URL
		 * @var string
		 */
		protected $home = 'https://www.sophos.com/';


		/**
		 * Wordpress VIP locale
		 * @var string
		 */
		protected $wordpress_vip_locale = '';


		/**
		 * Private constructor
		 */
		private function __construct() { }


		/**
		 * Create a new data object
		 *
		 * @param [\Sophos\Language|string] $language \Sophos\Language object or ISO language code
		 */
		public static function instance( $language ) {
			$arg = ( ! $language instanceof \Sophos\Language )
				 ? new \Sophos\Language( $language )
				 : $language;

			$region = \Sophos\Region::from_language( $arg );
			$lang   = new \Sophos\Language( $region->slug );
			$iso    = $lang->format_for_wordpress();
			$class  = sprintf( '\Sophos\Region\Data\%s', strtoupper( $iso ) );

			return class_exists( $class ) ? new $class( $lang ) : false;
		}


		/**
		 * Get the Google Analytics ID
		 * @return string
		 */
		public function google_analytics_id() {
			return $this->ua;
		}


		/**
		 * Get the Google site verification string
		 * @return string
		 */
		public function google_site_verification() {
			return $this->sc;
		}


		/**
		 * Get the sophos.com homepage URL for this region
		 * @return string
		 */
		public function sophos_homepage_url() {
			return $this->home;
		}


		/**
		 * Get the Wordpress VIP locale
		 *
		 * Wordpress normally uses language codes that match the format of
		 * language filenames, e.g. es_ES for Spanish, and which can be derived
		 * from the language codes we use in our URLs, e.g. es-es. It seems that
		 * Wordpress VIP uses a different system (two letter codes in most cases
		 * and language/territory codes in a few) and the locales we need can't
		 * be worked out, we just need to store them against each region.
		 *
		 * @return string
		 */
		public function wordpress_vip_locale() {
			return $this->wordpress_vip_locale;
		}
	}
}

namespace Sophos\Region\Data {

	class EN_US extends \Sophos\Region\Data {
		protected $home = 'https://www.sophos.com/en-us.aspx';
		protected $wordpress_vip_locale = 'en';
	}

	class FR_FR extends \Sophos\Region\Data {
		protected $ua 	= 'UA-28593094-1';
		protected $home = 'https://www.sophos.com/fr-fr.aspx';
		protected $wordpress_vip_locale = 'fr';
	}

	class DE_DE extends \Sophos\Region\Data {
		protected $home = 'https://www.sophos.com/de-de.aspx';
		protected $wordpress_vip_locale = 'de';
	}

	class ES_ES extends \Sophos\Region\Data {
		protected $ua 	= 'UA-34789247-1';
		protected $home = 'https://www.sophos.com/es-es.aspx';
		protected $wordpress_vip_locale = 'es';
	}

	class IT_IT extends \Sophos\Region\Data {
		protected $ua 	= 'UA-41423216-1';
		protected $home = 'https://www.sophos.com/it-it.aspx';
		protected $wordpress_vip_locale = 'it';
	}

	class NL_NL extends \Sophos\Region\Data {
		protected $ua = 'UA-40429393-1';
		protected $wordpress_vip_locale = 'nl';
	}

	class PT_BR extends \Sophos\Region\Data {
		protected $wordpress_vip_locale = 'pt-br';
	}

	class ES_419 extends \Sophos\Region\Data {
		protected $home = 'https://www.sophos.com/es-es.aspx';
		protected $wordpress_vip_locale = 'es';
	}

	class ZH_TW extends \Sophos\Region\Data {
		protected $home = 'https://www.sophos.com/zh-tw.aspx';
		protected $wordpress_vip_locale = 'zh-tw';
	}
}
