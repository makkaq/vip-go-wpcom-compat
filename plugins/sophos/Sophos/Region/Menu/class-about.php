<?php

namespace Sophos\Region\Menu {

	/**
	 * Regional menu data for footer-about
	 */
	class About extends \Sophos\Region\Menu\Data {


		/**
		 * Unregionalised name
		 */
		const NAME = 'footer-about';


		/**
		 * Menu items
		 *
		 * @return array
		 */
		public function items() {
			$iso = $this->language->format_for_sophos();

			return [
				[
					'menu-item-title'       => _x( 'Jobs/Careers', 'Footer link to the careers page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/company/careers.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Products', 'Footer link to the products page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/products.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Feedback', 'Footer link to the feedback page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/company/site-feedback.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Contact Us', 'Footer link to the contact page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/company/contact.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Press', 'Footer link to the press page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/company/press.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Modern Slavery Statement', 'Footer link to the modern slavery statement', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/legal/modern-slavery-act-transparency-statement.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],
			];
		}
	}
}


namespace Sophos\Region\Menu\About {


	class EN_US extends \Sophos\Region\Menu\About {
		// nothing to see here
	}


	class FR_FR extends \Sophos\Region\Menu\About {
		// nothing to see here
	}


	class DE_DE extends \Sophos\Region\Menu\About {

		/**
		 * Menu items
		 *
		 * @return [array]
		 */
		public function items() {
			$menu = parent::items();

			unset( $menu[6] ); // Remove the modern slavery statement

			// Just in case there are more than six items in the default, reorder the array
			return array_values( $menu );
		}
	}


	class ES_ES extends \Sophos\Region\Menu\About {
		// nothing to see here
	}


	class IT_IT extends \Sophos\Region\Menu\About\de_DE {
		// Like the Germans, the Italians don't have a modern slavery statement
	}


	class NL_NL extends \Sophos\Region\Menu\About {
		// nothing to see here
	}


	class PT_BR extends \Sophos\Region\Menu\About {
		// nothing to see here
	}


	class ES_419 extends \Sophos\Region\Menu\About {
		// nothing to see here
	}

	class ZH_TW extends \Sophos\Region\Menu\About {
		// nothing to see here
	}
}
