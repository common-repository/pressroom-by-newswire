<?php

add_action('init', 'newswire_register_pressrelease_type', 11);
function newswire_register_pressrelease_type() {

    $labels = array(
        'name' => _x('All News', 'post type general name'),
        'singular_name' => _x('Press Release', 'post type singular name'),
        'add_new' => _x('Add New', 'Press Release'),
        'add_new_item' => __('Add New Press Release'),
        'edit_item' => __('Edit Press Release'),
        'new_item' => __('New Press Release'),
        'all_items' => __('All Press Releases'),
        'view_item' => __('View Press Release'),
        'search_items' => __('Search Press Release'),
        'not_found' => __('No press release found'),
        'not_found_in_trash' => __('No press release found in the Trash'),
        'parent_item_colon' => '',
        'menu_name' => newswire_plugin_title(),
    );
    $capability_type = 'pressroom_block';
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'query_var' => true,
        'show_in_menu' => false, /* set this back to tru to make sure */
        'rewrite' => array('slug' => 'newsroom/pr'),
        'description' => 'All Press Releases',
        //'menu_position'   =>  99998, //unset this somewhere
        'menu_icon' => plugin_dir_url(__FILE__) . '/assets/images/newswire_icon.png',
        'taxonomies' => array('post_tag'),
        'has_archive' => false,
        /*'capabilities' => array(
        'edit_post'=>
        'edit_newswire_pr',
        'edit_posts'=> 'edit_newswire_pr'
        ),*/
        'capability_type' => array('pressroom_block', 'pressroom_blocks'),
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
        'map_meta_cap' => false,
        'supports' => array('title', 'editor', 'excerpt', 'revisions', 'thumbnail'),
    );
    register_post_type('pr', $args);
    
    //echo '<pre>';
    //var_dump( $GLOBALS['wp_post_types']['pr']);
    //echo '</pre>';
    //exit;
    /*object(stdClass)#133 (15) {
["edit_post"]=>
string(15) "edit_newswire_pr"
["read_post"]=>
string(15) "read_newswire_pr"
["delete_post"]=>
string(17) "delete_newswire_pr"
["edit_posts"]=>
string(16) "edit_newswire_prs"
["edit_others_posts"]=>
string(23) "edit_others_newswire_prs"
["publish_posts"]=>
string(19) "publish_newswire_prs"
["read_private_posts"]=>
string(24) "read_private_newswire_prs"
["read"]=>
string(4) "read"
["delete_posts"]=>
string(18) "delete_newswire_prs"
["delete_private_posts"]=>
string(26) "delete_private_newswire_prs"
["delete_published_posts"]=>
string(28) "delete_published_newswire_prs"
["delete_others_posts"]=>
string(25) "delete_others_newswire_prs"
["edit_private_posts"]=>
string(24) "edit_private_newswire_prs"
["edit_published_posts"]=>
string(26) "edit_published_newswire_prs"
["create_posts"]=>
string(16) "edit_newswire_prs"*/
}