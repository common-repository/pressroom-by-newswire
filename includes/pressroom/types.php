<?php
if ( !defined('ABSPATH') ) {
    exit;
}
/**
* Initialize all custom post type aka content blocks you can see from press page
*
*/
if ( !function_exists('newswire_pressroom_post_types')) :
add_action('init', 'newswire_pressroom_post_types', 2);
function newswire_pressroom_post_types() {
    $capability_type = 'pressroom_block';

    //add all custom post type needed        
    //1. Not important post type - just use for consolidating other post types for lisitng purposes
    //   do we need it at all?
    //   @todo: figure this out
    $labels = array(
       'name'                => _x( 'All PressRoom Blocks', 'post type general name', 'newswire' ),
        'singular_name'      => _x( 'Text Block', 'post type singular name', 'newswire' ),   
        'add_new'            => _x( 'Add New Press Release', 'pressroom', 'newswire' ),
        'add_new_item'       => __( 'Add New Press Release', 'newswire' ),                                
        'new_item'           => __( 'New  Block', 'newswire' ),
        'edit_item'          => __( 'Edit Block', 'newswire' ),
        'view_item'          => __( 'View Block', 'newswire' ),
        'all_items'          => __( 'All Blocks', 'newswire' ),
        'search_items'       => __( 'Search Blocks', 'newswire' ),
        'parent_item_colon'  => __( 'Parent Blocks:', 'newswire' ),
        'not_found'          => __( 'No Block found.', 'newswire' ),
        'not_found_in_trash' => __( 'No Block found in Trash.', 'neswire' )     
    );        
    $args = array(
        'labels'               => $labels,        
        'public'               => false,
        'publicly_queryable'   => false,
        'query_var'            => false,
        'show_in_admin_bar'    => false,
        'show_in_nav_menus'    => false,
        'show_ui'              => true,
        'show_in_menu'         => false,
        'rewrite'              => array('slug' => 'press'),
        'description'          => __('PressRoom blocks menu wrapper', 'newswire' ),                
        'has_archive'          => false,
        'map_meta_cap'         => false,
        //'capabilities'         => array('create_posts'=> false),
        'capabilities' => array(
            'edit_post'          => 'edit_pressroom_block', 
            'read_post'          => 'read_pressroom_block', 
            'delete_post'        => 'delete_pressroom_block', 
            'edit_posts'         => 'edit_pressroom_blocks', 
            'edit_others_posts'  => 'edit_others_pressroom_blocks', 
            'publish_posts'      => 'publish_pressroom_blocks',       
            'read_private_posts' => 'read_private_pressroom_blocks', 
            'create_posts'       => 'edit_pressroom_blocks', 

            'read'                => "read",
            'delete_posts'         => "delete_pressroom_blocks",
            'delete_private_posts'   => "delete_private_{$capability_type}s",
            'delete_published_posts' => "delete_published_{$capability_type}s",
            'delete_others_posts'    => "delete_others_{$capability_type}s",
            'edit_private_posts'     => "edit_private_{$capability_type}s",
            'edit_published_posts'  => "edit_published_{$capability_type}s",
        ),
        'capability_type'      => array('pressroom_block','pressroom_blocks')            
    );      
    register_post_type('pressroom', $args);

  

//first block - Rich Text Block/Pin      
    $labels = array(
        'name'               => _x( 'Text Block', 'post type general name', 'newswire' ),
        'singular_name'      => _x( 'Text Block', 'post type singular name', 'newswire' ),
        'menu_name'          => _x( 'Text Block', 'admin menu', 'newswire' ),
        'name_admin_bar'     => _x( 'Text Block', 'add new on admin bar', 'newswire' ),
        'add_new'            => _x( 'Add New', 'pin_as_image', 'newswire' ),
        'add_new_item'       => __( 'Add New Text Block', 'newswire' ),
        'new_item'           => __( 'New Text Block', 'newswire' ),
        'edit_item'          => __( 'Edit Text Block', 'newswire' ),
        'view_item'          => __( 'View Text Block', 'newswire' ),
        'all_items'          => __( 'All Text Blocks', 'newswire' ),
        'search_items'       => __( 'Search Text Blocks', 'newswire' ),
        'parent_item_colon'  => __( 'Parent Text Blocks:', 'newswire' ),
        'not_found'          => __( 'No Text Block found.', 'newswire' ),
        'not_found_in_trash' => __( 'No Text Block found in Trash.', 'newswire' )     
    );        
    $args = array(
        'labels'        => $labels,        
        'public'             => false,
        'publicly_queryable' => false,
        'query_var'          => true,
        'show_in_admin_bar' => false,
        'show_in_nav_menus' => false,
        'show_ui' => true,
       // 'show_in_menu' => 'edit.php?post_type=pr',
        'show_in_menu'=> false,
        'rewrite'            => array('slug' => 'pressroom/text'),
        'description'   => 'Pressroom',                      
        'has_archive'   => false,
        'capability_type'     => array('pressroom_block','pressroom_blocks'),
        //'map_meta_cap'        => true,
        'supports'=> array('title', 'editor')
    );   register_post_type('pin_as_text', $args);


// Embedded Block/Pin -Youtube vides and others
    $labels = array(
        'name'               => _x( 'Embed Block', 'post type general name', 'newswire' ),
        'singular_name'      => _x( 'Embed Block', 'post type singular name', 'newswire' ),
        'menu_name'          => _x( 'Embed Block', 'admin menu', 'newswire' ),
        'name_admin_bar'     => _x( 'Embed Block', 'add new on admin bar', 'newswire' ),
        'add_new'            => _x( 'Add New', 'pin_as_Link', 'newswire' ),
        'add_new_item'       => __( 'Add New Embed Block', 'newswire' ),
        'new_item'           => __( 'New Embed Block', 'newswire' ),
        'edit_item'          => __( 'Edit Embed Block ', 'newswire' ),
        'view_item'          => __( 'View Embed Block', 'newswire' ),
        'all_items'          => __( 'All Embed Blocks', 'newswire' ),
        'search_items'       => __( 'Search Embed Blocks', 'newswire' ),
        'parent_item_colon'  => __( 'Parent Embed Blocks:', 'newswire' ),
        'not_found'          => __( 'No Embed Block found.', 'newswire' ),
        'not_found_in_trash' => __( 'No Embed Block found in Trash.', 'newswire' )             
    );        
    $args = array(
        'labels'        => $labels,        
        'public'             => false,
        'publicly_queryable' => false,
        'query_var'          => true,
        'show_in_admin_bar' => false,
        'show_in_nav_menus' => false,
        'show_ui' => true,
     // 'show_in_menu' => 'edit.php?post_type=pr',
        'show_in_menu'=> false,
        'rewrite'            => array('slug' => 'pressroom/embed'),
        'description'   => 'Pressroom',                      
        'has_archive'   => false,

        'capability_type'     => array('pressroom_block','pressroom_blocks'),
        //'map_meta_cap' => true,
        'supports'=> array('title', 'editor')
    );  register_post_type('pin_as_embed', $args);


// Image album block/Pin
    $labels = array(
        'name'               => _x( 'Image Albums', 'post type general name', 'newswire' ),
        'singular_name'      => _x( 'Image Album', 'post type singular name', 'newswire' ),
        'menu_name'          => _x( 'Image Album', 'admin menu', 'newswire' ),
        'name_admin_bar'     => _x( 'Image Album', 'add new on admin bar', 'newswire' ),
        'add_new'            => _x( 'Add New', 'pin_as_image', 'newswire' ),
        'add_new_item'       => __( 'Add New Image Album', 'newswire' ),
        'new_item'           => __( 'New Image Album', 'newswire' ),
        'edit_item'          => __( 'Edit Image Album', 'newswire' ),
        'view_item'          => __( 'View Image Album', 'newswire' ),
        'all_items'          => __( 'All Image Albums', 'newswire' ),
        'search_items'       => __( 'Search Image Albums', 'newswire' ),
        'parent_item_colon'  => __( 'Parent Image Albums:', 'newswire' ),
        'not_found'          => __( 'No Image Album found.', 'newswire' ),
        'not_found_in_trash' => __( 'No Image Album found in Trash.', 'newswire' )            
    );    
    $args = array(
        'labels'        => $labels,        
        'public'             => false,
        'publicly_queryable' => false,
        'query_var'          => true,
        'show_in_admin_bar' => false,
        'show_in_nav_menus' => false,
        'show_ui' => true,
     // 'show_in_menu' => 'edit.php?post_type=pr',
        'show_in_menu'=> false,
        'rewrite'            => array('slug' => 'pressroom/album'),
        'description'   => 'Pressroom',                      
        'has_archive'   => false,
        'register_meta_box_cb' => 'newswire_metabox_image_gallery',
        'capability_type'     => array('pressroom_block','pressroom_blocks'),
        //'map_meta_cap' => true,
        'supports'=> array('title')

    );  register_post_type('pin_as_image', $args);

// Quote block/Pin 
    $labels = array(
        'name'               => _x( 'Quote Block Block', 'post type general name', 'newswire' ),
        'singular_name'      => _x( 'Quote Block Block', 'post type singular name', 'newswire' ),
        'menu_name'          => _x( 'Quote Block Block', 'admin menu', 'newswire' ),
        'name_admin_bar'     => _x( 'Quote Block Block', 'add new on admin bar', 'newswire' ),
        'add_new'            => _x( 'Add New', 'pin_as_Link', 'newswire' ),
        'add_new_item'       => __( 'Add New Quote Block', 'newswire' ),
        'new_item'           => __( 'New Quote Block ', 'newswire' ),
        'edit_item'          => __( 'Edit Quote Block ', 'newswire' ),
        'view_item'          => __( 'View Quote Block', 'newswire' ),
        'all_items'          => __( 'All Quote Block', 'newswire' ),
        'search_items'       => __( 'Search Quote Block', 'newswire' ),
        'parent_item_colon'  => __( 'Parent Quote Block:', 'newswire' ),
        'not_found'          => __( 'No Quote Block found.', 'newswire' ),
        'not_found_in_trash' => __( 'No Quote Block found in Trash.', 'newswire' )     
    );        
    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'publicly_queryable'  => false,
        'query_var'           => true,
        'show_in_admin_bar'   => false,
        'show_in_nav_menus'   => false,
        'show_ui'             => true,
     // 'show_in_menu'     => 'edit.php?post_type=pr',
        'show_in_menu'        => false,
        'rewrite'             => array('slug'=>'pressroom/quote'),
        'description'         => 'Pressroom',
        'register_meta_box_cb'=> 'newswire_metabox_pin_as_quote',
        'has_archive'         => false,
        'capability_type'     => array('pressroom_block','pressroom_blocks'),
        //'map_meta_cap' => true,
        'supports'            => array('title', 'editor')
    );   register_post_type('pin_as_quote', $args);


