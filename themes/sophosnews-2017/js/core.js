(function ($) {

	// Resize mobile menu to
	function setMobileMenuHeight() {
		var headerHeight = $( '#masthead' ).outerHeight();
		var windowHeight = window.innerHeight;
		var menuOffset = headerHeight;
		var menuHeight = windowHeight - menuOffset;

		$( "#site-navigation" ).css( "height", menuHeight );
		// .css("top", menuOffset);
	}

	function hasClass( element, className ) {
		return element.className.match( new RegExp( '(\\s|^)' + className + '(\\s|$)' ) );
	}

	// Control the sticky sidebar for mobile/tablet and desktop.
	function toggleStickySidebar( mq ) {
		if ( mq.matches ) {
			Stickyfill.stop();
		} else {
			Stickyfill.init();
			$( '#content .content-container' ).height( 'auto' );
		}
	}

	function calculateContentHeight( mq ) {
		if ( mq.matches ) {
			$( '#content .content-container' ).height( $( '.content-area' ).outerHeight( true ) );
		} else {
			$( '#content .content-container' ).height( 'auto' );
		}
	}

	$( document ).ready(function() {

		// Show mobile menu
		$( '#mobile-menu-switch .toggle' ).click(function(event) {
			event.stopPropagation();
			$( this ).toggleClass( 'on' );
			$( '#site-navigation' ).toggle();
			setMobileMenuHeight();
			event.preventDefault();
		});

		// Sticky Sidebar
		$( '#secondary' ).Stickyfill();
		var mq = window.matchMedia( "(max-width: 768px)" );
		mq.addListener( toggleStickySidebar );
		toggleStickySidebar( mq );

		// Open social sharing links in new window
		$( '.js-share-modal' ).click(function(event) {
			window.open( this.href, '', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0' );
			event.preventDefault();
		});

        // Toggle Search Bar & Focus
		$( 'a[href="#search"]' ).click(function( event ) {
			$( '.site-search-block' ).show().find( '.search-field' ).focus();
			$( '.news-header-navigation' ).addClass( 'search-active' );
			event.preventDefault();
		});
		$( 'a[href="#search-close"]' ).click(function( event ) {
			$( '.site-search-block' ).hide();
			$( '.news-header-navigation' ).removeClass( 'search-active' );
			event.preventDefault();
		});

		// Toggle Sophos News Nav
		$( 'a[href="#newsmenu"]' ).click(function(event) {
			$( this ).parent().toggleClass( 'active' );
			$( '.news-header-navigation' ).toggleClass( 'active' );
			event.preventDefault();
		});

		// Mobile Footer: Latest/Featured article toggle
		$( '.mobile-footer a' ).click(function(event) {
			var selector = '.content-area';
			if ( this.hash === '#featured' ) {
				selector = '.widget-area';
			}
			if ( this.hash === '#latest' ) {
				selector = '.content-area';
			}

			$( '.content-area' ).removeClass( 'active' );
			$( '.widget-area' ).removeClass( 'active' );

			$( selector ).addClass( 'active' );

			$( '#content .content-container' ).height( $( selector ).outerHeight( true ) );

			$( this ).parent().parent().find( 'li' ).removeClass( 'active' );
			$( this ).parent().addClass( 'active' );

			event.preventDefault();
		});

		$( '.site-footer-title' ).click( function( event ) {
			var element = $( this );
			element.toggleClass( 'submenu-visible-on-mobile' );
			element.next( '.menu' ).toggleClass( 'menu-visible-on-mobile' );

			event.preventDefault();
		});

		// Run on front page only
		if ($( 'body' ).hasClass( 'home' )) {
			mq.addListener( calculateContentHeight );
			calculateContentHeight( mq );
		}

		// Setup Ajax Page Loader
		$( document.body ).ajaxPageLoader({
			content: '.ajax-content-wrapper',
			next: '.load-more a',
			infinScroll: false,
			spinOpts:    {
				lines:   10,
				length:  4,
				width:   2,
				radius:  6,
				scale:   0.25,
				corners: 0.75,
				opacity: 0.25,
				trail:   25,
				hwaccel: true,
				speed:   3,
				top:     "50px",
			}
		});
	});

	$( window ).resize(function() {
			setMobileMenuHeight();
	});

})(jQuery);
