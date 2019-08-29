<?php
/**
 * Create a summary post with links to posts from yesterday to -n days
 */

namespace Sophos\Post;

class Summary
{
    const CATEGORY = 'Weekly Summary';
    const LAST_RUN = 'sophos_weekly_summary_last_run';
    
    
    /**
     * Title template
     * 
     * @var string $_title
     */
    private $_title = 'Monday review - the hot %d stories of the week';


    /**
     * Start date for summary
     * 
     * @var string $_start
     */
    private $_start = 'now';
    
    
    /**
     * Array of arrays. One array for each day, each day is an array of WP_Post objects
     *
     * @var array
     */    
    private $_posts = array();

    
    /**
     * Period in days that summary covers
     * 
     * @var int
     */
    private $_days  = null;


    /**
     * Count of posts in this summary
     *
     * @var int $_count
     */
    private $_count = 0;


    /**
     * The number of posts in the summary 
     *
     * @var bool $_numberOfPosts
     */
    private $_numberOfPosts = null;


    /**
     * Constructor
     * 
     * @param int $days
     */
	private function __construct ($days) {
        if ( is_int($days) ) {
            $this->_days = $days;
        } else {
            throw new \Exception('Arguments must include a duration in days, I got ' . print_r($days, true));    
        }
	}


    /**
     * Constructor that can be called from cron
     *
     * @param int $days number of days posts to summarise
     */
	public static function create ($days) {        
		$summary = new self($days);
		$summary->_doCreate();
	}
	
    
    /**
     * Create a summary post
     */
	private function _doCreate () {   
	    $count    = $this->getNumberOfPosts();
        $category = term_exists(self::CATEGORY);
        $category = $category ? $category : wp_insert_term(self::CATEGORY, 'category');

        if ( $count ) {   
    	    $user = get_user_by('email', 'tips@sophos.com');
            $uid  = ($user instanceof \WP_User) ? $user->ID : null ;
            
            # FIXME something - I know not what - is stopping this from completing. Most likely a filter
            # in another plugin or bit of code. The post is created but code after this doesn't execute.
            # This can, apparently, be caused by a filter exiting with wp_die() - which you'll never see
            # because it's running under cron. wp_die(). meh. What was wrong with throw?
            $id   = wp_insert_post( array(
    			'post_title'    => sprintf($this->_title, $count),
    			'post_content'  => $this->_content(),
    			'post_excerpt'  => $this->_excerpt(),
    			'post_status'   => 'draft',
    			'post_author'   => $uid,
    			'post_category' => array($category),
     		), true);

            if ( is_numeric($id) and $id > 0 ) {                
                set_post_thumbnail( $id, $this->_thumbnail() );
                update_option(self::LAST_RUN, time());
            } else {
                $error = ( $id instanceof \WP_Error )
                       ? $id->get_error_message()
                       : "The post could not be created. It returned a status of $id";

                throw new \Exception($error);
            }
        }
	}


    /**
     * @return string Excerpt
     */
    private function _excerpt() {
        return esc_html__( 'Get yourself up to date with everything we\'ve written in the last seven days - it\'s weekly roundup time.', 'nakedsecurity' );
    }


    /**
     * @return int Image ID
     */
    private function _thumbnail () {
        return 230400;
    }


    /**
     * Decide if a post is a weekly review 
     *
     * @return (bool)
     */
    public function isNotMondayReview ($post) {
        list($start) = explode('%', $this->_title);
        return ( $start == substr($post->post_title, 0, strlen($start)) ) ? false : true;
    }


    /**
     * Generate the summary article content
     * 
     * @return string HTML
     */
    private function _content () {
        ob_start();
        ?>
            <p>
                <?php echo esc_html( $this->_excerpt() ) ?>
            </p>                
                <?php foreach (range($this->_days, 1) as $day) :
                    
                    $date  = strtotime(sprintf('%s -%d days', $this->_start, $day));
                    $posts = $this->getPostsFromDay($date);

                    if ( count($posts) ) :
                    ?>
                    <h2><?php echo date('l j F Y', $date ); ?></h2>
                    <ul>
                    <?php foreach ($posts as $post) : 
                        if ( $this->isNotMondayReview($post) ) : ?>
                            <li>
                                <a title="<?php echo esc_attr( get_the_title($post->ID) ); ?>" href="<?php echo esc_url( get_permalink($post->ID) ) ?>"><?php echo esc_html( get_the_title($post->ID) ) ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </ul>
               <?php endif; endforeach; ?>
			   <?php 
					$link   = sprintf( '<a title="%s" href="#newsletter-signup">%s</a>', __('Sign up to the newsletter', 'nakedsecurity'), __('daily newsletter', 'nakedsecurity') ); 
					$promo  = esc_html__( 'Would you like to keep up with all the stories we write? Why not sign up for our %s to make sure you don\'t miss anything. You can easily unsubscribe if you decide you no longer want it.', 'nakedsecurity' ); 
					$credit = esc_html__( 'Image of %s courtesy of %s.', 'nakedsecurity' );
				?>
            <p><?php echo sprintf($promo, $link); ?></p>
			[twitter-follow screen_name='NakedSecurity' show_count='yes']
			<p style="font-size:85%;"><em><?php echo sprintf($credit, '<a
					href="https://www.shutterstock.com/pic.mhtml?id=181340945" title="Days of week. Image courtesy of Shutterstock">days of week</a>', '<a href="https://www.shutterstock.com/" title="Shutterstock homepage">Shutterstock</a>'); ?></em></p>
		<?php
        return ob_get_clean();
    }


    /**
     * Get the number of posts in this summary
     * 
     * @return int The number of posts in this summary
     */
    public function getNumberOfPosts () {
        if ( is_null($this->_numberOfPosts) ) {
            $count = 0;
            
            foreach (range($this->_days, 1) as $day) {                
                $date  = strtotime(sprintf('%s -%d days', $this->_start, $day));
                $posts = array_filter( $this->getPostsFromDay($date), array($this,'isNotMondayReview') );

                $count = $count + count( $posts );
            }
            
            $this->numberOfPosts = $count;
        }
        
        return $this->numberOfPosts;
    }


    /**
     * Get an array of posts published on a particular day
     * 
     * @param int $date Date in unixtime format
     * @return array Array of WP_Post objects
     */
    public function getPostsFromDay ($date) {
        if ( !array_key_exists($date, $this->_posts) ) {
            $year  = date('Y', $date);
            $week  = date('W', $date);
            $day   = date('j', $date);
            $query = new \WP_Query( array(
                'post_type' => 'post',
                'year'      => $year, 
                'w'         => $week,
                'day'       => $day,
                'nopaging'  => true
            ));

            $this->_posts[$date] = ( $query->have_posts() ) ? $query->get_posts() : array();
        }

        return $this->_posts[$date];
    }
    

    /**
     * Show information about the weekly summary
     */
    public static function showAdminNotice ()
    {
        if ($last = get_option(self::LAST_RUN)) : ?>
            <div class="updated">
                <p><?php echo esc_html( sprintf('The Weekly Summary was last run at %s', $last) ); #date(DATE_RFC822, $last) ); ?></p>
            </div>
        <?php endif;
    }
}
