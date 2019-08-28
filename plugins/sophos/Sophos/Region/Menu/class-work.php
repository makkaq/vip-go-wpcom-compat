<?php

namespace Sophos\Region\Menu {

	/**
	 * Regional menu data for footer-work
	 */
	class Work extends \Sophos\Region\Menu\Data {


		/**
		 * Menu name
		 */
		const NAME = 'footer-work';


		/**
		 * Menu items
		 *
		 * @return array
		 */
		public function items() {
			$iso = $this->language->format_for_sophos();

			return [
				[
					'menu-item-title'       => _x( 'Become a Partner', 'Footer link to the partner application form', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/partners/partner-application.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Partner Portal (login)', 'Footer link to the partner portal', 'sophos-news' ),
					'menu-item-url'         => 'http://partnerportal.sophos.com/',
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Resellers', 'Footer link to the resellers page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/partners/resellers.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Tech Partners', 'Footer link to the partners\' page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/partners.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'OEM', 'Footer link to the OEM solutions page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/solutions/oem-solutions.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],
			];
		}
	}
}


namespace Sophos\Region\Menu\Work {


	class EN_US extends \Sophos\Region\Menu\Work {
		// nothing to see here
	}


	class FR_FR extends \Sophos\Region\Menu\Work {

		/**
		 * Menu items
		 *
		 * @return [array]
		 */
		public function items() {
			$menu = parent::items();
			$menu[5]['menu-item-url'] = sprintf( '//www.sophos.com/%s/partners/oem-and-technology.aspx', $this->language->format_for_sophos() );

			return $menu;
		}
	}


	class DE_DE extends \Sophos\Region\Menu\Work\fr_FR {
		// Do as the French do
	}


	class ES_ES extends \Sophos\Region\Menu\Work\fr_FR {
		// Do as the French do
	}


	class IT_IT extends \Sophos\Region\Menu\Work\fr_FR {
		// Do as the French do
	}


	class NL_NL extends \Sophos\Region\Menu\Work\fr_FR {
		// Do as the French do
	}


	class PT_BR extends \Sophos\Region\Menu\Work\fr_FR {
		// Do as the French do
	}


	class ES_419 extends \Sophos\Region\Menu\Work\fr_FR {
		// Do as the French do
	}


	class ZH_TW extends \Sophos\Region\Menu\Work\fr_FR {
		// Do as the French do
	}
}
