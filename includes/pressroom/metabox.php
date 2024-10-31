<?php
if ( !defined('ABSPATH') ) {
    exit;
}

function newswire_contact_image_ui() {
    global $post;

    // Get WordPress' media upload URL
    $upload_link = esc_url( get_upload_iframe_src( 'image', get_the_ID() ) );
    
    $meta = newswire_data(get_the_ID());
    // See if there's a media id already saved as post meta
    if ( isset( $meta['contact_media_id'] ) && $meta['contact_media_id'] ) :
        $your_img_id = intval($meta['contact_media_id']);
        // Get the image src
        $your_img_src = wp_get_attachment_image_src( $your_img_id, 'pin_as_contact_thumb' );
        //var_dump($your_img_src);
    else:
        $your_img_id = 0; 
    endif;
    if (!empty($meta['show_contact_media_id']) && $meta['show_contact_media_id'] ==  '2' ) {
        $toggle = '';
        $meta['contact_media_id'] = 0;
        unset($your_img_src);
         $your_img_id  =0;
    }else{
        $toggle ='checked';

        $meta['show_contact_media_id'] = 1;
    }
    

    $html = sprintf('<input type="checkbox" class="toggle-contact-image" %s  value="1"><br/>', $toggle);
    $html .= ' 
    <br/>
    <!-- Your image container, which can be manipulated with js -->
    <div class="custom-img-container ">
        ';
        if ( isset($your_img_src) ) :
            if ( intval($meta['show_contact_media_id'] == 1))
                $html .= sprintf('<img class="upload-custom-img " src="%s" alt="" style="max-width:100%%;cursor: pointer" title="click image to change or upload new one"/>', $your_img_src[0]);
            else
                $html .= sprintf('<img class="upload-custom-img hidden" src="%s" alt="" style="max-width:100%%;cursor: pointer" title="click image to change or upload new one" />', $your_img_src[0]);
        else:
            $html .= sprintf('<div class="hide-if-no-js drag-drop uploader %s">
        <div id="drag-drop-area" style="position: relative;">
            <div class="drag-drop-inside">
                <p><input  type="button" value="Select an Image File" class="button upload-custom-img" style="position: relative; z-index: 1;"></p>
            </div>
        </div>
    </div>', ($toggle == 'checked') ? "": 'hidden');


        endif;
    $html .='
    
    </div>

    
       ';

        $html .= '<!-- A hidden input to set and post the chosen image id -->';
        $html .= sprintf('<input class="custom-img-id" name="newswire_data[contact_media_id]" type="hidden" value="%s" />', intval(esc_attr( $your_img_id )));
        $html .= sprintf('<input class="show_contact_media_id" name="newswire_data[show_contact_media_id]" type="hidden" value="%s" />', $meta['show_contact_media_id'] );


       return $html;

}
//
// Remove metaboxs?
//add_action('admin_menu', 'newswire_pressrom_remove_meta_boxes', 10 );
function newswire_pressrom_remove_meta_boxes() {
    global $wp_meta_boxes;
    if (empty($screen)) {
        $screen = get_current_screen();
    } elseif (is_string($screen)) {
        $screen = convert_to_screen($screen);
    }

    $page = $screen->id;

    if (!isset($wp_meta_boxes)) {
        $wp_meta_boxes = array();
    }

    if (!isset($wp_meta_boxes[$page])) {
        $wp_meta_boxes[$page] = array();
    }

    if (!isset($wp_meta_boxes[$page][$context])) {
        $wp_meta_boxes[$page][$context] = array();
    }

    //foreach ( array('high', 'core', 'default', 'low') as $priority )
    unset($wp_meta_boxes); //[$page][$context][$priority]);//[$id] = false;
}

/*************************************************************************************
 *  MetaBox Generator
 *
 *************************************************************************************/



/**
 * Show admin notice for invalid post
 *
 */
add_action('admin_notices', 'newswire_show_last_notice');
function newswire_show_last_notice($errors = null, $extra = '') {

    if (null == $errors) {
        $errors = newswire_admin_get_error();
    }

    $html_errors = '';

    if (!empty($errors) && is_array($errors)) {

        foreach ($errors as $key => $message) {
            if (!is_array($message)) {
                //$html_errors.= sprintf('<p>%s<p>', $message) ;
                //printf('<div class="error"> %s</div>' ,   $message );
                newswire_show_last_notice($key . ': ' . $message);
            } else {
                newswire_show_last_notice($message, $extra);
            }
        }

    } elseif ($errors != '') {
        echo '<div class="error"><p>';
        echo $errors;
        echo '</p></div>';
        //printf('<div class="error"><p>%s</p></div>' ,  $errors );
    }
    add_action('admin_footer', 'newswire_flush_error_notice');
}

/**
* 
*/
add_filter('newswire_data_meta_contact_email', 'newswire_data_meta_contact_email_filter', 10, 4);
function newswire_data_meta_contact_email_filter($val = null, $field = null, $post_meta = null, $post = null) {
    if ($val != '') {
        return $val;
    } else {

        global $display_name, $user_email;

        get_currentuserinfo();

        return $user_email;
        //$options = newswire_options();
        //return $options['newswire_user']['email'];
    }
}

add_filter('newswire_data_meta_pen_name', 'newswire_data_meta_pen_name_filter');
function newswire_data_meta_pen_name_filter($val) {
    if (trim($val) != '') {
        return $val;
    } else {
        global $display_name, $user_email, $current_user;

        get_currentuserinfo();

        if ($current_user->display_name) {
            return $current_user->display_name;
        }

        if ($current_user->user_nicename) {
            return $current_user->user_nicename;
        }

        //    $options = newswire_options();
        //return $options['newswire_user']['displayname'];
    }
}
/**
 * Author info tab
 */
//add_filter('newswire_data_meta_profile_url', 'newswire_data_meta_profile_url_filter');
function newswire_data_meta_profile_url_filter($val) {
    if ($val != '') {
        return $val;
    } else {
        //var_dump($post);
        //get current post
        if (is_admin() && $post) {

            global $post;

            $post_meta = newswire_data($post->ID);

            if (!isset($post_meta['profile_url'])) {
                $options = newswire_options();
                return $options['newswire_user']['profile_url'];
            }

        } else {
            if (current_user_can('administrator')) {
                $options = newswire_options();
                return $options['newswire_user']['profile_url'];
            }

        }

    }

    return $val;
}
add_filter('newswire_data_show_contact_block_image', 'newswire_data_include_image_filter' );
add_filter('newswire_data_include_image', 'newswire_data_include_image_filter');
function newswire_data_include_image_filter($val) {
    var_dump($val);
    if ( $val ) {
        return $val;
    } else {
        return '0';
    }
}

# rendering element callbacks

/**
 *
 *  callback function to generate article status content from post meta box
 *
 * @param $value null, int article_id
 */
function newswire_article_status($value = '') {

    global $post;

    $options = get_option(NEWSWIRE_OPTIONS);

    $newswire_data = get_post_meta($post->ID, 'newswire_data');

    //check wp status and config

    return $post->post_status;

}

/**
 *
 * todo: @convert this dynamic pull information from newswire.net
 */
function newswire_meta_category_dropdown($value, $fields) {

    $cat_options = array(
        1 => 'Press Release',
        5 => 'Blog Post',
        6 => 'Interview',
        7 => 'Review',
        8 => 'OpEd',
        4 => 'News Story',
    );

    //if ( current_user_can('editor') || current_user_can('administrator' )) {
    $cat_options[3] = 'News';
    //}

    $html = '<select name="newswire_data[category_id]" id="category_id">';
    foreach ($cat_options as $id => $text) {
        $selected = '';
        if ($value == $id) {
            $selected = 'selected';
        }

        $html .= sprintf('<option value="%s" label="%s" %s>%s</option>', $id, $text, $selected, $text);
    }
    $html .= '</select>';

    /*
    if ( intval($value) == 2 )
    {
    $html ='<select name="newswire_data[category_id]" id="category_id">
    <option value="1" label="Press Release">Press Release</option>
    <option value="2" label="Finance News" selected>Finance News</option>
    </select>';
    } else {

    $html ='<select name="newswire_data[category_id]" id="category_id">
    <option value="1" label="Press Release" selected>Press Release</option>
    <option value="2" label="Finance News" >Finance News</option>
    </select>';

    }
     */
    return $html;

}

function newswire_toggle_link_name($value, $field, $post_meta) {
    $html = '';
    if ( isset($post_meta['link_name']) && !$post_meta['link_name'] ) {

        $checked = '';

    } else {

        $checked = 'checked="checked"';

    }

    if (current_user_can('PRReporter')) {

        //$post_meta['profile_url']
        if ($value == '') {

            $field['value'] = '';
        }

        $html .= '
        <div class="toggle">
            <input name="newswire_data[' . $field['toggle_id'] . ']" type="checkbox" id="' . $field['id'] . '_toggle" value="1" ' . $checked . '/>
            <div class="' . (($checked == '') ? 'hidden' : '') . '" >
                <input type="text" name="newswire_data[' . $field['id'] . ']" id="field-' . $field['id'] . '" value="' . $field['value'] . '" style="width:75%;" />
            </div>
        </div>';

    } else {

        $html .= '
        <div class="toggle">
            <input name="newswire_data[' . $field['toggle_id'] . ']" type="checkbox" id="' . $field['id'] . '_toggle" value="1" ' . $checked . '/>
            <div class="' . (($checked == '') ? 'hidden' : '') . '" >
                <input type="text" name="newswire_data[' . $field['id'] . ']" id="field-' . $field['id'] . '" value="' . $field['value'] . '" style="width:75%;" />
            </div>
        </div>';
    }

    return $html;

}

function newswire_toogle_company_id($value, $field, $post_meta) {
    if (current_user_can('PRReporter')) {
        return null;
    }

    $options = newswire_options();
    $company_profiles = '';
    if ($options['company_profiles']) {
        foreach ($options['company_profiles'] as $id => $profile) {
            if ($post_meta['company_id'] == $id) {
                $selected = 'selected';
            } else {
                $selected = '';
            }

            $company_profiles .= sprintf('<option value="%s" %s>%s</option>', $id, $selected, $profile);
        }
    }

    if (!empty($post_meta['company_profile'])) {
        $checked = 'checked="checked"';
    } else {
        $checked = '';
    }

    if ($company_profiles == '') {

        $html .= '
        <div class="toggle">
            <input name="newswire_data[' . $field['toggle_id'] . ']" type="checkbox" id="' . $field['id'] . '_toggle" value="1" ' . $checked . '/>
            <div class="' . (($checked == '') ? 'hidden' : '') . '" >
                <select name="newswire_data[company_id]" id="company_id">
                    <option value="0" label="-- no company --" selected="selected">-- no company --</option>' . $company_profiles . '
                </select>
            </div>
        </div>';

    } else {

        $html .= '
        <div class="toggle">
            <input name="newswire_data[' . $field['toggle_id'] . ']" type="checkbox" id="' . $field['id'] . '_toggle" value="1" ' . $checked . '/>
            <div class="' . (($checked == '') ? 'hidden' : '') . '" >
                <select name="newswire_data[company_id]" id="company_id">
                    ' . $company_profiles . '
                </select>
            </div>
        </div>';

    }
    return $html;

}

function newswire_toggle_include_company_body($value, $field, $post_meta) {
    //on by default
    if ( !empty($post_meta['show_company_info']) && $post_meta['show_company_info'] || !isset($post_meta['show_company_info'])) {
        //if( $post_meta['show_company_info'] ) {
        $checked = 'checked="checked"';
    } else {
        $checked = '';
    }

    $html = '
    <div class="toggle">
        <input name="newswire_data[show_company_info]" type="checkbox" id="' . $field['id'] . '" value="1" ' . $checked . '/>
    </div>';

    return $html;

}

function newswire_publish_date_element($value) {
    return get_the_date() . ' ' . get_the_time();
}
#-----------------------------------------------------------------------------------------------------


/*
* PR Metaboxes custom
*/ 

if ( !function_exists('newswire_modify_post_title_label')):
/**
 * Admin-  Modify post title label fro mdifferent block type (pressroom )
 */
add_action('enter_title_here', 'newswire_modify_post_title_label', 10, 2);
 function newswire_modify_post_title_label($title, $post) {
    switch ($post->post_type) {
        case 'pin_as_social':
            add_filter('the_editor', 'newswire_add_placeholder_text_pin_as_social');
            remove_all_actions('media_buttons');
            return 'Enter Social Media Name - <span>i.e. Facebook</span> ';
            break;
        case 'pin_as_quote':
            add_filter('the_editor', 'newswire_add_placeholder_text_pin_as_quote');
            remove_all_actions('media_buttons');
            return 'Enter title here - <span>i.e. Quote from CEO</span> ';

            break;
        case 'pin_as_link':
            return 'Enter title here - i.e. Links ';
            break;
        case 'pin_as_embed':
            add_filter('the_editor', 'newswire_add_placeholder_text_pin_as_embed');
            remove_all_actions('media_buttons');
            return $title;
            break;
        default:
            return $title;
            break;
    }
}
/**
*
*/
function newswire_add_placeholder_text_pin_as_social($editor) {
    return '<label id="placeholder-text-editor-pin-as-social" for="wp-content-editor-container">Enter social media embed code. Make sure to size to 300px wide.</label>' . $editor;
}
function newswire_add_placeholder_text_pin_as_quote($editor) {
    return '<label id="placeholder-text-editor-pin-as-quote" for="wp-content-editor-container">Enter quote here.</label>' . $editor;
}
function newswire_add_placeholder_text_pin_as_embed($editor) {
    return '<label id="placeholder-text-editor-pin-as-embed" for="wp-content-editor-container">Enter embed code here. Make sure to size to 300px wide.</label>' . $editor;
}

endif;


if ( !function_exists('newswire_remove_richedit_from_editor')):
/**
* remove wyswyg from editor
*/ 
add_filter('user_can_richedit', 'newswire_remove_richedit_from_editor');
function newswire_remove_richedit_from_editor($c) {

    global $post_type;

    if ( in_array($post_type, array('pin_as_embed',  'pin_as_social'))) {
        
        return false;
    }

    return $c;
}
endif;


/**
* Helper functions to create metaboxes
*/

if ( !function_exists('newswire_post_meta_box_elements')):
    
/**
* Generate metabox elements by array
*/
function newswire_post_meta_box_elements($panel) {

    global $newswire_config;

    $tabs = array(
        'article_status' => null,
        'category' => '',
        /*
        'article_status' => array(
        array ( "name" => __('Article Status','newswire' ),
        "desc"  => __('Indicates pending, published, not submitted', 'newswire'),
        "id"    => "article_status",
        'type'  => 'text',
        'width' => '20%',
        'type'  => 'callback',
        'callback' =>  'newswire_article_status'
        )
        ),
         */
        

        'bonus_category' => array(
            array("name" => __('Category', 'newswire'),
                "desc" => __('Choose the category the best fits your article content.  This category selection will help Google and other websites display your article to readers that are interested in your content', 'newswire'),
                "id" => "category_id2",
                "type" => "callback",
                'callback' => 'newswire_select_categories',
            ),
        ),

        'author_info' => array(
            /* convert to callback */
            /* array ( "name" => __('User ID','newswire'),
            "desc" => '',
            "id" => "user_id",
            'attrib' => array('readonly'=> "true" ),
            "type" => "text",
            ),*/

            
            array(
                'name' => __('Pen Name', 'newswire'),
                'desc' => __($newswire_config['tooltip']['pen_name'], 'newswire'),
                'id' => 'pen_name',
                'type' => 'text',

            ),

            /* convert this to callback */
            array(
                'name' => __('Link Name?', 'newswire'),
                'desc' => __( 'Insert URL, beginning with http.  By checking this box your pen name will be linked to the website you enter.  Insert your Google+ profile link here to earn Google Authorship credit.', 'newswire'),
                'id' => 'profile_url',
                'toggle_id' => 'link_name',
                'type' => 'callback',
                'callback' => 'newswire_toggle_link_name',
            ),
        ),

        'company_info' => array(

            //Include Company Info in Article Body?
            array(

                'name' => __('Include Company Info in Article Body?', 'newswire'),
                'desc' => __($newswire_config['tooltip']['company_info_include'], 'newswire'),
                'value' => 1,
                'id' => 'show_company_info',
                'type' => 'callback',
                'callback' => 'newswire_toggle_include_company_body',

            ),

            //Use Info Stored in Company Profile?
            newswire_stored_company_profile(),

            //Schema
            array(

                'name' => __('Schema', 'newswire'),
                'desc' => __($newswire_config['tooltip']['company_info_schema'], 'newswire'),
                'id' => 'schema_id',
                'type' => 'callback',
                'callback' => 'newswire_select_schema',
            ),

            //Contact Funnel
            /*
            array (

            'name' => __('Contact Funnel','newswire'),
            'desc' => __( $newswire_config['tooltip']['company_info_contact_funnel'] ,'newswire'),
            'id' => 'author_info_contact_email_field',
            'type' => 'html',
            'html' => '<select name="page_id" id="page_id">
            <option value="2" label="Newswire Network Ltd">Newswire Network Ltd</option>
            </select>'

            ),*/

            array(

                'name' => __('Company Name', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_name',
                'type' => 'text',
                'toggle_class' => 'toggle_company_info',

            ),

            array(

                'name' => __('Ticker Symbols', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_tickers',
                'type' => 'text',
                'toggle_class' => 'toggle_company_info',

            ),

            array(

                'name' => __('Address', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_address',
                'type' => 'text',
                'toggle_class' => 'toggle_company_info',
            ),

            array(

                'name' => __('City', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_city',
                'type' => 'text',
                'toggle_class' => 'toggle_company_info',
            ),

            array(

                'name' => __('Country', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_country',
                'type' => 'callback',
                'callback' => 'newswire_select_countries',
                'toggle_class' => 'toggle_company_info',

            ),

            array(

                'name' => __('State or Province', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_state',
                'type' => 'text',
                'toggle_class' => 'toggle_company_info',

            ),

            array(

                'name' => __('Postal Code', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_zip',
                'type' => 'text',
                'toggle_class' => 'toggle_company_info',
            ),

            array(

                'name' => __('Telephone', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_telephone',
                'type' => 'text',
                'toggle_class' => 'toggle_company_info',
            ),

            array(
                'name' => __('Email', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_email',
                'type' => 'text',
                'toggle_class' => 'toggle_company_info',
            ),
            array(
                'name' => __('Website', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_website',
                'type' => 'text',
                'toggle_class' => 'toggle_company_info',
            ),

            array(

                'name' => __('Company Description', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_desc',
                'prefix_id' =>'nwexpress_',
                'type' => 'textarea',   
                'toggle_class' => 'toggle_company_info',

            ),


        ),

        'pressroom_contact_pin' => array(
            array(

                'name' => __('Add Image to Contact Block', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'contact_image',
                'type' => 'callback',
                'callback' => 'newswire_contact_image_ui',

            ),

            array(

                'name' => __('First Name', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'first_name',
                'type' => 'text',

            ),

            array(

                'name' => __('Last Name', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'last_name',
                'type' => 'text',

            ),

            array(

                'name' => __('Position', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'contact_position',
                'type' => 'text',
            ),

            array(

                'name' => __('Company Name', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_name',
                'type' => 'text',

            ),

            array(

                'name' => __('Ticker Symbols', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_tickers',
                'type' => 'text',

            ),

            array(

                'name' => __('Address', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_address',
                'type' => 'text',

            ),

            array(

                'name' => __('City', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_city',
                'type' => 'text',

            ),

            array(

                'name' => __('Country', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_country',
                'type' => 'callback',
                'callback' => 'newswire_select_countries',

            ),

            array(

                'name' => __('State or Province', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_state',
                'type' => 'text',

            ),

            array(

                'name' => __('Postal Code', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_zip',
                'type' => 'text',

            ),

            array(

                'name' => __('Telephone', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_telephone',
                'type' => 'text',

            ),

            array(
                'name' => __('Email', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_email',
                'type' => 'text',

            ),
            array(
                'name' => __('Website', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'company_website',
                'type' => 'text',

            ),
            array(
                'name' => __('Biography', 'newswire'),
                'desc' => __('', 'newswire'),
                'prefix_id' =>'nwexpress_',
                'id' => 'biography',
                'type' => 'textarea',

            ),

        ),

        

        'add_image' => array(

            array("name" => __('Image Caption', 'newswire'),
                "desc" => '',
                "id" => "img_caption",
                'width' => '100%',
                "type" => "text",
            ),

            array(
                'name' => __('Image Alt Tag', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'img_alt_tag',
                'width' => '100%',
                'type' => 'text',

            ),

            array(
                'name' => __('Hyperlink Image', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => 'img_caption_link',
                'width' => '100%',
                'type' => 'text',

            ),

            array(
                'name' => __('Caption Link?', 'newswire'),
                'desc' => __($newswire_config['tooltip']['img_alt_tag_link'], 'newswire'),
                'id' => 'img_alt_tag_link',
                'width' => '100%',
                'type' => 'text',

            ),

            array(
                'name' => __('Include Image in Article Body', 'newswire'),
                'desc' => __($newswire_config['tooltip']['include_image'], 'newswire'),
                'id' => 'include_image',                
                'type' => 'checkbox',
                'default' => '1'
            ),
             array(
                'name' => __('Set as featured image', 'newswire'),
                'desc' => __('', 'newswire'),
                'id' => '_set_as_featured',                
                'type' => 'checkbox',
                'default' => '0'
            )



        )

        
    );
    return $tabs[$panel];
};
endif;

if ( !function_exists('newswire_generate_meta_box_fields')):
/**
 * Generate post meta box field elements
 * Global function
 */
function newswire_generate_meta_box_fields($fields, $post_meta) {

    $html = $output = '';

    //get newswire options
    $options = get_option(NEWSWIRE_OPTIONS);

    // get post custom fields specific to newswire only
    $custom_values = maybe_unserialize($post_meta);

    $output .= '<table class="form-table wp-list-table"><col width="25%"/><col/>';

    if (is_array($fields)) {
        foreach ($fields as $field) {

            $field = wp_parse_args( $field, $defaults = array('placeholder'=> '', 'category'=> '', 'type'=> '', 'toggle_class' => '', 'id' => '', 'value'=> '', 'width' => '90%', 'attrib' => array(), 'html' => '' ) );
            $checked = '';
            //set value
            //if ( is_callable(name))

            //set up value
            if ( has_filter('newswire_data_meta_' . $field['id']) ) {

                $field['value'] = apply_filters('newswire_data_meta_' . $field['id'], isset($custom_values[$field['id']]) ? $custom_values[$field['id']] : '');

            } elseif (isset($custom_values[$field['id']])) {

                $field['value'] = $custom_values[$field['id']];
            }

            switch ($field['type']) {
                case 'uploadimage':

                break;
                case 'callback':

                    if (is_callable($field['callback'])) {
                        $html = call_user_func($field['callback'], isset($custom_values[$field['id']]) ? $custom_values[$field['id']] : '', $field, $custom_values);
                    }

                    $output .= '
                     <tr class="'    . $field['toggle_class'] . '">
                        <th style="width:25%">
                            <label for="'    . $field['id'] . '">
                                <strong>'    . $field['name'] . '</strong>';
                                if ( $field['desc']!='')
                                    $output .= '<div class="howto"><img src="'   . NEWSWIRE_PLUGIN_URL . 'assets/images/help.png" title="' . $field['desc'] . '" /></div>';
                            
                            $output .= '</label>
                        </th>
                        <td>'    . $html . '</td>
                    </tr>'  ;
                    break;

                case 'info':
                    $output .= '
                    <tr>
                        <td colspan="2">
                            <div class="howto">'     . $field['desc'] . '</div>
                        </td>
                    </tr>
                '   ;
                    break;

                case 'datetime':
                    //
                    break;

                case 'textarea':
                    $output .= '
                     <tr class="'    . $field['toggle_class'] . '">
                        <th style="width:25%">
                            <label for="'    . $field['id'] . '">
                                <strong>'    . $field['name'] . '</strong>
                                <div class="howto">'     . $field['desc'] . '</div>
                            </label>
                        </th>
                        <td>
                            <textarea placeholder="'. $field['placeholder'] . '" name="newswire_data[' . $field['id'] . ']" id="' . $field['prefix_id']. $field['id'] . '" rows="' . (@$field['rows'] ? $field['rows'] : 8) . '" style="width:100%;">' . $field['value'] . '</textarea>
                        </td>
                    </tr>
                '   ;
                    break;

                case 'seo_tools_article_url':

                    $output .= '
                    <tr>
                        <th style="width:25%">
                            <label for="'    . $field['id'] . '"><strong>' . $field['name'] . '</strong>';
                    if ($field['desc'] != '') {
                        $output .= '<div class="howto"><img src="' . NEWSWIRE_PLUGIN_URL . 'assets/images/help.png" title="' . $field['desc'] . '" /></div>';
                    }

                    $output .= '</th>
                        <td>
                            <code>Choose URL http://newswire.net/newsrooom/[id]-<input type="text" name="newswire_data['     . $field['id'] . ']" id="' . $field['id'] . '" value="' . $field['value'] . '" style="width:30%;" />.html</code>
                        </td>
                    </tr>
                '   ;

                    break;

                case 'text':
                    $output .= '
                    <tr class="'     . $field['toggle_class'] . '">
                        <th style="width:25%">
                            <label for="'    . $field['id'] . '"><strong>' . $field['name'] . '</strong>';

                    if ($field['desc'] != '') {
                        $output .= '<div class="howto"><img src="' . NEWSWIRE_PLUGIN_URL . 'assets/images/help.png" title="' . $field['desc'] . '" /></div>';
                    }

                    $output .= '</th>
                        <td>
                            <input type="text" name="newswire_data['     . $field['id'] . ']" id="' . $field['id'] . '" value="' . $field['value'] . '" style="width:' . ($field['width'] ? $field['width'] : '75%') . ';"  ' . newswire_array_as_html_attributes($field['attrib']) . ' />
                        </td>
                    </tr>
                '   ;
                    break;

                case 'html':

                    $output .= '
                    <tr>
                        <th style="width:25%">
                            <label for="'    . $field['id'] . '"><strong>' . $field['name'] . '</strong>';
                    if ($field['desc'] != '') {
                        $output .= '<div class="howto"><img src="' . NEWSWIRE_PLUGIN_URL . 'assets/images/help.png" title="' . $field['desc'] . '" /></div>';
                    }

                    $output .= '</th>
                        <td>'    . $field['html'] . ' </td>
                    </tr>
                '   ;
                    break;

                case 'checkbox':

                    if ( $field['value'] || !isset($field['value']) ) {
                        $checked = 'checked="checked" ';
                    } else {
                        if ( !empty($field['default']) && $field['default'] ) {
                           $checked = 'checked="checked" ';
                        }
                    }

                    $output .= '
                    <tr>
                        <th style="width:25%">
                            <label for="'    . $field['id'] . '"><strong>' . $field['name'] . '</strong>';
                    if ($field['desc'] != '') {
                        $output .= '<div class="howto"><img src="' . NEWSWIRE_PLUGIN_URL . 'assets/images/help.png" title="' . $field['desc'] . '" /></div>';
                    }

                    $output .= '</th>
                        <td>
                            <input type="checkbox" name="newswire_data['     . $field['id'] . ']" id="' . $field['id'] . '" value="'.( !empty($field['value']) ? $field['value']: '1').'"  ' . $checked . ' />
                        </td>
                    </tr>
                '   ;

                    break;

                case 'toggle_text':

                    $output .= '
                    <tr>
                        <th style="width:25%">
                            <label for="'    . $field['id'] . '"><strong>' . $field['name'] . '</strong>';
                    if ($field['desc'] != '') {
                        $output .= '<div class="howto"><img src="' . NEWSWIRE_PLUGIN_URL . 'assets/images/help.png" title="' . $field['desc'] . '" /></div>';
                    }

                    $output .= '</th>
                        <td>
                            <div class="toggle">
                                <input name="newswire_data['     . $field['toggle_id'] . ']" type="checkbox" id="' . $field['id'] . '_toggle" value="1" ' . $checked . '/>
                                <div class="hidden">
                                    <input type="text" name="newswire_data['     . $field['id'] . ']" id="field-' . $field['id'] . '" value="' . $field['value'] . '" style="width:75%;" />
                                </div>
                            </div>
                        </td>
                    </tr>
                '   ;

                    break;

                case 'text_browse':

                    $output .= '
                    <tr>
                        <th style="width:25%">
                            <label for="'    . $field['id'] . '"><strong>' . $field['name'] . '</strong>
                            <div class="howto">'     . $field['desc'] . '</div>
                        </th>
                        <td>
                            <input type="text" name="'   . $field['id'] . '" id="' . $field['id'] . '" value="' . ($meta ? $meta : stripslashes(htmlspecialchars($field['std']))) . '" style="width:75%;" />
                            <a href="#" class="button input-browse-button" rel="'    . $field['id'] . '"' . (@$field['library'] ? ' data-library="' . $field['library'] . '"' : '') . ' data-choose="' . __('Choose a file', 'om_theme') . '" data-select="' . __('Select', 'om_theme') . '">' . __('Browse', 'om_theme') . '</a>
                        </td>
                    </tr>
                '   ;
                    break;

                case 'select':
                    $output .= '
                    <tr>
                        <th style="width:25%">
                            <label for="'    . $field['id'] . '"><strong>' . $field['name'] . '</strong>
                            <div class="howto">'     . $field['desc'] . '</div>
                        </th>
                        <td>
                            <select id="'    . $field['id'] . '" name="newswire_data[' . $field['id'] . ']">
                '   ;

                    $selected = $field['value'];

                    foreach ($field['options'] as $k => $option) {
                        $output .= '<option' . ($selected == $k ? ' selected="selected"' : '') . ' value="' . $k . '">' . $option . '</option>';
                    }
                    $output .= '
                            </select>
                        </td>
                    </tr>
                '   ;
                    break;

            }

        }
    }

    $output .= '</table>';

    return $output;
}
endif;

