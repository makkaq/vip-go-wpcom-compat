<?php

namespace Sophos\Region\Menu {

	/**
	 * Regional menu data for footer-community
	 */
	class Community extends \Sophos\Region\Menu\Data {


		/**
		 * Unregionalised name
		 */
		const NAME = 'footer-community';


		/**
		 * Menu items
		 *
		 * @return array
		 */
		public function items() {
			$iso = $this->language->format_for_sophos();

			return [
				[
					'menu-item-title'       => _x( 'Sophos News', 'Footer link to Sophos News', 'sophos-news' ),
					'menu-item-url'         => '/',
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Social Networks', 'Footer link to community page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/company/community.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Naked Security News', 'Footer link to Naked Security', 'sophos-news' ),
					'menu-item-url'         => 'https://nakedsecurity.sophos.com',
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Podcasts', 'Footer link to Podcasts page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/company/podcasts.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'RSS', 'Footer link to RSS feeds page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/company/rss-feeds.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],
			];
		}
	}
}


namespace Sophos\Region\Menu\Community {


	class EN_US extends \Sophos\Region\Menu\Community {
		// nothing to see here
	}


	class FR_FR extends \Sophos\Region\Menu\Community {
		// nothing to see here
	}


	class DE_DE extends \Sophos\Region\Menu\Community {
		// nothing to see here
	}


	class ES_ES extends \Sophos\Region\Menu\Community {
		// nothing to see here
	}


	class IT_IT extends \Sophos\Region\Menu\Community {
		// nothing to see here
	}


	class NL_NL extends \Sophos\Region\Menu\Community {
		// nothing to see here
	}


	class PT_BR extends \Sophos\Region\Menu\Community {
		// nothing to see here
	}


	class ES_419 extends \Sophos\Region\Menu\Community {
		// nothing to see here
	}

	class ZH_TW extends \Sophos\Region\Menu\Community {
		// nothing to see here
	}
}
