<?php


/**
 * Togle html radio option
 *
 * @param $value mix
 * @param $default mix default value
 *
 * @return string
 */
function newswire_radio_selected($value = '', $default = null) {
    if (empty($value)) {
        return '';
    }

    if ($value !== $default) {
        return;
    } else {
        return ' checked="checked" ';
    }

}

/**
 * Togle html radio option
 *
 * @param $value mix
 * @param $default mix default value
 *
 * @return string
 */

function newswire_chk_selected($value) {
    if (empty($value)) {
        return '';
    } else {
        return ' checked="checked" ';
    }

}


add_action('newswire_api_validate_success', 'newswire_api_valid_callback', 10, 1);
function newswire_api_valid_callback($result = array()) {
    wp_cache_delete(NEWSWIRE_OPTIONS, 'options');

    if (!empty($result)) {
        $options = newswire_options();
        $options['api_validated'] = 1;
        $options['newswire_user'] = $result['userinfo'];
        $options['company_profiles'] = $result['companyprofiles'];

        update_option(NEWSWIRE_OPTIONS, $options);

    }
}


/**
 * Validate newswire email and api key and secret key
 *
 * @param $email string
 * @param $api_key string
 * @param $secret_key string
 *
 * return boolean
 */
function newswire_validate_api_account($email = '', $api_key = '', $secret_key = '', $options = null) {

    $options = newswire_options();

    $client = Newswire_Client::getInstance();

    $client->setEmail($email);

    $client->setApiKey($api_key);

    $client->setSecretKey($secret_key);

    $response = $client->test();
    //var_dump($response);
    //  exit;
    $status = wp_remote_retrieve_response_code($response);

    if ($status == 200) {

        $body = json_decode(wp_remote_retrieve_body($response), true);

        //  var_dump($response);
        do_action('newswire_api_validate_success', $body);

        //  return $body;
        //return true;
        return 'valid';

    } else {

        $options['api_validated'] = 0;
        $options['newswire_user'] = '';
        $options['company_profiles'] = '';
        update_option('newswire_options', $options);

        return wp_remote_retrieve_body($response);

    }
    return false;
}

/** 
* html input generic callback for add_settings_field page
*/
function newswire_settings_input($args) {
    
    $args = wp_parse_args( $args, $defaults = array('description'=> '', 'attr' => array('rows'=> null)) );
    $option = newswire_options();
    if ($args['type'] == 'textarea') {
        echo '<textarea rows="' . ($args['attr']['rows'] ? $args['attr']['rows'] : '20') . '" cols="100" name="newswire_options[' . $args['field'] . ']">' . $option[$args['field']] . '</textarea>';
    } elseif ($args['type'] == 'select') {
        echo '<select name="newswire_options[' . $args['field'] . ']">';
        foreach ($args['options'] as $k => $val) {
            if ($option[$args['field']] == $k) {
                $selected = 'selected';
            } else {
                $selected = '';
            }

            printf('<option value="%s" %s>%s</option>', $k, $selected, $val);
        }
        echo '</select>';
    }

    if ($args['description']) {
        printf('<p class="description">%s</p>', $args['description']);
    }

}

/**
* 
*/
function newswire_settings_page_radio_boxes($args) {
    $option = newswire_options();
    printf('<input name="newswire_options[%1$s]" type="text" value="%2$s" size="65" placeholder="%3$s">', $args['field'], $option[$args['field']], $args['placeholder']);
    if ($args['description']) {
        printf('<p class="description">%s</p>', $args['description']);
    }

}

/**
*
*/
function newswire_settings_page_dropdown($args) {

    $option = newswire_options();

    $assigned_page_template = $option['pressroom_page_template'];

    echo wp_dropdown_pages(array(
        'name' => 'newswire_options[pressroom_page_template]',
        'echo' => false,
        'show_option_none' => __('- None -', 'newswire'),
        'selected' => !empty($assigned_page_template) ? $assigned_page_template : false,
    ));

    ?><a href="<?php echo admin_url(add_query_arg(array('post_type' => 'page'), 'post-new.php'));?>" class="button-secondary"><?php _e('New Page', 'newswire');?></a>
        <?php printf('<a href="javascript:;" class="button" onclick=\'return window.open("%s", "prevew-pressroom", "width=700, height=500")\'>Preview</a> ', get_permalink($assigned_page_template));?>
    <br><?php
    $checked1 = '';
    $checked3 = $checked2 = '';
    if ($option['pressroom_theme'] == '' || $option['pressroom_theme'] == 'default') {
        $checked3 = 'checked="checked"';
    } elseif ($option['pressroom_theme'] == 'standard') {
        //custom
        $checked2 = 'checked="checked"';
    } else {
        $checked1 = 'checked="checked"';
    }

    echo '<br>';
    echo '<div id="pressroom-toggle-settings">';
    echo '<input type="radio" value="default" name="newswire_options[pressroom_theme]" id="pressroom-theme-3" ' . $checked3 . '><label for="pressroom-theme-3">Default</label><br>';
    echo '<input type="radio" value="current_theme" name="newswire_options[pressroom_theme]" id="pressroom-theme-1" ' . $checked1 . '><label for="pressroom-theme-1">Current theme header and footer</label><br>';
    echo '<input type="radio" value="standard" name="newswire_options[pressroom_theme]" id="pressroom-theme-2" ' . $checked2 . '><label for="pressroom-theme-2">Custom</label>';
    echo '</div>';
}

/**
*
*/
function newswire_settings_swatches($args) {
    $option = newswire_options();
    //echo '<div class="toggle-settings">';
    printf('<input name="newswire_options[%1$s]" class="wp-color-picker %4$s" type="text" value="%2$s" placeholder="%3$s">', $args['field'], $option[$args['field']], $args['placeholder'], $args['class']);
    //printf('swatchcolor');
    if ($args['description']) {
        printf('<p class="description">%s</p>', $args['description']);
    }

    //echo '</div>';
}

/**
*
*/
function newswire_settings_text($args) {
    $option = newswire_options();
    printf('<input name="newswire_options[%1$s]" type="text" value="%2$s" size="' . ($args['attr']['size'] ? $args['attr']['size'] : '65') . '" placeholder="%3$s">', 
        $args['field'], 
        $option[$args['field']], 
        !empty($args['placeholder']) ? $args['placeholder'] :''

    );
    if ($args['description']) {
        printf('<p class="description">%s</p>', $args['description']);
    }

}

/*
*
*/
function newswire_settings_imageuploader($args) {
    $option = newswire_options();
    //printf('<input name="image_upload" type="file"  size="50" >');
    //echo '<div class="media-upload-form type-form validate">';
    //echo media_upload_form();
    //echo '</div>';
    if ('toggle-settings' == $args['class']) {
        echo '<div class="toggle-settings">';
    }

    if ($option[$args['field']] != '') {
        $preview = sprintf('<img src="%s" border="0" width="300" /> ', $option[$args['field']]);
    } else {
        $preview = '';
    }

    echo '<div class="">';
    printf('<div class="preview-image">%s</div><input name="newswire_options[%s]" type="text" value="%s" size="65" placeholder="%s"> <a class="button secondary newswire-media-upload">Select Image</a> <a class="remove-uploaded-image button">Remove Image</a>', $preview, $args['field'], $option[$args['field']], $args['placeholder']);
    if ($args['description']) {
        printf('<p class="description">%s</p>', $args['description']);
    }

    echo '</div>';
    if ('toggle-settings' == $args['class']) {
        echo '</div>';
    }

}

