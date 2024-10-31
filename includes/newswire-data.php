<?php
/**
* @package: common
*/
/**
* Always tag pressroom blocks with "pressroom" tag
* todo: Replace with custom taxonomy instead of default post_tag
*
*/
if ( !function_exists('newswire_tag_pressroom_content')):
    add_action('save_post_pin_as_text', 'newswire_tag_pressroom_content');
    add_action('save_post_pin_as_social', 'newswire_tag_pressroom_content');
    add_action('save_post_pin_as_quote', 'newswire_tag_pressroom_content');
    add_action('save_post_pin_as_link', 'newswire_tag_pressroom_content');
    add_action('save_post_pin_as_image', 'newswire_tag_pressroom_content');
    add_action('save_post_pin_as_embed', 'newswire_tag_pressroom_content');
    add_action('save_post_pin_as_contact', 'newswire_tag_pressroom_content');
     add_action('save_post_pin_as_latestpr', 'newswire_tag_pressroom_content');
    function newswire_tag_pressroom_content($post_ID, $post =null, $update = null){
        wp_set_post_tags( $post_ID, 'pressroom', $append = true );
        //wp_set_post_terms( $post_ID, 'pressroom', '_pr_listing', $append = true );
    }
endif;

/* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*  ///////////////////////////////////////////// SAVING post meta - newswire_data /////////////////////////////////////////////////////
* /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/
if ( ! function_exists('newswire_save_post_meta_values')):
// Do we need to save it 
///Need to save custom fields before sending to newswire
//add_action('post_updated', 'newswire_save_post_meta_values');    
 /**
 * Save custom fields for pressroom blocks
 */
add_action('transition_post_status', 'newswire_save_post_meta_values', 10, 3);
function newswire_save_post_meta_values($new_status, $old_status, $post) {
    
    //remove_action('post_updated' , 'newswire_save_post_meta_values');
    remove_action('transition_post_status' , 'newswire_save_post_meta_values');

     //Skip if not triggerd by POST data
    if ( empty($_POST['newswire_data']) ) return;

    // Check if our nonce is set.
    /* Verify the nonce before proceeding. */
    if (!isset($_POST[NEWSWIRE_POST_META_NONCE]) || !wp_verify_nonce($_POST[NEWSWIRE_POST_META_NONCE], NEWSWIRE_POST_META_NONCE) ) {
        return $post_id;
    }

    //post type permitted?    
    if ( !in_array( get_post_type( $post ), newswire_pressroom_blocks() ) ) return;
    $post_id = $post->ID;
    
    //skip doing cron
    if (defined('DOING_CRON') && DOING_CRON) {
        return 0;
    }   

    // if current post being edited is not pr - press release
    //modify notice message/link here to direct to presroom page 
    if ( get_post_type($post) != 'pr' ) {
        newswire_modify_post_update_message_redirect();
    }


    $old_data = newswire_data($post->ID);

    // Sanitize user input.
    $mydata = $_POST['newswire_data'];
    $post_type = get_post_type($post);
    //before newswire_data is saved apply some filters 
    $mydata = apply_filters('newswire_data_before_save', $mydata, $post, $old_data);
    $mydata = apply_filters('newswire_data_before_save_'.$post_type, $mydata, $post, $old_data);

    //update post meta now
    update_post_meta($post_id, NEWSWIRE_POST_META_CUSTOM_FIELDS, $mydata);
    
    do_action('newswire_data_after_save', $mydata, $post);
    do_action('newswire_data_after_save_'.$post_type, $mydata, $post);
    //return 0;
    do_action('newswire_data_'.$new_status, $mydata, $post);

    
}//end function
endif;

/**
* Customize submitdiv. Added more checkboxes 
* This box is being customized per version pro and light version so its ready to make them different from each other
* 
*/
if ( !function_exists('newswire_submission_options')):
add_action('post_submitbox_misc_actions', 'newswire_submission_options');
/**
 * Toggle content syndication checkboxes
 */
function newswire_submission_options() {

    global $post;
  
    $type = get_post_type( $post );
    
    if  ( 'pr' !=  $type) return;
    echo '<div id="newswire-publication-options">';
        do_action('newswire_submission_options_before', $post);
        if ( apply_filters('newswire_submission_options', true ))
            do_action('newswire_submission_options_default', $post);
        do_action('newswire_submission_options_after', $post);
    echo "</div>";
}
endif;

