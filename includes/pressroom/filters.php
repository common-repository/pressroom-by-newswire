<?php
if ( !defined('ABSPATH') ) {
    exit;
}

/** 
* pressroom blocks filters 
*/
if ( !function_exists('newswire_contact_download_links')):

add_filter('newswire_contact_block_footer', 'newswire_contact_download_links' );
function newswire_contact_download_links(){

    echo '<ul>';
    printf('<li class="download"> <span>Download: </span></li>');
    printf('<li><a href="?action=download_contact_details&dtype=html&id=%d" >HTML</a></li>', get_the_ID());
    printf('<li><a href="?action=download_contact_details&dtype=vcard&id=%d" >vCard</a></li>', get_the_ID());
    printf('<li><a href="?action=download_contact_details&dtype=txt&id=%d" >TEXT</a></li>', get_the_ID());
    echo '</ul>';
}
endif;


add_action('wp_head', 'newswire_print_newswroom_custom_style', 100);
function newswire_print_newswroom_custom_style() {
    $options = newswire_options();
    echo '<style type="text/css">';
    if ( current_user_can('administrator' )) :
        echo '.navbar-fixed-top { top: 32px};';
    endif;
    echo $options['custom_css'];
    echo $options['pressroom_custom_css'];

    echo '</style>';
}

/**
 * wp version 3.7 and up
 * resttric from media access only for PRReporter
 */
add_filter('ajax_query_attachments_args', "newswire_user_restrict_media_library");
function newswire_user_restrict_media_library($query) {

    global $current_user;
    $user = wp_get_current_user();

    //if ( in_array('PRReporter', $user->roles) && count($user->roles) == 1 && current_user_can('prreporter_cap') ) {
    //
    if (current_user_can('prreporter_cap_only')) {
        $query['author'] = $user->ID;
    }

    return $query;
}

add_theme_support('post-thumbnails');
add_image_size('pin_as_contact_thumb', 100, 100, true); // Hard Crop Mode
add_image_size('pin_as_image_thumb', 300, 300, true); // Hard Crop Mode
add_image_size('pin_image_size1', 300, 300, true); // Hard Crop Mode
add_image_size('pin_image_size2', 200, 200, true); // Hard Crop Mode
add_image_size('pin_image_size3', 150, 200, true); // Hard Crop Mode
add_image_size('pin_image_size4', 300, 200, true); // Hard Crop Mode
add_image_size('pin_image_size5', 300, 400, true); // Hard Crop Mode

/**
 * Add thumnail support for 300x300
 */
if (function_exists('add_theme_support')) {
    /**
     * Setup image size and post thumbnail
     */
    function newswire_image_setup() {
        add_theme_support('post-thumbnails');
        add_image_size('newswire_thumb', 640, 640, true); //300 pixels wide (and unlimited height)
        add_image_size('pin_as_contact_thumb', 100, 100, true); // Hard Crop Mode
    }
    add_action('after_setup_theme', 'newswire_image_setup');

    /**
     * Setup image custom sizes
     */
    function my_custom_sizes($sizes) {
        return array_merge($sizes, array(
            'newswire_thumb' => __('Newswire Thumb'),
            'pin_as_contact_thumb' => __('Contact Image'),

        ));
    }
    add_filter('image_size_names_choose', 'my_custom_sizes');
}

// Internationlization
// load_theme_textdomain( 'newswire', NEWSWIRE_PLUGIN_ASSETS_DIR . '/languages' );
/**
 * Register post status
 *
 */
add_action('init', 'newswire_register_post_status');
function newswire_register_post_status() {

    //$client = Newswire_Client::getInstance();
    //$result = $client->post('article/sentbacklist', null);
    //var_dump(wp_remote_retrieve_body($result));

    register_post_status('re-do', array(
        'label' => _x('Sentback', 'post'),
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'public' => true,
        '_builtin' => true, /* internal use only. */
        'label_count' => _n_noop('Sentback <span class="count">(%s)</span>', 'Sentback <span class="count">(%s)</span>'),
    ));

    register_post_status('sponsored', array(
        'label' => _x('Sponsored', 'post'),
        'public' => true,
        '_builtin' => true, /* internal use only. */
        'label_count' => _n_noop('Sponsored <span class="count">(%s)</span>', 'Sponsored <span class="count">(%s)</span>'),
    ));

    register_post_status('archived', array(
        'label' => _x('Archived', 'post'),
        'public' => true,
        '_builtin' => true, /* internal use only. */
        'label_count' => _n_noop('Archived <span class="count">(%s)</span>', 'Archived <span class="count">(%s)</span>'),
    ));

    register_post_status('processing', array(
        'label' => _x('Processing', 'post'),
        'public' => true,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        '_builtin' => true, /* internal use only. */
        'label_count' => _n_noop('Processing <span class="count">(%s)</span>', 'Processing <span class="count">(%s)</span>'),
    ));

}


if ( !function_exists('newswire_maybe_insert_mainphoto')):
/**
 * Single Press Release content related hooks.
 *
 *
 * Head hook if press release page
 */
