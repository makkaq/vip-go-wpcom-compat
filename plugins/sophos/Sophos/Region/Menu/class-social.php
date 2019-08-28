<?php

namespace Sophos\Region\Menu {

	/**
	 * Regional menu data for footer-social
	 */
	class Social extends \Sophos\Region\Menu\Data {


		/**
		 * Menu name
		 */
		const NAME = 'footer-social';


		/**
		 * Menu items
		 *
		 * @return array
		 */
		public function items() {
			return [
				[
					'menu-item-title'       => 'Facebook',
					'menu-item-url'         => 'https://www.facebook.com/securitybysophos',
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => 'Twitter',
					'menu-item-url'         => 'https://twitter.com/sophos',
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => 'YouTube',
					'menu-item-url'         => 'http://www.youtube.com/sophoslabs',
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],
			];
		}
	}
}


namespace Sophos\Region\Menu\Social {


	class EN_US extends \Sophos\Region\Menu\Social {
		// nothing to see here
	}


	class FR_FR extends \Sophos\Region\Menu\Social {

		/**
		 * Menu items
		 *
		 * @return [array]
		 */
		public function items() {
			return [
				[
					'menu-item-title'       => __( 'Facebook', 'sophos-news' ),
					'menu-item-url'         => 'https://www.facebook.com/SophosFrance',
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => __( 'Twitter', 'sophos-news' ),
					'menu-item-url'         => 'https://twitter.com/SophosFrance',
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => __( 'YouTube', 'sophos-news' ),
					'menu-item-url'         => 'https://www.youtube.com/user/CommunauteSophosFRA',
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],
			];
		}
	}


	class DE_DE extends \Sophos\Region\Menu\Social {
		// nothing to see here
	}


	class ES_ES extends \Sophos\Region\Menu\Social {

		/**
		 * Menu items
		 *
		 * @return [array]
		 */
		public function items() {
			return [
				[
					'menu-item-title'       => __( 'Facebook', 'sophos-news' ),
					'menu-item-url'         => 'https://www.facebook.com/SophosSeguridadIT',
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => __( 'Twitter', 'sophos-news' ),
					'menu-item-url'         => 'https://twitter.com/SophosIberia',
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => __( 'YouTube', 'sophos-news' ),
					'menu-item-url'         => 'https://www.youtube.com/user/SophosIberia',
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],
			];
		}
	}


	class IT_IT extends \Sophos\Region\Menu\Social {
		// nothing to see here
	}


	class NL_NL extends \Sophos\Region\Menu\Social {
		// nothing to see here
	}


	class PT_BR extends \Sophos\Region\Menu\Social {
		// nothing to see here
	}


	class ES_419 extends \Sophos\Region\Menu\Social\es_ES {
		// Do as the Spanish do
	}


	class ZH_TW extends \Sophos\Region\Menu\Social\es_ES {
		// Do as the Spanish do
	}
}