if ( !function_exists('newswire_submission_options_default_handler')):
add_action('newswire_submission_options_default', 'newswire_submission_options_default_handler', 10, 1);
function newswire_submission_options_default_handler($post) {
    $post_meta = newswire_data();
    $sync = get_post_meta($post->ID, 'freesites_submission', true );
    
    $howto = newswire_config('freesites_submission', 'tooltip');
    $howtoimg = sprintf('<img src="' . NEWSWIRE_PLUGIN_URL . 'assets/images/help.png" title="%s"> ', $howto);

    if ( intval($sync) === 2 ) {

        //echo '<p><input type="radio" name="newswire_data[newswire_submission]" value="freesites" checked="checked"> Publish on 5 New Sites(Free) </p>';
        printf('<p class=""><input type="checkbox" name="newswire_data[newswire_submission]" value="freesites" checked="checked"> Publish on 5 News Sites (Free) %s</a> </p>',  $howtoimg);
    } else {
        
        if ( isset($post_meta['newswire_freesite_submission']) && $post_meta['newswire_freesite_submission'] === 'freesites' ) {
            //echo '<p><input type="checkbox" name="newswire_data[newswire_submission]" value="freesites" checked="checked"> Publish on 5 New Sites(Free) </p>';
            printf( '<p class=""><input type="checkbox" name="newswire_data[newswire_freesite_submission]" value="freesites" checked="checked"> Publish on 5 News Sites (Free) %s</a></p>',  $howtoimg);
        } else {
            //echo '<p><input type="checkbox" name="newswire_data[newswire_submission]" value="freesites" > Publish on 5 New Sites(Free) </p>';
            if ( defined('NEWSWIREXPRESS') && NEWSWIREXPRESS && !intval($post_meta['newswire_freesite_submission']) )
                printf( '<p class=""><input type="checkbox" name="newswire_data[newswire_freesite_submission]" value="freesites" > Publish on 5 News Sites (Free) %s</a></p>', $howtoimg);
            elseif (isset($post_meta['newswire_freesite_submission']) && !intval($post_meta['newswire_freesite_submission']) )  {
                printf( '<p class=""><input type="checkbox" name="newswire_data[newswire_freesite_submission]" value="freesites"> Publish on 5 News Sites (Free) %s</a></p>', $howtoimg);
            } else {
                printf( '<p class=""><input type="checkbox" name="newswire_data[newswire_freesite_submission]" value="freesites" checked="checked"> Publish on 5 News Sites (Free) %s</a></p>', $howtoimg);
            }
                
        
        }

        if ( defined('NEWSWIREXPRESS') && NEWSWIREXPRESS )
            if ( 'newswire' == $post_meta['newswire_submission'] /*newswire_submission_type($post->ID) =='newswire' */ ||  !isset($post_meta['newswire_submission']) ) {
                //if newswire_submission === 'disable'
                //echo '<input type="hidden" name="disable_sumbmission" id="input_disable_submission" value="newswire" >';
                
                echo '<p><input type="checkbox" name="newswire_data[newswire_submission]" id="" value="newswire" checked="checked"> Publish on Newswire</p>';
            } else {
                //echo '<input type="hidden" name="disable_sumbmission" id="input_disable_submission" value="newswire" >';
                echo '<p><input type="checkbox" name="newswire_data[newswire_submission]" id=""  value="newswire"> Publish on Newswire</p>';
            }

    }


    if ( isset($post_meta['newswire_copytoblog']) && /*make_blog_copy($post->ID) */ $post_meta['newswire_copytoblog']  ) {
        echo '<p><input type="checkbox" name="newswire_data[newswire_copytoblog]" value="1" checked="checked"> Include on blog page</p>';
    } else {
        echo '<p><input type="checkbox" name="newswire_data[newswire_copytoblog]" value="1" > Include on blog page</p>';
    }

    //tags to pressroom
    if ( isset($post_meta['include_on_pressroom_page']) && /*has_term('pressroom', 'post_tag', $post) && */ $post_meta['include_on_pressroom_page'] ) {
        echo '<p><input type="checkbox" name="newswire_data[include_on_pressroom_page]" id="include_on_pressroom_page" value="1" checked="checked"> Include on PressRoom Page</p>';
    } else {
        echo '<p><input type="checkbox" name="newswire_data[include_on_pressroom_page]" id="include_on_pressroom_page"  value="1"> Include on PressRoom Page</p>';
    }
}
endif;

