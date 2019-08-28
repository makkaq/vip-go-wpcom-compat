<?php

namespace Sophos\Region\Menu {

	/**
	 * Regional menu data for footer-legal
	 */
	class Legal extends \Sophos\Region\Menu\Data {


		/**
		 * Menu name
		 */
		const NAME = 'footer-legal';


		/**
		 * Menu items
		 *
		 * @return array
		 */
		public function items() {

			$iso = $this->language->format_for_sophos();

			return [
				[
					'menu-item-title'       => _x( 'Legal', 'Legal menu link to Legal page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/legal.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Privacy', 'Legal menu link to Privacy page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/legal/sophos-group-privacy-policy.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Cookie Information', 'Legal menu link to Cookie Information page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/legal/cookie-information.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],
			];
		}
	}
}


namespace Sophos\Region\Menu\Legal {


	class EN_US extends \Sophos\Region\Menu\Legal {
		// nothing to see here
	}


	class FR_FR extends \Sophos\Region\Menu\Legal {
		// nothing to see here
	}


	class DE_DE extends \Sophos\Region\Menu\Legal {
		// nothing to see here
	}


	class ES_ES extends \Sophos\Region\Menu\Legal {
		// nothing to see here
	}


	class IT_IT extends \Sophos\Region\Menu\Legal {
		// nothing to see here
	}


	class NL_NL extends \Sophos\Region\Menu\Legal {
		// nothing to see here
	}


	class PT_BR extends \Sophos\Region\Menu\Legal {
		// nothing to see here
	}


	class ES_419 extends \Sophos\Region\Menu\Legal\es_ES {
		// Do as the Spanish do
	}


	class ZH_TW extends \Sophos\Region\Menu\Legal\es_ES {
		// Do as the Spanish do
	}
}