/*
*
*/
function newswire_settings_dropdownlist($args) {

}

/**
 * Print pressroom settings page
 * remove this soon
 *
 * @return void
 */
function newswire_pressroom_settings() {
    ?>
        <div class="wrap" id="newswire-settings">

        <div id="icon-options-general" class="icon32"><br></div>

        <h2> <?php _e('PressRoom Settings', 'newswire')?></h2>

        <p class="description"> <?php _e('Customize your PressRoom.', 'newswire')?></p>

        <br/>

        <ul class="newswire-tabs">
            <?php
    //do_action('pressroom_tabs');
    ?>
            <li class="newswire-tab "><a href="#pressroom-settings-general"><?php _e('PressRoom', 'newswire');?></a></li>
            <li class="newswire-tab hide-if-no-js"><a href="#pressroom-settings-rss"><?php _e('Press Release RSS', 'newswire');?></a></li>
            <li class="newswire-tab hide-if-no-js"><a href="#pressroom-settings-css"><?php _e('CSS', 'newswire');?></a></li>
            <!-- <li class="newswire-tab hide-if-no-js"><a href="#pressroom-settings-shortcodes"><?php _e('Shortcodes', 'newswire');?></a></li> //-->
        </ul>

        <form action="options.php" method="post">

            <?php
    settings_fields('newswire_options');
    ?>
            <?php do_newswire_settings_sections('newswire_pressroom');?>

            <?php submit_button();?>

        </form>

    </div>

    <?php
}

/**
 * Newswire Option - Select supported post type for newswire article submission
 *
 * @return void
 */
function newswire_supported_post_type_option_callback() {
    $html = '';

    $options = newswire_options();

    $item = 'supported_post_types';

    $slug = '';

    $seperator = '<br>';

    //get existing post types
    $post_types = get_post_types($args = array('_builtin' => false, 'public' => true), 'objects');

    //add post by default
    if (in_array('post', $options['supported_post_types'])) {
        $html .= sprintf('<label for=""><input name="newswire_options[%s][]" type="checkbox" id="%s" value="%s" %s> %s</label> %s', $item, $item . $slug, 'post', 'checked="checked"', 'Post', $seperator);
    } else {
        $html .= sprintf('<label for=""><input name="newswire_options[%s][]" type="checkbox" id="%s" value="%s" %s> %s</label> %s', $item, $item . $slug, 'post', '', 'Post', $seperator);
    }

    foreach ($post_types as $post_type) {

        //if ( $post_type->name == 'post' ) continue;

        if (in_array($post_type->name, $options['supported_post_types'])) {
            $checked = 'checked="checked"';
        }

        if ($post_type->name == 'pr') {
            $checked .= ' disabled';
        }

        $slug = $post_type->name;
        $label = $post_type->labels->name;
        $html .= sprintf('<label for=""><input name="newswire_options[%s][]" type="checkbox" id="%s" value="%s" %s> %s</label> %s', $item, $item . $slug, $slug, $checked, $label, $seperator);

        //reset $checked
        $checked = '';
    }
    echo $html;
    echo '</div>';
}

/**
 *
 * first settings section which is GENERAL add div container for tab function
 */
function newswire_option_email() {

    $option = newswire_options();
    echo '<input name="newswire_options[newswire_api_email]" type="text" value="' . $option['newswire_api_email'] . '" size="60">';
    echo '<p class="description">Please use the same email account you used to signup for newswire.net account</p>';
}

function newswire_option_api_key_callback() {

    $option = get_option('newswire_options');
    echo '<input name="newswire_options[newswire_api_key]" type="text" value="' . $option['newswire_api_key'] . '" size="60">';
    printf('<p class="description">Please get your api key from <a href="%s" target="_blank">newswire.net My Settings</a> </p>', NEWSWIRE_API_LINK);
}

function newswire_option_api_secret() {
    $option = get_option('newswire_options');
    echo '<input name="newswire_options[newswire_api_secret]" type="text" value="' . $option['newswire_api_secret'] . '" size="80">';
    printf('<p class="description">Please get your secret key from <a href="%s" target="_blank">newswire.net My Settings</a> </p>', NEWSWIRE_API_LINK);
}

function newswire_option_api_validate_callback() {

    $options = newswire_options();

    extract($options);

    //if (!$newswire_api_email || !$newswire_api_key ) {
    //  return;
    //}
    echo '<div class="validate-api-wrapper">';

    /*

    if ( $options['api_validated'] ) {
    echo '<strong style="color: red">Validated</p>';
    }*/

    if (!$options['api_validated']) {
        //echo ' <p class="description" style="display: inline "> API not valid.</p>';
        //submit_button('Validate', 'small' , 'submit-validate-api');
        // echo '<button class="button button-small" id="newswire-validate-api-ajax">Validate API</button><span class="spinner"></span>';
        //if ( !empty($_POST) )
        if (  $options['newswire_api_email'] !== '' || $options['newswire_api_key'] !== '' )  {
            printf( '<div class="error settings-error"> <p class="" style="font-weight: strong ">%s: Invalid API Credential.</p></div>', newswire_plugin_title());
        }
    } else {

        //echo '<button class="button button-small" id="newswire-validate-api-ajax">Re-Validate API</button><span class="spinner"></span>';
        echo '<div class=""><p class="" style="display: inline; font-weight: bold; font-size:14px; color: green "> Api is valid.</p></div>';
    }
    echo '</div>';
}

function newswire_article_submission_mode_callback() {

    $options = get_option(NEWSWIRE_OPTIONS);

    $html .= '<select name="newswire_options[article_submission_mode]">';

    $checked = '';

    foreach (array('autosubmit' => 'Auto Submit', 'manual' => 'Manual') as $val => $field) {

        if ($val == trim($options['article_submission_mode'])) {
            $checked = 'selected="selected" ';
        }

        $html .= sprintf('<option value="%s" %s>%s</option>', $val, $checked, $field);
        $checked = '';
    }

    $html .= '</select>';

    $html .= '<p class="description">Manual Submission: Requires users action to submit article to newswire</p>';
    $html .= '<p class="description">Auto Submit: article is submitted to newswire when save as pending</p>';

    echo $html;

}

function newswire_article_force_submission_callback() {
    $option = get_option('newswire_options');
    if ($option['force_submission']) {
        echo '<input name="newswire_options[force_submission]" type="checkbox" value="1" checked="checked">';
    } else {
        echo '<input name="newswire_options[force_submission]" type="checkbox" value="1" >';
    }

    echo ' Force submission <p class="description" style="display: "> Require article to be submitted to newswire before publishing locally.</p>';
}

function newswire_article_submission_manual_callback() {

    $option = get_option('newswire_options');

    if ($option['article_submission_manual']) {
        echo '<input name="newswire_options[article_submission_manual]" type="checkbox" value="1" checked="checked">';
    } else {
        echo '<input name="newswire_options[article_submission_manual]" type="checkbox" value="1" >';
    }

    echo '<p class="description" style="display: inline"> Allow article to be submitted manually.</p>';

}

