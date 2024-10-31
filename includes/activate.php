<?php
/**
 * When plugin is deactivated
 */
if (!function_exists('newswire_pressroom_activate')):
/**
* Function call when this plugins is activated
*
*/
function newswire_pressroom_activate() {
    /*
    * Pro version slug 
    */
    $plugin_basename = 'newswirexpress/newswirexpress.php';

    /** didnt work out 
    if ( is_plugin_active( $plugin_basename ) ) {
            deactivate_plugins( plugin_basename(__FILE__) );
            $admin_url = ( function_exists( 'get_admin_url' ) ) ? get_admin_url( null, 'plugins.php' ) : esc_url( '/wp-admin/plugins.php' );
            wp_die( 
                sprintf(
                    "<strong>%s</strong> <br /><br />%s <a href='%s'>%s</a>.",
                    __('Cannot activate free version if pro version is active', 'newswire'),
                
                    __( 'Back to the WordPress', 'newswire' ),
                    $admin_url,
                    __( 'Plugins page', 'newswire' )
                )
            );
        }
    */

    /*
    * Get default config from file
    */
    $opt = newswire_config('', 'settings');

    /*
    * Get default options from db if any
    */
    $options = newswire_options();

    /*
    * create pressroom page
    */
    if (!isset($options['pressroom_page_template'])) {
        $page_id = wp_insert_post(array(
            'post_title' => 'pressroom',
            'post_type' => 'page',
            'post_status' => 'publish',
            'ping_status' => 'closed',
            'comment_status' => 'closed',
            'post_content' => '[pressroom_blocks]',
        ));
        $opt['pressroom_page_template'] = $page_id;
        update_post_meta($page_id, 'admin_notices', '<p>Do not remove the shortcode [pressroom_blocks] from this page.  You can add, modify, delete and rearrange the content blocks that display on the PressRoom page by visiting PressRoom in the Dashboard <br/> ' . sprintf('<a href="%s">%s</a>', admin_url('edit.php?post_type=pressroom'), admin_url('edit.php?post_type=pressroom')) . ' <br/><a href="#" class="hide-notice" type="pressroom">Hide this notice</a></p>');
    }


    /*
    * Send default option/config from file to db
    */
    if (empty($options)) {
        
        //set default
        update_option( NEWSWIRE_OPTIONS, $opt );
    }

    /*
    * Register press release content
    */
    newswire_register_pressrelease_type();
    /*
    * Register pressroom blocks
    */
    newswire_pressroom_post_types();
    /*
    * refresh permalinks
    */
    flush_rewrite_rules();
    /*
    * Create initial/dummy content (pressroom blocks etc)
    */
    maybe_autocreate_pressroom_content();
    /*
    * Allow other future plugin to hook after this activation
    */
    do_action('newswire_activate');
    //do_action('newswire_after_activate');
} //end function
endif;



if (!function_exists('newswire_pressroom_deactivate')):
/**
 * When usr Decactivate plugin
 * uninstall.php overrides this one
 */
function newswire_pressroom_deactivate() {
    //Some code here
    //look uninstall.php
}
endif;


