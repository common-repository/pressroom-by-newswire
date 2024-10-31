<?php
if ( !defined('ABSPATH') ) {
    exit;
}
if ( !function_exists('newswire_admin_menu') ):
    
add_action('admin_menu', 'newswire_admin_menu');
/**
* Register PressRoom Menu
*/
function newswire_admin_menu() {

     global 
        $submenu, 
        $menu,    
        $_wp_real_parent_file,
        $_wp_submenu_nopriv,
        $_registered_pages,
        $_parent_pages,

        $typenow, $pagenow, $submenu_file, $post, $parent_file;

        $options = newswire_options();
    /// Main Menu Handler
    //@todo - make sure to get a correct index so not to override others
    if ( current_user_can('edit_pressroom_blocks') ) {
        
        $menu['5.000123456'] = array('PressRoom', 'edit_pressroom_blocks', 'edit.php?post_type=pressroom', '', 'menu-top menu-icon-pressroom', 'menu-pressroom', 'div');
    }


    $i = 1;
    // PressRoom Block menus
    //Listing All Content block menu

    if ( current_user_can('edit_pressroom_blocks') ) {
  
        $submenu['edit.php?post_type=pressroom'][++$i] = array(
            $menu_title = sprintf('<span class="">%s</span>', __('All PressRoom Blocks', 'newswire'))
            , $capability = 'edit_pressroom_blocks', ('edit.php?post_type=pressroom'),
        );
    }

    // Add Press Release Content Menu;

    if ( current_user_can('edit_pressroom_blocks')) {

        $submenu['edit.php?post_type=pressroom'][++$i] = array(
            $menu_title = sprintf('<span class="">%s</span>', __('Add Press Release', 'newswire'))
            , $capability = 'edit_pressroom_blocks', ('post-new.php?post_type=pr'),
        );
    }   

    // Menu blocks for each content block 
    // @todo: set correct capability and current_user_can if needed to show/hide to specific user role
    //
    if ( current_user_can('edit_pressroom_blocks') ):

        //Block menus
        $submenu['edit.php?post_type=pressroom'][++$i] = array(
            $menu_title = sprintf('<span class="">%s</span>', __('Add Text Block', 'newswire'))
            , $capability = 'edit_pressroom_blocks', ('post-new.php?post_type=pin_as_text'),
        );

        $submenu['edit.php?post_type=pressroom'][++$i] = array(
            $menu_title = sprintf('<span class="">%s</span>', __('Add Embed Block', 'newswire'))
            , $capability = 'edit_pressroom_blocks', ('post-new.php?post_type=pin_as_embed'),
        );

        $submenu['edit.php?post_type=pressroom'][++$i] = array(
            $menu_title = sprintf('<span class="">%s</span>', __('Add Image Album', 'newswire'))
            , $capability = 'edit_pressroom_blocks', ('post-new.php?post_type=pin_as_image'),
        );

        $submenu['edit.php?post_type=pressroom'][++$i] = array(
            $menu_title = sprintf('<span class="">%s</span>', __('Add Quote Block', 'newswire'))
            , $capability = 'edit_pressroom_blocks', ('post-new.php?post_type=pin_as_quote'),
        );

        $submenu['edit.php?post_type=pressroom'][++$i] = array(
            $menu_title = sprintf('<span class="">%s</span>', __('Add Social Media', 'newswire'))
            , $capability = 'edit_pressroom_blocks', ('post-new.php?post_type=pin_as_social'),
        );

        $submenu['edit.php?post_type=pressroom'][++$i] = array(
            $menu_title = sprintf('<span class="">%s</span>', __('Add Link Block', 'newswire'))
            , $capability = 'edit_pressroom_blocks', ('post-new.php?post_type=pin_as_link'),
        );

        $submenu['edit.php?post_type=pressroom'][++$i] = array(
            $menu_title = sprintf('<span class="">%s</span>', __('Add Contact Block', 'newswire'))
            , $capability = 'edit_pressroom_blocks', ('post-new.php?post_type=pin_as_contact'),
        );

        if ( isset($options['latest_press_releases']) && $options['latest_press_releases'] && get_post($options['latest_press_releases']) ) {
             $submenu['edit.php?post_type=pressroom'][++$i] = array(
                $menu_title = sprintf('<span class="">%s</span>', __('Add Latest PR Block', 'newswire'))
            , $capability = 'edit_pressroom_blocks', ('post.php?post='.$options['latest_press_releases'].'&action=edit'),
            );
        } else {
             $submenu['edit.php?post_type=pressroom'][++$i] = array(
                $menu_title = sprintf('<span class="">%s</span>', __('Add Latest PR Block', 'newswire'))
            , $capability = 'edit_pressroom_blocks', ('post-new.php?post_type=pin_as_latestpr'),
            );
        }
       


        // Settings
        add_submenu_page('edit.php?post_type=pressroom', 
            $page_title = __('Settings', 'newswire'), 
            $menu_title = __('Settings', 'newswire'), 
            $capability = 'administrator', 'newsroom-settings', 
            $function = 'newswire_settings_page_callback'
        );
        
        //download pro
        //if ( !in_array( newswirexpres_pro(), array_keys(get_plugins()) ) ) {
            add_submenu_page( 'edit.php?post_type=pressroom', 
                $page_title = __('Download Pro', 'newswire'), 
                $menu_title = __('Download Pro Version', 'newswire'), 
                $capability = 'administrator', 'newswire-download-pro', '__return_false'
                );
        //}
    endif;

}
endif;


