<?php

namespace Sophos\Region\Menu {

	/**
	 * Regional menu data for secondary menu
	 */
	class Secondary extends \Sophos\Region\Menu\Data {


		/**
		 * Menu name
		 */
		const NAME = 'secondary';


		/**
		 * Menu items
		 *
		 * @return array
		 */
		public function items() {
			$iso = $this->language->format_for_sophos();

			return [
				[
					'menu-item-title'       => _x( 'Overview', 'Main menu link to Overview page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/company.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Investors', 'Main menu link to Investors page', 'sophos-news' ),
					'menu-item-url'         => '//investors.sophos.com/',
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Press', 'Main menu link to Press page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/company/press.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Events', 'Main menu link to Events page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//secure2.sophos.com/%s/company/events.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Community', 'Main menu link to Community page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/company/community.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Blog', 'Main menu link to Sophos News', 'sophos-news' ),
					'menu-item-url'         => '/',
					'menu-item-description' => '',
					'menu-item-classes'     => 'menu-item-blog',
				],[
					'menu-item-title'       => _x( 'Careers', 'Main menu link to Careers page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//secure2.sophos.com/%s/company/careers.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Contact', 'Main menu link to Contact page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/company/contact.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],
			];
		}
	}
}


namespace Sophos\Region\Menu\Secondary {

	class EN_US extends \Sophos\Region\Menu\Secondary {
		// nothing to see here
	}


	class FR_FR extends \Sophos\Region\Menu\Secondary {
		// nothing to see here
	}


	class DE_DE extends \Sophos\Region\Menu\Secondary {
		// nothing to see here
	}


	class ES_ES extends \Sophos\Region\Menu\Secondary {
		// nothing to see here
	}


	class IT_IT extends \Sophos\Region\Menu\Secondary {
		// nothing to see here
	}


	class NL_NL extends \Sophos\Region\Menu\Secondary {
		// nothing to see here
	}


	class PT_BR extends \Sophos\Region\Menu\Secondary {
		// nothing to see here
	}


	class ES_419 extends \Sophos\Region\Menu\Secondary {
		// nothing to see here
	}


	class ZH_TW extends \Sophos\Region\Menu\Secondary {
		// nothing to see here
	}
}
