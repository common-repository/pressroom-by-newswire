<?php
if ( !defined('ABSPATH') ) {
    exit;
}

if ( !function_exists('newswire_global_scripts')):
/*
 *  Register all css and scripts enqueue later only when needed
 *
 */
add_action('wp_loaded', 'newswire_global_scripts', 1);
function newswire_global_scripts() {
    global $post;
    $plugin_dir  = basename(NEWSWIRE_DIR);
    add_thickbox();
    //register
    //wp_register_script( $handle, $src, $deps, $ver, $in_footer );
    //wp_register_script('newswire-zclip', plugins_url('assets/js/jquery.zclip.1.1.1/jquery.zclip.js', __FILE__), array('jquery'), null, true);
    wp_register_script('newswire-zeroclip', plugins_url($plugin_dir.'/assets/js/zeroclipboard/ZeroClipboard.min.js'), array('jquery'), null, true);
    wp_register_script('newswire-jplugin', plugins_url($plugin_dir.'/assets/js/jquery.plugin.min.js'), array('jquery'), null, true);
    wp_register_script('newswire-more', plugins_url($plugin_dir.'/assets/js/more.min.js'), array('jquery'), null, true);
    wp_register_script('newswire-readmore', plugins_url($plugin_dir.'/assets/js/readmore.min.js'), array('jquery'), null, true);
    wp_register_script('newswire-masonry', plugins_url($plugin_dir.'/assets/js/jquery.masonry.min.js'), array('jquery'), null, true);
    wp_register_script('newswire-infinitescroll', plugins_url($plugin_dir.'/assets/js/jquery.infinitescroll.min.js'), array('jquery'), null, true);
    //include nivoslider

    wp_register_script('nivoslider-js', plugins_url( basename(NEWSWIRE_DIR).'/assets/js/nivo-slider/jquery.nivo.slider.pack.js'), array('jquery'), null, true);
    wp_register_style('nivoslider-css', plugins_url( basename(NEWSWIRE_DIR).'/assets/js/nivo-slider/nivo-slider.css'));
    wp_register_style('nivoslider-theme', plugins_url(basename(NEWSWIRE_DIR).'/assets/js/nivo-slider/themes/default/default.css'));

    //common plugin css
    wp_register_style('newsroom-css-global', plugins_url($plugin_dir.'/assets/css/plugin.css'));
    wp_enqueue_style('newsroom-css-global');

}
endif;

if ( !function_exists('newswire_load_js_and_css')):
/**
* enqueue required js and css from admin
*
* @todo: load css and js only where its needed. one way to avoid conflict and lessen the admin load
*/
add_action('admin_enqueue_scripts', 'newswire_load_js_and_css' );
function newswire_load_js_and_css() {

    global $hook_suffix;

     //localize script
    $_newswire_vars = array(
        'site_url' => get_site_url(),
        'name' => get_bloginfo(),
        'theme_directory' => get_template_directory_uri()
    );    
    wp_localize_script( 'jquery', 'siteinfo', $_newswire_vars );


    wp_enqueue_media(); 
    /* need this for some sortable box from settings later 
    wp_enqueue_script( 'jquery-ui-core'      );
    wp_enqueue_script( 'jquery-ui-tabs'      );
    wp_enqueue_script( 'jquery-ui-mouse'     );
    wp_enqueue_script( 'jquery-ui-draggable' );
    wp_enqueue_script( 'jquery-ui-droppable' );
    wp_enqueue_script( 'jquery-ui-sortable'  );
    
    */
    // global js    
    wp_register_script( 'newswire-admin', plugins_url( basename(NEWSWIRE_DIR) . '/assets/js/admin.js'), array('jquery', 'wp-color-picker'), '1.1', true );
    wp_enqueue_script( 'newswire-admin' );

    //Needed from categorys
    wp_register_script('newswire-admin-masonry', plugins_url( basename(NEWSWIRE_DIR) . '/assets/js/jquery.masonry.min.js'), array('jquery'), null, true);
    wp_enqueue_script( 'newswire-admin-masonry' );

    //pluploader
    //wp_enqueue_script('plupload-all');
    wp_enqueue_script('plupload-handlers');

    //post specific
    wp_register_script( 'newswire_post', plugins_url( basename(NEWSWIRE_DIR)  . '/assets/js/post.js' ), array('jquery', 'jquery-ui-tabs', 'jquery-ui-tooltip', 'jquery-ui-datepicker'), '1.1', true);
    wp_enqueue_script( 'newswire_post' );

    //global css
    wp_register_style( 'newswire_css_admin', NEWSWIRE_PLUGIN_URL . 'assets/css/admin.css');
    wp_enqueue_style( 'newswire_css_admin');

    //enqueue color picker/swash
    wp_enqueue_style('wp-color-picker'); 
    //wp_enqueue_style('jquery-ui-picker'); 
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
}
endif;



/**
* Category filter masonry
* prninja settings page , category tab
*/
add_action('admin_footer', 'newswire_admin_category_filter');
function newswire_admin_category_filter() { 
    ?><script>
        jQuery(document).ready(function($){     
            $masonry = $('.category-filter');
            if ( !$masonry.length ) return;

            $masonry.masonry({
                itemSelector : '.pin-box',
                isFitWidth: true
            }).css('visibility', 'visible');                    
            $masonry.animate({opacity: 1});         
        }); 
</script><?php
}
