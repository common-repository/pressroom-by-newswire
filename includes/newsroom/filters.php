<?php


/**
 * Get newswire username
 */
add_filter('newswire_data_meta_user_id', 'newswire_data_meta_user_id_filter');
function newswire_data_meta_user_id_filter($val) {
    $options = newswire_options();
    return $options['newswire_user']['username'];
}


/**
* Remove live links from pr content
*/
if ( !function_exists('newswire_live_links_settings')) :

add_action('wp_loaded', 'newswire_live_links_settings');
function newswire_live_links_settings() {

    global $post;

    if ( get_post_type( $post ) != 'pr') return;

    $src = 'http://newswire.net/article/util/site';

    $settings = get_transient( md5($src) );

    if  ( empty($settings) ) {
        
        //grab from newswire
        $response = wp_remote_get( $src );

        if ( ! is_wp_error( $response ) ) {
            $settings = json_decode( wp_remote_retrieve_body( $response ) );
        
            if( $settings ) {
                //newswire_live_links
                $parsed = parse_url ( site_url() );
                    

                foreach( $settings as $site) {
                    
                    if ( strpos($site->domain, $parsed['host']) >= 0 ) {
                        $options = newswire_options();              
                        $options['newswire_live_links'] = $site->live_links;
                        update_newswire_options($options);
                    }
                    //if ( strpos(haystack, needle)
                }

                //expire every other day
                set_transient(md5($src), $settings, 60 * 60 * 24 * 2 );
            }           
        }
    }//end empty
    
}//end function

endif;

/**
* Newswire Live links removal
*/
add_filter('the_content', 'newswire_remove_link_references');
function newswire_remove_link_references($content) {
    
    //skip other post type immediately dont waste time
    if ( get_post_type() != 'pr' ) return $content;

    $options = newswire_options();

    if ( !$options['newswire_live_links'] ) {
        
        if ( class_exists('DOMDocument') ):
            try {
                $html = $content;
                $dom = new DOMDocument();
                $dom->encoding = 'utf-8';
                $dom->loadHTML('<?xml encoding="utf-8" ?>'.$html);

                //Evaluate Anchor tag in HTML
                $xpath = new DOMXPath($dom);
                $hrefs = $xpath->evaluate("/html/body//a");

                for ($i = 0; $i < $hrefs->length; $i++) {
                        $href = $hrefs->item($i);
                        $url = $href->getAttribute('href');
                        $children = $href->childNodes;

                        if ( strpos( $url, 'newswire.net') > 0 ) :
                            //remove and set href attribute        
                            $href->removeAttribute('href');
                            //$href->setAttribute("href", '#');
                            //$href->parentNode->removeChild($href);
                            //$hrefs[$i] = $url;
                            $new = $dom->createElement('span');
                            //unset($href); //$dom->removeChild($href);
                            if ( $href->hasChildNodes() ) {
                                foreach($href->childNodes as $node) {
                                    $new->appendChild($node);
                                }
                            }
                            //$newelement = $dom->createTextNode($url); 
                            //$href->parentNode->appendChild($children, $href); 
                            //$href->nodeValue = $url;
                            $href->parentNode->replaceChild($new, $href);
                        endif;
                }

                // save html
                $html=$dom->saveHTML();

                return $html;

            } catch (Exception $e ) {

                return $content;
            }

            return $content;


        endif;

        return $content;
    }

    return $content;
}

/* --------------------------------------------------------
 Custom Table Column For listing data using wordpress way
----------------------------------------------------------- */



if ( !function_exists('newswire_show_custom_column_pressroom_blocktype_header')):
/*
* All PressRoom Blocks Add custom columns block type from pressroom listing table
*/ 
function newswire_pressroom_custom_column_header( $columns ) {
    $date = $columns['date'];
    unset($columns['date']);
    $columns[ 'pressroom_blocktype' ] = 'Block Type';
    $columns[ 'pressroom_indicator' ] = 'Visible on Pressroom Page?';
    $columns['author'] = __( 'Author','newswire' );
    $columns['date'] = $date;
        return $columns;
}
// send the filter hook
foreach(array('pressroom') as $cpt) {
    add_filter( "manage_edit-{$cpt}_columns", 'newswire_pressroom_custom_column_header', 1  );   
}

function newswire_show_custom_columns( $column_name, $post_id ) {    
    if ( $column_name == 'author') {
        echo get_the_author($post_id);
        return;
    }

    if ( 'pressroom_blocktype' == $column_name ) {
         $cpts = newswire_pressroom_postypes();
        echo $cpts[get_post_type( $post_id )];
        return;
    }

    if ( 'pressroom_indicator' == $column_name && get_post_type( $post_id ) == 'pr' ) {

        if ( has_term('pressroom', 'post_tag', $post_id ) ) {
            echo "Yes";
        } else {
            echo 'No';
        }
    } else {
        echo "Yes";
    }

   
}

foreach( array_keys(newswire_pressroom_postypes()) as $cpt )
    add_action( "manage_{$cpt}_posts_custom_column", 'newswire_show_custom_columns', 10, 2 );
endif;


/**
* Plugin 
* Create settings link
*/
add_filter( 'plugin_action_links_'.NEWSWIRE , 'newswire_add_settings_link' );
function newswire_add_settings_link($links) {
    $settings_link = '<a href="edit.php?post_type=pressroom&page=newsroom-settings">Settings</a>';
    $links = array_merge(array($settings_link), $links );
    return $links;
}



if ( !function_exists('newswire_excerpt_length')):
add_filter( 'excerpt_length', 'newswire_excerpt_length', 999 );
function newswire_excerpt_length( $length ) {
    global $post;
    $type = get_post_type( $post->ID );
    if ( 'pr' == $type )
        return 255;
}
endif;

/**
 * When newswire_option is update fetch categories from newswire
 */
add_action('update_option_' . NEWSWIRE_OPTIONS, 'newswire_fetch_categories_when_option_updated', 12, 2);
function newswire_fetch_categories_when_option_updated($old_value, $value) {

    if ( !empty($value['newsroom_page_template'] ) ) :
        $url = get_permalink($value['newsroom_page_template']);

        if (!empty($url)) {
            newswire_fetch_categories($force = 1);
        }
    endif;
}



/**
* Move all functions here that needs to be pluggable
*/
if (!function_exists('newswire_newsroom_nextpage')):
function newswire_newsroom_nextpage() {
    
    $options = newswire_options();
    
    $newsroom_page = $options['newsroom_page_template'];
    
    $paged = ($_GET['next']) ? $_GET['next'] : 1;

    $next_page = get_permalink( $newsroom_page ) .'?next='. intval($paged + 1 ) ;
    
    return sprintf('<a href="%s"></a>', $next_page );
}
endif;

