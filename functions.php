<?php
// retrieves the attachment ID from the file URL
function newswire_get_image_id($image_url) {
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
        return $attachment[0]; 
}

function newswire_data_update($options, $id = null) {
    
    global $post;
    
    $post_id = $id ? $id : $post->ID;

    //NEWSWIRE_POST_META_CUSTOM_FIELDS
    if ( $post_id )
        update_post_meta($post_id, NEWSWIRE_POST_META_CUSTOM_FIELDS, $options);
}
/**
* plugin title
*/
function newswire_plugin_title() {
   return  apply_filters('newswire_plugin_title', __('PressRoom by Newswire', 'newswire'));
}


/**
 *
 */
function newswire_post_meta($id = null) {

    global $post;

    $post_meta = get_post_meta($id ? $id : $post->ID, NEWSWIRE_POST_META_CUSTOM_FIELDS, $single = true);

    return $post_meta;
}



/**
 *  set/get global newswire var
 *
 * @param $key  string
 * @param $value mix
 *
 *
 * @return $newswire_config mix
 */
function newswire_var($key = '', $value = null) {

    global $newswire_config;

    if (empty($key)) {
        return null;
    }

    if (isset($value)) {
        //set and return value;
        return $newswire_config[$key] = $value;

    } else {
        //return value
        if (isset($newswire_config[$key])) {
            return $newswire_config[$key];
        } else {
            return @$newswire_config[$key];
        }

    }
}

/*
 * fetch newswire article
 */
function newswire_remote_article($query_data = null) {

    $defaults = array('format' => 'json');

    $query_data = wp_parse_args($query_data, $defaults);

    $qs = http_build_query($query_data);

    $url = newswire_api_url() . '/article/browse?' . $qs;

    //var_dump($url);

    $response = wp_remote_get($url, newswire_http_default_args());

    if (!is_wp_error($response)) {

        $html = wp_remote_retrieve_body($response);

        return $html;

    } else {

        //log or return errors
        var_dump($response);
    }
}

/**
 * Search and replace article field
 *
 */
function newswire_article_template($article, $itemtemplate) {
    //str replace article
    foreach ($article as $key => $value) {
        $token = "{" . $key . "}";
        if (strpos($itemtemplate, $token) !== false) {
            $itemtemplate = str_replace($token, $value, $itemtemplate);
        }

    }
    return $itemtemplate;
}

/**
 * fetch newswire categories and saved it locally from wp_option table
 *
 * @param $update_remote - integer update categories from remote -
 *
 * @return $categories array
 */
function newswire_fetch_categories($update_remote = 0) {

    $categories = get_option('newswire_categories');

    if ($update_remote || empty($categories)) {

        $response = wp_remote_get($url = newswire_api_url() . '/article/categorylist', newswire_http_default_args());

        $categories = json_decode(wp_remote_retrieve_body($response), true);

        $options = newswire_options();

        //$url = get_permalink($options['newsroom_page_template']);
        $url = '';
        
        $flat = array();
        foreach ($categories as $category_id => $top) {
            //main cat link
            //$categories[$category_id]['permalink'] = $url . '?category=' . sanitize_title($top['name']);
            $flat[$category_id] = $top['name'];
            if (isset($top['sub']) && is_array($top['sub'])) {

                foreach ($top['sub'] as $sub_id => $sub) {
                    //subcat link
                    //$categories[$category_id]['sub'][$sub_id]['permalink'] = $url . '?category=' . sanitize_title($top['name']) . '&subcat=' . sanitize_title($sub['name']);
                    $flat[$sub_id] = $sub['name'];
                    if (isset($sub['subsub']) && is_array($sub['subsub'])) {
                        foreach ($sub['subsub'] as $subsub_id => $subsub) {
                            $flat[$subsub_id] = $subsub['name'];
                            //$menuli .= sprintf('<li><a href="%s">%s</a></li>', $link.'/?category='.$top['name'].'&subcat='.$sub['name'],  $subsub['name']);
                            $categories[$category_id]['sub'][$sub_id]['subsub'][$subsub_id]['permalink'] = $url . '?category=' . urlencode($top['name']) . '&subcat=' . sanitize_title($sub['name']);
                        }
                    } // end subsub if
                } //end foreach sub
            } //end if sub
        } //endforeach

        //save categories
        update_option('newswire_categories', $categories);
        update_option('newswire_categories_flat', $flat);
        return $categories;

    } else {

        return $categories;
    }
}





