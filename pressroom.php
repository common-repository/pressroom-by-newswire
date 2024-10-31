<?php
/**
 * Plugin Name: PressRoom by Newswire
 * Plugin URI:  http://www.pressroom.ninja
 * Description: PressRoom™  by Newswire creates a fully optimized press room (sometimes called a media room) with press release submission capability. 
 * Author:      DE Brown
 * Author URI:  http://newswire.net/profile/500
 * Version:     1.2
 * Text Domain: newswire
 * Domain Path: /languages/
 * License:
 *
 *
 *
 */
if ( !defined('ABSPATH') ) {
    exit;
}

if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
    add_action( 'admin_notices', 
        create_function( '', 
            "echo '<div class=\"error\"><p>" 
            . __('PressRoom by Newswire requires PHP 5.3 to function properly. Please upgrade PHP or deactivate Plugin Name.', 'newswire') 
            . "</p></div>';" ) 
        );
    return;
}

# SET PATHS AND URLS
define('NEWSWIRE', plugin_basename(__FILE__));
define('NEWSWIRE_PLUGIN_VERSION', '0.0.43');
define('NEWSWIRE_DEVELOPMENT_MODE', true);
define('NEWSWIRE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('NEWSWIRE_DIR', plugin_dir_path(__FILE__));
define('NEWSWIRE_PLUGIN_ASSETS_URL', NEWSWIRE_PLUGIN_URL . '/assets');
define('NEWSWIRE_PLUGIN_ASSETS_DIR', NEWSWIRE_DIR . '/assets');

# INCLUDES
include NEWSWIRE_DIR . '/config/config.php';
include NEWSWIRE_DIR . '/config/tooltip.php';
include NEWSWIRE_DIR . '/bootstrap.php';


register_activation_hook(__FILE__, 'newswire_pressroom_activate');
register_deactivation_hook(__FILE__, 'newswire_pressroom_deactivate');
do_action('newswire_pressroom_init');



