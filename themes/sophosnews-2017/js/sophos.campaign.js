/*!
 * Sophos campaigns
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
 	 * @param  {String} str Optional URL or query string containing a campaign ID
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

        var sophos = /^[a-zA-Z0-9\-]+\.(sophos\.com|sophos\.test|go-vip\.net)$/;
        var domain = window.location.hostname.match(sophos).pop();

        if (typeof domain === 'string' && domain.length > 0) {
            Cookies.set('CampaignID', _sophos.Campaign.getCampaignId(str), {
                // set cookie domain based on current tld
                domain: domain,
                expires: 180,
                path: '/'
            });
        }
    };

})(Sophos, jQuery);