//Social Media
    $labels = array(
        'name'               => _x( 'Social Media Block', 'post type general name', 'newswire' ),
        'singular_name'      => _x( 'Social Media Block', 'post type singular name', 'newswire' ),
        'menu_name'          => _x( 'Social Media Block', 'admin menu', 'newswire' ),
        'name_admin_bar'     => _x( 'Social Media Block', 'add new on admin bar', 'newswire' ),
        'add_new'            => _x( 'Add New', 'pin_as_Link', 'newswire' ),
        'add_new_item'       => __( 'Add New Social Media', 'newswire' ),
        'new_item'           => __( 'New Social Media ', 'newswire' ),
        'edit_item'          => __( 'Edit Social Media ', 'newswire' ),
        'view_item'          => __( 'View Social Media', 'newswire' ),
        'all_items'          => __( 'All Social Media', 'newswire' ),
        'search_items'       => __( 'Search Social Media', 'newswire' ),
        'parent_item_colon'  => __( 'Parent Social Media:', 'newswire' ),
        'not_found'          => __( 'No Social Media found.', 'newswire' ),
        'not_found_in_trash' => __( 'No Social Media found in Trash.', 'newswire' )         
    );        
    $args = array(
        'labels'        => $labels,        
        'public'             => false,
        'publicly_queryable' => false,
        'query_var'          => true,
        'show_in_admin_bar' => false,
        'show_in_nav_menus' => false,
        'show_ui' => true,
    // 'show_in_menu' => 'edit.php?post_type=pr',
        'show_in_menu'=> false,
        'rewrite'            => array('slug' => 'pressroom/social-media'),
        'description'   => 'Pressroom',                      
        'has_archive'   => false,
        //'register_meta_box_cb' => array($pressroom, 'add_metabox_header_script'),
        'capability_type'     => array('pressroom_block','pressroom_blocks'),
        //'map_meta_cap' => true,
        'supports'=> array('title', 'editor')
    );  register_post_type('pin_as_social', $args);