/**
* Normalize newswire_data meta
*/
add_filter('newswire_data_before_save', 'normalizes_newswire_data_before_saving', 1, 2);
function normalizes_newswire_data_before_saving($options, $post) {
    
    //postback url - so newswire knows where to update submission when newswire editor approve or reject
    if ( defined('NEWSWIREXPRESS') && NEWSWIREXPRESS ) {
        //set postback url - newsxpress version only
        $options['postback_url'] = site_url('?action=submit_postback');
    }


    //normalize link block
    if ( isset($options['text']) && !count($options['text']) ) {
        $options['text'] = $options['link'] = array();

    }

    //include image
    if (empty($options['newswire_submission'])) {
        $options['newswire_submission'] = "0";
    }

    //include image
    if (empty($options['include_image'])) {
        $options['include_image'] = "0";
    }

    //link name
    if (empty($options['link_name'])) {
        $options['link_name'] = "0";
    }

    //show company info
    if (empty($options['show_company_info'])) {
        $options['show_company_info'] = "0";
    }

    //var_dump($_POST['newswire_data']);
    //var_dump($options);
    //exit;
    
    return $options;
}




/**
* Include press release to pressroom page
*/
add_action('newswire_data_after_save_pr', 'newswire_include_pr_to_pressroom_blocks', 20, 2);
function newswire_include_pr_to_pressroom_blocks($options, $post) {
    
  //  die('sdf');
    //wp_set_post_tags($post->ID, 'test', true);
    //tag as pressroom to include pr to pressroom page
    if ( !empty($options['include_on_pressroom_page']) ) {
        
        wp_set_post_tags($post->ID, 'pressroom', true);

    } else {

        wp_remove_object_terms($post->ID, 'pressroom', 'post_tag');
    }

}

/*
* Set correct category tags for newsroom display/listings of articles
* @package: newswirexpress
*/
if ( !function_exists('newswire_normalize_category_page_listing')):
add_action('newswire_data_after_save_pr', 'newswire_normalize_category_page_listing', 15, 2);
function newswire_normalize_category_page_listing($options, $post) {
    
    if ( !defined('NEWSWIREXPRESS')  ) return;

    $post_id = $post->ID;

    $cats = get_option('newswire_categories_flat');
    
    //set post tags based on 
    $meta = $options;

    $tags = array('newsroom');

    if ( is_array($cats) )
        foreach($cats as $catid=>$catname) {
            //if ( $catid == $meta['category_id'] ) $tags[] = $catname;
            if ( !empty($meta['category_id2']))
                if ( $catid == $meta['category_id2'] ) $tags[] = $catname;
                
        }
    if ( $tags )
        wp_set_post_tags( $post_id, $tags, false);
}
endif;

if ( !function_exists('newswire_sync_post_thumbnail')):
/**
* Set post thumbnail
*/
add_action('newswire_data_before_save_pr', 'newswire_sync_post_thumbnail', 9, 3);
function newswire_sync_post_thumbnail($options, $post, $old_data) {
    if ( !$post->ID ) return;

    if (isset($options['_set_as_featured']) && $options['_set_as_featured']) {
        //$mydata['include_image'] = "0";
        set_post_thumbnail( $post->ID, $options['_set_as_featured']);
        //$mydata['_set_as_featured']
    } elseif ( isset($old_data['_set_as_featured']) && $old_data['_set_as_featured'] ) {
        
        delete_post_thumbnail($post->ID);
        delete_post_thumbnail($old_data['newswire_cloned']);

    }
    return $options;
}
endif;

