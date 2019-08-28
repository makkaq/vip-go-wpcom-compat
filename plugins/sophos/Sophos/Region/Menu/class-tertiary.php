<?php

namespace Sophos\Region\Menu {

	/**
	 * Regional menu data for tertiary menu
	 */
	class Tertiary extends \Sophos\Region\Menu\Data {


		/**
		 * Menu name
		 */
		const NAME = 'tertiary';


		/**
		 * Menu items
		 *
		 * @return array
		 */
		public function items() {
			return [
				[
					'menu-item-title'       => _x( 'Search', 'Blog menu link for search form', 'sophos-news' ),
					'menu-item-url'         => '#search',
					'menu-item-description' => '',
					'menu-item-classes'     => 'is-icon icon-search',
				],
			];
		}
	}
}


namespace Sophos\Region\Menu\Tertiary {

	class EN_US extends \Sophos\Region\Menu\Tertiary {
		// nothing to see here
	}


	class FR_FR extends \Sophos\Region\Menu\Tertiary {
		// nothing to see here
	}


	class DE_DE extends \Sophos\Region\Menu\Tertiary {
		// nothing to see here
	}


	class ES_ES extends \Sophos\Region\Menu\Tertiary {
		// nothing to see here
	}


	class IT_IT extends \Sophos\Region\Menu\Tertiary {
		// nothing to see here
	}


	class NL_NL extends \Sophos\Region\Menu\Tertiary {
		// nothing to see here
	}


	class PT_BR extends \Sophos\Region\Menu\Tertiary {
		// nothing to see here
	}


	class ES_419 extends \Sophos\Region\Menu\Tertiary {
		// nothing to see here
	}

	class ZH_TW extends \Sophos\Region\Menu\Tertiary {
		// nothing to see here
	}
}