/**
 * newsroom blog pagination
 */
function newswire_newsroom_pagination($total) {

    global $wp_query;

    wp_reset_query();

    $big = 999999999; // need an unlikely integer

    return paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $total,
    ));
}
if (!function_exists('newsroom_pagination')):
    function newsroom_pagination($total) {
        return newswire_newsroom_pagination($total);
    }
endif;

/**
 * Create newsroom instance base on the built-in template selected from admin
 *
 * @param $options array newswire options
 *
 * @return void;
 */
function newswire_newsroom_daemon($options) {
    extract($options);
    if (!empty($newsroom_theme)) {
        $classfile = realpath(NEWSWIRE_DIR . '/classes/newsroom-' . $newsroom_theme . '.php');

        if (!file_exists($classfile)) {
            wp_die($message = 'There is a missing file. Please contact support!', $title = 'Newswire Plugin Error', $args);
        } else {
            require_once $classfile;
            $class = 'Newswire_Newsroom_' . ucfirst($newsroom_theme);
            return new $class($options);
        }
    }
}

/**
 * UPdate post status by id
 *
 * @param $post_id integer
 * @param $status string wordpress valid post status , pending, publish etc
 *
 * @return void
 */
function newswire_update_post_status($post_id, $status) {

    global $wpdb;

    if (empty($post_id) || empty($status)) {
        return;
    }

    $wpdb->update($wpdb->posts, array('post_status' => $status), array('ID' => $post_id));
}

/**
 * Get newswire admin error for admin settings
 * THis makes it possible to persist error after wordpress refresh/redirect after saving settings on admin
 *
 * @param $key string
 *
 * @return $error array
 */
function newswire_admin_get_error($key = '') {

    $error = get_option(NEWSWIRE_ADMIN_ERROR);

    if (isset($error[$key])) {
        return $error[$key];
    } else {
        return $error;
    }
}




////////////////////////
// Admin notice helper
////////////////////////
/**
 * Persist generated error to wordpress option table
 * @todo - probbly try to use transient api
 *
 * @param $message string
 *
 * @return void
 */
function newswire_admin_write_error($message) {

    update_option(NEWSWIRE_ADMIN_ERROR, $message);
}





///////////////////
// API Helper 
///////////////////

/**
 * check if newswire is on dev mode
 */
function newswire_dev() {

    return !NEWSWIRE_DEVELOPMENT_MODE ? false : true;
}



/**
 * Newswire api url
 *
 * @param $path string
 *
 * @return $url string http url
 */
function newswire_url($path = '') {

    //prod
    if (defined(NEWSWIRE_DEVELOPMENT_MODE) && NEWSWIRE_DEVELOPMENT_MODE === false) {
        $url = NEWSWIRE_API_URL;

    } else {
        //dev
        $url = NEWSWIRE_API_DEVURL;
    }
    //what
    return $url . $path;
}



/**
 * From newswire settings page, print out checkboxes to indicate
 * what type of wp post are supported for newswire article submission
 *
 * @return void
 */
function newswire_option_supported_types() {
    //get save supported types
    //$options = newswire_option('supported_post_types');

    $seperator = '<br>';

    //get existing post types
    $post_types = get_post_types($args = array('_builtin' => true, 'public' => true), 'objects');

    foreach ($post_types as $post_type) {

        if (in_array($post_type, $options)) {
            $checked = 'checked="checked"';
        }

        $slug = $post_type->name;

        $label = $post_type->labels->name;

        $html .= sprintf('<label for="%s[]"><input name="%s[]" type="checkbox" id="%s" value="%s" %s> %s</label> %s', $item, $item, $item, $slug, $checked, $label, $seperator);

        //reset $checked
        $checked = '';
    }
    echo $html;
}


/**
 * Wrapper for get_post_meta, update_post_meta
 *
 * @param $post_id
 * @param $key
 *
 * @return $post_meta mix
 */