if ( !function_exists('newswire_attach_image')):
/**
* Attach newswire data img_url to specific pr post
*/
function newswire_attach_image($file = null, $parent_post_id = 0, $return_type ='url' ) {

    // Check the type of tile. We'll use this as the 'post_mime_type'.
    $import_date = 'post';

    // Initially, Base it on the -current- time.
    $time = current_time('mysql', 1);
    // Next, If it's post to base the upload off:
    if ('post' == $import_date && $parent_post_id > 0) {
        
        $post = get_post($parent_post_id);
       

        if ($post && substr($post->post_date_gmt, 0, 4) > 0) {
            $time = $post->post_date_gmt;
        }


    } elseif ('file' == $import_date) {
        $time = gmdate('Y-m-d H:i:s', @filemtime($file));
    }

    // A writable uploads dir will pass this test. Again, there's no point overriding this one.
    if (!(($uploads = wp_upload_dir($time)) && false === $uploads['error'])) {
        return new WP_Error('upload_error', $uploads['error']);
    }
    $wp_filetype = wp_check_filetype($file, null);

    extract($wp_filetype);


    //skip non image
    if (!wp_match_mime_types('image', $type)) {
        continue;
    }

    if ((!$type || !$ext) && !current_user_can('unfiltered_upload')) {
        continue;
    }
    //Is the file allready in the uploads folder?
    if (preg_match('|^' . preg_quote(str_replace('\\', '/', $uploads['basedir'])) . '(.*)$|i', $file, $mat)) {

        $filename = basename($file);
        $new_file = $file;

        $url = $uploads['baseurl'] . $mat[1];

        $attachment = get_posts(array('post_type' => 'attachment', 'meta_key' => '_wp_attached_file', 'meta_value' => ltrim($mat[1], '/')));
        if (!empty($attachment)) {
            return new WP_Error('file_exists', __('Sorry, That file already exists in the WordPress media library.', 'add-from-server'));
        }

        //Ok, Its in the uploads folder, But NOT in WordPress's media library.
        if ('file' == $import_date) {
            $time = @filemtime($file);
            if (preg_match("|(\d+)/(\d+)|", $mat[1], $datemat)) {
                //So lets set the date of the import to the date folder its in, IF its in a date folder.
                $hour = $min = $sec = 0;
                $day = 1;
                $year = $datemat[1];
                $month = $datemat[2];

                // If the files datetime is set, and it's in the same region of upload directory, set the minute details to that too, else, override it.
                if ($time && date('Y-m', $time) == "$year-$month") {
                    list($hour, $min, $sec, $day) = explode(';', date('H;i;s;j', $time));
                }

                $time = mktime($hour, $min, $sec, $month, $day, $year);
            }
            $time = gmdate('Y-m-d H:i:s', $time);

            // A new time has been found! Get the new uploads folder:
            // A writable uploads dir will pass this test. Again, there's no point overriding this one.
            if (!(($uploads = wp_upload_dir($time)) && false === $uploads['error'])) {
                return new WP_Error('upload_error', $uploads['error']);
            }

            $url = $uploads['baseurl'] . $mat[1];
        }
    } else {
        $filename = wp_unique_filename($uploads['path'], basename($file));

        // copy the file to the uploads dir
        $new_file = $uploads['path'] . '/' . $filename;
        if (false === copy($file, $new_file)) {
            return $newfile . " Cannot be copied ";
            return new WP_Error('upload_error', sprintf(__('The selected file could not be copied to %s.', 'add-from-server'), $uploads['path']));
        }

        // Set correct file permissions
        $stat = stat(dirname($new_file));
        $perms = $stat['mode'] & 0000666;
        @chmod($new_file, $perms);
        // Compute the URL
        $url = $uploads['url'] . '/' . $filename;

        if ('file' == $import_date) {
            $time = gmdate('Y-m-d H:i:s', @filemtime($file));
        }

    }

    //Apply upload filters
    $return = apply_filters('wp_handle_upload', array('file' => $new_file, 'url' => $url, 'type' => $type));
    $new_file = $return['file'];
    $url = $return['url'];
    $type = $return['type'];

    $title = preg_replace('!\.[^.]+$!', '', basename($file));
    $content = '';

    // use image exif/iptc data for title and caption defaults if possible
    if ( !function_exists('wp_read_image_metadata')) {
        require_once ABSPATH .'/wp-admin/includes/image.php';
    }
    if ($image_meta = wp_read_image_metadata($new_file)) {
        if ('' != trim($image_meta['title'])) {
            $title = trim($image_meta['title']);
        }

        if ('' != trim($image_meta['caption'])) {
            $content = trim($image_meta['caption']);
        }

    }

    if ($time) {
        $post_date_gmt = $time;
        $post_date = $time;
    } else {
        $post_date = current_time('mysql');
        $post_date_gmt = current_time('mysql', 1);
    }

    // Construct the attachment array
    $attachment = array(
        'post_mime_type' => $type,
        'guid' => $url,
        'post_parent' => $parent_post_id,
        'post_title' => $title,
        'post_name' => $title,
        'post_content' => '',
    );


    //Win32 fix:
    $new_file = str_replace(strtolower(str_replace('\\', '/', $uploads['basedir'])), $uploads['basedir'], $new_file);

    // Save the data
    $id = @wp_insert_attachment($attachment, $new_file, $parent_post_id);

    if (!is_wp_error($id)) {
        $data = wp_generate_attachment_metadata($id, $new_file);
        wp_update_attachment_metadata($id, $data);
    }


    if ( $return_type == 'id') return $id;

    return $url;

}
endif;



