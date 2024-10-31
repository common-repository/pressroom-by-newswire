<?php
if ( !defined('ABSPATH') ) {
    exit;
}

//run all updates and release hooks here
global $newswire_pressroom_db_version;
$newswire_pressroom_db_version = "1.1";

if ( !function_exists('newswire_pressroom_update')):
/**
*
*/
//add_action('newswire_pressroom_update', 'newswire_pressroom_update');
function newswire_pressroom_update() {
    global $wpdb;
    global $newswire_pressroom_db_version;
    $installed_ver = get_option( "newswire_pressroom_db_version" );

    if ( $installed_ver === false) {
        $installed_ver = "default";
    }

    if( $installed_ver != $newswire_pressroom_db_version ) {

        /*$sql = "";         
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        */
        do_action('newswire_pressroom_update');
        do_action('newswire_pressroom_update_'.sanitize_key($installed_ver));
        $action = 'newswire_pressroom_update_'.sanitize_key($installed_ver).'_to_'.sanitize_key($newswire_pressroom_db_version);

        do_action($action);
        
    }
}
endif;

if ( !function_exists('newswire_pressroom_db_check')):
/**
*
*/
add_action('plugins_loaded', 'newswire_pressroom_db_check');
function newswire_pressroom_db_check() {
    global $newswire_pressroom_db_version;
    if ( get_site_option( 'newswire_pressroom_version' ) != $newswire_pressroom_db_version) {
        newswire_pressroom_update();
        //myplugin_install();
        //do_action('newswire_pressroom_update');
        update_option( "newswire_pressroom_db_version", $newswire_pressroom_db_version );
    }   //do_action('newswire_pressroom_update_'.get_site_option( 'newswire_pressroom_db_version' ).'_'.$newswire_pressroom_db_version);
}
endif;

if ( !function_exists('newswire_keep_default_pressroom_blocks_from_page')):

/* start writing updates here */
/**
* THis hook runs for all new updates, if you want to hook to specific version
* add_action('newswire_presssroom_update_10_to_101')
*/
add_action('newswire_pressroom_update_default', 'newswire_keep_default_pressroom_blocks_from_page' );
function newswire_keep_default_pressroom_blocks_from_page() {
    global $wpdb;
    //loop through eache existing pr blocks
    $posts = get_posts( $args = array('post_status' => 'publish', 
        'post_types' => array('pin_as_text', 'pin_as_embed', 'pin_as_social', 'pin_as_link', 'pin_as_contact', 'pin_as_image', 'pin_as_quote')) );
    foreach($posts as $post) {
        wp_set_post_tags($post->ID, 'pressroom', true );
    }

    //attach default image to sample contact block
    $post = get_page_by_title( $page_title = '(sample) Contact Block', OBJECT, $post_type ='pin_as_contact' );
    $meta = newswire_data($post->ID);

    if ( $post ) {
        $attachment_id = newswire_attach_image($image = NEWSWIRE_PLUGIN_ASSETS_DIR . '/images/DEBrown.jpg', $post->ID, 'id');
        if ( $meta && $attachment_id ) {
            //create new attachment media and attach to $post;
            $meta['contact_media_id'] = $attachment_id;
            $meta['show_contact_media_id'] = 1;
            update_post_meta( $post->ID, 'newswire_data', $meta );
        }
    }   
}
endif;