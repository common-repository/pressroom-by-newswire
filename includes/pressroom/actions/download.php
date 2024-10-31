<?php
use JeroenDesloovere\VCard\VCard;


if ( ! function_exists('newswire_download_and_activate_pro')):
/**
* Create download link for pro version
*/
add_action('load-admin_page_newswire-download-pro', 'newswire_download_and_activate_pro');
function newswire_download_and_activate_pro() {
    
    if ( ! current_user_can('install_plugins') )
        wp_die( __( 'You do not have sufficient permissions to install plugins on this site.' ) );
    
    $plugin = 'newswirexpress/newswirexpress.php';

    if ( !in_array($plugin, array_keys(get_plugins()) ) ) {

        //download url
        $download_url = 'http://www.newswire.net/newswirexpress/newswirexpress.zip';

            /* download Pro */
        if ( ! function_exists( 'get_plugins' ) )
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        $uploadDir = wp_upload_dir();
        $zip_name = array('newswirexpress', 'newswirexpress.php');


        //get all plugins
        //$all_plugins = get_plugins();
        
        if ( !file_exists( WP_PLUGIN_DIR . '/' . $zip_name[0] ) ) {
            

            $received_content = file_get_contents( $download_url );

            if ( ! $received_content && $valid ) {
            $result['error'] = __( "Failed to download the zip archive. Please, upload the plugin manually", 'newswire' );
        } else {
        
            if ( is_writable( $uploadDir["path"] ) ) {
                $file_put_contents = $uploadDir["path"] . "/" . $zip_name[0] . ".zip";
                if ( file_put_contents( $file_put_contents, $received_content ) ) {
                    @chmod( $file_put_contents, octdec( 755 ) );
                    if ( class_exists( 'ZipArchive' ) ) {
                        $zip = new ZipArchive();
                        if ( $zip->open( $file_put_contents ) === TRUE ) {
                            $zip->extractTo( WP_PLUGIN_DIR );
                            $zip->close();
                        } else {
                            $result['error'] = __( "Failed to open the zip archive. Please, upload the plugin manually", 'newswire' );
                        }
                    } elseif ( class_exists( 'Phar' ) ) {
                        $phar = new PharData( $file_put_contents );
                        $phar->extractTo( WP_PLUGIN_DIR );
                    } else {
                        $result['error'] = __( "Your server does not support either ZipArchive or Phar. Please, upload the plugin manually", 'newswire' );
                    }
                    @unlink( $file_put_contents );
                } else {
                    $result['error'] = __( "Failed to download the zip archive. Please, upload the plugin manually", 'newswire' );
                }
            } else {
                $result['error'] = __( "UploadDir is not writable. Please, upload the plugin manually", 'newswire' );
            }
        }
        }

        

        //deactivate free version plugin
        deactivate_plugins( 'pressroom-by-newswire/pressroom.php' ,true);

        /* activate Pro */
        if ( validate_plugin($plugin) && file_exists( WP_PLUGIN_DIR . '/' . $zip_name[0] ) ) {
        
            if ( is_multisite() && is_plugin_active_for_network( plugin_basename( __FILE__ ) ) ) {
                /* if multisite and free plugin is network activated */
                $active_plugins = get_site_option( 'active_sitewide_plugins' );
                $active_plugins[ $plugin ] = time();
                update_site_option( 'active_sitewide_plugins', $active_plugins );
            } else {
                /* activate on a single blog */
                $active_plugins = get_option( 'active_plugins' );
                array_push( $active_plugins, $plugin );
                update_option( 'active_plugins', $active_plugins );
            }
          
            wp_safe_redirect(admin_url('plugins.php?activate=true') );

            //$result['pro_plugin_is_activated'] = true;
        } elseif ( empty( $result['error'] ) ) {
            $result['error'] = __( "Failed to download the zip archive. Please, upload the plugin manually", 'newswire' );
        }

        if ( $result['error'] ) {
            //add_action('admin_notices' , create_function($result['error'], 'function($message){ echo "<p class=\'updated error\'>$message</p>"; }'));
            wp_die( $result['error'] , 'Install Plugin', $args );
        }

    } else {
        wp_die( sprintf('NewswireXpress Free Pro Version is already downloaded. Go <a href="%s">Here</a> to activate',admin_url('plugins.php')) , 'Install Plugin');
    }

}//end function
endif;

if ( !function_exists('newswire_serve_downloads')):
/**
* Server all download here for pressroom
*/
add_action('init', 'newswire_serve_downloads', 500);
function newswire_serve_downloads() {
    $action = empty($_GET['action']) ? '' : $_GET['action'];

    if ( $action == '') return;

    if ( has_action( 'newswire_'.$action )):
        do_action('newswire_'.$action);
        exit;
    endif;

}
endif;

