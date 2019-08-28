<?php
/**
 * Custom template tags for this theme.
 *
 * @package Sophos
 */

$template_tags = [
	'featured-image',
	'social-links',
	'post-navigation',
	'post-thumbnail',
	'posts-navigation',
	'posted-on',
	'posted-by',
	'entry-footer',
	'archive-title',
	'term-listing',
];

function sophos_load_template_tags( $template_tags ) {
	foreach ( $template_tags as $template_tag ) {
		require get_template_directory() . '/inc/template-tags/' . $template_tag . '.php';
	}
}

sophos_load_template_tags( $template_tags );
