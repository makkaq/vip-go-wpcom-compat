/*!
 * JavaScript Cookie v2.1.4
 * https://github.com/js-cookie/js-cookie
 *
 * Copyright 2006, 2015 Klaus Hartl & Fagner Brack
 * Released under the MIT license
 */
;(function (factory) {
	var registeredInModuleLoader = false;
	if (typeof define === 'function' && define.amd) {
		define(factory);
		registeredInModuleLoader = true;
	}
	if (typeof exports === 'object') {
		module.exports = factory();
		registeredInModuleLoader = true;
	}
	if (!registeredInModuleLoader) {
		var OldCookies = window.Cookies;
		var api = window.Cookies = factory();
		api.noConflict = function () {
			window.Cookies = OldCookies;
			return api;
		};
	}
}(function () {
	function extend () {
		var i = 0;
		var result = {};
		for (; i < arguments.length; i++) {
			var attributes = arguments[ i ];
			for (var key in attributes) {
				result[key] = attributes[key];
			}
		}
		return result;
	}

	function init (converter) {
		function api (key, value, attributes) {
			var result;
			if (typeof document === 'undefined') {
				return;
			}

			// Write

			if (arguments.length > 1) {
				attributes = extend({
					path: '/'
				}, api.defaults, attributes);

				if (typeof attributes.expires === 'number') {
					var expires = new Date();
					expires.setMilliseconds(expires.getMilliseconds() + attributes.expires * 864e+5);
					attributes.expires = expires;
				}

				// We're using "expires" because "max-age" is not supported by IE
				attributes.expires = attributes.expires ? attributes.expires.toUTCString() : '';

				try {
					result = JSON.stringify(value);
					if (/^[\{\[]/.test(result)) {
						value = result;
					}
				} catch (e) {}

				if (!converter.write) {
					value = encodeURIComponent(String(value))
						.replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent);
				} else {
					value = converter.write(value, key);
				}

				key = encodeURIComponent(String(key));
				key = key.replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent);
				key = key.replace(/[\(\)]/g, escape);

				var stringifiedAttributes = '';

				for (var attributeName in attributes) {
					if (!attributes[attributeName]) {
						continue;
					}
					stringifiedAttributes += '; ' + attributeName;
					if (attributes[attributeName] === true) {
						continue;
					}
					stringifiedAttributes += '=' + attributes[attributeName];
				}
				return (document.cookie = key + '=' + value + stringifiedAttributes);
			}

			// Read

			if (!key) {
				result = {};
			}

			// To prevent the for loop in the first place assign an empty array
			// in case there are no cookies at all. Also prevents odd result when
			// calling "get()"
			var cookies = document.cookie ? document.cookie.split('; ') : [];
			var rdecode = /(%[0-9A-Z]{2})+/g;
			var i = 0;

			for (; i < cookies.length; i++) {
				var parts = cookies[i].split('=');
				var cookie = parts.slice(1).join('=');

				if (cookie.charAt(0) === '"') {
					cookie = cookie.slice(1, -1);
				}

				try {
					var name = parts[0].replace(rdecode, decodeURIComponent);
					cookie = converter.read ?
						converter.read(cookie, name) : converter(cookie, name) ||
						cookie.replace(rdecode, decodeURIComponent);

					if (this.json) {
						try {
							cookie = JSON.parse(cookie);
						} catch (e) {}
					}

					if (key === name) {
						result = cookie;
						break;
					}

					if (!key) {
						result[name] = cookie;
					}
				} catch (e) {}
			}

			return result;
		}

		api.set = api;
		api.get = function (key) {
			return api.call(api, key);
		};
		api.getJSON = function () {
			return api.apply({
				json: true
			}, [].slice.call(arguments));
		};
		api.defaults = {};

		api.remove = function (key, attributes) {
			api(key, '', extend(attributes, {
				expires: -1
			}));
		};

		api.withConverter = init;

		return api;
	}

	return init(function () {});
}));

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

