<?php


if ( !function_exists('newswire_query_republished_pr')) :
/**
 * Newswire report related json of articles published to plugin site
 */
add_action('init', 'newswire_query_republished_pr', 1000);
function newswire_query_republished_pr() {
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

    if ( $action !== 'newswire_republish_articles') {
        return;
    }
    $today = getdate();
    /**
     * The WordPress Query class.
     * @link http://codex.wordpress.org/Function_Reference/WP_Query
     *
     */
    $args = array(

        //Type & Status Parameters
        'post_type' => 'pr',
        'post_status' => 'publish',
        /*
        'date_query' => array(
        array(
        'year'  => $today['year'],
        'month' => $today['mon'],
        'day'   => $today['mday'],
        ),
        ),*/

        //Pagination Parameters
        'posts_per_page' => -1,
        'nopaging' => true,

        //Custom Field Parameters
        'meta_key' => NEWSWIRE_ARTICLE_ID,
        'meta_value' => '',
        'meta_compare' => '!=',

        //Parameters relating to caching
        'no_found_rows' => false,
        'cache_results' => false,

    );

    $query = new WP_Query($args);
    $data = array();
    foreach ($query->get_posts() as $post) {
        $meta = get_post_meta($post->ID, NEWSWIRE_ARTICLE_ID, $single = true);
        $data[] = array('article_id' => $meta, 'url' => get_permalink($post->ID));
    }

    echo json_encode($data);
    die();
}

endif;