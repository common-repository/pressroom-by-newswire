<?php
/**
* 
* @package: newswirexpress
* @subpackage: 
* 
*
*/
add_filter('newswire_push_article_validate','newswire_test_article_before_submission', 10, 2);
function newswire_test_article_before_submission($valid, $post) {

    $post_ID = $post->ID;


    //if doing cron skip
    if (defined('DOING_CRON') && DOING_CRON) {
       return $valid;
    }

    //skip autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $valid;
    }

    // if no post_id
    if (! isset($post->ID) ) {
        return $valid;
    }

    if ( empty($_POST['newswire_data']) || !isset($_POST['newswire_data']) ) {
        return false;
    }

     //are we just running plugin custom cron?
    if (newswire_var('cron')) {
        return $valid;
    }

    // Check the user's permissions.
  

    //skip from rss - if this pr came from rss pull via newswire, skip everything here
    if (get_post_meta($post_ID, 'rss_source_url', true)) {
        return false;
    }
    /**
     * validate fields before submitting to newswire
     */
    $validator = new Newswire_Validator();

    if (!$validator->isValid($post)) {

        newswire_update_post_status($post_ID, 'draft');

        $validator->write_error_notice();

        newswire_override_post_save_message_redirect('pending');

        return false;

    } else {

        $valid = true;
    }
    //var_dump('validate');
    /**
    * Everything seems fine if code reach this part
    */
    return $valid ? $valid : false;
}


/*
* When user intend to submit pr to newswire anystatus-->pending|publish
*/
if ( !function_exists('newswire_submit_article')):
add_action('pending_pr', 'newswire_submit_article', 20, 2); 
add_action('publish_pr','newswire_submit_article', 10, 2);
function newswire_submit_article($post_ID, $post) {
    // var_dump($test);
    $test = false;
    $test = apply_filters('newswire_push_article_validate', $test, $post);
    $meta = newswire_data($post_ID);

    if ( $test && $meta['newswire_submission'] =='newswire') {
       do_action('newswire_push_article_to_newswire', $post);
    }
}
endif;