function newswire_article_submission_auto_callback() {

    $option = get_option('newswire_options');

    if ($option['article_submission_auto']) {
        echo '<input name="newswire_options[article_submission_auto]" type="checkbox" value="1" checked="checked">';
    } else {
        echo '<input name="newswire_options[article_submission_auto]" type="checkbox" value="1" >';
    }

    echo ' Enable Auto Submit <p class="description" style="display: "> Automatically submit article when user save article as PENDING.</p>';

}
function newswire_article_submission_lock_callback() {
    $option = get_option('newswire_options');

    if ($option['article_submission_lock']) {
        echo '<input name="newswire_options[article_submission_lock]" type="checkbox" value="1" checked="checked">';
    } else {
        echo '<input name="newswire_options[article_submission_lock]" type="checkbox" value="1" >';
    }

    echo ' Lock article after submission <p class="description" style="display: "> Prevent user from editing article content upon submission to newswire.net.</p>';

}

/** newsroom */

/**
 * Checkboxes from newsroom tab
 */
function newswire_newsroom_boxes_callback() {

    $option = newswire_options();

    if ($option['newsroom_show_featured_content']) {
        echo '<input name="newswire_options[newsroom_show_featured_content]" type="checkbox" value="1" checked="checked"> ';
    } else {
        echo '<input name="newswire_options[newsroom_show_featured_content]" type="checkbox" value="1" > ';
    }

    echo __('Show Featured Content', 'newswire');
    echo '<br>';

    if ($option['newsroom_show_category_nav']) {
        echo '<input name="newswire_options[newsroom_show_category_nav]" type="checkbox" value="1" checked="checked"> ';
    } else {
        echo '<input name="newswire_options[newsroom_show_category_nav]" type="checkbox" value="1" > ';
    }

    echo __('Show Category Navs', 'newswire');
    echo '<br>';

    /*

    if ( $option['newsroom_disabled'] )
    echo '<input name="newswire_options[newsroom_disabled]" type="checkbox" value="1" checked="checked"> ';
    else
    echo '<input name="newswire_options[newsroom_disabled]" type="checkbox" value="1" > ';

    # label
    echo __('Disable Newsroom', 'newswire');
    echo '<br>';

     */

    if ($option['newsroom_show_likes']) {
        echo '<input name="newswire_options[newsroom_show_likes]" type="checkbox" value="1" checked="checked">';
    } else {
        echo '<input name="newswire_options[newsroom_show_likes]" type="checkbox" value="1" >';
    }

    echo ' Show Likes <br>';

    if ($option['newsroom_show_comments']) {
        echo '<input name="newswire_options[newsroom_show_comments]" type="checkbox" value="1" checked="checked">';
    } else {
        echo '<input name="newswire_options[newsroom_show_comments]" type="checkbox" value="1" >';
    }

    echo ' Show Comments <br>';

    if ($option['newsroom_show_author']) {
        echo '<input name="newswire_options[newsroom_show_author]" type="checkbox" value="1" checked="checked"> ';
    } else {
        echo '<input name="newswire_options[newsroom_show_author]" type="checkbox" value="1" > ';
    }

    echo __('Show Author', 'newswire');
}

function newswire_newsroom_pinperpage() {

    $option = newswire_options();

    echo '<input name="newswire_options[newsroom_pinperpage]" type="text" value="' . $option['newsroom_pinperpage'] . '" size="5">';
    echo '<p class="description">Total number of article per page</p>';

    //echo ' Show Categories <p class="description" style="display: "> .</p>';
}

function newswire_newsroom_pinwidth() {

    $option = newswire_options();

    echo '<input name="newswire_options[newsroom_pinwidth]" type="text" value="' . $option['newsroom_pinwidth'] . '" size="5">px';
    //echo '<p class="description">Pin width</p>';
    //echo ' Show Categories <p class="description" style="display: "> .</p>';
}

/**
 * Save default page for newsroom
 */
function newswire_newsroom_default_page() {
    //get global option

    $option = get_option(NEWSWIRE_OPTIONS);

    $assigned_page_template = $option['newsroom_page_template'];

    echo wp_dropdown_pages(array(
        'name' => 'newswire_options[newsroom_page_template]',
        'echo' => false,
        'show_option_none' => __('- None -', 'newswire'),
        'selected' => !empty($assigned_page_template) ? $assigned_page_template : false,
    ));

    ?><a href="<?php echo admin_url(add_query_arg(array('post_type' => 'page'), 'post-new.php'));?>" class="button-secondary"><?php _e('New Page', 'newswire');?></a>
    <a href="<?php echo newswire_newsroom_url()?>" target="_blank" class="button button-secondary"><?php _e('Preview', 'newswire');?></a><br><?php

    //echo '<p class="description" style="display: "> '. _e('Associate WordPress Pages with the Local Newsroom page', 'newswire').'</p>';

    //Associate WordPress Pages with the following BuddyPress Registration pages.
}

/**
 * Save what feeds to use for newsroom apge
 */
function newswire_newsroom_feed_source() {

    $option = newswire_options();

    $html .= '<select name="newswire_options[newsroom_feed_source]">';

    $checked = '';

    foreach (array('local' => 'Local Newswroom', 'newswire' => 'Newswire Live') as $val => $field) {

        if ($val == trim($option['newsroom_feed_source'])) {
            $checked = 'selected="selected" ';
        }

        $html .= sprintf('<option value="%s" %s>%s</option>', $val, $checked, $field);
        $checked = '';
    }

    $html .= '</select>';

    $html .= '<p class="description">Local NewsRoom: You can only see press releases you published</p>';
    $html .= '<p class="description">' . __('Newswire Live: Content will be comming from newswire.net feed and all links willl be referenced to newswire', 'newswire') . '</p>';

    echo $html;
}

function newswire_newsroom_customcss() {
    $option = newswire_options();

    echo '<textarea rows="20" cols="100" name="newswire_options[custom_css]">' . $option['custom_css'] . '</textarea>';
}


/**
 * Auto create pages when options are updated
 */
