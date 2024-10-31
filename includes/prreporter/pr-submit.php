<?php
/**
* @package: newswirexpress
* @subpackage: prreporter
*/

/**
* catch lightbox choices
*/
//add_action('init', 'newswire_payment_process');
function newswire_payment_process(){
    $action = empty($_REQUEST['action']) ? '' : $_REQUEST['action'];
    if  ( $empty ) return;
    $url = urldecdoe($_GET['payment_url']);
    $post = get_post(empty($_REQUEST['post']) ? '' : $_REQUEST['post']);
    try {

        $article_id = newswire_submit($post);
        wp_redirect( add_query_arg( array('id'=> $article_id), $url ) );
    }catch(Exception $e) {
        wp_die($e->getMessage());
    }
}
//Show lightbox



/**
* http send to api newswire
*/
function newswire_submit($post, $article_id, $create = 1) {
    $response=   newswire_remote_submit_article($post, $article_id, $create);
    
    if ( is_wp_error($response) ) {

        $error_message = $response->get_error_message();
        $wpdb->query("UPDATE $wpdb->posts SET post_status = 'draft' WHERE ID = $post_ID ");
        newswire_admin_write_error($error_message);
        newswire_override_post_save_message_redirect();
        //push to last error lag

    } else {

        $body = json_decode(wp_remote_retrieve_body($response), true);
        //remote error
        if (!isset($body['article_id']) && $body['errors']) {

            $wpdb->query("UPDATE $wpdb->posts SET post_status = 'draft' WHERE ID = $post_ID ");
            newswire_admin_write_error($body['errors']);
            newswire_override_post_save_message_redirect();

        } elseif (isset($body['article_id'])) {
            //Success
            //Update post meta
            //update_post_meta($post_ID, 'rss_source_url', esc_url( $body['permalink']) );
            update_post_meta($post_ID, NEWSWIRE_ARTICLE_SUBMITTED, '1');
            update_post_meta($post_ID, NEWSWIRE_ARTICLE_ID, $body['article_id']);

            update_post_meta($post_ID, 'newswire_submission_result', $body);
            
            //if ( current_user_can('PRReporter') ) {
                newswire_update_post_status($post_ID, 'processing');
                newswire_override_post_save_message_redirect('processing');
            //} else {
                //newswire_update_post_status($post_ID, 'pending');
                //newswire_override_post_save_message_redirect('pending');
            //}
        } else {
            //var_dump($body);
            //error handle it
            //revert it to draft
            $wpdb->query("UPDATE $wpdb->posts SET post_status = 'draft' WHERE ID = $post_ID ");
            //foreach($body['errors'] as $err)
            newswire_admin_write_error('Sorry! something went wrong. Please contact plugin support');
            newswire_override_post_save_message_redirect();

        }
    }
}
/**
* Show lightbox after submission - dont submit to newswire yet
*/

add_action('save_newswire_data', 'newswire_prreporter_pr_to_newsroom_only' , 10, 2);
function newswire_prreporter_pr_to_newsroom_only($post) {

    if (!current_user_can('publish_newswire_prs_to_pressroom') && current_user_can('PRReporter')) {
        //if they can'tp publish pr to pressroom send them to newsroom only
        wp_set_post_tags($post->ID, 'newsroom');

    } else {

        //remove tag newsroom here
        //wp_set_post_tags($post_ID, 'pressroom' );
        //wp_remove_object_terms( $post_id, 'sweet', 'newsroom' );
    }
}
