<?php

/**
 * Remove legacy shortcodes
 * http://siteturner.com/dt-shortcodes/
 */
add_action( 'after_setup_theme', function () {
	$legacy_shortcodes = [
		'dt_list',
		'dt_gap',
		'dt_divider',
		'dt_tooltip',
		'dt_quote',
		'dt_alert',
		'dt_button',
		'dt_icon',
		'dt_highlight',
		'dt_accordian',
		'dt_accordian_section',
		'dt_toggle',
		'dt_tabgroup',
		'dt_tab',
		'dt_one_half',
		'dt_one_third',
		'dt_one_fourth',
		'dt_one_sixth',
		'dt_progressbar',
		'dt_pricing_group',
		'dt_pricing',
		'twitter-follow' // Legacy VIP Twitter shortcode
	];

	foreach ( $legacy_shortcodes as $shortcode ) {
		add_shortcode( $shortcode, '__return_false' );
	}
});


/**
 * Add an inline aweber sign-up form
 *
 * @param $atts Array of attributes
 */
add_shortcode( 'aweber', function ( $atts ) {

	if ( ! array_key_exists( 'id', $atts ) ) {
		return;
	}

	$id = filter_var( $atts['id'], FILTER_VALIDATE_REGEXP, [
		'options' => [
			'regexp' => '/^([a-z0-9]+|1o83qdl8u)$/',
		],
	]);

	if ( false === $id ) {
		return;
	}

	// Fix the incorrect French ID I told Jerome to use ;)
	if ( '1o83qdl8u' === $id ) {
		$id = 286036380;
	}

	ob_start();
	?><center><div class="AW-Form-<?php echo esc_attr( $id ); ?>"></div>
	<script type="text/javascript">(function(d, s, id) {
	    var js, fjs = d.getElementsByTagName(s)[0];
	    if (d.getElementById(id)) return;
	    js = d.createElement(s); js.id = id;
	    js.src = "//forms.aweber.com/form/80/<?php echo esc_attr( $id ); ?>.js";
	    fjs.parentNode.insertBefore(js, fjs);
	}(document, "script", "aweber-wjs-<?php echo esc_attr( $id ); ?>"));
	</script></center><?php

	return ob_get_clean();
});
