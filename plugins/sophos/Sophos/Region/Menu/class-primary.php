<?php

namespace Sophos\Region\Menu {

	/**
	 * Regional menu data for primary menu
	 */
	class Primary extends \Sophos\Region\Menu\Data {


		/**
		 * Menu name
		 */
		const NAME = 'primary';


		/**
		 * Menu items
		 *
		 * @return array
		 */
		public function items() {
			$iso = $this->language->format_for_sophos();

			return [
				[
					'menu-item-title'       => _x( 'Products', 'Main menu link to Products page', 'sophos-news' ),
					'menu-item-url'         => '#',
					'menu-item-description' => '',
					'menu-item-classes'     => 'has-icons',
					'menu-item-children'    => [
						[
							'menu-item-title'       => _x( 'XG Firewall', 'Main menu link to XG Firewall page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/products/next-gen-firewall.aspx', $iso ),
							'menu-item-description' => _x( 'The next thing in next-gen.', 'Short description of XG Firewall in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'SG UTM', 'Main menu link to SG UTM page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/products/unified-threat-management.aspx', $iso ),
							'menu-item-description' => _x( 'The ultimate network security package.', 'Short description of SG UTM in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'Secure Wi-Fi', 'Main menu link to Secure Wi-Fi page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/products/secure-wifi.aspx', $iso ),
							'menu-item-description' => _x( 'Super secure, super wi-fi.', 'Short description of Secure Wi-Fi in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'Secure Web Gateway', 'Main menu link to Secure Web Gateway page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/products/choose-swg.aspx', $iso ),
							'menu-item-description' => _x( 'Complete web protection everywhere.', 'Short description of Secure Web Gateway in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'Secure Email Gateway', 'Main menu link to Secure Email Gateway page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/products/choose-swg.aspx', $iso ),
							'menu-item-description' => _x( 'Simple protection for a complex problem.', 'Short description of Secure Email Gateway in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'PureMessage', 'Main menu link to PureMessage page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/products/puremessage.aspx', $iso ),
							'menu-item-description' => _x( 'Good news for you. Bad news for spam.', 'Short description of PureMessage in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'Endpoint Protection', 'Main menu link to Endpoint Protection page', 'sophos-news' ),
							'menu-item-url'         => '//.sophos.com/en-us/products/endpoint-antivirus.aspx',
							'menu-item-description' => _x( 'Comprehensive security for users and data.', 'Short description of Endpoint Protection in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'Intercept X', 'Main menu link to Intercept X page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/products/intercept-x.aspx', $iso ),
							'menu-item-description' => _x( 'A completely new approach to endpoint security.', 'Short description of Intercept X in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'Sophos Clean', 'Main menu link to Sophos Clean page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/products/sophos-clean.aspx', $iso ),
							'menu-item-description' => _x( 'Advanced scanner and malware removal tool.', 'Short description of Sophos Clean in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'Mobile Control', 'Main menu link to page about Mobile Control', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/products/mobile-control.aspx', $iso ),
							'menu-item-description' => _x( 'Countless devices, one solution.', 'Short description of Mobile Control in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'SafeGuard Encryption', 'Main menu link to SafeGuard Encryption page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/products/safeguard-encryption.aspx', $iso ),
							'menu-item-description' => _x( 'Protecting your data, wherever it goes.', 'Short description of SafeGuard Encryption in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'Server Protection', 'Main menu link to Server Protection page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/products/server-security.aspx', $iso ),
							'menu-item-description' => _x( 'Security optimized for servers.', 'Short description of Server Protection in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'Sophos Home', 'Main menu link to product page of Sophos Home', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/products/sophos-home.aspx', $iso ),
							'menu-item-description' => _x( 'Free protection for home computers.', 'Short description of Sophos Home in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],
					],
				],[
					'menu-item-title'       => _x( 'Solutions', 'Main menu link to Solutions page', 'sophos-news' ),
					'menu-item-url'         => '#',
					'menu-item-description' => '',
					'menu-item-classes'     => 'has-icons',
					'menu-item-children'    => [
						[
							'menu-item-title'       => _x( 'Industries', 'Main menu link to Industries page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/solutions/industries.aspx', $iso ),
							'menu-item-description' => _x( 'Your industry. Our expertise.', 'Short description of Industries in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'IT Initiatives', 'Main menu link to IT Initiatives page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/solutions/initiatives.aspx', $iso ),
							'menu-item-description' => _x( 'Embrace IT initiatives with confidence.', 'Short description of IT Initiatives in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'Compliance', 'Main menu link to Compliance page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/solutions/compliance.aspx', $iso ),
							'menu-item-description' => _x( 'Helping you to stay regulatory compliant.', 'Short description of Compliance in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'OEM Solutions', 'Main menu link to OEM Solutions page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/solutions/oem-solutions.aspx', $iso ),
							'menu-item-description' => _x( 'Trusted by world-leading brands.', 'Short description of OEM Solutions in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'Professional Services', 'Main menu link to Professional Services page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/support/professional-services.aspx', $iso ),
							'menu-item-description' => _x( 'Our experience. Your peace of mind.', 'Short description of Professional Services in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'SophosLabs', 'Main menu link to SophosLabs page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/threat-center/threat-analyses.aspx', $iso ),
							'menu-item-description' => _x( 'Behind the scene of our 24/7 security.', 'Short description of SophosLabs in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'Public Cloud', 'Main menu link to Public Cloud page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/solutions/public-cloud.aspx', $iso ),
							'menu-item-description' => _x( 'Stronger, simpler cloud security.', 'Short description of Public Cloud in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],
					],
				],[
					'menu-item-title'       => _x( 'Partners', 'Main menu link to Partners page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/partners.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Support', 'Main menu link to Support page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/support.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Company', 'Main menu link to Company page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/company.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => '',
				],[
					'menu-item-title'       => _x( 'Downloads', 'Main menu link to Downloads page', 'sophos-news' ),
					'menu-item-url'         => '#',
					'menu-item-description' => '',
					'menu-item-classes'     => 'is-icon icon-download',
					'menu-item-children'    => [
						[
							'menu-item-title'       => _x( 'Free Trials', 'Main menu link to Free Trials page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/products/free-trials.aspx', $iso ),
							'menu-item-description' => _x( 'All product trials in one place.', 'Short description of Free Trials in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'Free Tools', 'Main menu link to Free Tools page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/products/free-tools.aspx', $iso ),
							'menu-item-description' => _x( 'Try our tools for use at home.', 'Short description of Free Tools in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],[
							'menu-item-title'       => _x( 'Get Pricing', 'Main menu link to Get Pricing page', 'sophos-news' ),
							'menu-item-url'         => sprintf( '//www.sophos.com/%s/products/request-a-quote.aspx', $iso ),
							'menu-item-description' => _x( 'The right price every time.', 'Short description of Get Pricing in main sophos.com menu', 'sophos-news' ),
							'menu-item-classes'     => '',
						],
					],
				],[
					'menu-item-title'       => _x( 'Search', 'Main menu link to Search page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/search-results.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => 'is-icon icon-search',
				],[
					'menu-item-title'       => _x( 'Sign In', 'Main menu link to Sign In page', 'sophos-news' ),
					'menu-item-url'         => sprintf( '//www.sophos.com/%s/login.aspx', $iso ),
					'menu-item-description' => '',
					'menu-item-classes'     => 'is-icon icon-account',
				],
			];
		}
	}
}


namespace Sophos\Region\Menu\Primary {


	class EN_US extends \Sophos\Region\Menu\Primary {
		// nothing to see here
	}


	class FR_FR extends \Sophos\Region\Menu\Primary {


		/**
		 * Menu items
		 *
		 * @return [array]
		 */
		public function items() {
			$menu    = parent::items();
			$menu[1] = [
				'menu-item-title'       => _x( 'Labs', 'Main menu link to SophosLabs page', 'sophos-news' ),
				'menu-item-url'         => sprintf( '//www.sophos.com/%s/threat-center/threat-analyses.aspx', $this->language->format_for_sophos() ),
				'menu-item-description' => '',
				'menu-item-classes'     => '',
			];

			return $menu;
		}
	}


	class DE_DE extends \Sophos\Region\Menu\Primary\fr_FR {
		// do as the French do
	}


	class ES_ES extends \Sophos\Region\Menu\Primary\fr_FR {
		// do as the French do
	}


	class IT_IT extends \Sophos\Region\Menu\Primary\fr_FR {
		// do as the French do
	}


	class NL_NL extends \Sophos\Region\Menu\Primary\fr_FR {
		// do as the French do
	}


	class PT_BR extends \Sophos\Region\Menu\Primary\fr_FR {
		// do as the French do
	}


	class ES_419 extends \Sophos\Region\Menu\Primary\fr_FR {
		// do as the French do
	}


	class ZH_TW extends \Sophos\Region\Menu\Primary\fr_FR {
		// do as the French do
	}
}
