<?php
/*
 * Need to fetch all selected categories from settings once using wp single event with a max number of articles
 *
 * Make sure the fetch articles doesnt get submitted to newswire again
 *
 */
if (!function_exists('fetch_feed')) {
    require_once ABSPATH . WPINC . '/feed.php';
}

class Newswire_Freesites {

    private $max_posts = 0;
    /**
     * cache imported articles so we use it to check dpulicates based on article permalink
     */
    private $_imported = array();
    /**
     *
     */
    private $_save_to_db = true;
    /**
     *
     */
    
    /**
     *
     */
    //private $_http://newswire.net/rssfeeds/channel/feed/rss/article/category/2
    private $_cron_schedules = array('newswire_cron_hourly', 'newswire_cron_twice_daily', 'newswire_cron_once_daily');

    /**
     * Class constructor
     */
    public function __construct() {

        $this->_options = newswire_options();

        //add custom cron schedules
        add_filter('cron_schedules', array($this, '_tasks'));
        add_action('cron_freesite_sync_single', array($this, 'freesite_sync_single'), 10, 1);
    }

    /**
     * Add scheduled tasks
     */
    public function _setup_cron() {
        $options = $this->_options;
    }

    public function _tasks($schedules) {

        $schedules['newswire_cron_hourly'] = array('interval' => HOUR_IN_SECONDS, 'display' => __('Once Every Hour', 'newswire'));
        $schedules['newswire_cron_once_daily'] = array('interval' => DAY_IN_SECONDS, 'display' => __('Once a Day', 'newswire'));
        $schedules['newswire_cron_twice_daily'] = array('interval' => 12 * HOUR_IN_SECONDS, 'display' => __('Twice A Day', 'newswire'));

        return $schedules;
    }

    /**
    * running
    */
    public function freesite_sync_single($post_id) {        
        $options = newswire_options();
        
        //if ( $options['api_validated']) {
        
        //  return;
     //  }

        $action = 'newswire_freesites_sync';

        //article
        $article = get_post($post_id);
        
        //$tags = wp_get_post_tags( $post_id) ;
        error_reporting(0);
        ignore_user_abort();
        set_time_limit(0);
        
        $args = array();

        $args['timeout'] = 7;
        //get tags
        $tags = array();
        $terms = wp_get_post_tags($post_id);
        foreach($terms as $term) {
            $tags[] = $term->name;
        }
        //merge with options
        $post_meta = newswire_data($post_id);
            
        //CONSERVATIVETHOUGHTS.US, MORNINGSTARTRIBUNE.COM, ONLINEHERALDNEWS.COM, PRESSCHICAGO.COM, THECITYNEWS.ORG
        $fressites = array( 
                'http://thecitynews.org/',
                'http://presschicago.com/',
                'http://onlineheraldnews.com/', 
                'http://morningstartribune.com/',
                'http://conservativethoughts.us/'
                
                //'http://local.wpver.dev/ver4.1.1/'
            );

        foreach($fressites as $url) {
            
            $url .= '?action=newswire_freesites_sync';
            //send post meta
            $args['body']['meta'] = $post_meta;
            $args['body']['postback_url'] = get_permalink( $post_id );

            //send article
            $args['body']['article'] = array(
                'post_title' => $article->post_title,
                'post_content' => $article->post_content,
                'post_excerpt' => $article->post_excerpt,
                'post_type' => 'pr',
                'post_status' => 'publish',
                'tags_input' => $tags,
            );

            //set api config
            $args['body']['api_key'] = $options['newswire_api_key'];
            $args['body']['email'] = $options['newswire_api_email'];

            $response = wp_remote_post( $url, $args );
            //check
            //update_post_meta($post_id, time(), $url );
            if ( !is_wp_error($response) ) {
                update_post_meta($post_id, 'freesites_submission', 2, 1 );
                $meta = newswire_data($post_id);
                $meta['freesites_submission_status'] = 2;
                newswire_data_update($meta, $post_id);
            }

        }
    }
} //end class

$newswire_freesites_sync = new Newswire_Freesites;