add_action('newswire_download_contact_details', 'newswire_download_contact_details');
function newswire_download_contact_details() {

    $download_as = empty($_GET['dtype']) ? 'html' : $_GET['dtype'] ;
  

    $id = empty($_GET['id']) ? '' : $_GET['id'] ;

    if ( !intval($id) ) return;
    if (ini_get('zlib.output_compression')) {
        ini_set('zlib.output_compression', 'Off');
    }
    ob_start(); 
    //include template
    $post = get_post( $id );
    $meta = newswire_data($id);
    $filename = sprintf('%s.%s', sanitize_title( get_the_title($id) ) , $download_as);
    //as html, csv or text
    if ( 'txt' == $download_as ) {
        newswire_download_contact_as_txt($post, $meta, $filename);
    }

    if ( 'vcard' == $download_as ) {
         newswire_download_contact_as_vcard($post, $meta, $filename);
        //header('Content-Type: text/vcard');
    }

    if ( 'html' ==  $download_as ) {

        newswire_download_contact_as_html($post, $meta, $filename);
        
    }
    //$content.='teasdf';

}

if ( !function_exists('newswire_download_contact_as_txt')) :
    function newswire_download_contact_as_txt($post, $meta, $filename) {

        if ( isset( $meta['contact_media_id']) && $meta['contact_media_id'])
            $your_img_id = intval($meta['contact_media_id']);
        
        $txt = "";
        $txt .= sprintf("First Name: %s \n", $meta['first_name']);
        $txt .= sprintf("Last Name: %s \n", $meta['last_name']);
        $txt .= sprintf("Position: %s \n", $meta['contact_position']);
        $txt .= sprintf("Company Name: %s \n", $meta['company_name']);
        $txt .= sprintf("Ticker Symbol: %s \n", $meta['company_tickers']);
        $txt .= sprintf("Adress: %s \n", $meta['company_address']);
        $txt .= sprintf("City: %s \n", $meta['first_name']);
        $txt .= sprintf("Country: %s \n", $meta['first_name']);
        $txt .= sprintf("State or Province: %s \n", $meta['first_name']);
        $txt .= sprintf("Postal Code: %s \n", $meta['first_name']);
        $txt .= sprintf("Telephone: %s \n", $meta['first_name']);
        $txt .= sprintf("Email: %s \n", $meta['company_email']);
        $txt .= sprintf("Website: %s \n", $meta['first_name']);
        if ( isset($your_img_id) && $your_img_id ) {
            // Get the image src
            $your_img_src = wp_get_attachment_image_src( $your_img_id, 'pin_as_contact_thumb' );
            $txt .= sprintf("Photo: %s \n", $your_img_src[0]);

        }

        
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename=' . rawurldecode($filename));
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        header('Cache-control: private');
        header('Pragma: private');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-Length: ' . strlen($txt));
        echo $txt;
        ob_get_flush();
        exit;
    }
endif;

if ( !function_exists('newswire_download_contact_as_vcard') ) :
    function newswire_download_contact_as_vcard($post, $meta, $filename) {
        require_once NEWSWIRE_DIR . '/includes/classes/vcard.php';
        
        $vcard = '';

        // define vcard
        $vcard = new VCard();

        // define variables
        $lastname = $meta['last_name'];
        $firstname = $meta['first_name'];
        $additional = '';
        $prefix = '';
        $suffix = '';

        // add personal data
        $vcard->addName($lastname, $firstname, $additional, $prefix, $suffix);

        // add work data
        $vcard->addCompany( $meta['company_name']);
        $vcard->addJobtitle( $meta['contact_position'] );
        $vcard->addEmail( $meta['company_email'] );
        $vcard->addPhoneNumber($meta['company_telephone'], 'PREF;WORK');
        $vcard->addPhoneNumber($meta['company_telephone'], 'WORK');
        //$name, $extended , $street, $city, $region, zip
        $vcard->addAddress( $meta['company_name'], null, $meta['company_address'], $meta['company_city'], $meta['company_state'], $meta['company_zip'], $meta['company_country']);
        $vcard->addURL( $meta['company_website'] );

        //@todo - 
        $your_img_id = intval($meta['contact_media_id']);
        if ( $your_img_id ) {
            // Get the image src
            $your_img_src = get_attached_file( $your_img_id, 'pin_as_contact_thumb' );
            $vcard->addPhoto($your_img_src);
        }

        // return vcard as a string
        //return $vcard->getOutput();

        // return vcard as a download
        return $vcard->download();

       
    }
endif;

/**
* download contact details as html
* is using the current block template
* @param $post object wp post  
* @param $meta associative array post meta
* @return void
*/
if ( !function_exists('newswire_download_contact_as_html') ) :
    function newswire_download_contact_as_html($data, $meta, $filename) {
        global $post;
        $post = $data;
        $html = "";
        $file = sprintf('%s-%s.php', NEWSWIRE_DIR . '/includes/pressroom/templates/pressroom', $data->post_type);
       // var_dump($data);
        setup_postdata( $post );
        
        remove_all_filters('newswire_contact_block_footer' );
        $html = '<div>';
        $html .= get_pressroom_template($file);
        $html .= '</div';
        wp_reset_query();
        wp_reset_postdata();
        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename=' . rawurldecode($filename));
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        header('Cache-control: private');
        header('Pragma: private');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-Length: ' . strlen($html));
        echo $html;
        //ob_get_flush();
        exit;
}
endif;