/*-------------------------------------------------------------------------
 * Extracts and stores a unique identifier for this visitor.
 *
 * DEPENDENCIES
 *   - JQuery - http://jquery.com/
 *   - js-cookie - https://github.com/js-cookie/js-cookie
 *
 * USAGE
 *   Call the processGaToken function to analyse the user session and store a
 *   unique identifier for this user.  Must be placed after Google Analytics
 *   injection, and the dependencies listed above.
 *
 *
 * DEPLOYMENT
 *   DO NOT deploy this code 'as is'. Run it through Uglify or similar and use that
 *   as the deployed code.  For example, http://lisperator.net/uglifyjs/.
 *
 * CHANGE LOG
 *
 *   26/03/2014 TB - Initial draft. v1.0
 *   09/04/2014 MS - v1.1, Added DNT, switched to jQuery noConflict, removed default 'o' tracker, added _trackEvent to make script independent
 *   17/09/2015 MS - v1.2, Changed UUID function and switched jQuery Cookie for JS Cookie
 *
 * PARAMETERS
 *
 *   cookieName
 *      The name of the persisted cookie with the unique identifier. Default is 'gaGuid'.
 *
 *   cookieExpiryDays
 *      The number of days the cookie will last. Default is 2 years.
 *
 *   slidingExpiration:
 *      Not implemented.
 *
 *   queryStringArg
 *      The querystring key which, if provided, will override any value set in the cookie - which
 *      is then persisted in a new cookie.  Default is 'gaguid'.
 *
 *   useGoogleVid
 *      When setting the cookie, tries to extract the Google visitor id from the ga cookie.
 *      Default is false.
 *
 *   trackerName
 *      Not implemented.
 *
 *   gaIndex
 *      The slot for the custom variable. Required. This is a number whose value can range from 1 - 5,
 *      inclusive. A custom variable should be placed in one slot only and not be re-used across
 *      different slots Default is 5.
 *
 *   gaCustomVarName
 *      The name for the custom variable. Required. This is a string that identifies the custom
 *      variable and appears in the top-level Custom Variables report of the Analytics reports.
 *      Default is 'GaGuid'.
 *
 *   gaScope
 *      The scope for the custom variable. Optional. The scope defines the level
 *      of user engagement with the site. It is a number whose possible values are 1 (visitor-level),
 *      2 (session-level), or 3 (page-level). When left undefined, the custom variable scope defaults
 *      to page-level interaction.  Default is '3'.
 *
 *   gaAccount
 *      Not used.  Namespace dictates the custom variable injection.
 *
 *   outputDebug
 *      Outputs info statements if true.  Default is false.
 *
 *
 *
 * EXAMPLES
 *
 *
 *   Call and setup the GA cookie with defaults:
 *
 *      (function () {
 *         window.sophosGaGuidStore.processGaToken();
 *      });
 *
 *
 *   Call and setup the GA cookie with custom configuration overrides:
 *
 *      (function () {
 *         window.sophosGaGuidStore.processGaToken({
 *             outputDebug: true,
 *             useGoogleVid: true
 *         });
 *      });
 *-------------------------------------------------------------------------*/



var sophosGaGuidStore = {};
/// <summary>Checks and sets the GA id.</summary>