if ( !function_exists('newswire_resolve_active_menus')):

add_filter('parent_file', 'newswire_resolve_active_menus');
/*
 * Admin - Hack admin active menu
 */
function newswire_resolve_active_menus($parent_file) {


    //return $parent_file;
    global $typenow, $pagenow, $submenu_file, $post, $post_new_file;
   
    $options = newswire_options();

    

    //specific to PRReporter
    if (current_user_can('PRReporter')) {

        if ($typenow == 'pr' && $pagenow == 'edit.php') {

            $parent_file = 'edit.php?post_type=pressroom';

            return $parent_file;
        }

        if ($typenow == 'pr' && $pagenow == 'post.php') {

            $parent_file = 'edit.php?post_type=pressroom';

            return $parent_file;
        }

    } elseif (current_user_can('author') || current_user_can('editor')) {

        if ($typenow == 'pr' && $pagenow == 'post.php') {

            $parent_file = 'edit.php?post_type=pressroom';

            $submenu_file = 'edit.php?post_type=pressroom';

            return $parent_file;
        }
    }

    //all other roles goes here

    if ($typenow == 'pr' && $pagenow == 'post-new.php') {

        $submenu_file = 'post-new.php?post_type=pr';

        $parent_file = 'edit.php?post_type=pressroom';

        return $parent_file;

    } elseif ($parent_file == 'edit.php?post_type=pr' && $submenu_file == 'edit.php?post_type=pr' && $pagenow== 'post.php') {

        //lastchanges
        //$submenu_file = ('edit.php?post_type=pr');
        $submenu_file = 'post-new.php?post_type=pr';

        $parent_file = 'edit.php?post_type=pressroom';

        return $parent_file;

    } elseif ($parent_file == 'edit.php?post_type=pr' && $submenu_file == 'post-new.php?post_type=pr') {
        $submenu_file = ('post-new.php?post_type=pr');
        $parent_file = 'edit.php?post_type=pressroom';

    } elseif ($submenu_file == 'post-new.php?post_type=pr' || ($submenu_file == 'edit.php?post_type=pr')) {
        //$submenu_file = str_replace('=press, replace, subject)
        //   echo $submenu_file;
        //   echo $parent_file;
        $parent_file = 'edit.php?post_type=pressroom';

    } elseif ( !empty($_REQUEST['page']) && $_REQUEST['page'] == 'newsroom-settings') {
        
        return $parent_file;

    } elseif ('post-new.php' == $pagenow && in_array($typenow, newswire_pressroom_blocks())) {
        
        $submenu_file = ('post-new.php?post_type=' . $_GET['post_type']);
        $parent_file = 'edit.php?post_type=pressroom';

    } elseif ('edit.php' == $pagenow && (get_query_var('post_type') && $typenow == 'pr')) {
        //edit.php?post_type=pr
        $submenu_file = ('edit.php?post_type=pr');
        $parent_file = 'edit.php?post_type=pr';
    } elseif ($typenow == 'pr') {
        //edit.php?post_type=pr
        $submenu_file = ('edit.php?post_type=pr&page=newsroom-settings');
        $parent_file = 'edit.php?post_type=pr';

    } elseif ('edit.php' == $pagenow && (get_query_var('post_type') && $typenow == 'pressroom')) {
        //edit.php?post_type=pr
        $submenu_file = ('edit.php?post_type=pressroom');
        $parent_file = 'edit.php?post_type=pressroom';

   } elseif ('post.php' == $pagenow && in_array($typenow, newswire_pressroom_blocks()) && $_GET['post'] == $options['latest_press_releases'] ) {
        

    } elseif ('post.php' == $pagenow && in_array($typenow, newswire_pressroom_blocks())) {

        $post_type = get_post_type($post->ID);

        $submenu_file = ('edit.php?post_type=pressroom');
        $parent_file = 'edit.php?post_type=pressroom';
    } elseif ('post.php' == $pagenow && in_array($typenow, array('pr'))) {
        $submenu_file = ('edit.php?post_type=pressroom');
        $parent_file = 'edit.php?post_type=pressroom';
    

    } elseif ($parent_file == 'edit.php?post_type=pr') {
        $submenu_file = ('post-new.php?post_type=pr');
        $parent_file = 'edit.php?post_type=pressroom';

    }
    //var_dump($submenu_file);
    //var_dump($parent_file);
    //echo $submenu_file;
    return $parent_file;
}

endif;