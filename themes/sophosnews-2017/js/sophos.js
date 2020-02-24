/*!
 * Sophos utilities
 *
 * @version 1.0.0
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
 * @param {object} _sophos
 * @param {object} jQuery
 */
(function(_sophos, jQuery) {

    /**
     * @namespace Sophos.Utils
     */
    _sophos.Utils = _sophos.Utils || {};

    /**
     * Setup input so that default content is removed on focus
     *
     * @param {jQuery} input
     */
    _sophos.Utils.virginise = function(input) {
        input.focus(function() {
            var input = jQuery(this);
            if (input.hasClass("virgin")) {
                input.data('default', input.val());
                input.removeClass('virgin').val('');
            }
        });

        input.blur(function () {
            if (input.val() === '') {
                input.addClass('virgin').val(input.data('default'));
            }
        });
    };

    /**
     * Very rough guess if a string is an email address
     *
     * @param {string} email
     * @returns {Boolean}
     */
    _sophos.Utils.isEmailAddress = function(email) {
        return (email.indexOf('@') > 0) ? true : false; // FIXME utils
    };

	/**
	 * Test if a URL looks like a Wordpress post
	 *
	 * @param  {[type]}  url URL
	 * @return {Boolean}
	 */
	_sophos.Utils.isSophosWordpressURL = function(url) {
		return /https?:\/\/[^\.]+\.sophos\.com\/[0-9]{4}\/[0-9]{2}\/[0-9]{2}\/[^\/]+\/?(?:\?[^/]+)?$/.test(url);
	};

	/**
	 * Turn a query string into an object
	 *
	 * If no URL is provided as an argument
	 * then it uses the location.search object
	 *
	 * @param {[type]} url Optional URL or query string
	 */
    _sophos.Utils.QueryString = function(url) {
        var str   = url || location.search;
        var pairs = str.slice( str.indexOf('?') + 1 ).split('&');
		var utils = _sophos.Utils;

        for (var i = 0; i < pairs.length; i++) {
            var pair = pairs[i].split('=');
            this[ pair[0] ] = utils.cast(pair[1]) || '';
        }
    };

    /**
     * Convert a string to the type it represents
     *
     * See http://stackoverflow.com/questions/18799685/string-conversion-to-undefined-null-number-boolean
     *
     * @param {string} value
     */
    _sophos.Utils.cast = function(value) {
        if (value === "undefined") {
            return undefined;
        }

        try {
            return JSON.parse(value);
        } catch (e) {
            return value;
        }
    };

	/**
	 * Fast UUID generator, RFC4122 version 4 compliant.
	 *
	 * @author Jeff Ward (jcward.com).
	 * @license MIT license
	 * @link http://stackoverflow.com/questions/105034/how-to-create-a-guid-uuid-in-javascript/21963136#21963136
	 */
	_sophos.Utils.uuid = (function() {
		var self = {};
		var lut = []; for (var i=0; i<256; i++) { lut[i] = (i<16?'0':'')+(i).toString(16); }
		self.generate = function() {
			var d0 = Math.random()*0xffffffff|0;
			var d1 = Math.random()*0xffffffff|0;
			var d2 = Math.random()*0xffffffff|0;
			var d3 = Math.random()*0xffffffff|0;
			return lut[d0&0xff]+lut[d0>>8&0xff]+lut[d0>>16&0xff]+lut[d0>>24&0xff]+'-'+
				lut[d1&0xff]+lut[d1>>8&0xff]+'-'+lut[d1>>16&0x0f|0x40]+lut[d1>>24&0xff]+'-'+
				lut[d2&0x3f|0x80]+lut[d2>>8&0xff]+'-'+lut[d2>>16&0xff]+lut[d2>>24&0xff]+
				lut[d3&0xff]+lut[d3>>8&0xff]+lut[d3>>16&0xff]+lut[d3>>24&0xff];
		};

		return self;
	})();

})(Sophos = window.Sophos || {}, jQuery);

/*!
 * Sophos campaigns
 *
 * @version 1.0.1
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
     * @namespace Sophos.Campaign
     */
    _sophos.Campaign = _sophos.Campaign || {};


    /**
     * Set a default campaign ID
     */
    _sophos.Campaign.defaultCampaignId = 0;


	/**
	 * Get a Sophos campaign ID
	 *
	 * The ID is extracted from the following sources in order:
	 * 	* Argument
	 * 	* Query string
	 * 	* Cookie
	 * 	* Default
 	 *
 	 * @param  {[type]} str Optional URL or query string containing a campaign ID
 	 * @return {String}     Sophos campaign ID
 	 */
    _sophos.Campaign.getCampaignId = function (str) {
		// Split the optional argument str into a query string
		// (falls back to location.search if str is undefined)
        var query = new Sophos.Utils.QueryString(str);

        // Cookies can store values of type undefined as the string "undefined" so
        // we cast the value properly before doing anything with it. Note that undefined
        // values will also test TRUE against our regular expression - presumably
        // they're re-cast as strings internally before the match.
        var cid    = Sophos.Utils.cast(Cookies.get('CampaignID'));
        var re     = /^[a-zA-Z0-9]+$/;
        var cookie = (!cid || typeof cid === 'undefined' || !re.test(cid)) ? _sophos.Campaign.defaultCampaignId : cid;

        return ( query && query.cmp && re.test(query.cmp) ) ? query.cmp : cookie;
    };


	/**
	 * Set a Sophos campaign cookie
	 * @param {String} str Optional URL or query string containing a campaign ID
	 */
    _sophos.Campaign.setCookie = function (str) {
        Cookies.set('CampaignID', _sophos.Campaign.getCampaignId(str), {
            // set cookie domain based on current tld
            domain: window.location.hostname.replace(/^(?:blogs|news|nakedsecurity)\d?(\..+)$/, "$1"),
            expires: 180,
            path: '/'
        });
    };

})(Sophos, jQuery);


