(function ( $ ) {

	// Resize featured slide image to adjacent content frame
	function setSlideImageHeight() {
		var slideArticle = $( '.slide-article' );
		var contentFrame = slideArticle.find( '.content-frame' );
		var contentFrameHeight = contentFrame.innerHeight();

		slideArticle.find( '.dynamic-image' ).css("min-height", contentFrameHeight);
	}

	$( document ).ready( function () {

		// Show mobile menu
		// $('#mobile-menu-switch .toggle').click(function(event) {
		// 	event.stopPropagation();
		// 	$(this).toggleClass('on');
		// 	$('#site-navigation').toggle();
		// 	setMobileMenuHeight();
		// 	event.preventDefault();
		// });

		$( document.body ).ajaxPageLoader( {
			content:     '.content-wrapper',
			next:        '.load-more a',
			infinScroll: false,
			spinOpts:    {
				lines:   10,
				length:  4,
				width:   2,
				radius:  6,
				scale:   0.25,
				color:   '#71A865',
				corners: 0.75,
				opacity: 0.25,
				trail:   25,
				hwaccel: true,
				speed:   3,
				top:     "105px",
			}
		} );

		$( '.slide-collection' ).slick( {
			slide:  '.slide-article',
			arrows: false,
			dots:   true
		} );

		$( '.recommended-panel .card-collection' ).slick( {
			slide:          '.card-slide',
			arrows:         false,
			dots:           true,
			slidesToShow:   3,
			slidesToScroll: 3,
			responsive:     [
				{
					breakpoint: 768,
					settings:   {
						slidesToShow:   1,
						slidesToScroll: 1
					}
				}
			]
		} );

		$( '.video-image a, .video-title a' ).magnificPopup( { type: 'iframe' } );

		$( '.article-social-links li[class!=rss] a' ).click( function ( e ) {
			e.preventDefault();
			window.open( this.href, "", "toolbar=0, status=0, width=500, height=500" );
		} );

		$( '.js-collapse-footer' ).click( function ( e ) {
			e.preventDefault();
			if ( window.innerWidth < 769 ) {
				$( this ).parent().toggleClass( 'is-open' );
				$( "#collapseFooter" ).slideToggle( 'slow' );
			}

		} );

		// Toggle search box
		$( '.search-toggle' ).click( function ( e ) {
			e.preventDefault();
			$( '.site-header .site-search-block' ).toggle();
			$( '.site-header input.search-field' ).focus();

			$( '.header-container' ).toggleClass( 'is-open' );
		} );

		// Hide search box with esc
		$( document ).keydown( function( e ) {
			var key = e.which;
			if( key === 27 ) {
				$( '.site-header .site-search-block' ).hide();
				$( '.search-toggle' ).removeClass('is-open');
			}
		});

		// Legacy Cleanup
		function removeDuplicateImages() {
			var featImg  = $( '.entry-featured-image img' );
			var entryImg = $( '.entry-content img' );

			if ( entryImg.length & featImg.length ) {
				var entryImgName = entryImg.attr( 'src' ).match(/.*\/(.*)$/)[1];
				var featImgName  = featImg.attr( 'src' ).match(/.*\/(.*)$/)[1];

				if ( featImgName === entryImgName ) {
					entryImg.remove();
				}
			}
			// console.log( featImgName );
			// console.log( entryImgName );
		}

		function destroyTT() {
			$( '.entry-content tt' ).each( function () {
				var $parent = $( this ).parent();

				if ( $parent.is( 'ul' ) || ( $parent.is( 'p' ) && $parent.parent().is( 'blockquote' ) ) ) {
					$( this ).replaceWith( this.childNodes );
				}

				if ( $( this ).children( 'strong' ).length > 0 ) {
					$( this ).children().unwrap();
				}

			} );
		}

		if ( $( 'body' ).hasClass( 'single-post' ) ) {
			removeDuplicateImages();
			destroyTT();
		}

		// Hide or show breaking news panel based on cookie & click
		var breakingNewsPanel = $( '.breaking-news-panel' );
		var breakingPostId = breakingNewsPanel.attr( 'id' );
		var breakingHidden = Cookies.getJSON( 'breaking_news' );

		if( typeof breakingHidden === "undefined" ) {
			breakingHidden = {};
		}

		// If the current post_ID exists in the cookie, hide the breaking news panel.
		if ( typeof breakingHidden !== "undefined" ) {
			if ( breakingHidden[ breakingPostId ] === 'hidden' ) {
				breakingNewsPanel.hide();
			} else {
				breakingNewsPanel.show();
			}
		} else {
			breakingNewsPanel.show();
		}

		// Add the current post_ID to the cookie and save it.
		$( '#hide-breaking-news' ).click( function ( e ) {
			e.preventDefault();
			breakingNewsPanel.hide();
			breakingHidden[ breakingPostId ] = 'hidden';
			Cookies.set( 'breaking_news', breakingHidden, { expires: 30, path: '/' } );
		} );

		// Delay this function to grab an accurate height
		setInterval( function () {
			setSlideImageHeight();
		}, 10 );


	} );

	$( window ).resize( function () {
		setSlideImageHeight();
	} );

	// Resize mobile menu to device viewport
	// function setMobileMenuHeight() {
	// 	var headerHeight = $('#masthead').outerHeight();
	// 	var windowHeight = window.innerHeight;
	// 	var menuOffset = headerHeight;
	// 	var menuHeight = windowHeight - menuOffset;

	// 	$("#site-navigation").css("height", menuHeight);
	// 	// .css("top", menuOffset);
	// }

})( jQuery );

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
 * Naked Security
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
     * @namespace Sophos.Newsletter
     */
    _sophos.Newsletter = _sophos.Newsletter || {};


    /**
     * CSS selector for newsletter sign-up form
     *
     * @type String
     */
    var signUp = 'form#newsletter';


    /**
     * CSS selector for where to attach a newsletter signup form
     *
     * @type String
     */
    var attach = '#newsletter-signup';


    /**
     * Default classes for the newsletter signup form
     *
     * @type String
     */
    var newsletterClasses = 'newsletter-panel contained';


    /**
     * Newsletter cookie name
     *
     * @type String
     */
    var newsletterCookie = 'nakedsecurity-hide-newsletter';


    /**
     * Alter the placement & styles of newsletter signup for single post pages
     *
     * @type String
     */

    if (jQuery('body').hasClass('single-post')) {
        var campaigns = ['top']; // , 'middle', 'bottom']; <--- see results below
        var campaign  = campaigns[Math.floor(Math.random() * campaigns.length)];

        switch (campaign) {
            case 'top':
                newsletterClasses = 'newsletter-panel';
                attach = '#newsletter-signup.top';
                break;
			/*

			Option 'middle' and 'bottom' have been retired following a one month A/B/C test. VIP don't like us
			just commenting out code but I'm commenting the retired case statements because we're likely to run
			more tests with more case statements and I don't want to accidentally re-run the retired options.

			The results from 08 Sept 2016 to 05 Oct 2016 were as follows:

			Option		Views		Sign-ups	Conversion Rate
			-------------------------------------------------------
			   Top		222,789		719			0.32%
			Middle		222,670		137			0.06%
			Bottom		222,973		46

			case 'middle':
                newsletterClasses = 'newsletter-panel';
                attach = '#newsletter-signup.middle';
                break;
            case 'bottom':
                newsletterClasses = 'newsletter-panel';
                attach = '#newsletter-signup.bottom';
                break;
			*/
        }
    }


    /**
     * Newsletter sign-up form handler
     *
     * @returns {Boolean}
     */
    _sophos.Newsletter.join = function() {
        var input = jQuery(this).find('input.email');

        // If the input's untouched, stop
        if (!input.val() || input.hasClass('virgin')) {
            return false;
        } else if ( !Sophos.Utils.isEmailAddress(input.val()) ) {
            _sophos.Newsletter.badEmail();
        } else {
            _sophos.Newsletter.busy();
            jQuery.ajax({
                url: Sophos.ajaxurl, // created by WP
                type: "post",
                data: {
                    "action": "newsletter_subscribe", // Required by wp's admin-ajax
                    "email": input.val(),
                    "nonce": Sophos.nonce // created by WP
                },
                dataType: "text",
                context: jQuery(this),
                timeout: _sophos.Newsletter.error,
                error:   _sophos.Newsletter.error,
                success: _sophos.Newsletter.ok
            });
        }
    };


    /**
     * Newsletter sign-up form state for a bad email address
     */
    _sophos.Newsletter.badEmail = function() {
        jQuery(signUp).removeClass().addClass('bademail').find('input:disabled').prop('disabled', false);
    };


    /**
     * Newsletter sign-up form state during the request
     */
    _sophos.Newsletter.busy = function() {
        jQuery(signUp).removeClass().addClass('busy').find('input').prop('disabled', true);
    };


    /**
     * Newsletter sign-up form state for a successful subscription
     */
    _sophos.Newsletter.success = function() {
        jQuery(signUp).removeClass().addClass('success');
        jQuery( '.' + newsletterClasses.split(' ').join('.') ).delay(3000).fadeOut(1000);
        Cookies.set( newsletterCookie, true, { expires: 3650, path: '/' } );
    };


    /**
     * Newsletter sign-up form state for a failed subscription
     */
    _sophos.Newsletter.failure = function() {
        jQuery(signUp).removeClass().addClass('failure');
        jQuery( '.' + newsletterClasses.split(' ').join('.') ).delay(3000).fadeOut(1000);
    };

    _sophos.Newsletter.remove = function() {
        jQuery(attach).remove();
        Cookies.set( newsletterCookie, true, { expires: 3650, path: '/' } );
    };

    /**
    * Check for the newsletter cookie. Hidden if completed or user opts to not show it.
    */
    _sophos.Newsletter.show = function() {
        return !Cookies.get( newsletterCookie );
    };


    /**
     * Handle errors in the AJAX request
     *
     * @param {Object} jqXHR
     * @param {String} textStatus
     * @param {String} errorThrown
     */
    _sophos.Newsletter.error = function(jqXHR, textStatus, errorThrown) {
        switch ( parseInt(errorThrown, 10) ) {
            case 502:
                _sophos.Newsletter.badEmail();
                break;
            default:
                _sophos.Newsletter.failure();
        }
    };


    /**
     * Handle a successful AJAX request
     *
     * @param {String} data
     * @param {String} textStatus
     * @param {Object} jqXHR
     */
    _sophos.Newsletter.ok = function(data, textStatus, jqXHR) {
        switch (parseInt(data)) {
            case -1:
                _sophos.Newsletter.failure();
                break;
            case  0:
                _sophos.Newsletter.badEmail();
                break;
            case  1:
                if (window._gaq) {
                    var message = (campaign) ? 'Email Newsletter sign-up - ' + campaign : 'Email Newsletter sign-up';
                    window._gaq.push(['ns._trackEvent', 'Naked Security', message]);
                }
                _sophos.Newsletter.success();
                break;
        }
    };


    /**
     * Keep the sign-up form content out of Google listings by loading it with Javascript
     *
     */
    jQuery(document).ready(function() {

        if ( _sophos.Newsletter.show() ) {

            jQuery(attach).append(
                '<section class="' + newsletterClasses + '">'+
                  '<div class="container">'+
                    '<div class="panel-content">'+
                        '<form onsubmit="return false" method="post" action="" id="newsletter" class="ready" autocomplete="off">' +
                            '<div class="newsletter-messaging">' +
                                '<h3 class="newsletter-title success-hide">Get the latest security news in your&nbsp;inbox.</h2>' +
                                '<div class="state failure"><div class="icon"></div><p>Sorry, something happened and we could not sign you up. Please try again later.</p></div>' +
                                '<div class="state success"><div class="icon"></div><h3>Congratulations, you have successfully signed up for our daily news!</h3><p>Check your inbox for our confirmation email.</p></div>' +
                                '<div class="state bademail"><p>Sorry, we will not accept that email address. Please try a different address.</p></div>' +
                                '<div class="state busy"><p>We\'re adding your address to our list...</p></div>' +
                            '</div>' +
                            '<div class="state ready controls">' +
                                '<div class="capture">' +
                                    '<input type="text" value="you@example.com" class="email virgin" name="email"><input type="submit" value="Subscribe" class="button" name="submit">' +
                                '</div>' +
                            '</div>' +
                            '<button class="close-link">Don\'t show me this again</button>' +
                        '</form>' +
                '</div></div></section>'
            );

            // Add handlers to understand if the input's been touched
            Sophos.Utils.virginise(jQuery(signUp).find('input.email'));

            // Setup Ajax handler
            jQuery(signUp).on('submit', _sophos.Newsletter.join);

            // Setup don't show
            jQuery('.close-link', attach).on('click', _sophos.Newsletter.remove);

            if (window._gaq && campaign) {
                window._gaq.push(['ns._trackEvent', 'Naked Security', 'Show newsletter - ' + campaign]);
            }
        } else {
            jQuery(attach).remove();
        }
    });


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
    _sophos.Campaign.defaultCampaignId = '70130000001xGqlAAE';


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
		_sophos.Ad.setupAdTracking('ns');
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
        ['_trackPageview', '/nakedsecurity'+window.location.pathname+window.location.search]
    );


    /**
     * Add data to the nakedsecurity.sophos.com profile
     */
    window._gaq.push(
        ['ns._setAccount', 'UA-737537-25'],
        ['ns._setCustomVar', 4, 'CampaignID', Sophos.Campaign.getCampaignId(), 3],
        ['ns._trackPageview']
    );


    /**
     * Create the appropriate Google Analytics script element
     */
    (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' === document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
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


	/**
	 * @namespace Sophos.Ad
	 */
	_sophos.Survey = _sophos.Survey || {};


	/**
	 * Setup the survey
	 *
	 * Trigger our user survey. The survey is anonymous and asks users to
	 * answer six questions. Alongside the questions we capture the article
	 * title, author, categories and tags. We also generate an ID so that we
	 * can get the session source (e.g. Google, Facebook) from Google Analytics.
	 * No PII is captured in either the survey or Google Analytics.
	 */
	_sophos.Survey.setup = function () {
		var dice     = 15;
		var cookie   = 'user-survey';
		var value    = 1;
		var expires  = 365;
		var category = 'User Survey';

		// Check it's an English article
		if (window.location.pathname.search(/^\/\d\d\d\d\/\d\d\/\d\d\/[^\/]+\/?$/) >= 0) {
			// Check if this user's seen it
			if (!Cookies.get(cookie)) {
				// Roll a dice
				if (1 === Math.ceil(Math.random() * dice)) {
					(function(d,c,j){if(!document.getElementById(j)){var pd=d.createElement(c),s;pd.id=j;pd.src=('https:'===document.location.protocol)?'https://polldaddy.com/survey.js':'http://i0.poll.fm/survey.js';s=document.getElementsByTagName(c)[0];s.parentNode.insertBefore(pd,s);}}(document,'script','pd-embed'));

					window._polldaddy = window._polldaddy || [];

					var pdtags = {
						'uuid': Sophos.Utils.uuid.generate(),
						'title': jQuery('.entry-header h1.entry-title').text(),
						'author': jQuery('.entry-author a[rel="author"]').text(),
					};

					// Add article categories to list of tags
					jQuery('.entry-categories a').each(function() {
						pdtags[jQuery(this).text()] = 1;
					}).get().join(',');

					// Add article tags to list of tags
					jQuery('.entry-tags a[rel="tag"]').each(function() {
						pdtags[jQuery(this).text()] = 1;
					}).get().join(',');

					window._polldaddy.push( {
						type: "iframe",
						auto: "1",
						height: '568px',
						domain: "sophoslabs.polldaddy.com/s/",
						id: "user-survey",
						placeholder: "pd_1499706777",
						tags: pdtags
					});

					jQuery.magnificPopup.open({
						modal: false,
						showCloseBtn: true,
						closeBtnInside: true,
						enableEscapeKey: true,
						closeOnBgClick: false,
						alignTop: true,
						items: {
							src: '#survey-wrapper',
							type: 'inline'
						},
						callbacks: {
							open: function () {
								Cookies.set(cookie, 1, { 'expires': expires });
								window._gaq.push(['ns._setCustomVar', 1, category, pdtags.uuid, 2]);
							}
						}
					});
				}
			}
		}
	};

	// Commenting rather than deleting this because we'll be switching it back
	// on sooner or later and I don't want to forget how we did it ;)
	// jQuery(document).ready( _sophos.Survey.setup );

})(Sophos, jQuery);

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
         * @namespace Sophos.Podcast
         */
        _sophos.Podcast = _sophos.Podcast || {};




        /**
         * [description]
         * @param  {Number} position Position in milliseconds
         * @return {[type]}          [description]
         */
        _sophos.Podcast.savePodcastPosition = function (position) {
            // FIXME, include episode number
            // FIXME, validate position
            console.log('Playback saved at ' + position + ' milliseconds');

            if ('number' === typeof position && position < 7200000) {
                if (position > 0) {
                    Cookies.set('podcast-position', position);
                }
            }
        };

        _sophos.Podcast.init = function () {
            var iframe   = document.querySelector('.podcast iframe');
            var close    = document.querySelector('.podcast .close');
            var widget   = SC.Widget(iframe);
            var position = Sophos.Utils.cast(Cookies.get('podcast-position'));



            widget.bind(SC.Widget.Events.READY, function () {
                console.log('Widget is ready');

                if ('number' === typeof position && position < 7200000) {
                    widget.seekTo(position);
                    console.log('Setting podcast to previously save position ' + position);
                } else {
                    console.log('Something wrong with position ' + position);
                }
            });


            widget.bind(SC.Widget.Events.PLAY, function () {
                console.log('User hit play');

                Sophos.Podcast.panel.classList.add('play');
                Sophos.Podcast.panel.classList.add('sticky');
            });

            widget.bind(SC.Widget.Events.PAUSE, function () {
                console.log('Podcast is paused');

                Sophos.Podcast.panel.classList.remove('play');
                Sophos.Podcast.panel.classList.add('paused');

                widget.getPosition(function (pos) {
                    console.log('getPosition: ' + pos);
                    Sophos.Podcast.savePodcastPosition(pos);
                });
            });

            widget.bind(SC.Widget.Events.SEEK, function () {
                console.log('User is seeking');

                widget.getPosition(function (pos) {
                    console.log('getPosition: ' + pos);
                    Sophos.Podcast.savePodcastPosition(pos);
                });
            });

            window.addEventListener('beforeunload', function () {
                widget.getPosition(function (pos) {
                    console.log('User is leaving, saving final position: ' + pos);
                    Sophos.Podcast.savePodcastPosition(pos);
                });
            });

            var unpin = function (event) {
                event.preventDefault();
                widget.pause();
                Sophos.Podcast.panel.classList.remove('play');
                Sophos.Podcast.panel.classList.remove('sticky');
            }

            close.addEventListener('click', unpin);
            close.addEventListener('touchstart', unpin);
        };


        _sophos.Podcast.loadSoundCloudAPI = function () {

            var sc = document.createElement('script');

            sc.addEventListener('load', function () {
                console.log('API loaded');

                if (!SC || 'object' !== typeof SC) {
                    console.log('SC is not an object');
                    return false;
                }

                if (!Cookies || 'function' !== typeof Cookies) {
                    console.log('Cookies is not a function');
                    return false;
                }

                Sophos.Podcast.init();
            });
        	sc.src   = 'https://w.soundcloud.com/player/api.js';
            sc.type  = 'text/javascript';
            sc.async = true;

            document.getElementsByTagName('head')[0].appendChild(sc);
        }


        jQuery('.podcast iframe').on('load', function () {
            console.log('Podcast has loaded');


            Sophos.Podcast.panel = document.querySelector('.about-panel');
            Sophos.Podcast.loadSoundCloudAPI();
        });



        //document.querySelector()

})(Sophos, jQuery);
