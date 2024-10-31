<?php
/**
*
*/
function newswire_stored_company_profile() {
    return null;
}

if ( !function_exists('newswire_metabox_pin_as_contact')):
/**
* Add metabox for pin_as_content
*/
function newswire_metabox_pin_as_contact() {

    add_meta_box( 
        $id       = 'newswire-pressroom-contact', 
        $title    = 'Contact Details', 
        $callback = 'newswire_html_contact_details', 
        $screen   = 'pin_as_contact', 
        $context  = 'advanced', 
        $priority = 'core', 
        $args     = null
    );
}

/**
* Metabox handler
*/
function newswire_html_contact_details() {

    global $post, $newswire_config;

    $post_meta = get_post_meta($post->ID, NEWSWIRE_POST_META_CUSTOM_FIELDS, $single = true);

    ?><div><?php
        wp_nonce_field( NEWSWIRE_POST_META_NONCE, NEWSWIRE_POST_META_NONCE );
    ?><div>
            <div id="nwire-company-info" class=""  >
                <p class="howto"><?php echo $newswire_config['tooltip']['pressroom_contact_pin'] ?></p>
                <?php  
                    echo newswire_generate_meta_box_fields( 
                        newswire_post_meta_box_elements('pressroom_contact_pin'), $post_meta
                    );
                ?>
            </div>
        </div>
    </div>
<?php
}
endif;