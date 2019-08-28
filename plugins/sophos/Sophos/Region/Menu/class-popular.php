<?php

namespace Sophos\Region\Menu {

	/**
	 * Regional menu data for footer-popular
	 */
	class Popular extends \Sophos\Region\Menu\Data {


		/**
		 * Menu name
		 */
		const NAME = 'footer-popular';


		/**
		 * Menu items
		 *
		 * @return array
		 */
		public function items() {
			$iso = $this->language->format_for_sophos();

			return [
				[
					'menu-item-title'       => _x( 'Free Trials', 'Footer link to free trials page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/products/free-trials.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Free Tools', 'Footer link to free tools page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/products/free-tools.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Whitepapers', 'Footer link to whitepapers page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/security-news-trends/whitepapers.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Technical Papers', 'Footer link to technical papers page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/threat-center/technical-papers.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Buy Online', 'Footer link to "How to Buy Sophos Products" page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/products/buy-sophos-online.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Sophos Brand Store', 'Footer link to shops.sophos.com', 'sophos-news' ),
					'menu-item-url'         => 'https://shop.sophos.com/',
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],
			];
		}
	}
}


namespace Sophos\Region\Menu\Popular {


	class EN_US extends \Sophos\Region\Menu\Popular {
		// nothing to see here
	}


	class FR_FR extends \Sophos\Region\Menu\Popular {
		// nothing to see here
	}


	class DE_DE extends \Sophos\Region\Menu\Popular {

		/**
		 * Menu items
		 *
		 * @return [array]
		 */
		public function items() {
			$menu    = parent::items();
			$menu[5] = [
				'menu-item-title'       => _x( 'Contact', 'Footer link to contact link page', 'sophos-news' ),
				'menu-item-url'         => sprintf( '//www.sophos.com/%s/company/contact.aspx', $this->language->format_for_sophos() ),
				'menu-item-description' => '',
				'menu-item-classes'     => '',
			];

			unset( $menu[6] );

			// Just in case there are more than six items in the default, reorder the array
			$menu = array_values( $menu );

			return $menu;
		}
	}


	class ES_ES extends \Sophos\Region\Menu\Popular {
		// nothing to see here
	}


	class IT_IT extends \Sophos\Region\Menu\Popular {
		// nothing to see here
	}


	class NL_NL extends \Sophos\Region\Menu\Popular {
		// nothing to see here
	}


	class PT_BR extends \Sophos\Region\Menu\Popular {
		// nothing to see here
	}


	class ES_419 extends \Sophos\Region\Menu\Popular {
		// nothing to see here
	}


	class ZH_TW extends \Sophos\Region\Menu\Popular {
		// nothing to see here
	}
}
