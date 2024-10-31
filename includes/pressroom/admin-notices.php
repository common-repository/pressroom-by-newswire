<?php
if ( !defined('ABSPATH') ) {
    exit;
}

/**
 *
 * Disply message when article is locked for approval
 * or when it has been published from newswire
 */
add_action('admin_notices', 'newswire_notify_pending_article');
function newswire_notify_pending_article() {

    global $post;

    $screen = get_current_screen();

    if ('post' != $screen->base || empty($_GET['post']) ) {
        return;
    }

    $options = newswire_options();

    $submitted = get_post_meta( $_GET['post'], NEWSWIRE_ARTICLE_SUBMITTED, true);

    $newswire_article_id = get_post_meta($_GET['post'], NEWSWIRE_ARTICLE_ID, true);

    if (in_array(get_post_type($post->ID), $options['supported_post_types']) && $submitted && $newswire_article_id)
    //if ( in_array(get_post_type( $post ) , array('pr') ) && $submitted && $newswire_article_id )
    {

        $post_status = get_post_status($post);

        $submitted = get_post_meta($post->ID, NEWSWIRE_ARTICLE_SUBMITTED, true);

        $newswire_article_id = get_post_meta($post->ID, NEWSWIRE_ARTICLE_ID, true);

        switch ($post_status) {
            case 'processing':
            case 'pending':
                printf('<div class="updated"><p>%s</p></div>', newswire_plugin_title() .'. This post has been locked for editing and waiting approval after submission. ');

                do_action('newswire_article_submitted');

                break;
            case 're-do':
            case 'sentback':
                printf('<div class="error"><p>%s</p></div>', newswire_plugin_title().'. This post has been rejected for some reason. Please checkout from newswire and resubmit ');
                break;
            case 'publish':
                printf('<div class="updated"><p>%s</p></div>', 'Published to newswire.');
                break;
        }
    }


}



/**
 * Lock post edit screen when a post has been submitted and taking newswire settings into consideration.
 *
 * We just hide the publish meta box
 *
 * @todo: check from different post types
 */
add_action('admin_notices', 'newswire_remove_post_publish_box');
function newswire_remove_post_publish_box() {

    global $post;

    $options = newswire_options();

    $screen = get_current_screen();

    if ('post' !== $screen->base || empty($_GET['post']) ) {
        return;
    }

    $submitted = get_post_meta($_GET['post'], NEWSWIRE_ARTICLE_SUBMITTED, true);

    $newswire_article_id = get_post_meta($_GET['post'], NEWSWIRE_ARTICLE_ID, true);

    // check if it has been submitted no need to remotely call some api endpoints from newswire as it will be a waste of resources just use post_meta
    $status = get_post_status($_GET['post']);
    
    if ( current_user_can('PRReporter') )
        remove_meta_box('tagsdiv-post_tag', 'pr', 'side');


    //if ( in_array( $status, array('pending',  'publish'))  && ( $options['article_submission_lock'] || $options['force_submission'] ) && $submitted  && $newswire_article_id )
    if (in_array($status, array('publish')) && $submitted && $newswire_article_id) {
        foreach ($options['supported_post_types'] as $type) {
            //foreach( array('pr') as $type) {
            remove_meta_box('submitdiv', $type, 'side');
        }
//        remove_meta_box('submitdiv', 'post', 'side');
    }

}


/**
* Generate newswire plugin notices 
* do Api validation
*/
add_action('admin_notices', 'newswire_admin_notices');
function newswire_admin_notices() {
    global $pagenow;
    
    $getpage = isset($_REQUEST['page']) ? $_REQUEST['page'] : null;

    if ( 'edit.php' == $pagenow && $getpage == 'newsroom-settings' )  
        if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true )
             echo '<div class="updated fade"><p> <strong>' . __( 'Settings Saved.' ) . '</strong></p></div>';

    $page = isset($_GET['page']) ? $_GET['page'] : '';
    if ( 'newsroom-settings' == $page ) return;

    $message = '';
    
    $opt = newswire_options();

    if ( !$opt['api_validated'] && ( $opt['newswire_api_email'] == '' || $opt['newswire_api_key'] == ''  ) ) {
        $message = newswire_plugin_title().': Please enter free API credential <a href="'.site_url('wp-admin/edit.php?post_type=pressroom&page=newsroom-settings&tab=api').'" style="color: red">here</a> to enable press release syndication to other websites through the plugin.';
    } elseif ( !$opt['api_validated'] && ( $opt['newswire_api_email'] !== '' && $opt['newswire_api_key'] !== ''   ))  {
        $message = sprintf('%s: Invalid API Credential', newswire_plugin_title());
    }

    if ( $message == '') return;

    ?> <div class="newswire-error error ">
        <p><?php _e( $message, 'newswire' ); ?></p>
    </div><?php   
}



/**
* Create admin notices
* Global function to show admin notics
*/
add_action('admin_notices', 'newswire_show_notices');
function newswire_show_notices() {
    
    global $post, $pagenow, $action;
    

    if ( !($pagenow == 'post.php' && $action == 'edit') ) {
        return;
    }

    $options = newswire_options();

    if ( isset($_GET['remove_page_notices']) ) {        
        delete_post_meta($post->ID, 'admin_notices');
    }

    if ( $options['pressroom_page_template'] == $post->ID ) {
        $message = get_post_meta( $post->ID, 'admin_notices', $single = true);

    }else {
        $message = '';
    }
    
    $message = apply_filters('newswire_show_notices', $message );

    if ( $message == '') return;
    ?>
    <div class=" updated ">
        <p><?php _e( $message, 'newswire' ); ?></p>
    </div><?php   
    
}
