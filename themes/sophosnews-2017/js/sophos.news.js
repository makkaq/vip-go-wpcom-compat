/*!
 * Sophos News
 */


/**
 * Establish a Sophos namespace if it doesn't exist
 *
 * @namespace Sophos
 */
var Sophos = Sophos || {};


/**
 * Extend the Sophos namespace
 *
 * @namespace Sophos
 * @param {Sophos} _sophos
 * @param {jQuery} jQuery
 */
(function(_sophos, jQuery) {


	/**
     * Setup a Google Analytics command stack to track the page view
     *
     * @type Array
     */
    window._gaq = window._gaq || [];


	/**
	 * @namespace Sophos.Campaign
	 */
	_sophos.Campaign = _sophos.Campaign || {};


	/**
	 * Set a site-specific campaign ID
	 */
	_sophos.Campaign.defaultCampaignId = '70130000001xKqzAAE';


    /**
     * Set the campaign cookies
     */
    _sophos.Campaign.setCookie();
	_sophos.Partner.setCookie();


	/**
	 * Set up PDF tracking when the DOM is loaded
	 */
	jQuery(_sophos.GA.setUpPDFTracking);


	/**
     * Add data to the .sophos.com profile
     */
	window._gaq.push(
		['_setAccount', 'UA-737537-1'],
		['_setDomainName', '.sophos.com'],
		['_setAllowLinker', true],
		['_setAllowHash', false],
		['_setCustomVar', 4, 'CampaignID', Sophos.Campaign.getCampaignId(), 3],
		['_trackPageview', '/corpblog'+window.location.pathname+window.location.search]
	);


    /**
     * Add data to the news.sophos.com profile
     */
    window._gaq.push(
        ['news._setAccount', 'UA-737537-53'],
        ['news._trackPageview']
    );


	/**
     * Add data to local profiles
     */
	if (window._sophosLocalAnalytics && window._sophosLocalAnalytics.substring(0, 3) === 'UA-') {
		window._gaq.push(
			['local._setAccount', window._sophosLocalAnalytics],
			['local._trackPageview']
		);
	}


	/**
     * Create the appropriate Google Analytics script element
     */
	(function() {
	var ga = document.createElement('script');
	ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' === document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();


	/**
	 * Eloqua
	 */
	var _elqQ = _elqQ || [];
	_elqQ.push(['elqSetSiteId', '1777052651']);
	_elqQ.push(['elqTrackPageView']);

	(function () {
	var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true;
	s.src = '//img03.en25.com/i/elqCfg.min.js';
	var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
	})();

}(Sophos, jQuery));