add_action('update_option_newswire_options', 'newswire_hook_settings_updated', 10, 2);
function newswire_hook_settings_updated($old, $new) {
    //var_dump($new);
    //exit;
    if (!empty($_POST['newswire_options'])) {

        $options = $new;

      

        remove_action('update_option_newswire_options', 'newswire_hook_settings_updated');

        
        //create pressroom page
        if (!isset($options['pressroom_page_template'])) {
            $page_id = wp_insert_post(array(
                'post_title' => 'pressroom',
                'post_type' => 'page',
                'post_status' => 'publish',
                'ping_status' => 'closed',
                'comment_status' => 'closed',
                'post_content' => '[pressroom_blocks]',
            ));
            $options['pressroom_page_template'] = $page_id;
            update_post_meta($page_id, 'admin_notices', '<p>Do not remove the shortcode [pressroom_blocks] from this page.  You can add, modify, delete and rearrange the content blocks that display on the PressRoom page by visiting PressRoom in the Dashboard <br/> ' . admin_url('edit.php?post_type=pressroom') . ' <a href="#" class="hide-notice" type="pressroom">Hide</a></p>');
        
            //update_post_meta( $page_id, '_wp_page_template', 'pressroom-template-standard.php');
        } elseif (!get_page_by_title('pressroom')) {

            $page_id = wp_insert_post(array(
                'post_title' => 'pressroom',
                'post_type' => 'page',
                'post_status' => 'publish',
                'ping_status' => 'closed',
                'comment_status' => 'closed',
                'post_content' => '[pressroom_blocks]',
            ));
            $options['pressroom_page_template'] = $page_id;
            update_post_meta($page_id, 'admin_notices', '<p>Do not remove the shortcode [pressroom_blocks] from this page.  You can add, modify, delete and rearrange the content blocks that display on the PressRoom page by visiting PressRoom in the Dashboard <br/> ' . admin_url('edit.php?post_type=pressroom') . ' <a href="#" class="hide-notice" type="pressroom">Hide</a></p>');
        }

        //newswire_validate_api_account();
        //
        //validate_newswire_api();
        $client = Newswire_Client::getInstance();

        $client->setEmail($options['newswire_api_email']);

        $client->setApiKey($options['newswire_api_key']);

        $client->setSecretKey($options['newswire_api_secret']);

        $response = $client->test();

        //var_dump($response);
        //exit;
        $status = wp_remote_retrieve_response_code($response);

        if ($status == 200) {

            $body = json_decode(wp_remote_retrieve_body($response), true);
         
            $options['api_validated'] = 1;
            $options['newswire_user'] = $body['userinfo'];
            $options['company_profiles'] = $body['companyprofiles'];
            $options['site_id'] = $body['siteinfo']['site_id'];

        } else {

            $options['api_validated'] = 0;
            $options['newswire_user'] = '';
            $options['company_profiles'] = '';

        }

        newswire_options_update($options);

        //maybe autocreate pages

        if (get_post($options['newsroom_page_template']) !== null && get_page_by_title('newsroom')) {
            //die('asdf');
            //maybe_autocreate_pages($categories, $options);
            do_action('newswire_categories_change', $options);

            //import content newsroom
            $count = wp_count_posts('pr');
            if (empty($count->publish) || $count->publish < 50) {
                //import now
                // $GLOBALS['newswire_rss_cron']->_import_newswire_newsroom_category_default();
                set_time_limit(0);
                ignore_user_abort();
                //do_action('newswire_category_import' );
                do_action('newswirexpress_after_activate');
            }
        }

        do_action('newswire_option_updated');
    }//endif

}//end function

function newswire_newsroom_category_filter_item($catname, $categories) {
    global $newswire_config;
    foreach ($categories as $found_id => $top) {
        if (strtolower($top['name']) == strtolower($catname)) {

            break;
        }
    }

    $foundcat = array($found_id => $categories[$found_id]);

    foreach ($foundcat as $category_id => $top) {
        //skip cat
        if (in_array(strtolower($top['name']), (array)$newswire_config['exclude_categories'])) {
            continue;
        }

        if (sanitize_title($top['name']) == $_REQUEST['category']) {
            $active = 'active';
        } else {
            $active = '';
        }

        if (is_array($top['sub'])) {

            $menuli .= sprintf('<li class="pin-box %s"><input type="checkbox" name="newswire_options[include_categories][]" value="%d" ><a class=""  href="%s">%s</a><ul class="">', $active . ' ' . strtolower($top['name']), $category_id, $top['permalink'], $top['name']);

            foreach ($top['sub'] as $sub_id => $sub) {
                if (is_array($sub['subsub'])) {
                    $menuli .= sprintf('<li class=""><input type="checkbox" name="newswire_options[include_categories][]" value="%s"> <a class=""   href="%s">%s</a><ul class="">', $category_id . '_' . $sub_id, $sub['permalink'], $sub['name']);
                    /*foreach($sub['subsub'] as $subsub_id=>$subsub) {
                    $menuli .= sprintf('<li><input type="checkbox" name="newswire_options[include_categories][]" value="%d"><a href="%s">%s</a></li>', trim($subsub_id), $sub['permalink'],  $subsub['name']);
                    }*/
                    $menuli .= '</li></ul>';
                } else {
                    $menuli .= sprintf('<li><input type="checkbox" name="newswire_options[include_categories][]" value="%s"><a href="%s">%s</a></li>', $category_id . '_' . $sub_id, $sub['permalink'], $sub['name']);
                }
            }
            $menuli .= '</li></ul>';
        } else {

            if (strtolower($top['name']) == 'news') {
                $news_category_default = sprintf('<input type="hidden" name="newswire_options[include_categories][]" value="%d" >', $category_id);
                $menuli .= sprintf('<li class="pin-box %s"><input type="checkbox" name="newswire_options[include_categories][]" value="%d" disabled checked="checked"><a href="%s">%s</a><span class="description-note"> (always included)</span></li>', $active, $category_id, $top['permalink'], $top['name']);
            } else {
                $menuli .= sprintf('<li class="pin-box %s"><input type="checkbox" name="newswire_options[include_categories][]" value="%d" ><a href="%s">%s</a></li>', $active . ' ' . sanitize_title(strtolower($top['name'])), $category_id, $top['permalink'], $top['name']);
            }

        }
    }
    echo $menuli;
    echo $news_category_default;
}
/**
 * Print category selection control - settings
 */
function newswire_newsroom_categoryfilter() {

    global $newswire_config;

    $options = newswire_options();
    echo '<p>Maximum number of articles to import per page per hour: <input type="text" size="3" name="newswire_options[categories_maximum_import]" value="' . $options['categories_maximum_import'] . '"></p>';
    echo '<p>Check the Newswire article categories that you wish to include from your NewsRoom.</p>';
    echo '<p><input type="checkbox" id="toggle-all-categories"> Check/Uncheck All (Note: it may take up to an hour for additional pages to populate.)</p>';
    $categories = newswire_fetch_categories(0);

    // maybe_autocreate_pages($categories, $options);

    echo '<h4>Section Pages</h4>';

    echo '<ul class="category-filter">';
    newswire_newsroom_category_filter_item('news', $categories);
    newswire_newsroom_category_filter_item('blog post', $categories);
    newswire_newsroom_category_filter_item('interview', $categories);
    newswire_newsroom_category_filter_item('oped', $categories);
    newswire_newsroom_category_filter_item('opinion', $categories);
    newswire_newsroom_category_filter_item('review', $categories);

    echo '</ul>';
    echo '<hr>';

    echo '<h4>Category Pages</h4>';
    echo '<div id="category-filter-wrapper">';
    echo '<ul class="category-pages category-filter" >';
    foreach ($newswire_config['default_categories'] as $cat) {
        newswire_newsroom_category_filter_item(strtolower($cat), $categories);
    }

    /*
    foreach($categories as $category_id=>$top) {
    //skip cat
    if ( in_array(strtolower($top['name']), array('news', 'blog post', 'opinion', 'interview', 'oped', 'review', 'news story')) ) continue;

    if ( sanitize_title($top['name']) == $_GET['category']  ) $active = 'active'; else $active = '';



    if ( is_array($top['sub']) ) {

    $menuli .= sprintf('<li class="pin-box %s"><input type="checkbox" name="newswire_options[include_categories][]" value="%d" ><a class=""  href="%s">%s</a><ul class="">',$active . ' '.strtolower($top['name']), $category_id,  $top['permalink'], $top['name'] );

    foreach($top['sub'] as $sub_id=>$sub) {
    if( is_array($sub['subsub']) ) {
    $menuli .= sprintf('<li class=""><input type="checkbox" name="newswire_options[include_categories][]" value="%s"> <a class=""   href="%s">%s</a><ul class="">', $category_id.'_'.$sub_id, $sub['permalink'], $sub['name']);
    //foreach($sub['subsub'] as $subsub_id=>$subsub) {
    //  $menuli .= sprintf('<li><input type="checkbox" name="newswire_options[include_categories][]" value="%d"><a href="%s">%s</a></li>', trim($subsub_id), $sub['permalink'],  $subsub['name']);
    //}
    $menuli .= '</li></ul>';
    } else {
    $menuli .= sprintf('<li><input type="checkbox" name="newswire_options[include_categories][]" value="%s"><a href="%s">%s</a></li>', $category_id.'_'.$sub_id, $sub['permalink'], $sub['name']);
    }
    }
    $menuli .= '</li></ul>';
    } else {

    if ( strtolower($top['name']) == 'news' ) {
    $news_category_default = sprintf('<input type="hidden" name="newswire_options[include_categories][]" value="%d" >', $category_id);
    $menuli .= sprintf('<li class="pin-box %s"><input type="checkbox" name="newswire_options[include_categories][]" value="%d" disabled checked="checked"><a href="%s">%s</a><span class="description-note"> (always included)</span></li>', $active, $category_id, $top['permalink'], $top['name'] );
    } else {
    $menuli .= sprintf('<li class="pin-box %s"><input type="checkbox" name="newswire_options[include_categories][]" value="%d" ><a href="%s">%s</a></li>', $active, $category_id, $top['permalink'], $top['name'] );
    }

    }
    }
    echo $menuli;
     */
    echo '</ul>';
    //echo $news_category_default;

    //  if ( count($options['include_categories']) ) {
    echo '<script>';
    echo 'var checked = "' . join(',', $options['include_categories']) . '";';
    echo 'var cats = checked.split(",");';
    echo 'jQuery(document).ready(function(){
            for(var i in cats) {
              jQuery(".category-filter input[type=\"checkbox\"][value=\""+cats[i]+"\"]").attr("checked", true);
            }
            jQuery(".category-filter input[type=\"checkbox\"]").click(function(e){

               if ( jQuery(e.target).attr("checked") )
                    jQuery(e.target).parents(".pin-box").children("input").prop("checked", true);

            })
        });</script>';
    //  }

}

