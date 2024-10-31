<?php


//specific to pin_as_image
if ( !function_exists('newswire_metabox_image_gallery')):
/**
* add metabox
*/
function newswire_metabox_image_gallery(){

    add_meta_box( 
        $id = 'newswire-pressroom-image', 
        $title = 'Upload Images', 
        $callback = 'newswire_print_imagegallery_metabox', 
        $screen = 'pin_as_image', $context = 'advanced', 
        $priority = 'core', 
        $args = null
    );

    wp_enqueue_script('plupload-all');
    wp_enqueue_script('pin_as_image_js', NEWSWIRE_PLUGIN_ASSETS_URL . '/js/pin_as_image.js', 'jquery', null, true);

}
endif;

if ( !function_exists('newswire_print_imagegallery_metabox')):
function newswire_print_imagegallery_metabox() {


        global $post_ID;

        ?>
        <?php media_upload_form();?>

        <script type="text/javascript">

            var post_id = <?php echo $post_ID;?>, shortform = 3;

            function uploadSuccess(fileObj, serverData) {
                var item = jQuery('#media-item-' + fileObj.id);

                // on success serverData should be numeric, fix bug in html4 runtime returning the serverData wrapped in a <pre> tag
                serverData = serverData.replace(/^<pre>(\d+)<\/pre>$/, '$1');

                // if async-upload returned an error message, place it in the media item div and return
                if ( serverData.match(/media-upload-error|error-div/) ) {
                    item.html(serverData);
                    return;
                } else {
                    jQuery('.percent', item).html( pluploadL10n.crunching );
                }

                prepareMediaItem(fileObj, serverData);
                updateMediaForm();

                //console.log(serverData);
                //console.log(fileObj);
                //pin_as_image.update();
                pin_as_image.prepare_attachment(serverData);
            }
        </script>
        <input type="hidden" name="post_id" id="post_id" value="<?php echo $post_ID;?>" />
        <div id="pin_as_image_attachment"></div>
        <div id="media-items" class="hide-if-no-js"></div>
        <br/>

        <div class="wide">
        <?php 
           

        ?>
        Select Thumbnail Size:
        <select name="newswire_data[thumbnail_size]" >
            <option value="pin_image_size1">300x300</option>
             <option value="pin_image_size2">200x200</option>
              <option value="pin_image_size3">150x200</option>
               <option value="pin_image_size4">300x200</option>
                <option value="pin_image_size5">300x400</option>
               
        </select>
         <p>
        </div>

        <?php



        newswire_metabox_image_attachment_listing();

        wp_reset_query();

         wp_nonce_field( NEWSWIRE_POST_META_NONCE, NEWSWIRE_POST_META_NONCE );
}
endif;


/**
 *  @todo: ?
 */
function newswire_metabox_image_attachment_listing() {

    //require class
    try {
        require_once NEWSWIRE_DIR .'includes/classes/pressroom-attachment-list-table.php';
        $table = new Pressroom_Image_Attachment_List_Table;
        $table->prepare_items();
        $table->display();

    } catch (Exception $e) {
        var_dump($e->getMessages());
    }?>
   <?php    //exit;
}