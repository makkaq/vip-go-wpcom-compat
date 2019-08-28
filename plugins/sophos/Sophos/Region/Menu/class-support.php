<?php

namespace Sophos\Region\Menu {

	/**
	 * Regional menu data for footer-support
	 */
	class Support extends \Sophos\Region\Menu\Data {


		/**
		 * Menu name
		 */
		const NAME = 'footer-support';


		/**
		 * Menu items
		 *
		 * @return array
		 */
		public function items() {
			$iso = $this->language->format_for_sophos();

			return [
				[
					'menu-item-title'       => _x( 'Extended Warranties', 'Footer link to support packages page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/support/technical-support.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Knowledgebase', 'Footer link to support knowledgebase', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/support/knowledgebase.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Downloads & Updates', 'Footer link to downloads page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/support/downloads.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Documentation', 'Footer link to support documentation', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/support/documentation.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Professional Services', 'Footer link to professional services page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/support/professional-services.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Training', 'Footer link to training page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/about-us/training.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],
			];
		}
	}
}


namespace Sophos\Region\Menu\Support {


	class EN_US extends \Sophos\Region\Menu\Support {
		// nothing to see here
	}


	class FR_FR extends \Sophos\Region\Menu\Support {
		// nothing to see here
	}


	class DE_DE extends \Sophos\Region\Menu\Support {
		// nothing to see here
	}


	class ES_ES extends \Sophos\Region\Menu\Support {
		// nothing to see here
	}


	class IT_IT extends \Sophos\Region\Menu\Support {
		// nothing to see here
	}


	class NL_NL extends \Sophos\Region\Menu\Support {
		// nothing to see here
	}


	class PT_BR extends \Sophos\Region\Menu\Support {
		// nothing to see here
	}


	class ES_419 extends \Sophos\Region\Menu\Support {
		// nothing to see here
	}


	class ZH_TW extends \Sophos\Region\Menu\Support {
		// nothing to see here
	}
}