/** Third tab - Shortcode */
function newswire_newsroom_shortcodes() {
    //echo '<h3>All Shortcodes information goes here</h3>';
    echo sprintf('<iframe src="%s" width="900" height="1500"></iframe>', plugins_url('includes/iframe-shortcodes-help.php', dirname(__FILE__)));
}

function newswire_settings_textarea($args) {
    extract($args);
    $option = newswire_options();
    echo '<textarea rows="10" cols="100" name="newswire_options[' . $key . ']">' . $option[$key] . '</textarea>';

}
/**
 * From do_settings_sections wp core funtion
 * no available hoook or filters to insert wrapper between settings section, so have to improvise
 * our own function that basically do the same thing
 *
 */
function do_newswire_settings_sections($page) {

    global $wp_settings_sections, $wp_settings_fields;

    if (!isset($wp_settings_sections[$page])) {
        return;
    }

    foreach ((array) $wp_settings_sections[$page] as $section) {
        if ($section['title']) {
            echo "<h3>{$section['title']}</h3>\n";
        }

        if ($section['callback']) {
            call_user_func($section['callback'], $section);
        }

        if (!isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section['id']])) {
            continue;
        }

        do_action($section['id'] . '_begin', $section['id']);

        echo '<table class="form-table">';
        do_settings_fields($page, $section['id']);
        echo '</table>';
        do_action($section['id'] . '_end', $section['id']);
    }
}


/****************************************************************************************************************
 * 
 * TABS -Create the settings page
 *
 ****************************************************************************************************************/
/**
 * Creat wrapper for newswire settings tabs
 */
add_action('newswire-settings-general_begin', 'newswire_settings_tabs');
add_action('newswire-settings-newsroom_begin', 'newswire_settings_tabs');
add_action('newswire-settings-css_begin', 'newswire_settings_tabs');
add_action('newswire-settings-shortcodes_begin', 'newswire_settings_tabs');
add_action('newswire-settings-categories_begin', 'newswire_settings_tabs');
add_action('newswire-settings-rss_begin', 'newswire_settings_tabs');


add_action('newsroom-settings-general_begin', 'newswire_settings_tabs');
add_action('pressroom-settings-general_begin', 'newswire_settings_tabs');
add_action('pressroom-settings-rss_begin', 'newswire_settings_tabs');
add_action('pressroom-settings-css_begin', 'newswire_settings_tabs');
function newswire_settings_tabs($section_id) {
    echo sprintf('<div id="%s">', $section_id);
}

add_action('newswire-settings-general_end', 'newswire_settings_tabs_end');
add_action('newswire-settings-newsroom_end', 'newswire_settings_tabs_end');
add_action('newswire-settings-css_end', 'newswire_settings_tabs_end');
add_action('newswire-settings-rss_end', 'newswire_settings_tabs_end');
add_action('newswire-settings-categories_end', 'newswire_settings_tabs_end');
add_action('newswire-settings-shortcodes_end', 'newswire_settings_tabs_end');
add_action('newsroom-settings-general_end', 'newswire_settings_tabs_end');
add_action('pressroom-settings-general_end', 'newswire_settings_tabs_end');
add_action('pressroom-settings-rss_end', 'newswire_settings_tabs_end');
add_action('pressroom-settings-css_end', 'newswire_settings_tabs_end');
function newswire_settings_tabs_end() {
    echo '</div>';
}

/** add section callbacks */
function newswire_option_api_section_handler() {}
function newswire_other_option_handler() {}
function newswire_settings_css_section() {}
function newswire_supported_post_type_handler() {}
function newswire_localnewsroom_option_handler() {}
function newswire_shortcodes_info() {}
function newswire_settings_pressroom_css_section() {}
function newswire_settings_pressroom_rss_section() {}
function newswire_settings_section() {}
function newswire_newsroom_settings_section() {}




//Light version
add_action('newswire_settings_tabs', 'newswire_settings_tabs_header');
function newswire_settings_tabs_header(){

    ?><li class="newswire-tab "><a href="#newswire-settings-general"><?php  _e('API', 'newswire');?></a></li>
       <li class="newswire-tab "><a href="#pressroom-settings-general"><?php _e('PressRoom Settings', 'newswire');?></a></li>
       <li class="newswire-tab "><a href="#pressroom-settings-css"><?php     _e('CSS', 'newswire');?></a></li>   
    <?php
}

/**
* Newswire plugin Main Settings Page
*/
function newswire_settings_page_callback() {

//    newswire_validate_api_account();

    $options = newswire_options();

    ?><div class="wrap" id="newswire-settings">

        <div id="icon-options-general" class="icon32"><br></div>

        <h2> <?php echo apply_filters('newswire_plugin_setting_title',__('PressRoom by Newswire Settings', 'newswire'))?></h2>

        <p class="description"> <?php echo apply_filters('newswire_plugin_setting_description', __('Customize PressRoom.', 'newswire')) ?></p>

        <br/>
        <ul class="newswire-tabs">
            <?php
                if ( function_exists('newswire_settings_tab_header')):
                    newswire_settings_tab_header();
                else:
                    do_action('newswire_settings_tabs');
                endif;
            ?>
        </ul>
        <form action="options.php" method="post">

        <?php
        /**
             * Needed to do this so not to overwrite the flag
             * @todo - find a better way  probably separate it from settings array
             */
            settings_fields('newswire_options');
            echo '<input type="hidden" name="newswire_options[api_validated]" value="' . $options['api_validated'] . '">';

        ?>
            <?php //do_newswire_settings_sections('newswire');?>

            <?php do_newswire_settings_sections('newsroom-settings');?>

            <?php submit_button();?>

        </form>

    </div>
    <?php

} //end newswire_settings_page_callback



