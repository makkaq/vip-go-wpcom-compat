<?php

/*
 Plugin Name: Weekly Summary
 Plugin URI: https://nakedsecurity.sophos.com
 Description: Auto-generate Naked Security weekly summary articles
 Version: 0.3
 Author: Mark Stockley (Compound Eye Ltd)
 Author URI: 
 License: GPL3

 Copyright 2013  Mark Stockley  (email : mark@compoundeye.co.uk)

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
 * @package Weekly Summary
 * @author Mark Stockley
 * @version 0.3
 * @copyright Copyright (c) 2013, Mark Stockley
 * @license https://opensource.org/licenses/gpl-3.0.html
 */

namespace Sophos\Post;

class Controller {
    const START = "next monday";
    
    /**
     * Controller object
     * 
     * @var $_instance
     */
    private static $_instance = null;
    
    
    /**
     * Singleton accessor
     * 
     * @return object \Sophos\Post\Controller
     */
    public static function run () {
        require_once 'Cron.php';
        require_once 'Summary.php';
        
        self::$_instance = ( !is_null(self::$_instance) ) ? self::$_instance : new self();
    }
    
    
    /**
     * Constructor
     */
	private function __construct () {
        $this->scheduleWeelySummary();
	}


    /**
     * Schedule the creation of a weekly summary article
     */
	public function scheduleWeelySummary () {
		$code = array('\Sophos\Post\Summary', 'create');
		$cron = new \Sophos\Cron\Weekly();

        $cron->setName('sophos_weekly_summary');
        $cron->setStart( strtotime(date("Y-m-d",strtotime(self::START))) );
		$cron->addCode($code, array( 'days' => 7 ));
	}
}

\Sophos\Post\Controller::run();