// Link
    $labels = array(
        'name'               => _x( 'Link Block', 'post type general name', 'newswire' ),
        'singular_name'      => _x( 'Link Block', 'post type singular name', 'newswire' ),
        'menu_name'          => _x( 'Link Block', 'admin menu', 'newswire' ),
        'name_admin_bar'     => _x( 'Link Block', 'add new on admin bar', 'newswire' ),
        'add_new'            => _x( 'Add New', 'pin_as_Link', 'newswire' ),
        'add_new_item'       => __( 'Add New Link', 'newswire' ),
        'new_item'           => __( 'New Link ', 'newswire' ),
        'edit_item'          => __( 'Edit Link ', 'newswire' ),
        'view_item'          => __( 'View Link', 'newswire' ),
        'all_items'          => __( 'All Links', 'newswire' ),
        'search_items'       => __( 'Search Links', 'newswire' ),
        'parent_item_colon'  => __( 'Parent Links:', 'newswire' ),
        'not_found'          => __( 'No Link found.', 'newswire' ),
        'not_found_in_trash' => __( 'No Link found in Trash.', 'newswire' )         
    );        
    $args = array(
        'labels'        => $labels,        
        'public'             => false,
        'publicly_queryable' => false,
        'query_var'          => true,
        'show_in_admin_bar' => false,
        'show_in_nav_menus' => false,
        'show_ui' => true,
     // 'show_in_menu' => 'edit.php?post_type=pr',
        'show_in_menu'=> false,
        'rewrite'            => array('slug' => 'pressroom/link'),
        'description'   => 'Pressroom',                      
        'has_archive'   => false,
        'register_meta_box_cb' => 'newswire_metabox_pin_as_link',
        'capability_type'     => array('pressroom_block','pressroom_blocks'),
        //'map_meta_cap' => true,
        'supports'=> array('title')
    );  register_post_type('pin_as_link', $args);