if ( !function_exists('auto_create_pin_as_image_sample')):
/**
 *
 */
function auto_create_pin_as_image_sample($parent_post_id = null) {
    $files = array();
    //Open the media directory and add all of the images to an array.
    $dir = NEWSWIRE_PLUGIN_ASSETS_DIR . '/images/media';
    if ($media_dir = opendir($dir)) {
        while ($m_file = readdir($media_dir)) {
            if ($m_file != "." && $m_file != "..") {
                $files[] = $dir . '/' . $m_file;
            }
        }
    }
    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
    require_once ABSPATH . 'wp-admin/includes/image.php';

    foreach ($files as $file) {
        newswire_attach_image($file, $parent_post_id);
    }

}
endif;

if ( !function_exists('maybe_autocreate_pressroom_content')):
/**
* Autocreate sample pressroom content when plugin is activated
*/
function maybe_autocreate_pressroom_content() {
    if (!get_option('newswire_autocreate_pressroom')) {

        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
        require_once ABSPATH . 'wp-admin/includes/image.php';

        //temp skip submission
        newswire_var('cron', 1);
        //pr
        $post_id = wp_insert_post(array(
            'post_type' => 'pr',
            'post_status' => 'draft',
            'post_title' => __('(sample) Press Release', 'newswire'),
            'post_excerpt' => __('This press release was written with the ', 'newswire') 
                    . newswire_plugin_title() 
                    . __('Plugin. Your PressReleases can be shown only on your site, or distributed through the Newswire network.  It\'s your choice!','newswire'),
                    
            'post_content' => '<p>(<a href="http://www.newswire.net/">Newswire.net</a> -- ' . date_i18n('F j, Y', strtotime('Y-m-d')) . ') -- Press releases written on NewswireXpress are properly formatted, and can be either published only on your website, or syndicated through the Newswire Network.  Its your choice!</p><p></p><p>You can even include your PR\'s in the blog page of your site.</p>',
        ));
        wp_set_post_tags($post_id, 'pressroom');

        //set sample image
        $wp_image_url = newswire_attach_image($image = NEWSWIRE_PLUGIN_ASSETS_DIR . '/images/sampleprimage.jpg', $post_id);


        update_post_meta($post_id, 'newswire_data', array('img_url' => $wp_image_url, 'include_image'=> 1));

        //disable submission
        update_post_meta($post_id, 'newswire_submission', 'disable');

        //update status
        wp_update_post(array("ID" => $post_id, 'post_status' => 'publish'));
        //
        newswire_var('cron', false);

        //pin_as_text
        wp_insert_post(array(
            'post_type' => 'pin_as_text',
            'post_status' => 'publish',
            'post_title' => '(sample) Text Block',
            'post_content' => 'In these text blocks, you can put any information that you would like to share with the journalists, bloggers, analysts and customers that visit this page.  The text on this page can be downloaded with a click, and used as the basis of articles and posts written about your company.  Only 500 characters will be displayed in this box, but you can write an unlimited amount here.  The full text of this block can be displayed by clicking on the link at the bottom of this box.  Sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text sample text.',
        ));

    //pin_as_embed
        wp_insert_post(array(
            'post_type' => 'pin_as_embed',
            'post_status' => 'publish',
            'post_title' => '(sample) Embed Block - Video',
            'post_content' => '<iframe width="300" height="215" src="//www.youtube.com/embed/lCR6YXDQ304" frameborder="0" allowfullscreen></iframe>',
        ));
    //pin_as_image
        $post_id = wp_insert_post(array(
            'post_type' => 'pin_as_image',
            'post_status' => 'publish',
            'post_title' => '(sample) Image Album',
            'post_content' => '',
        ));
        auto_create_pin_as_image_sample($post_id);

    //pin_as_quote
        $quote_id = wp_insert_post(array(
            'post_type' => 'pin_as_quote',
            'post_status' => 'publish',
            'post_title' => '(sample) Quote Block',
            'post_content' => 'The NewswireXpress(tm) WordPress Plugin is a game-changer for the websites that install it. ',
        ));
        
        $newswire_data = array();
        $newswire_data['quote_source'] = 'D.E. Brown, Editor-in-chief';
        $newswire_data['quote_source_url'] = 'http://newswire.net/profile/dougbrown';
        update_post_meta($quote_id, 'newswire_data', $newswire_data);

        


        //pin_as_link
        //
        $link = wp_insert_post(array(
            'post_type' => 'pin_as_link',
            'post_status' => 'publish',
            'post_title' => '(sample) Link Block - Emails',
            'post_content' => '',
        ));

        $newswire_data = array();
        $newswire_data['text'][0] = 'Email BG Cheese - CEO';
        $newswire_data['text'][1] = 'Email IM Slick - Sales Manager';
        $newswire_data['text'][2] = 'Email GD Story - Public Relations';
        $newswire_data['text'][3] = 'Email RE Load - CTO';

        $newswire_data['link'][0] = 'mail:support@newswire.net';
        $newswire_data['link'][1] = 'mail:support@newswire.net';
        $newswire_data['link'][2] = 'mail:support@newswire.net';
        $newswire_data['link'][3] = 'mail:support@newswire.net';
        update_post_meta($link, 'newswire_data', $newswire_data);

        //pin_as_contact
        $contact = wp_insert_post(array(
            'post_type' => 'pin_as_contact',
            'post_status' => 'publish',
            'post_title' => '(sample) Contact Block',
            'post_content' => '',
        ));
        $newswire_data = array();
        $newswire_data['first_name'] = 'BG';
        $newswire_data['last_name'] = 'Cheese';
        $newswire_data['contact_position'] = 'CEO';
        $newswire_data['company_name'] = 'Anycompany';
        $newswire_data['company_tickers'] = 'GTRICH:OTC';
        $newswire_data['company_address'] = '1234 Anywhere';
        $newswire_data['company_city'] = 'Your Town';
        $newswire_data['company_country'] = 'none';
        $newswire_data['company_state'] = 'Your State';
        $newswire_data['company_zip'] = '12345';
        $newswire_data['company_telephone'] = '(800) 555-1212';
        $newswire_data['company_email'] = 'bgcheese@anycompany.com';
        $newswire_data['company_website'] = 'http://anycompany.com';
    
        $attachment_id = newswire_attach_image($image = NEWSWIRE_PLUGIN_ASSETS_DIR . '/images/DEBrown.jpg', $contact, 'id');
        if ( $attachment_id ) {
            //create new attachment media and attach to $post;
            $newswire_data['contact_media_id'] = $attachment_id;
            $newswire_data['show_contact_media_id'] = 1;
        }
    

        update_post_meta($contact, 'newswire_data', $newswire_data);

        //create pin_as_social
        wp_insert_post(array(
            'post_type' => 'pin_as_social',
            'post_status' => 'publish',
            'post_title' => '(sample) Social Media - Twitter',
            'post_content' => base64_decode('PGEgY2xhc3M9InR3aXR0ZXItdGltZWxpbmUiIGhyZWY9Imh0dHBzOi8vdHdpdHRlci5jb20vdG90Ym94ZXIiIGRhdGEtd2lkZ2V0LWlkPSI0NjYzNTEyNTAwNTM2ODUyNDgiPlR3ZWV0cyBieSBAdG90Ym94ZXI8L2E+IDxzY3JpcHQ+IWZ1bmN0aW9uKGQscyxpZCl7dmFyIGpzLGZqcz1kLmdldEVsZW1lbnRzQnlUYWdOYW1lKHMpWzBdLHA9L15odHRwOi8udGVzdChkLmxvY2F0aW9uKT8naHR0cCc6J2h0dHBzJztpZighZC5nZXRFbGVtZW50QnlJZChpZCkpe2pzPWQuY3JlYXRlRWxlbWVudChzKTtqcy5pZD1pZDtqcy5zcmM9cCsiOi8vcGxhdGZvcm0udHdpdHRlci5jb20vd2lkZ2V0cy5qcyI7ZmpzLnBhcmVudE5vZGUuaW5zZXJ0QmVmb3JlKGpzLGZqcyk7fX0oZG9jdW1lbnQsInNjcmlwdCIsInR3aXR0ZXItd2pzIik7PC9zY3JpcHQ+'),
        ));

        wp_insert_post(array(
            'post_type' => 'pin_as_social',
            'post_status' => 'publish',
            'post_title' => '(sample) Social Media - Facebook',
            'post_content' => '<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fnewswires&amp;width=300&amp;height=300&amp;colorscheme=light&amp;show_faces=true&amp;header=true&amp;stream=true&amp;show_border=true" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:300px; height:300px;" allowTransparency="true"></iframe>',
        ));

        wp_insert_post(array(
            'post_type' => 'pin_as_social',
            'post_status' => 'publish',
            'post_title' => '(sample) Social Media - Linkedin',
            'post_content' => '<a href="http://www.linkedin.com/pub/douglas-e-brown/53/456/5aa"><img src="https://static.licdn.com/scds/common/u/img/webpromo/btn_myprofile_160x33.png" width="160" height="33" class="aligncenter" border="0" alt="View Douglas E Brown\'s profile on LinkedIn"></a>',
        ));

        update_option('newswire_autocreate_pressroom', true);
    }
}
endif;


