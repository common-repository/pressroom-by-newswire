<?php
if ( !defined('ABSPATH') ) {
    exit;
}

// Register sidebar for PR single page
if ( function_exists('register_sidebar')) {

    register_sidebar(array(

        'id' => 'press-release-sidebar',

        'name' => __('Press Release Sidebar', 'newswire'),

        'description' => __('Sidebar that appears on the right of Press Release Pages.  You may need to edit your sidebar.php file and call this code <?php dynamic_sidebar("press-release-sidebar") ?> ', 'newswire'),

        'before_widget' => '<aside id="%1$s" class="widget %2$s">',

        'after_widget' => '</aside>',

        'before_title' => '<h3 class="widget-title">',

        'after_title' => '</h3>',

    ));
}
