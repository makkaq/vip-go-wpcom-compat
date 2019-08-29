<?php
/**
 * Create a weekly cron job
 */

namespace Sophos\Cron;

class Weekly 
{
    const version     = 0.3;
	const schedule    = 'sophos_cron_weekly';
	const interval    = 604800;
	const description = 'Weekly';


    /**
     * @var int Name of cron job
     */
    private $_name = null;


    /**
     * @var int Start date in unixtime
     */
	private $_start = null;


    /**
     * 
     */
	public function __construct () {        
		$create = array($this, 'createWordpressSchedule');

        // Establish a weekly cron schedule if it doesn't exist
		if ( !has_filter('cron_schedules', $create) ) {
			add_filter('cron_schedules', $create);
		}
	}
    

    /**
     * Set the name
     * 
     * @param string $name
     */
    public function setName ($name) {
        $this->_name = $name;
    }

    
    /**
     * Get the name
     * 
     * @return string Name
     */
    public function getName () {
        return $this->_name;
    }

	/**
	 * Set the start time
     * 
	 * @param int $time unixtime
	 */
	public function setStart ($time) {
		$this->_start = $time;
	}

	
    /**
     * Get the start time
     * 
     * @return int unixtime
     */
	public function getStart () {
		return (!is_null($this->_start)) ? $this->_start : strtotime("now");
	}


    /**
     * Add a callback to the cron job
     * 
     * @param array $code Function or method called by the cron job
     * @param array $args Arguments to pass to the cron job
     */
	public function addCode (array $code, array $args = array()) {
	    if ($action = $this->getName()) {
            $schedule = get_option(self::schedule);
            $version  = get_option('sophos_cron_version');
    
            if ( !has_action($action, $code) ) {
                add_action($action, $code);
            }

            if ( ($schedule != self::interval) or ($version != self::version) or !wp_next_scheduled($action, $args) ) {
                $this->_clearCronJobsByHook($action);
                update_option('sophos_cron_version', self::version);
                update_option(self::schedule, self::interval);
                
                if ( $start = $this->getStart() ) {
                    $event = wp_schedule_event($start, self::schedule, $action, $args);
    
                    if ($event !== false) {
                        $schedule = wp_next_scheduled($action, $args);
                        
                        update_option($action, $schedule);
                    } else {
                         throw new \Exception("The scheduled event could not be added");    
                    }        
                } else {
                    throw new \Exception("The start date has not been set");
                }
            }            
        }
        else {
            throw new \Exception('You have not set a name for the cron job');
        }
	}


    /**
     * Show information about the state of weekly crons
     */
    public function showAdminNotice () {
        $name     = $this->getName();
        $start    = date(DATE_RFC822, get_option($name));
        $interval = self::interval; 
        ?>
        <div class="updated">
            <p><?php echo esc_html( sprintf( __( 'The cron job %s will start on %s and run every %d seconds', 'nakedsecurity' ), $name, $start, $interval) ); ?></p>
        </div>
        <?php
    }


    /**
     * Clear all scheduled jobs with the same hook
     * 
     * @param string $hook the name of an action hook
     */
    private function _clearCronJobsByHook ($hook) {
        // This is how we'd like to do it but it doesn't work if you created 
        // jobs with arguments and try to delete them without arguments
        // wp_clear_scheduled_hook( $hook );

        if ($crons = get_option('cron')) {
            foreach ($crons as $time => $value) {
                if (is_array($value) && array_key_exists($hook, $value)) {
                    unset($crons[$time][$hook]);

                    if ( array_key_exists($time, $crons) && empty($crons[$time])) unset($crons[$time]);                    
                }
            }
            
            update_option('cron', $crons);
        }
    }


    /**
     * Create a new wordpress schedule for this class
     * 
     * @param array $schedules Wordpress schedules array
     */
    public function createWordpressSchedule ($schedules) {        
		if ( is_array($schedules) ) {
		    if ( !array_key_exists(self::schedule, $schedules) 
		    or (  array_key_exists(self::schedule, $schedules) && $schedules[self::schedule]['interval'] != self::interval ) ) {
                $schedules[self::schedule] = array(
                    'interval' => self::interval,
                    'display'  => __( self::description )
                );
            }
		}

		return $schedules;
	}
}
