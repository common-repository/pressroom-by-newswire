<?php
/**
*
*/

if ( !function_exists('newswire_metabox_pin_as_quote')):
/**
* Add metabox for pin_as_content
*/
function newswire_metabox_pin_as_quote() {

    add_meta_box(
        $id = 'newswire-pressroom-quote', 
        $title = 'Enter Quote source, i.e DE Brown, Neswire Network, LTD', 
        $callback = 'newswire_html_quote_details', 
        $screen = 'pin_as_quote', $context = 'advanced', 
        $priority = 'core', 
        $args = null
    );

}

/**
* Metabox handler
*/
function newswire_html_quote_details() {

    global $post;

    $post_meta = wp_parse_args( get_post_meta($post->ID, NEWSWIRE_POST_META_CUSTOM_FIELDS, $single = true), array('quote_source'=> '', 'quote_source_url'=> '') );
        ?><div class="widefat">

                <textarea rows="6" style="width: 100%" name="newswire_data[quote_source]"><?php echo $post_meta['quote_source']?></textarea><br/>
                 <div class="description">Quote Source Link:</div>
                <input style="width: 100%" name="newswire_data[quote_source_url]" type="text" value="<?php echo $post_meta['quote_source_url']?>">
               

            </div>
   <?php
        wp_nonce_field( NEWSWIRE_POST_META_NONCE, NEWSWIRE_POST_META_NONCE);

}
endif;

  