if ( !function_exists('newswire_prevent_activate_plugin')):
# customize editor - paragraph
add_action('admin_init', 'newswire_prevent_activate_plugin');
function newswire_prevent_activate_plugin() {
    //wp_enqueue_script('jquery-ui-datepicker');
    //wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

    $options = newswire_options();
    global $typenow , $pagenow;
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
    $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
    $plugin_status = isset($_REQUEST['plugin_status']) ? $_REQUEST['plugin_status'] : '';
    $s = isset($_REQUEST['s']) ? urlencode($_REQUEST['s']) : '';

    if ( 'activate' == $action &&  'newswirexpress/newswirexpress.php' == urldecode($plugin) ) {
        //cannot activate pro if light is currently activce
        //wp_redirect('plugins.php?plugin_status=' . $plugin_status );
        wp_die( 'Sorry! Cannot activate free and pro version at the same time. Please deactivate one and then continue' );
        exit;
    }

    //prevew adding new pin_as_latestpr 
    $options = newswire_options();

    if ( 'post-new.php' == $pagenow && get_post($options['latest_press_releases']) && 'pin_as_latestpr' == $typenow) {
        //unset($post_new_file);
       // wp_redirect(admin_url('post.php?post='.$options['latest_press_releases'].'&action=edit') );
        //$post_new_file = null;
        wp_redirect(admin_url('post.php?post='.$options['latest_press_releases'].'&action=edit') );
        exit;
    } 


}
endif;