add_action('wp_head', 'newswire_maybe_insert_mainphoto', 2);
function newswire_maybe_insert_mainphoto() {

    global $post;

    wp_reset_postdata();

    //google badge
    echo '<script src="https://apis.google.com/js/platform.js" async defer></script>';

    if (is_object($post) && get_post_type($post->ID) == 'pr' && is_single()) {
        
        remove_action( 'wp_head', 'rel_canonical' );
        //hide company info
        if ($data = newswire_data()) {
            if ( empty($data['show_company_info']) ) {
                printf("<style>%s</style>", '#company_nap,#company_desc_wrapper{display:none}');
            }
        }
        //print canonical url meta tag
        newswire_canonical_url( $post, true);

        
        //insert photo
        /** insert photo to pr object content */
        if (!has_post_thumbnail($post->ID)) {
            add_filter('the_content', 'newswire_insert_photo_to_pr_content');
        } elseif (function_exists('newsninja_seo_breadcrumbs')) { //Theme related
            add_filter('the_content', 'newswire_insert_photo_to_pr_content');
        }

        function newswire_insert_photo_to_pr_content($content) {

            $html = '';
            /*
            img_caption: "Original Watermen",
            img_alt_tag: "Original Watermen",
            img_caption_link: "http://www.originalwatermen.com",
            img_alt_tag_link: "http://www.originalwatermen.com",
             */

            $meta = wp_parse_args(newswire_data() , array('img_alt_tag'=> '', 'img_caption' => '', 'img_caption_link' => ''));
            
        
            if ( !empty($meta['include_image'])  )
                $html = sprintf('<p><img src="%s" title="%s" alt="%s" border=0 width="auto"> <br> <a href="%s" class="aligncenter pressroom-photocaption" >%s</a> </p>',
                /*$meta['img_url']*/newswire_image_url( get_the_ID(),  $meta['img_url'] ), $meta['img_caption'], $meta['img_alt_tag'], $meta['img_caption_link'] ? $meta['img_caption_link'] : '#', $meta['img_caption']);
            
            return $html . $content;
        }

        //insert description to pr content
        add_filter('the_content', 'newswire_insert_description');
        function newswire_insert_description($content) {

            $meta = newswire_data();
            //var_dump($meta);

            $html = !empty($meta['description']) ? $meta['description'] : '';

            return $html . $content;
        }
    }
} //end function
endif;

/**
 * Add tinymce callback, make sure to call this from support post type exluding editing page
 *
 *  1. Take care of automatic paragraph and newline to break convertion
 *  2. add noneditable tinymce plugin
 *  3. remove quicktags from pin_as_quote and pin_as_social
 *
 * @todo: subject for optimization
 *
 */
add_action('admin_head', 'newswire_tinymce_mods_init');
function newswire_tinymce_mods_init() {

    global $post_type, $post;

    $newswire_options = newswire_options();

    $newswire_screen = get_current_screen();

    //if ( $newswire_screen->base == 'post' && in_array( $post_type, $newswire_options['supported_post_types'] ) ):
    if ($post_type == 'pin_as_embed' || $post_type == 'pin_as_social') {
        function newswire_remove_quicktags() {
            // return false shows php notice
            return array('buttons'=>  null);
        }
       add_filter('quicktags_settings', 'newswire_remove_quicktags');
    }
  
    if ( $newswire_screen->base == 'post' && in_array($post_type,  array('pr') ) ):
        
        if (get_post_meta($post->ID, 'rss_source_url', true)) {
            return;
        }

        //if ( is_disable_submission( $post->ID ) ) return;

        /**
         * enable noneditable plugin
         */
        add_filter('tiny_mce_before_init', 'newswire_tinymce_init_callback');
        function newswire_tinymce_init_callback($settings) {

            $settings['setup'] = "function(ed){
                         ed.onInit.add(window.newswire.tinymce_init);
                         ed.onLoadContent.add(window.newswire.tinymce_onloadcontent);
                    }";
           /* $settings['apply_source_formatting'] = false;
            $settings['wpautop'] = false;
            // Don't remove line breaks
            $settings['remove_linebreaks'] = false;
            // Convert newline characters to BR tags
            $settings['convert_newlines_to_brs'] = true;
            // Do not remove redundant BR tags
            $settings['remove_redundant_brs'] = false;

            $settings['remove_linebreaks'] = false;
            */
            $settings['plugins'] .= ',noneditable';

            return $settings;
        }
        

        /**
         * Add wyswyg plugin
         */
        add_filter('mce_external_plugins', 'newswire_tinymce_mode', 999);
        function newswire_tinymce_mode($plugins) {
            global $tinymce_version;

            if (version_compare($tinymce_version, '4.0.0', '>=')) {
                $plugpath = NEWSWIRE_PLUGIN_URL . '/assets/js/tinymce/plugins/noneditable/plugin.min.js';
            } else {
            $plugpath = NEWSWIRE_PLUGIN_URL . '/assets/js/tinymce/plugins/noneditable/editor_plugin.js';
        }

        //$plugpath = NEWSWIRE_PLUGIN_URL . '/assets/js/tinymce/plugins/noneditable/editor_plugin.js';
        $plugins['noneditable'] = $plugpath;

        return $plugins;

    }

    endif;
}



if ( !function_exists('newswire_add_fbroot')):
add_action('wp_footer', 'newswire_add_fbroot');
/**
* Add Fbroot markup
*/
function newswire_add_fbroot() {
    
    if ( !is_newswire_pressroom_page() ) return;

    ?><div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script><?php
}
function newswire_print_default_fb() {
    ?><div class="fb-page" data-href="https://www.facebook.com/newswires" data-width="300" data-height="600" data-hide-cover="false" data-show-facepile="true" data-show-posts="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/newswires"><a href="https://www.facebook.com/newswires">Newswire</a></blockquote></div></div>
    <?php
}
endif;