/**
* copy to blog post
*
*/
if ( !function_exists('newswire_copy_as_blog')):
add_filter('newswire_data_before_save_pr', 'newswire_copy_as_blog', 10, 3);
function newswire_copy_as_blog($options, $post, $old_meta) {
    
    $post_id = $post->ID;   

    $cloned = isset( $old_meta['newswire_cloned'] ) ? $old_meta['newswire_cloned'] : false ;

    if ( get_post( $cloned ) && $cloned && isset($options['newswire_copytoblog']) ) {
        
        //just update blog copy
        newswire_clone_pr( $post, $cloned );
        
        $options['newswire_cloned'] = $cloned;

        //update_post_meta($post_ID, 'newswire_cloned', $new_id );
   
    } elseif ( isset($options['newswire_copytoblog']) &&  $post->post_status == 'publish' && $options['newswire_copytoblog'] ) {
    
        //remember blog copy id
        $new_id = newswire_clone_pr($post, false);
        
        $options['newswire_cloned'] = $new_id;
        
        //update_post_meta($post_id, 'newswire_cloned', $new_id);
    } elseif ( get_post( $cloned ) && !isset($options['newswire_copytoblog']) ) {
        
        wp_delete_post( $cloned , $force_delete = true );
    }

    return $options;
}
endif;

//free sites submission
if ( !function_exists('newswire_freesites_submission_handler')):
add_filter('newswire_data_before_save_pr', 'newswire_freesites_submission_handler', 10, 3);
function newswire_freesites_submission_handler($options, $post, $old_meta) {

    //$options['freesites_submission_status'] = 0;
    $newswire = $options['newswire_submission'];

    //keys to keep before overwrriting
    $options['freesites_submission_status'] =  isset($old_meta['freesites_submission_status']) && $old_meta['freesites_submission_status'] ? $old_meta['freesites_submission_status'] : 0;
    
    


    //all is okay
    $post_ID = $post->ID;   

    $newswire_options = newswire_options();

    //freesites routine start
    //$sync = get_post_meta($post_ID, $newswire_data['freesites_submission'], true );
    $toggle = isset($options['newswire_freesite_submission']) && $options['newswire_freesite_submission'] ? $options['newswire_freesite_submission'] : 0 ; //toggle

    if ('newswire' == $newswire ||  ( empty($toggle) ) ) {
        
        //remove schedule
        //wp_unschedule_event( intval($stamp), 'cron_freesite_sync_single', array($post_ID) );
        wp_clear_scheduled_hook( 'cron_freesite_sync_single', array( $post->ID ) );
        update_post_meta($post_ID, 'freesites_submission', 0 , 1); //willr emove this soon
        $options['freesites_submission_status'] = 0;
        
    } elseif ( 'freesites' == $toggle && $post->post_status == 'publish' ) {

        $sync = !isset($old_meta['freesites_submission_status']) ? 0: $old_meta['freesites_submission_status'];

        if ( intval($sync) < 2 && $newswire_options['api_validated'] ):

           // if ( 'freesites' == $toggle  ) {
                //$stamp = time() + 3; //time() + ( 72 * 3600);
                $stamp = time() + ( 72 * 3600); //final stamp
                //cron it
                wp_schedule_single_event( $stamp, 'cron_freesite_sync_single', array( $post->ID ) );
                //remember it
                update_post_meta($post_ID, 'freesites_submission', 1, 0 ); //will remove this soon
                $options['freesites_submission_status'] = 1;
            //}
        endif;

    } else {
        $options['newswire_freesite_submission'] = 0;
    }  


    //continue only when all fields are vlalid
    $validator = new Newswire_Validator();
    if (!$validator->isValid($post)) {
        //die('error');
        //dont need to write error notices
        return $options;
    } else {
        //die('valid');
    }

    return $options;
}
endif;

//when savinng meta data for pin_as_latestpr
if ( !function_exists('newswire_data_pin_as_latestpr')):
add_filter('newswire_data_before_save_pin_as_latestpr', 'newswire_data_pin_as_latestpr', 10, 3);
function newswire_data_pin_as_latestpr($options, $post, $old_meta) {
    
    if ( empty($options['include_local_pressreleases']) ) {
        $options['include_local_pressreleases'] = '0';
    }

    $opt = array();
    $opt['latest_press_releases'] = $post->ID;
    newswire_options_update($opt);

    return $options;
}
endif;
