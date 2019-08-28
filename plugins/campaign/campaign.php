<?php

/*
 Plugin Name: Campaign Codes
 Plugin URI: https://nakedsecurity.sophos.com
 Description: Handle Sophos campaign tracking codes
 Version: 0.1.1
 Author: Mark Stockley (Compound Eye Ltd)
 Author URI:
 License: GPL3

 Copyright 2018  Mark Stockley  (email : mark@compoundeye.co.uk)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 2, as
 published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * @package   Sophos
 * @author    Mark Stockley
 * @version   0.1
 * @copyright Copyright (c) 2018, Mark Stockley
 * @license   https://opensource.org/licenses/gpl-3.0.html
 */

namespace Sophos\Campaign;


/**
 * Disallow crawling of URLs with campaign codes in robots.txt
 *
 * @param  [string] $output	Robots.txt output
 * @param  [bool] $public	Whether the site is considered "public"
 * @return [string]	Robots.txt output
 */
function disallow ( $output, $public ) {
	return $output . "Disallow: /*?*cmp=" . PHP_EOL . PHP_EOL;
}

add_filter( 'robots_txt', '\Sophos\Campaign\disallow', 10, 2 );