/**
 * Extend the Sophos namespace
 *
 * @namespace Sophos
 * @param {Sophos} _sophos
 * @param {jQuery} jQuery
 */
(function(_sophos, jQuery) {


    /**
     * @namespace Sophos.Partner
     */
    _sophos.Partner = _sophos.Partner || {};


	/**
     * Set a partner cookie
     */
	_sophos.Partner.setCookie = function () {
		var utils  = _sophos.Utils;
        var query  = new utils.QueryString();

		// Cookies can store values of type undefined as the string "undefined" so
        // we cast the value properly before doing anything with it. Note that undefined
        // values will also test TRUE against our regular expression - presumably
        // they're re-cast as strings internally before the match.
        var cookie = utils.cast( Cookies.get('SOPHOS_PARTNERID') );

		if (!cookie) {
			var re = /^[\-a-zA-Z0-9]{1,18}$/;

			if (query.id && re.test(query.id)) {
				Cookies.set('SOPHOS_PARTNERID', query.id, {
					domain: window.location.hostname.replace(/^(?:blogs|news|nakedsecurity)\d?(\..+)$/, "$1"),
					expires: null,
					path: '/'
				});
			}
		}
	};

})(Sophos, jQuery);


/**
 * Extend the Sophos namespace
 *
 * @namespace Sophos
 * @param {Sophos} _sophos
 * @param {jQuery} jQuery
 */
(function(_sophos, jQuery) {


    /**
     * @namespace Sophos.GA
     */
    _sophos.GA = _sophos.GA || {};


	/**
	 * Track a pageview
	 *
	 * @param  {String} url   URL to track
	 * @param  {String} title Optional title
	 * @param  {String} referrer Optional referrer
	 */
	_sophos.GA.trackPageView = function (url, title, referrer) {
		if (undefined !== title) {
			window._gaq.push(['_set', 'title', title]);
		}

		if (undefined !== referrer && Sophos.Utils.isSophosWordpressURL(referrer)) {
			window._gaq.push(['_setReferrerOverride', referrer]);
		}

		window._gaq.push(['_trackPageview', url]);
	};


	/**
     * Set a partner cookie
     */
	_sophos.GA.setUpPDFTracking = function () {
		// Get a list of anchors linking to PDFs on sophos.com within the
		// .entry-content element (this should exist on both sites)
		jQuery('.entry-content a[href]').each(function (index, el) {
			var a       = jQuery(el);
			var href    = a.attr('href');
			var title   = a.attr('title') || a.text();
			var matches = href.match(/https?:\/\/[^\.]+\.sophos\.com(\/.*?\.pdf(?:\?[^/]+)?)$/);

		    if ( matches ) {
				var path = matches.pop();

				a.on('click', function (e) {
					e.preventDefault();

					// Record the click as a page view
					Sophos.GA.trackPageView(path, title, window.location.href);

					// Update the campaign cookie
					Sophos.Campaign.setCookie(path);

					// Give Google Analytics some time to run
					window.setTimeout(function (location) {
						window.location.href = location;
					}, 250, href);
				});
			}
		});
	};

})(Sophos, jQuery);


/*!
 * Sophos ads
 *
 * @version 1.0.0
 * @requires jQuery
 */

/**
 * Extend the Sophos namespace
 *
 * @namespace Sophos
 * @param {Sophos} _sophos
 * @param {jQuery} jQuery
 */
(function(_sophos, jQuery) {


    /**
     * @namespace Sophos.Ad
     */
    _sophos.Ad = _sophos.Ad || {};


	/**
	 * Setup ad tracking
	 *
	 * Find each inline ad, record that it's been shown and record if it's clicked
	 *
	 * @param  {String} tracker Optional Google Analytics tracker
	 */
	_sophos.Ad.setupAdTracking = function (tracker) {

		var prefix = tracker ? tracker + '.' : '';

		jQuery('aside.sophos-inline-ad').each(function (index) {
			var ad       = jQuery(this);
			var campaign = ad.find("[data-ga-label]").data('ga-label') || '';
			var a   	 = ad.find('a').first();
			var text     = [campaign,a.attr('href')].join(' - ');

			// Trigger an event if the link is clicked
			ad.find('a').on('click', function () {
				window._gaq.push([prefix + '_trackEvent', 'Inline Ad', 'Click', text]);
			});

			// Trigger an event now to show it's been viewed
			window._gaq.push([prefix + '_trackEvent', 'Inline Ad', 'View', text]);
		});
	};

})(window.Sophos = window.Sophos || {}, jQuery);

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
	 * Set up ad tracking
	 */
	jQuery(function () {
		_sophos.Ad.setupAdTracking(undefined);
	});


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