function newswire_get_post_meta($post_id = 0, $key = '') {
    if (is_object($post_id)) {

        $post_id = $post->ID;
    }

    $post_meta = get_post_meta($post_id, NEWSWIRE_POST_META_CUSTOM_FIELDS, $single = true);

    if (isset($post_meta[$key]) && $key != '') {
        return $post_meta[$key];
    } else {
        return $post_meta;
    }

}


/**
 * send https request to newswire
 *
 * @param $args
 *
 * @return $data mix
 */
function newswire_remote_post_defaults($args = array()) {

    $defaults = array('api_key' => '', 'user_id' => '', 'email' => '');

    $data = wp_parse_args($args, $defaults);

    return $data;
}



if ( !function_exists('newswire_http_default_args')):
/**
*
*/
function newswire_http_default_args($args = null) {

    $defaults = array('timeout' => 40);

    return wp_parse_args($args, $defaults);
}
endif;


/**
* Wrapper for getting newswire api url
*/
function newswire_api_url() {

   return NEWSWIRE_API_URL;
}


if ( !function_exists('newswire_pressroom_postypes')):
/**
*  Array of supported custom post type
*/
function newswire_pressroom_postypes() {
    return array(
        'pr' => 'Press Release',
        'pin_as_text' => 'Text',
        'pin_as_embed' => 'Embed',
        'pin_as_image' => 'Image Album',
        'pin_as_quote' => 'Quote',
        'pin_as_social' => 'Social Media',
        'pin_as_link' => 'Link',
        'pin_as_contact' => 'Contact',
        'pressroom' => 'Pressroom',
        'pin_as_latestpr' => 'Latest PR'
    );
}
endif;

/**
* Template Tags
*/

if ( !function_exists('is_newswire_pressroom_page')) :
function is_newswire_pressroom_page() {
    return is_newswire_pressroom();
}
/*
 *
 */
function is_newswire_pressroom() {
    
    wp_reset_query();
    wp_reset_postdata();
    
    global $post;
    //wp_reset_query();


    if (!$post) {

        $page = get_queried_object();

    } else {
        $page = $post;
    }

    $options = newswire_options();

    //check if page is tag as pressroom by looking at some value from newswire option
    $page_id = $options['pressroom_page_template']; //pageid

    if (is_object($page) && intval($page_id) === intval($page->ID)) {

        return true;
    }

    return false;
}
endif;


/*****************************************
* Html/variable helpers
******************************************/
if ( !function_exists('hex2rgb')):
/**
 *
 */
//if ( !function_exists('hexdec') ){
function hex2rgb($hex) {
    $hex = str_replace("#", "", $hex);

    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    $rgb = array($r, $g, $b);
    //return implode(",", $rgb); // returns the rgb values separated by commas
    return $rgb; // returns an array with the rgb values
}
endif;


/**
 * Print array values as html attributes
 *
 * @param $array array
 *
 * @return $string html string
 */
function newswire_array_as_html_attributes($array) {
    if (empty($array)) {
        return '';
    }

    $string = '';
    foreach ($array as $k => $val) {
        $string .= " $k=\"$val\" ";
    }
    return $string;
}


if ( !function_exists('newswire_canonical_url')):
/**
* Print canonical meta tags
*/
function newswire_canonical_url( $pr, $echo = false ) {

    global $post;

    if ( $pr ) {

        $url = get_post_meta( $pr->ID, 'rss_source_url',true);
  
    } else {

        $url = get_post_meta( $post->ID, 'rss_source_url',true);
  
    }

    if ( $url && $echo )
        printf("\n<link rel=\"canonical\" href=\"%s\" />\n", $url );

    return $url;
}
endif;


if ( !function_exists('newswire_image_url')):
/**
* Return pr content image - image_url or post thumbnail - pr
*/
function newswire_image_url($postID, $default = '') {
    $args = array(
        'numberposts' => 1,
        'order' => 'ASC',
        'post_mime_type' => 'image',
        'post_parent' => $postID,
        'post_status' => null,
        'post_type' => 'attachment',
    );

    $attachments = get_children($args);
    $images = array();

    if ($attachments) {
        foreach ($attachments as $attachment) {
            //$image_attributes = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' )  ? wp_get_attachment_image_src( $attachment->ID, 'thumbnail' ) : wp_get_attachment_image_src( $attachment->ID, 'full' );

            //echo '<img src="' . wp_get_attachment_thumb_url( $attachment->ID ) . '" class="current">';
            $images = wp_get_attachment_image_src($attachment->ID, 'full');
            break;
        }
    }

    if (!empty($images[0]) ) {
        return $images[0];

    } else {
        return $default;
    }

}
endif;




