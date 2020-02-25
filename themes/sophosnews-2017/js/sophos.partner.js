/*!
 * Sophos partner
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
