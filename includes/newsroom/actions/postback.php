<?php
if ( !defined('ABSPATH') ) {
    exit;
}

add_action('init', 'newswire_action_handle');
function newswire_action_handle() {
    $action = isset( $_REQUEST['action'] ) ? $_REQUEST['action']: '';

    //filter actions/registered action later 
    if ( has_action('__newswire__'. $action) ) {
        do_action('__newswire__'.$action);
    }
}
add_action('__newswire__submit_postback', 'newswire_submit_postback_handle');
function newswire_submit_postback_handle() {
    $options = newswire_options();
    extract($options);
    //approve article
    //
    //
    // 'api_key' => $apiKey,
    //        'article_id' => $article->getIdentity(),
    //       'prev_status' => $this->_cleanData['status'],
    //        'status' => $this->status,
    //        'title' => $article->getTitle(),
    //        'tags' => $article->getKeywords(','),
    //       'description' => $article->getDescription(),
    //        'body' => $article->body,
    //
    //  <option value="draft" label="Draft">Draft</option>
   // <option value="pending" label="Pending">Pending</option>
   // <option value="re-do" label="Sent Back" selected="selected">Sent Back</option>
   // <option value="published" label="Published">Published</option>
   // <option value="sponsored" label="Sponsored">Sponsored</option>
   // <option value="archived" label="Archived">Archived</option>
    //
    //
    $post_id = intval($_GET['post_id']);
    
    if ( $post_id > 0 && $newswire_api_key === $_POST['api_key'] )   {
        $post = get_post( $post_id );
        switch($_POST['status']) {
            case 'published' :
                $wp_status ='publish';
                break;
            case 're-do':
                $wp_status = 're-do';
            break;
            default:
                $wp_status = $_POST['status'];
            break;
        }
        $postarr = array('ID'=> $post_id, 'post_title' => $_POST['title'] , 'post_content' => $_POST['body'], 'post_excerpt' => $_POST['description'], 'post_status' => $wp_status);  
        wp_update_post( $postarr, $wp_error );
        die('success');
    }
}