if ( !function_exists('newswire_pressroom_blocks') ):
/**
*  List of custom post types supported by this plugin
*/
function newswire_pressroom_blocks() {

    $blocks = array(  'pin_as_contact', 'pin_as_embed', 'pin_as_image', 'pin_as_link', 'pin_as_quote', 'pin_as_social', 'pin_as_text', 'pr' , 'pin_as_latestpr');

    return apply_filters('newswire_pressroom_blocks', $blocks );
} 
endif;


if ( !function_exists('newswirexpres_pro')):
/**
*  Pro plugin version
*/
function newswirexpres_pro() {
    
    return 'newswirexpress/newswirexpress.php';
}
endif;


/**
 * Wrapper function to get newswire_options from wp_opion table
 *
 * @return array;
 */
function newswire_options( $key = null ) {

    /*$defaults = newswire_config('' , 'settings');
    if ( is_array($defaults) && sizeof( $defaults ) )
    return get_option( NEWSWIRE_OPTIONS,   $defaults );
    else */
    if ( $key != '' ) {
        
        $options = get_option( NEWSWIRE_OPTIONS );
        
        return $options[$key];

    } else {

        return get_option( NEWSWIRE_OPTIONS );
    }
}

if ( !function_exists('newswire_options_update') ):
/**
* Update newswire options
* @param $options array
*/
function newswire_options_update( $options ) {

    $options = wp_parse_args( $options, newswire_options() );
    
    update_option(NEWSWIRE_OPTIONS, $options);


}
endif;
/**
* Deprecated 
*/
function update_newswire_options($options) {

    $options = wp_parse_args($options, newswire_options());
    update_option(NEWSWIRE_OPTIONS, $options);
}


if ( !function_exists('newswire_options_delete') ):
/**
* Delete newswire options 
*
* @param $key string
*/
function newswire_options_delete( $key ) {

    if ( isset($key)  && !empty($key) ) {

        $options = newswire_options();

        unset($options[$key]);

        newswire_options_update( $options );
        
        return $options;
    }
}
endif;


/**
 * Get newswire configuration
 *
 * @param $key string
 * @param $group string
 *
 * @return $neswire_config array;
 */
function newswire_config($key, $group = 'settings') {

    global $newswire_config;

    if (isset($newswire_config[$group]) && $key == '') {
        return $newswire_config[$group];
    }

    if (isset($newswire_config[$group][$key])) {
        return $newswire_config[$group][$key];
    } else {
        if (newswire_dev()) {
            throw new Exception('Invalid configuration key ');
        } else {
            //try more debugging/logging option here later
        }
    }
}


if ( !function_exists('newswire_data')) :
/**
* wrapper to get postmeta specific to this plugin
*/
function newswire_data($id = null, $key ='') {
    global $post;
    if ($id) {
        $meta = get_post_meta($id, NEWSWIRE_POST_META_CUSTOM_FIELDS, true);
        if ($key !== '') {
            return isset($meta[$key]) ? $meta[$key] : '';
        } else {
            return $meta;
        }
    }
    
    if ($post->ID) {
        $meta = get_post_meta($post->ID, NEWSWIRE_POST_META_CUSTOM_FIELDS, true);
    }

    if ($key !== '') {
        return isset($meta[$key]) ? $meta[$key] : '';
    } else {
        return $meta;
    }
}
endif;

if ( !function_exists('newswire_plugin_slug') ):
/**
 * Wrapper 
 */
function newswire_plugin_slug() {
    return 'newswire';
}
endif;



/**
 * Debug functions
 */
//add_action('admin_footer' , 'newswire_debug' );
function newswire_debug() {
    echo '<pre>';
    var_dump(newswire_options());
    echo '</pre>';
}



/** lightbox version */
function istmooti_pr_form_callback_lightbox() {}




