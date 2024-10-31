<?php
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #  
# make sure uninstall.php is called from wordpress and not by calling this file directly
# @todo: make sure all otpions are deleted
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if ( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') ) {
    exit();
}

if (! function_exists('newswire_delete_children') ) :
function newswire_delete_children($post_id) {
    $args = array(
        'post_type' => 'any',
        'numberposts' => -1,
        'post_status' => 'any',
        'post_parent' => $post_id
    );
    $posts = get_posts($args);
    
    if ($posts) {
        foreach($posts as $post) {
            wp_delete_post($post->ID, true);
            
        }
    }
}
endif;

if ( !function_exists('newswire_delete_attachments')):
/**
* Delete all attachments
*
*/
function newswire_delete_attachments($post_id) {
    $args = array(
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_status' => 'any',
        'post_parent' => $post_id
    );
    $attachments = get_posts($args);
    
    if ($attachments) {
        foreach($attachments as $attachment) {
            wp_delete_attachment($attachment->ID, true);
            @unlink(get_attached_file( $attachment->ID, true));
        }
    }
}
endif;


if ( ! function_exists('newswire_uninstall_me')):
/**
* Uninstall callback
* do not delete anything if uninstalling this freeversion when newswirexpress is active or is available
*
*/
function newswire_uninstall_me() {
    $plugins = get_plugins();

    if ( in_array('newswirexpress/newswirexpress.php', array_keys($plugins) ) ) {
        
        //do not delete files
        return ;
    }

    set_time_limit(0);
    
    ignore_user_abort();
    
    global $wpdb;
    
    remove_role( 'PRReporter' );
    
    # if not wp mu
    if ( !is_multisite() ) {
        // delete_option( $option_name );
        // remove all option
        // newswire_delete_options();
        $options = get_option( 'newswire_options' );
            

        add_action('before_delete_post', 'newswire_delete_attachments');
        add_action('before_delete_post', 'newswire_delete_children');

        //dete content type
        //delete post_meta
        //post_term relationship
        //$sql = 'SELECT FROM '.$wpdb->posts ." WHERE post_type IN ('pr', 'pin_as_contact', 'pin_as_embed', 'pin_as_image', 'pin_as_link', 'pin_as_quote', 'pin_as_social', 'pin_as_text') ";
            
        $args = array(
            'nopaging' => true,
            'posts_per_page' => -1,
            'post_status' => 'any',
            'post_type' => array('pr', 'pin_as_contact', 'pin_as_embed', 'pin_as_image', 'pin_as_link', 'pin_as_quote', 'pin_as_social', 'pin_as_text')
            );
        $query = new WP_Query($args);
        $posts = $query->get_posts();

        foreach($posts as $post) {
            wp_delete_post( $post->ID, $force_delete = true );
        }

        
        $page = get_page_by_title('pressroom' );
        wp_delete_post( $page->ID, $force_delete = true );

        $page = get_page_by_title('newsroom' );
        wp_delete_post( $page->ID, $force_delete = true );
        
        //delete autocreated pages
        //delete newsroom page
        wp_delete_post($options['newsroom_page_template'], true);
        wp_delete_post($options['pressroom_page_template'], true);

        delete_option( 'newswire_autocreate_pressroom' );
        delete_option( 'newswire_options');
        delete_option( 'newswire_categories');
        delete_option( 'newswire_categories_flat');
        
    } else {
        // Multisite?
        
        add_action('before_delete_post', 'newswire_delete_attachments');
        add_action('before_delete_post', 'newswire_delete_children');
        

        $blog_ids = $wpdb->get_col( 'SELECT blog_id FROM '. $wpdb->blogs );
        $original_blgo_id = get_current_blog_id();
        foreach( $blogsds as $blog_id )  {
            switch_to_blog( $blog_id , $validate = false );

            delete_option( 'newswire_autocreate_pressroom' );
            delete_option( 'newswire_options');
            delete_option( 'newswire_categories');
            
            $options = get_option( 'newswire_options' );

            //delete autocreated pages
            //delete newsroom page
            wp_delete_post($options['newsroom_page_template'], true);
            wp_delete_post($options['pressroom_page_template'], true);

            //multile delete site options
        }
        //switch back to original blog 
        switch_to_blog( $original_blgo_id );

        $args = array(
            'nopaging' => true,
            'posts_per_page' => -1,
            'post_status' => 'any',
            'post_type' => array('pr', 'pin_as_contact', 'pin_as_embed', 'pin_as_image', 'pin_as_link', 'pin_as_quote', 'pin_as_social', 'pin_as_text')
            );
        $query = new WP_Query($args);
        $posts = $query->get_posts();

        foreach($posts as $post) {
            wp_delete_post( $post->ID, $force_delete = true );
        }
    }

    unset($options);
}
//call uninstall 
newswire_uninstall_me();
endif;