if( !function_exists('newswire_tab_settings_content')):
/**
 * 
 * Settings Tab Content
 */
add_action('admin_init', 'newswire_tab_settings_content');
function newswire_tab_settings_content() {

    # Register our setting so that $_POST handling is done for us and
    # our callback function just has to echo the <input>
    # callback validator is being called for each input
    register_setting('newswire_options', 'newswire_options', 'newswire_options_validator');

    //validate_newswire_api();
    //add_settings_field( $id, $title, $callback, $page, $section, $args );
    
    $page = 'newsroom-settings';


//FIRST TAB
    // Add the section to reading settings so we can add our
    // fields to it
    $section = 'newswire-settings-general';
    $args = null;
    add_settings_section($section, '', 'newswire_option_api_section_handler', $page);
        //  add_settings_field( $id, $title, $callback, $page, $section, $args );
        add_settings_field($id = 'newswire_api_email', $title = 'Newswire API Email', $callback = 'newswire_option_email', $page, $section, $args);
        add_settings_field($id = 'newswire_api_key', $title = 'Newswire API KEY', $callback = 'newswire_option_api_key_callback', $page, $section, $args);
        //add_settings_field($id = 'newswire_api_secret', $title = 'Newswire API secret key', $callback = 'newswire_option_api_secret', $page, $section, $args);
        add_settings_field($id = 'newswire_api_validate', $title = '', $callback = 'newswire_option_api_validate_callback', $page, $section, $args);
        
    

# Pressroom settings page
    $section = 'pressroom-settings-general';
    
    add_settings_section($section, '', 'newswire_settings_section', $page);
    
        add_settings_field($id = 'newswire-pressroom-block-content-settings'
            , $title = __('Block Body', 'newswire')
            , $callback = 'newswire_pressroom_pin'
            , $page
            , $section
            , $args = array('class' => 'toggle-settings', 'field' => 'pressroom_styles', 'placeholder' => __('or Enter URL for background image i.e http://myco.com/bg.png', 'newswire'))
        );

        add_settings_field($id = 'newswire-pressroom-block-header-settings'
            , $title = __('Block Header', 'newswire')
            , $callback = 'newswire_pressroom_pinheader'
            , $page
            , $section
            , $args = array('class' => 'toggle-settings', 'field' => 'pressroom_styles', 'placeholder' => __('or Enter URL for background image i.e http://myco.com/bg.png', 'newswire'))
        );

        //add pinfooter settings
        add_settings_field($id = 'newswire-pressroom-block-footer-settings'
            , $title = __('Block Footer', 'newswire')
            , $callback = 'newswire_pressroom_pinfooter'
            , $page
            , $section
            , $args = array('class' => 'toggle-settings', 'field' => 'pressroom_styles', 'placeholder' => __('or Enter URL for background image i.e http://myco.com/bg.png', 'newswire'))
        );

        //add reset button
        add_settings_field($id = 'newswire-pressroom-reset-button'
            , $title = '' // __('Upload Background', 'newswire')
            , $callback = 'newswire_pressroom_resetbutton'
            , $page
            , $section
            , $args = array('class' => 'toggle-settings', 'field' => 'pressroom_styles', 'placeholder' => __('or Enter URL for background image i.e http://myco.com/bg.png', 'newswire'))
        );


    
// SEVENTH TAB - CUSTOM CSS
    //css section
    $section = 'pressroom-settings-css';
    add_settings_section($section, '', 'newswire_settings_pressroom_css_section', $page);
        add_settings_field('pressroom-settings-css', '', 'newswire_settings_input', $page, $section, $args = array('type' => 'textarea', 'field' => 'pressroom_custom_css'));

}//end admin_init hook
endif;


if ( !function_exists('newswire_options_validator')):
/**
 * Validate Settings Data 
 *
 * @param $input mix array
 * @return void
 */
function newswire_options_validator($input) {
    global $pagenow;
    
    if ( $pagenow == 'edit' && isset($_GET['page']) && $_GET['page'] == 'newsroom-settings' ) return $input;

    //Reset style
    if ( !empty($_POST['reset_style']) ) {
        foreach ($_POST['reset_style'] as $field) {
            $input[$field] = array();
        }
    }
    if (!isset($input['include_categories'])) {
        $input['include_categories'][] = array();
    }

    if (isset($input['pressroom_page_title'])) {
        if ($input['pressroom_theme'] == 'current_theme') {
            update_post_meta(intval($input['pressroom_page_template']), '_wp_page_template', 'pressroom-template-current_theme.php');
        } else {
            update_post_meta(intval($input['pressroom_page_template']), '_wp_page_template', 'pressroom-template-standard.php');
        }
    }

    $options = newswire_options();

    if (empty($options)) {
        $input = wp_parse_args($input, newswire_config('', 'settings'));
    } else {
        //handle checkboxes fields , failing without default values
        $input = wp_parse_args($input, array('force_submission' => 0, 'article_submission_lock' => 0, 'newsroom_show_featured_content' => 0, 'newsroom_show_category_nav' => 0, 'newsroom_show_likes' => 0, 'newsroom_show_comments' => 0, 'newsroom_show_author' => 0));
        $input = wp_parse_args($input, $options);
    }
    /*
    if ( !isset($_POST['newswire_options']['supported_post_types']) ) {
    $input['supported_post_types'] = array('pr');
    } else {
    $input['supported_post_types'] = array_merge(array('pr'), $input['supported_post_types']);
    }*/
    $input['supported_post_types'] = array('pr');

    //merge back newswire_user key
    // if ( !empty($options['newswire_user'] ) )
    // $input['newswire_user'] = $options['newswire_user'];

    //import array to variables
    extract($input);

    //validate email
    if (!is_email(sanitize_email($input['newswire_api_email']))) {

        // validate api key and email here remotely
        add_settings_error($setting = 'newswire_options', $code = 'invalid-email-newswire-options', $message = 'Email address doesn\'t seems to be valid', $type = 'error');

        return $input;
    }
    //blank api keys and email
    if ($input['newswire_api_key'] == '') {
        add_settings_error($setting = 'newswire_options', $code = 'newswire-blank-apikey', $message = 'API key is required', $type = 'error');

        return $input;
    }
    if ($input['newswire_api_email'] == '') {

        add_settings_error($setting = 'newswire_options', $code = 'newswire-blank-email', $message = 'Email address is required', $type = 'error');

        return $input;
    }
    /*if ($input['newswire_api_secret'] == '') {

        add_settings_error($setting = 'newswire_options', $code = 'newswire-blank-secret', $message = 'Secret key is required', $type = 'error');

        return $input;
    }*/

    //can we validate here?
    //validate_newswire_api();

    return $input;

}
endif;

if ( !function_exists('newswire_pressroom_resetbutton')):
/**
 * Create reset button for page styles
 */
function newswire_pressroom_resetbutton($args) {

    echo '<div class="toggle-settings ' . $args['field'] . '">';
    echo '<input type="checkbox" name="reset_style[]" value="' . $args['field'] . '"> Reset Styles ';
    echo '</div>';
    //echo '<button class="button" id="reset-pressroom-settings" value="reset">Reset Settings</button>';
    //echo '<input type="submit" class="button" id="reset-pressroom-settings" value="Reset Style"> ';
}
endif;


