<?php
/**
* @package: newswirexpress
* @subpackage: prreporter
* @since newswirexpress 1.1.4
* @author: 
*
* @description: load all files related to PRReporeter
* mostly handlers to press release submission to 5 freesites, newswire , include to blog post page, include to pressroom
*
*/
add_action('newswire_init_prreporter', 'newswire_init_prreporter');
function newswire_init_prreporter($continue) {

    if ( $continue ) {
        include "metabox.php";
        include "pr-submit.php";
    }
}