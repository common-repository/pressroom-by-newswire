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

class Newswire_Submission {

   
} //end class