if ( !function_exists('newswire_pressroom_pinheader')):
/**
* Block header style
*/
function newswire_pressroom_pinheader($args) {

    $options = newswire_options();

    $options[$args['field']] = $index = wp_parse_args( $options[$args['field']], newswire_config('pressroom_styles'));

    $index = $options[$args['field']];

    echo '<div class="toggle-settings ' . $args['field'] . '" >';


    if ( empty($index['header']['bg']) ) $index['header']['bg'] = '_none';
    echo "<table>";
    echo '<tr style="background-color: #eaeaea;">';
    echo '<td class="padded">';
    printf('<div class="padded"> <input type="radio" name="newswire_options[%s][header][bg]" %s value="_none"> No Background</div>', $args['field'], newswire_chk_selected( ($index['header']['bg'] =='_none')  ? 1: 0 ));
    printf('<div class="padded"> <input type="radio" name="newswire_options[%s][header][bg]" %s value="_color_active"> Background Color <br>', $args['field'], newswire_chk_selected( ($index['header']['bg'] =='_color_active')  ? 1: 0 ));

    printf('<div class="color-picker"> <input name="newswire_options[%s][header][bg_color]" class="wp-color-picker " type="text" value="%s" ></div>', $args['field'], $index['header']['bg_color']);
    echo ' </div>';
    printf('<div class="padded"><input type="radio" name="newswire_options[%s][header][bg]" %s value="_image_active"> Background Image</div>', $args['field'], newswire_chk_selected( ($index['header']['bg'] =='_image_active')  ? 1: 0  ));
    if ($options[$args['field']]['header']['bg_image_url']) {
        $preview = sprintf('<img src="%s" border="0" width="300" /> ', $options[$args['field']]['header']['bg_image_url']);
    } else {
        $preview = '';
    }

    if ( empty($index['header']['bg_repeat']) ) $index['header']['bg_repeat'] = '';
    echo '<div class="bg-options">';
    printf('<div class="preview-image padded">%s</div><input name="newswire_options[%s][header][bg_image_url]" type="text" value="%s" size="30" placeholder="%s"> <a class="padded button secondary newswire-media-upload">Select Image</a> <a class="padded remove-uploaded-image button">Remove Image</a>',
        $preview, $args['field'], $options[$args['field']]['header']['bg_image_url'], $args['placeholder']);
    printf('<div class="padded"> <input type="radio" name="newswire_options[%s][header][bg_repeat]" value="repeat" %s> Repeat </div>', $args['field'], newswire_radio_selected($index['header']['bg_repeat'], 'repeat'));
    printf('<div class="padded"> <input type="radio" name="newswire_options[%s][header][bg_repeat]" value="repeat-x" %s> Repeat-X </div>', $args['field'], newswire_radio_selected($index['header']['bg_repeat'], 'repeat-x'));
    printf('<div class="padded"> <input type="radio" name="newswire_options[%s][header][bg_repeat]" value="repeat-y" %s> Repeat-Y </div>', $args['field'], newswire_radio_selected($index['header']['bg_repeat'], 'repeat-y'));
    printf('<div class="padded"> <input type="radio" name="newswire_options[%s][header][bg_repeat]" value="no-repeat" %s> No-Repeat </div>', $args['field'], newswire_radio_selected($index['header']['bg_repeat'], 'no-repeat'));
    echo "</div>";

    echo '</td></tr>';
    echo '</table>';
}
endif;


if ( !function_exists('newswire_pressroom_pinfooter')):
/**
* Block footer settings
*/
function newswire_pressroom_pinfooter($args) {

    $options = newswire_options();

    $options[$args['field']] = $index = wp_parse_args( $options[$args['field']], newswire_config('pressroom_styles'));

    $index = $options[$args['field']];

    echo '<div class="toggle-settings ' . $args['field'] . '" >';

    if ( empty($index['footer']['bg']) ) $index['footer']['bg'] = '_none';

    echo "<table>";
    echo '<tr style="background-color: #eaeaea;">';
    echo '<td class="padded">';
    printf('<div class="padded"> <input type="radio" name="newswire_options[%s][footer][bg]" %s value="_none"> No Background</div>', $args['field'], newswire_chk_selected( ($index['footer']['bg'] =='_none')  ? 1: 0 ));
    printf('<div class="padded"> <input type="radio" name="newswire_options[%s][footer][bg]" %s value="_color_active"> Background Color <br>', $args['field'], newswire_chk_selected( $index['footer']['bg'] =='_color_active' ? 1: 0 ));

    printf('<div class="color-picker"> <input name="newswire_options[%s][footer][bg_color]" class="wp-color-picker " type="text" value="%s" ></div>', $args['field'], $index['footer']['bg_color']);
    echo ' </div>';
    printf('<div class="padded"><input type="radio" name="newswire_options[%s][footer][bg]" %s value="_image_active"> Background Image</div>', $args['field'], newswire_chk_selected($index['footer']['bg'] =='_image_active' ? 1 : 0));
    if ($options[$args['field']]['footer']['bg_image_url']) {
        $preview = sprintf('<img src="%s" border="0" width="300" /> ', $options[$args['field']]['footer']['bg_image_url']);
    } else {
        $preview = '';
    }
if ( empty($index['footer']['bg_repeat']) ) $index['footer']['bg_repeat'] = '';
    echo '<div class="bg-options">';
    printf('<div class="preview-image padded">%s</div><input name="newswire_options[%s][footer][bg_image_url]" type="text" value="%s" size="30" placeholder="%s"> <a class="padded button secondary newswire-media-upload">Select Image</a> <a class="padded remove-uploaded-image button">Remove Image</a>',
        $preview, $args['field'], $options[$args['field']]['footer']['bg_image_url'], $args['placeholder']);
    printf('<div class="padded"> <input type="radio" name="newswire_options[%s][footer][bg_repeat]" value="repeat" %s> Repeat </div>', $args['field'], newswire_radio_selected($index['footer']['bg_repeat'], 'repeat'));
    printf('<div class="padded"> <input type="radio" name="newswire_options[%s][footer][bg_repeat]" value="repeat-x" %s> Repeat-X </div>', $args['field'], newswire_radio_selected($index['footer']['bg_repeat'], 'repeat-x'));
    printf('<div class="padded"> <input type="radio" name="newswire_options[%s][footer][bg_repeat]" value="repeat-y" %s> Repeat-Y </div>', $args['field'], newswire_radio_selected($index['footer']['bg_repeat'], 'repeat-y'));
    printf('<div class="padded"> <input type="radio" name="newswire_options[%s][footer][bg_repeat]" value="no-repeat" %s> No-Repeat </div>', $args['field'], newswire_radio_selected($index['footer']['bg_repeat'], 'no-repeat'));
    echo "</div>";

    echo '</td></tr>';
    echo '</table>';

}
endif;


if ( !function_exists('newswire_pressroom_pin')):
/**
 * Block body style settings
 *
 * @params @args array
 */
