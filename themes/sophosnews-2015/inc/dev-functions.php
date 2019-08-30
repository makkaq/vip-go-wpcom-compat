<?php

/**
 * Temporary helper functions for dev placeholders.
 */

// Optionally turn off placeholder images.
$unsplash_enabled = true;

$articles = [
	'We don\'t cover stupid, says a cyber insurer that\'s fighting a payout.',
	'Facebook moves to encrypt the emails it sends users',
	'Google\'s new My Account let\'s you tweak privacy and security settings.',
	'Serious Security: China Internet Network Information Center in...',
	'India strikes down controversial "Section 66A" social media...',
	'RadioShack to auction off customer data, violating own privacy...',
	'Serious Security: China Internet Network Information Center in...',
	'Top Silk Road drug dealer sentenced to 5 years',
	'Grade-hacking case brings 16 more felony charges for private...',
	'Were celebrating Sysadmin Day! Are you?',
	'COMPUTER RULES, LAST UPDATED 31 JULY 1988',
];
function sophos_article_card_placeholder() {
	global $articles;

	$html = '';
	$html .= '<article class="card-article">' . PHP_EOL;
	$html .= '	<div class="card-image">' . PHP_EOL;
	$html .= '		<div class="image-pointer"></div>' . PHP_EOL;
	$html .= '		<a href="">' . sophos_dynamic_image_placeholder( 412, 200, true ) . '</a>' . PHP_EOL;
	$html .= '	</div>' . PHP_EOL;
	$html .= '	<div class="card-content">' . PHP_EOL;
	$html .= '		<div class="meta-box">' . PHP_EOL;
	$html .= '			<div class="date-box"><span class="month">Aug</span><span class="day">02</span></div>' . PHP_EOL;
	$html .= '			<div class="categories-box"><a href="">news</a></div>' . PHP_EOL;
	$html .= '		</div>' . PHP_EOL;
	$html .= '		<h3 class="card-title"><a href="">' . $articles[ array_rand( $articles ) ] . '</a></h3>' . PHP_EOL;
	$html .= '	</div>' . PHP_EOL;
	$html .= '</article> <!-- .card-article -->' . PHP_EOL;

	return $html;
}

function sophos_story_snippet_placeholder() {
	global $articles;

	$html = '';
	$html .= '<article class="story-snippet">' . PHP_EOL;
	$html .= '	<div class="story-image">' . PHP_EOL;
	$html .= '		<a href="">' . sophos_dynamic_image_placeholder( 100, 100 ) . '</a>' . PHP_EOL;
	$html .= '	</div>' . PHP_EOL;
	$html .= '	<div class="story-content">' . PHP_EOL;
	$html .= '		<div class="story-meta ">' . PHP_EOL;
	$html .= '			<div class="story-categories"><a href="">news</a></div>' . PHP_EOL;
	$html .= '			<div class="story-date">June 1, 2015</div>' . PHP_EOL;
	$html .= '		</div>' . PHP_EOL;
	$html .= '		<h3 class="story-title"><a href="">' . $articles[ array_rand( $articles ) ] . '</a></h3>' . PHP_EOL;
	$html .= '	</div>' . PHP_EOL;
	$html .= '</article> <!-- .story-snippet -->' . PHP_EOL;

	return $html;
}

function sophos_feed_title_placeholder() {
	global $articles;

	$html = '';
	$html .= '<article class="feed-article">' . PHP_EOL;
	$html .= '	<div class="feed-content">' . PHP_EOL;
	$html .= '		<h4 class="feed-title"><a href="">' . $articles[ array_rand( $articles ) ] . '</a></h4>' . PHP_EOL;
	$html .= '	</div>' . PHP_EOL;
	$html .= '</article> <!-- .feed-article -->' . PHP_EOL;

	return $html;
}

function sophos_dynamic_image_placeholder( $width = 640, $height = 480, $zoom = false ) {
	global $unsplash_enabled;

	$zoom_class = '';
	if ( $zoom ) {
		$zoom_class = ' with-zoom';
	}

	$html = '<div class="dynamic-image-frame' . $zoom_class . '"><div class="dynamic-image"></div></div>';

	if ( $unsplash_enabled ) {
		$html = '<div class="dynamic-image-frame' . $zoom_class . '"><div class="dynamic-image" style="background-image:url(https://unsplash.it/' . $width . '/' . $height . '?image=' . rand( 1, 764 ) . ');"></div></div>';
	}

	return $html;
}