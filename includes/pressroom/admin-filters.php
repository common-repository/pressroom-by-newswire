<?php
if ( !defined('ABSPATH') ) {
    exit;
}
/** 
* return submission type by post_id
* @param $post_id int
* @return string  newswire or empty string
*/
function newswire_submission_type($post_id) {

    $data = newswire_data($post_id);
    $type = !empty($data['newswire_submission']) ? $data['newswire_submission'] : 'newswire';
    //var_dump($type);
    return $type;
}


/**
* Customize submitdiv. Added more checkboxes 
* deprecated - will remove soon
*/
if ( !function_exists('newswire_metabox_pr_checkboxes')):
//add_action('post_submitbox_misc_actions', 'newswire_metabox_pr_checkboxes');
/**
 * Toggle content syndication checkboxes
 */
function newswire_metabox_pr_checkboxes() {

    global $post;

    $type = get_post_type( $post );

    if  ( 'pr' !=  $type) return;

    if (!current_user_can('administrator')) {
        add_meta_box($id = 'nwire-post-meta-side', $title = 'Publication Options', $callback = 'newswire_metabox_prcheckboxes', $screen, $context = 'side', $priority = 'core', $callback_args = null);
        return;
    }

    echo '<div id="newswire-publication-options">';
    
        
    $sync = get_post_meta($post->ID, 'freesites_submission', true );
    
    $howto = newswire_config('freesites_submission', 'tooltip');
    $howtoimg = sprintf('<img src="' . NEWSWIRE_PLUGIN_URL . 'assets/images/help.png" title="%s"> ', $howto);

//exit;
    // title="' .  . '" />';
    /* light version 
    if ( intval($sync) == 2 ) {

        printf('<p class=""><input type="checkbox" name="newswire_data[newswire_submission]" value="freesites" checked="checked"> Publish on 5 News Sites (Free) %s</a> </p>',  $howtoimg);
    
    } else {
        
        if ( newswire_submission_type($post->ID) == 'freesites' ) {
            printf( '<p class=""><input type="checkbox" name="newswire_data[newswire_submission]" value="freesites" checked="checked"> Publish on 5 News Sites (Free) %s</a></p>',  $howtoimg);
        } else {
            printf( '<p class=""><input type="checkbox" name="newswire_data[newswire_submission]" value="freesites" > Publish on 5 News Sites (Free) %s</a></p>', $howtoimg);
        }

        
    } */

    if ( intval($sync) == 2 ) {

        //echo '<p><input type="radio" name="newswire_data[newswire_submission]" value="freesites" checked="checked"> Publish on 5 New Sites(Free) </p>';
        printf('<p class=""><input type="checkbox" name="newswire_data[newswire_submission]" value="freesites" checked="checked"> Publish on 5 News Sites (Free) %s</a> </p>',  $howtoimg);
    } else {
        
        if ( newswire_submission_type($post->ID) == 'freesites' ) {
            //echo '<p><input type="checkbox" name="newswire_data[newswire_submission]" value="freesites" checked="checked"> Publish on 5 New Sites(Free) </p>';
            printf( '<p class=""><input type="checkbox" name="newswire_data[newswire_submission]" value="freesites" checked="checked"> Publish on 5 News Sites (Free) %s</a></p>',  $howtoimg);
        } else {
            //echo '<p><input type="checkbox" name="newswire_data[newswire_submission]" value="freesites" > Publish on 5 New Sites(Free) </p>';
            printf( '<p class=""><input type="checkbox" name="newswire_data[newswire_submission]" value="freesites" > Publish on 5 News Sites (Free) %s</a></p>', $howtoimg);
        }

        if ( newswire_submission_type($post->ID) =='newswire'  ) {
            //if newswire_submission === 'disable'
            //echo '<input type="hidden" name="disable_sumbmission" id="input_disable_submission" value="newswire" >';
            
            echo '<p><input type="checkbox" name="newswire_data[newswire_submission]" id="" value="newswire" checked="checked"> Publish on Newswire</p>';
        } else {
            //echo '<input type="hidden" name="disable_sumbmission" id="input_disable_submission" value="newswire" >';
            echo '<p><input type="checkbox" name="newswire_data[newswire_submission]" id=""  value="newswire"> Publish on Newswire</p>';
        }

    }

    



    if ( make_blog_copy($post->ID) ) {
        echo '<p><input type="checkbox" name="newswire_copytoblog" value="1" checked="checked"> Include on blog page</p>';
    } else {
        echo '<p><input type="checkbox" name="newswire_copytoblog" value="1" > Include on blog page</p>';
    }

    //tags to pressroom
    if (has_term('pressroom', 'post_tag', $post)) {
        echo '<p><input type="checkbox" name="include_on_pressroom_page" id="include_on_pressroom_page" value="1" checked="checked"> Include on PressRoom Page</p>';
    } else {
        echo '<p><input type="checkbox" name="include_on_pressroom_page" id="include_on_pressroom_page"  value="1"> Include on PressRoom Page</p>';
    }
    echo "</div>";
    //tag to newsroom

    //var_dump(newswire_data());
}
endif;



if ( !function_exists('newswire_admin_body_class') ):
/**
* Add some namespace for css use admin only
*/
add_filter('admin_body_class', 'newswire_admin_body_class');
function newswire_admin_body_class($classes) {
    if ( is_string($classes) )
        return  " $classes newswire newswire-pressroom ";
}
endif;


/**
* Remove wpautop filter for pr content type
*/
add_action('admin_head', 'newswire_print_icons'); 
function newswire_print_icons(){
    global $typenow, $pagenow;
   /* $all_plugins = get_plugins();
    echo '<pre>';
    var_dump($all_plugins);
    echo '</pre>';
    exit;*/
    
    //remove quick edit action
    if (is_admin()) {
        add_filter('post_row_actions','remove_quick_edit',10,2);
        //removes quick edit from custom post type list
        function remove_quick_edit( $actions ) {
            global $post;

            if( in_array($post->post_type , array('pr', 'pin_as_contact', 'pin_as_embed', 'pin_as_image', 'pin_as_link', 'pin_as_quote', 'pin_as_social', 'pin_as_text', 'pressroom') ) ) {
                unset($actions['inline hide-if-no-js']);
            }
            return $actions;
        }
    }

    if  ( 'pr' == $typenow ) {       
        //remove_filter ('the_content',  'wpautop');
        //remove_filter ('comment_text', 'wpautop');
    }

   //maybe_autocreate_pressroom_content();
    ?>
    <!--[if lt IE 8]><!-->
    <link rel="stylesheet" href="<?php echo plugin_dir_url( dirname(dirname(__FILE__)));?>/assets/ie7/ie7.css">
    <script type="text/javascript">
    <?php if ( $typenow == 'pressroom' && $pagenow == 'edit.php' && current_user_can('manage_options') || current_user_can('author')  || current_user_can('editor') ): ?>
        var _pressroom_config = {'newswire_ordering_blocks': true };
        <?php else: ?>
        var _pressroom_config = {'newswire_ordering_blocks': false };
    <?php endif; ?>
    </script>
    <!--<![endif]-->
    <?php


}