/*!
 * Sophos Google Analytics
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
