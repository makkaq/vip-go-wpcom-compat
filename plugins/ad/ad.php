<?php

/*
 Plugin Name: Sophos Ad
 Plugin URI: https://www.sophos.com
 Description: Manage inline ads as widgets
 Version: 1.1.3
 Author: Mark Stockley (Compound Eye Ltd)
 Author URI: compoundeye.co.uk
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
 * @version   1.1.3
 * @copyright Copyright (c) 2018, Mark Stockley
 * @license   https://opensource.org/licenses/gpl-3.0.html
 */

require 'class-widget.php';
require 'widget/class-category.php';
require 'widget/class-random.php';
require 'class-shortcode.php';

add_action( 'widgets_init', function() {
	// Register ONE OF Category or Random, NOT BOTH
	// register_widget( '\Sophos\Ad\Widget\Category' );
	register_widget( '\Sophos\Ad\Widget\Random' );
});