sophosGaGuidStore.processGaToken = function(customConfig) {
    /// <summary>Main call into the GA token.</summary>
    /// <param name="config">For future use.</param>

    // Default configuration
    var defaultConfig = {
        cookieName: 'gaGuid', // The name of the persisted cookie.
        cookieExpiryDays: 730, // The number of days to expire the cookie.
        cookieDomain: window.location.hostname.replace(/^(?:blogs|news|nakedsecurity)\d?(\..+)$/, "$1"), // The cookie domain
        cookiePath: '/', // The cookie path
        slidingExpiration: false, // Whether to slide the expiration of the cookie
        queryStringArg: 'gaguid', // The querystring key to override the supplied value
        useGoogleVid: false, // Use the GA vid instead of uuid
        trackerName: '', // The tracker name to use for the vid.
        gaIndex: 5, // The index position for the GA variable
        gaCustomVarName: 'GaGuid', // The custom variable name in GA
        gaScope: 1, // The scope of the custom variable
        gaNamespace: '', // The google tracker namespace the custom variables should be attached to
        gaAccount: '', // The account to push against
        outputDebug: false // If populated this IP will be used when no IP is passed to queryIpData()
    };


    var config = {};

    jQuery.each(defaultConfig, function(index, value) {
        if (customConfig && customConfig.hasOwnProperty(index)) {
            config[index] = customConfig[index];
        } else {
            config[index] = value;
        }
    });


    var logInfo = function(message, object) {
        /// <summary>Safe info logging.</summary>
        /// <param name="message" type="String" >The message to be logged.</param>
        /// <param name="object" type="Object" optional="true">Additional information.</param>
        /// <returns type="Object">The bound view model.</returns>

        if (window && window.console && window.console.info && config.outputDebug) {
            window.console.info("Sophos GA Guid: " + message, object);
        }
    };

    var logError = function(message, object) {
        /// <summary>Safe error logging.</summary>
        /// <param name="message" type="String" >The message to be logged.</param>
        /// <param name="object" type="Object" optional="true">Additional information.</param>
        /// <returns type="Object">The bound view model.</returns>
        if (window && window.console && window.console.error) {
            window.console.error("Sophos GA Guid: " + message, object);
        }
    };


    logInfo("Config used", config);


    var getParameterByName = function(paramName) {
        /// <summary>Extracts a parameter from the querystring.</summary>
        /// <param name="paramName">The querystring key.</param>
        /// <returns type="string">The value of the parameter.</returns>

        paramName = paramName.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
        var regexPattern = "[\\?&]" + paramName + "=([^&#]*)";
        var regex = new RegExp(regexPattern);
        var result = regex.exec(window.location.href);
        if (!result) {
            return "";
        } else {
            return decodeURIComponent(result[1].replace(/\+/g, " "));
        }
    };

    // Check dependencies
    if (!jQuery || !Cookies) {
        logError("Missing dependencies. Could not load the Ga Guid token. Check jQuery, Cookies and GA.");
        return;
    }

    var googleId = function() {
        /// <summary>Extracts the Google Visitor Id.</summary>
        /// <returns type="string">The google visitor id, if found - or an empty string.</returns>

        var cookieValue = Cookies.get('__utma');

        if (cookieValue) {
            return cookieValue.split('.')[1];
        }

        return '';
    };

    /**
    * Fast UUID generator, RFC4122 version 4 compliant.
    *
    * @author Jeff Ward (jcward.com).
    * @license MIT license
    * @link http://stackoverflow.com/questions/105034/how-to-create-a-guid-uuid-in-javascript/21963136#21963136
    **/
    var UUID = (function() {
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


    var generateId = function() {
        /// <summary>Generates a unique identifier, which is either generated or extracted from the Google tracker code.</summary>
        /// <returns type="string">A unique id for this user.</returns>

        if (config.useGoogleVid) {
            if (typeof window._gaq !== 'undefined') {
                // Try and pull out the gaq vid
                var visitCode = googleId();

                if (visitCode) {
                    logInfo('Successfully found visitor code. ' + visitCode);
                    return visitCode;
                }

                logInfo('Visit code was empty. Using uuid.');
                return UUID.generate();
            } else {
                logInfo("Failed to find _gaq variable. Using Uuid.");
                return UUID.generate();
            }
        } else {
            logInfo("Uuid used for gaGuid as config stated false for useGoogleVid");
            return UUID.generate();
        }
    };

    var setCookie = function(uuid) {
        /// <summary>Sets a cookie with the tracking id.</summary>

        Cookies.set(config.cookieName, uuid, {expires: config.cookieExpiryDays, path: config.cookiePath, domain: config.cookieDomain});
    };

    var addToGaq = function(gaIndex, customVariableName, trackingId) {
        /// <summary>Inserts a custom variable into the gaq.</summary>

        var customNamespacePrefix = config.gaNamespace ? config.gaNamespace + '.' : '';
        window._gaq = window._gaq || [];

        logInfo('Inserting custom variable _setCustomVar value ' + trackingId + ' into gaq.');
        window._gaq.push(
                [customNamespacePrefix + '_setCustomVar', gaIndex, customVariableName, trackingId, config.gaScope],
                [customNamespacePrefix + '_trackEvent', 'GA Inject', 'Set Variable', undefined, undefined, true]
                );
    };


    (function() {
        /// <summary>Extracts and stores a unique identity for this user.</summary>

        // Bail out if the user sends a DNT header
        switch (navigator.msDoNotTrack || navigator.doNotTrack || 0) {
            case 1:
            case 'yes':
                logInfo("Not tracking this user because their Do Not Track preference is set");
                return;
        }

        /// <summary>Extracts and stores a unique identity for this user.</summary>
        var gaguid = getParameterByName(config.queryStringArg);

        if (gaguid !== "") {
            /* Request querystring takes precedence. No validation. */
            setCookie(gaguid);
            addToGaq(config.gaIndex, config.gaCustomVarName, gaguid);
        }
        else {

            /* Does a token exist */
            if (typeof Cookies.get(config.cookieName) !== 'undefined') {
                logInfo('Cookie defined - adding guid to gaq');

                /* Add the token to GA */
                addToGaq(config.gaIndex, config.gaCustomVarName, Cookies.get(config.cookieName));
            }
            else {
                /* Generate new Guid */
                var uuid = generateId();

                logInfo('Generated uuid = ' + uuid);

                setCookie(uuid);
                addToGaq(config.gaIndex, config.gaCustomVarName, uuid);

                logInfo('Cookie set with UUID ' + uuid);
            }
        }
    })();
};

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
     * Store the guid in a safe place
     */
	jQuery(function () {
		try {
			window.sophosGaGuidStore.processGaToken({
				useGoogleVid: true,
				outputDebug: false,
				gaScope: 1,
				gaAccount: 'UA-737537-1'
			});
		} catch (e) {
			// do nothing
		}
	});


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
