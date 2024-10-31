<?php
/**
*
*/

if ( !function_exists('newswire_metabox_pin_as_link')):
/**
* Add metabox for pin_as_content
*/
function newswire_metabox_pin_as_link() {

    add_meta_box(
        $id = 'newswire-pressroom-link', 
        $title = 'Add Links', 
        $callback = 'newswire_html_metabox_link_details', 
        $screen = 'pin_as_link', $context = 'advanced', 
        $priority = 'core', 
        $args = null
    );

}
endif; 

if ( !function_exists('newswire_html_metabox_link_details')) :
/**
*
* Metabox handler
*/
function newswire_html_metabox_link_details() {

   global $post;
  
        $post_meta = wp_parse_args( get_post_meta($post->ID, NEWSWIRE_POST_META_CUSTOM_FIELDS, $single = true), array("text"=> null) );

        ?><div>
            <?php
                wp_nonce_field( NEWSWIRE_POST_META_NONCE, NEWSWIRE_POST_META_NONCE);
            ?>
            <div>
                <div id="" class=""  >
                <table class="form-table" id="pin_as_links_table">
                      
                            <colgroup>
                        <col>
                        <col>
                        <col class="arrow-small-right">
                        <col>
                      </colgroup>
                    <thead>
                        <tr>
                        <th style="width: 4%; padding-left:10px;" nowrap="true"><input type="checkbox" id="pin_as_link-select-all"> <span style="font-size:11px"><?php _e('Select All', 'newswire') ?></span></th>
                      
                        <th style="text-align: center"><?php _e('Text', 'newswire') ?></th>
                        
                        <th style="text-align: center"><?php _e('URL', 'newswire') ?></th>
                        </tr>
                    </thead>
            <tbody><?php
            
                $total = count($post_meta['text']);
                if (empty($total)) {
                    $total = 1;
                }

        for ($ctr = 0; $ctr < $total; $ctr++) {?>
                    <tr class="pin_as_link_row">
                        <td style="width: 4%"><input type="checkbox" class="check-list" name="delete_index[]" value="<?php echo $ctr?>"></td>
                        <td><input type="text" name="newswire_data[text][]" id="company_tickers" value="<?php echo (isset($post_meta['text'][$ctr])) ? $post_meta['text'][$ctr] : ''?>" style="width:100%;"></td>
                    
                        <td><input type="text" name="newswire_data[link][]" id="company_tickers" value="<?php echo (isset($post_meta['link'][$ctr])) ? $post_meta['link'][$ctr] : ''?>" style="width:100%;"></td>
                    </tr>
        <?php }//endfor
            
        ?>

                </tbody></table>
                    <a class="button" id="pin_as_link_remove_row">Delete Selected</a>
                    <a class="button button button-primary " id="pin_as_link_add_row">+Add another link</a>
                </div>
            </div>
        </div>
    <?php
}
endif;