function newswire_pressroom_pin($args) {

    $options = newswire_options();

    //if ( $options[$args['field']] ) return;

    $options[$args['field']] = $index = wp_parse_args( $options[$args['field']], newswire_config('pressroom_styles'));

    //wp_parse_args($index, $default = array('body' => array('bg'=> '', 'bg_color'=> '', 'border'=> '')) );
    echo '<div class="toggle-settings ' . $args['field'] . '" >';
    echo '<table>';
    echo '<tr style="background-color: #eaeaea;">';
    echo '<td class="padded">';

    if ( empty($index['body']['bg']) ) $index['body']['bg'] = '_none';

    printf('<div class="padded"> <input type="radio" name="newswire_options[%s][body][bg]" %s value="_none"> No Background</div>', $args['field'], newswire_chk_selected( $index['body']['bg'] == '_none' ? 1: 0 ));
   
    printf('<div class="padded"> <input type="radio" name="newswire_options[%s][body][bg]" %s value="_color_active"> Background Color <br>', $args['field'], newswire_chk_selected( $index['body']['bg'] == '_color_active' ?  1 : 0));

    printf('<div class="color-picker"> <input name="newswire_options[%s][body][bg_color]" class="wp-color-picker " type="text" value="%s" ></div>', $args['field'], $index['body']['bg_color']);
   
    echo ' </div>';
    printf('<div class="padded"><input type="radio" name="newswire_options[%s][body][bg]" %s value="_image_active"> Background Image</div>', $args['field'], newswire_chk_selected($index['body']['bg'] == '_image_active' ? 1: 0));
    if ($options[$args['field']]['body']['bg_image_url']) {
        $preview = sprintf('<img src="%s" border="0" width="300" /> ', $options[$args['field']]['body']['bg_image_url']);
    } else {
        $preview = '';
    }
    if ( empty($index['body']['bg_repeat'])  ) $index['body']['bg_repeat'] = '';

    echo '<div class="bg-options">';
    printf('<div class="preview-image padded">%s</div><input name="newswire_options[%s][body][bg_image_url]" type="text" value="%s" size="30" placeholder="%s"> <a class="padded button secondary newswire-media-upload">Select Image</a> <a class="padded remove-uploaded-image button">Remove Image</a>',
        $preview, $args['field'], $options[$args['field']]['body']['bg_image_url'], $args['placeholder']);
    printf('<div class="padded"> <input type="radio" name="newswire_options[%s][body][bg_repeat]" value="repeat" %s> Repeat </div>', $args['field'], newswire_radio_selected($index['body']['bg_repeat'], 'repeat'));
    printf('<div class="padded"> <input type="radio" name="newswire_options[%s][body][bg_repeat]" value="repeat-x" %s> Repeat-X </div>', $args['field'], newswire_radio_selected($index['body']['bg_repeat'], 'repeat-x'));
    printf('<div class="padded"> <input type="radio" name="newswire_options[%s][body][bg_repeat]" value="repeat-y" %s> Repeat-Y </div>', $args['field'], newswire_radio_selected($index['body']['bg_repeat'], 'repeat-y'));
    printf('<div class="padded"> <input type="radio" name="newswire_options[%s][body][bg_repeat]" value="no-repeat" %s> No-Repeat </div>', $args['field'], newswire_radio_selected($index['body']['bg_repeat'], 'no-repeat'));
    echo "</div>";

    echo '</td></tr>';
    echo '<tr style="background-color: #eaeaea;">';
    echo '<td class="padded"><table><tr><td valign="top" style="vertical-align: top" class="">';

    //Border
     if ( empty($index['body']['border'])  ) $index['body']['border'] = '';
    printf('<div class="padded"><input type="checkbox" name="newswire_options[%s][body][border]" value="1" %s> <strong>Border</strong>', $args['field'], newswire_chk_selected($index['body']['border']));

    echo '<div class="bg-options">';
    //echo sprintf('<div class="color-picker">Color: <input name="newswire_options[%1$s]" class="wp-color-picker %4$s" type="text" value="%2$s" placeholder="%3$s"></div>', $args['field'], $option[$args['field']] , $args['placeholder'], $args['class']);
    //directoinal offset
    echo '<div class="padded alignright">Corner Radius ';
    printf('<div class="padded ">Top Left: <input type="text" size="2" name="newswire_options[%s][body][top_radius]" value="%s">px </div>', $args['field'], $index['body']['top_radius']);
    printf('<div class="padded">Top Right: <input type="text" size="2" name="newswire_options[%s][body][right_radius]" value="%s">px   </div>', $args['field'], $index['body']['right_radius']);
    printf('<div class="padded">Bottom Right: <input type="text" size="2" name="newswire_options[%s][body][bottom_radius]" value="%s">px    </div>', $args['field'], $index['body']['bottom_radius']);
    printf('<div class="padded">Bottom Left: <input type="text" size="2" name="newswire_options[%s][body][left_radius]" value="%s">px    </div>', $args['field'], $index['body']['left_radius']);

    echo '<div class="padded">Thickness ';
    printf('<div class="padded"><input type="text" size="2" name="newswire_options[%s][body][border_thickness]" value="%s">px  </div>', $args['field'], $index['body']['border_thickness']);

    echo "</div>";

    echo '<div class="padded">';
    printf('<div class="padded"><input name="newswire_options[%s][body][border_color]" class="wp-color-picker " type="text" value="%s" placeholder="%s"></div>', $args['field'], $index['body']['border_color'], $args['placeholder']);

    echo "</div>";

    echo '</td>';

    echo '';
    echo '<td class="padded "  style="vertical-align: top">';
//Box shadow
    if ( empty($index['body']['box_shadow'])  ) $index['body']['box_shadow'] = '';
    printf('<div class="padded"><input type="checkbox" name="newswire_options[%s][body][box_shadow]" value="1" %s> <strong>Box Shadow</strong>', $args['field'], newswire_chk_selected($index['body']['box_shadow']));

    echo '<div class="bg-options">';
    //echo sprintf('<div class="color-picker">Color: <input name="newswire_options[%1$s]" class="wp-color-picker %4$s" type="text" value="%2$s" placeholder="%3$s"></div>', $args['field'], $option[$args['field']] , $args['placeholder'], $args['class']);
    //directoinal offset
    //box-shadow: none|h-shadow v-shadow blur spread color |inset|initial|inherit;
    echo '<div class="padded alignright">';
    printf('<div class="padded">h-shadow: <input type="text" size="2" name="newswire_options[%s][body][h_shadow]" value="%s">px     </div>', $args['field'], $index['body']['h_shadow']);
    printf('<div class="padded">v-shadow: <input type="text" size="2" name="newswire_options[%s][body][v_shadow]" value="%s">px  </div>', $args['field'], $index['body']['v_shadow']);
    printf('<div class="padded">Spread: <input type="text" size="2" name="newswire_options[%s][body][spread_shadow]" value="%s">px   </div>', $args['field'], $index['body']['spread_shadow']);
    printf('<div class="padded">Blur: <input type="text" size="2" name="newswire_options[%s][body][blur_shadow]" value="%s">px </div>', $args['field'], $index['body']['blur_shadow']);
    printf('<div class="padded">Opacity: <input type="text" size="2" name="newswire_options[%s][body][opacity_shadow]" value="%s"> 0.0 - 1 </div>', $args['field'], $index['body']['opacity_shadow']);
    echo "</div>";

    echo '<div class="padded">';
    printf('<div class="padded"><input name="newswire_options[%s][body][shadow_color]" class="wp-color-picker " type="text" value="%s" placeholder="%s"></div>', $args['field'], $index['body']['shadow_color'], $args['placeholder'], $args['class']);

    echo "</div></td>";

    echo '</tr></table></td></tr>';
    echo '</table>';
}
endif;
