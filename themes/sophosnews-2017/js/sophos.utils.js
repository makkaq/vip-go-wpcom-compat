/*!
 * Sophos utilities
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