// Contact Block
    $labels = array(
        'name'               => _x( 'Contact Block', 'post type general name', 'newswire' ),
        'singular_name'      => _x( 'Contact Block', 'post type singular name', 'newswire' ),
        'menu_name'          => _x( 'Contact Block', 'admin menu', 'newswire' ),
        'name_admin_bar'     => _x( 'Contact Block', 'add new on admin bar', 'newswire' ),
        'add_new'            => _x( 'Add New', 'pin_as_contact', 'newswire' ),
        'add_new_item'       => __( 'Add New Contact', 'newswire' ),
        'new_item'           => __( 'New Contact Block', 'newswire' ),
        'edit_item'          => __( 'Edit Contact', 'newswire' ),
        'view_item'          => __( 'View Contact ', 'newswire' ),
        'all_items'          => __( 'All Contacts', 'newswire' ),
        'search_items'       => __( 'Search Contacts', 'newswire' ),
        'parent_item_colon'  => __( 'Parent Contacts:', 'newswire' ),
        'not_found'          => __( 'No Contact found.', 'newswire' ),
        'not_found_in_trash' => __( 'No Contact in Trash.', 'newswire' )     
    );    

    $args = array(
        'labels'        => $labels,        
        'public'             => false,
        'publicly_queryable' => false,
        'query_var'          => true,
        'show_in_admin_bar' => false,
        'show_in_nav_menus' => false,
        'show_ui' => true,
     // 'show_in_menu' => 'edit.php?post_type=pr',
        'show_in_menu'=> false,
        'rewrite'            => array('slug' => 'pressroom/contact'),
        'description'   => 'Pressroom',                      
        'has_archive'   => false,
        'register_meta_box_cb' => 'newswire_metabox_pin_as_contact',
        /*
        'capabilities'     => array(
            'edit_post' => 'edit_pressroom_block',
            'read_post' => 'read_pressroom_block',
            'delete_post'=> 'delete_pressroom_block',
            'edit_posts'=> '',
            'edit_others_posts'=>'',
            'publish_posts',
            'read_private_posts'
        ),*/
       'capability_type'     => array('pressroom_block','pressroom_blocks'),
      // 'map_meta_cap' => true,
       'supports'=> array('title')

    );  
    register_post_type('pin_as_contact', $args);

    // Contact Block
    $labels = array(
        'name'               => _x( 'Latest PR Block', 'post type general name', 'newswire' ),
        'singular_name'      => _x( 'Latest PR Block', 'post type singular name', 'newswire' ),
        'menu_name'          => _x( 'Latest PR Block', 'admin menu', 'newswire' ),
        'name_admin_bar'     => _x( 'Latest PR Block', 'add new on admin bar', 'newswire' ),
        'add_new'            => _x( 'Add New Latest PR Block', 'pin_as_latestpr', 'newswire' ),
        'add_new_item'       => __( 'Add Latest Press Releases Block', 'newswire' ),
        'new_item'           => __( 'New Latest PR Block', 'newswire' ),
        'edit_item'          => __( 'Edit Latest PR Block', 'newswire' ),
        'view_item'          => __( 'View Latest PR Block ', 'newswire' ),
        'all_items'          => __( 'All Latest PR Block', 'newswire' ),
        'search_items'       => __( 'Search Latest PR Block', 'newswire' ),
        'parent_item_colon'  => __( 'Parent Latest PR Block:', 'newswire' ),
        'not_found'          => __( 'No Latest PR Block found.', 'newswire' ),
        'not_found_in_trash' => __( 'No Latest PR pressroom_block in Trash.', 'newswire' )     
    );    

    $args = array(
        'labels'        => $labels,        
        'public'             => false,
        'publicly_queryable' => false,
        'query_var'          => true,
        'show_in_admin_bar' => false,
        'show_in_nav_menus' => false,
        'show_ui' => true,
     // 'show_in_menu' => 'edit.php?post_type=pr',
        'show_in_menu'=> false,
        'rewrite'            => array('slug' => 'pressroom/latestpr'),
        'description'   => 'Pressroom',                      
        'has_archive'   => false,
        'register_meta_box_cb' => 'newswire_metabox_pin_as_latestpr',
        /*
        'capabilities'     => array(
            'edit_post' => 'edit_pressroom_block',
            'read_post' => 'read_pressroom_block',
            'delete_post'=> 'delete_pressroom_block',
            'edit_posts'=> '',
            'edit_others_posts'=>'',
            'publish_posts',
            'read_private_posts'
        ),*/
       'capability_type'     => array('pressroom_block','pressroom_blocks'),
      // 'map_meta_cap' => true,
       'supports'=> array('title')

    );  
    register_post_type('pin_as_latestpr', $args);
    
}
endif;