add_action('newswire_push_article_to_newswire','newswire_push_article_to_newswire_handler', 1, 1);
function newswire_push_article_to_newswire_handler($post) {

    global $wpdb;
    
    $post_meta = newswire_data($post->ID);
    $post_ID = $post->ID;
    $newswire_settings = newswire_options();
    $post_type = get_post_type($post_ID); //get post type

    //clean_post_cache( $post_ID );
    //$post = get_post( $post_ID );
    //get newsqwire settings
   

    //skip if post_type is not supported
    //if ( !in_array( $post_type, (array)$newswire_settings['supported_post_types'] ) ) {
    if (!in_array($post_type, array('pr'))) {

        return 0;
    }

    $wp_post_status = get_post_status($post_ID);
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////// ACTUAL SENDING OF ARTICLE TO NEWSWIRE API ///////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //if submission to neswire is automated
    $submitted = get_post_meta($post_ID, NEWSWIRE_ARTICLE_SUBMITTED, true);
    $article_id = get_post_meta($post_ID, NEWSWIRE_ARTICLE_ID, true);
    //whats the remote status? regardless whe need tou udpate
    // Is our article sent back and we intend to resubmit again?
    if ( intval($submitted) && intval($article_id) ) {

        //Just update content? article needs to be edited by editor so
        //no use of sending new content once article has been published
        $response = newswire_remote_submit_article($post, $article_id, $create = 0);

        //handle remote result

    } elseif ('autosubmit' == $newswire_settings['article_submission_mode']) {

        //first time to submit article as pending
        $response = newswire_remote_submit_article($post, $article_id, $create = 1);

    }

    //////////////////////////////////////////////////////////////////////
    //$result = array('article_id' => 1, 'status'=> 'pending');
    //todo - handle error correctly during prod and dev
    //var_dump($response);
    //exit;
    ////////////////////////////////////////////////////////////////////////

    if ( is_wp_error($response) ) {

        $error_message = $response->get_error_message();
        $wpdb->query("UPDATE $wpdb->posts SET post_status = 'draft' WHERE ID = $post_ID ");
        newswire_admin_write_error($error_message);
        newswire_override_post_save_message_redirect();
        //push to last error lag

    } else {

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (!isset($body['article_id']) && $body['errors']) {

            $wpdb->query("UPDATE $wpdb->posts SET post_status = 'draft' WHERE ID = $post_ID ");
            newswire_admin_write_error($body['errors']);
            newswire_override_post_save_message_redirect();

        } elseif (isset($body['article_id'])) {
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
        //newswire_update_post_meta( $post_ID, NEWSWIRE_ARTICLE_STATUS , $result['status'] );
        //push some updates about succesfull submission
    }
}










/* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*  ///////////////////////////////////////////// Image block - Settings being saved pin_as_image //////////////////////////////////////
* /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/
/**
* Pin as image - save attachment
*/
if ( !function_exists('newswire_save_pin_as_image')):
/**
 * Admin hook callback
 *
 * attach uploaded images from pin_as_image post type
 */
add_action('save_post_pin_as_image', 'newswire_save_pin_as_image');
function newswire_save_pin_as_image($post_ID, $post = null, $update = null) {
    //from pin_as_image
    // unhook this function so it doesn't loop infinitely

    remove_action('save_post_pin_as_image',  'newswire_save_pin_as_image');

    if (isset($_POST['pin_attachment']) && is_array($_POST['pin_attachment'])) {
        foreach ($_POST['pin_attachment'] as $attachment_id) {
            $attachment = array('ID' => $attachment_id, 'post_parent' => $post_ID);
            //  var_dump($attachment)   ;
            wp_update_post($attachment);
        }
    }
    //exit;
}
endif;


/**
 * Send article to newswire
 *
 * @param $post $wp_post object
 * @Param $newswire_article_id integer actual article id from newswire
 *
 */
function newswire_remote_submit_article($post = null, $newswire_article_id = 0, $create = 0) {

    $data = newswire_api_prepare_http_data($method = 'submit', $post);

    $client = Newswire_Client::getInstance();

    //set timeout?
    if ($create) {
        $response = $client->submit_article($data);
    } else {
        $data['body']['article_id'] = $newswire_article_id;
        # code...
        // The only reason why
        //die('updating article');
        $response = $client->post('/article/submit/', $data);

        if (!is_wp_error($response)) {
            $data = json_decode(wp_remote_retrieve_body($response), true);
            if ($data['article_id'] && $data['success']) {
                newswire_update_post_status($post->ID, 'processing');
            }
        }

    }

    return $response;
}

/**
 ** Prepare data to be submitted to newswire
 *  use callbacks indicated from config file
 */
function newswire_api_prepare_http_data($method, $post) {

    global $newswire_config;

    $request = array();

    setup_postdata($post);

    $post_meta = newswire_get_post_meta($post->ID);

    //if creating new post
    switch ($method) {

        case 'submit':

            $body = array();

            $callbacks = newswire_config('article_fields_handler');

            foreach (array_keys($newswire_config['settings']['article_fields']) as $field) {

                if (function_exists($callbacks[$field])) {
                    $body[$field] = call_user_func($callbacks[$field], $post, $post_meta[$field]);
                }
            /* watch out when writing callback filter */
                elseif (isset($post_meta[$field])) {
                    $body[$field] = $post_meta[$field];
                } else {
                    unset($body[$field]);
                }

            }

            break;
    } //end switch

    $request['body'] = array('article' => $body, 'postback_url' => site_url('?action=submit_postback&post_id=' . $post->ID));
    //debug?
    //var_dump($request);
    //exit;
    return $request;
}
function newswire_pr_get_post_status($post, $val ='') {
    return 'processing';
}
/**
*
*/
function newswire_get_the_title_fieldmap($post, $val = null) {
//  setup_postdata( $post );
    return apply_filters('newswire_submit_article_title', $post->post_title);

}

/**
*
*/
function newswire_get_wp_excerpt_fieldmap($post, $val = null) {
//  setup_postdata( $post );
    return apply_filters('newswire_submit_article_description', $post->post_excerpt);

}

/**
*
*/
function newswire_get_the_content_fieldmap($post, $val = null) {
    //setup_postdata( $post );
    return apply_filters('newswire_submit_article_body', $post->post_content);
}

/**
*
*/
/*
function newswire_show_company_info_fieldmap($post, $val = null){
    return strval($val);
}*/

/**
*
*/
function newswire_get_current_user_email($post, $val) {

    global $display_name, $user_email;

    get_currentuserinfo();

    if ($val != '') {
        return $val;
    }

    return $user_email;
}

/**
* Get user display name;
*/
function newswire_get_current_user_displayname($post, $val) {
    
    global $display_name, $user_email;

    get_currentuserinfo();

    if ($val != '') {
        return $val;
    }

    return $display_name;

}


///////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////Customize Message after submisison ////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
*
*/
add_filter('post_updated_messages', 'newswire_post_updated_messages');
function newswire_post_updated_messages($messages) {
    global $post;
    $options = newswire_options();
    $url = get_permalink($options['pressroom_page_template']);
    //submitted to newswire
    $messages['post']['100'] = sprintf(__('Press release submitted to newswire. <a target="_blank" href="%s">Preview post</a>'), 
        esc_url(add_query_arg('preview', 'true', get_permalink($post->ID))));
    $messages['post']['200'] = sprintf(__('Press release was reverted as draft.'));
    $messages['post']['991'] = sprintf(__('Post updated. <a href="%s" target="_blank">View post.</a>'), $url );

    return $messages;
}

/**
* handle redirecting to edit post after save/update
*
*/
function newswire_block_redirect_update($location) {
    $location = add_query_arg('message', 991, $location);
    return $location;
}

function newswire_update_post_redirect_error($location) {
    $location = add_query_arg('message', 200, $location);
    return $location;
}

function newswire_update_post_redirect_pending($location) {
    $location = add_query_arg('message', 100, $location);
    return $location;
}
function newswire_override_post_save_message_redirect($status = '') {

    $error = newswire_admin_get_error();

    if (!empty($error)) {

        add_filter('redirect_post_location', 'newswire_update_post_redirect_error');
    } elseif ( in_array( strtolower($status), array('processing', 'pending')) && function_exists('newswire_config')) {
        add_filter('redirect_post_location', 'newswire_update_post_redirect_pending');

    }
}

function newswire_modify_post_update_message_redirect(){
    add_filter('redirect_post_location', 'newswire_block_redirect_update');
}