<?php

if(!class_exists('Newswire_WP_List_Table')){

    require_once( NEWSWIRE_DIR . '/includes/classes/newswire-wp-list-table.php' );
}

/**
 * Media Library List Table class.
 *
 * @package WordPress
 * @subpackage List_Table
 * @since 3.1.0
 * @access private
 */
class Pressroom_Image_Attachment_List_Table extends Newswire_WP_List_Table {

    var $detached = null, $is_trash;
    var $delete_posts = null;

   public function __construct( $args = null ) {


        parent::__construct( array(
            'plural' => 'attachments',
            'singuular' => 'attachment',
            'ajax' => true,
            'screen' => isset( $args['screen'] ) ? $args['screen'] : null,
        ) );
    }
    
    function ajax_user_can() {
        return current_user_can('upload_files');
    }

    function prepare_items() {
        global $wpdb, $post;


        //echo $post->ID;
        
        $this->query = new WP_Query(array(
            'post_parent' => $post->ID, // Get data from the current post
            'post_type' => 'attachment',    
            'post_status' => 'inherit',  
            'orderby' => 'menu_order date',
            'order'=> 'DESC'
        ));
        
        

        

        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);       

    

        $this->set_pagination_args( array(
            'total_items' => $this->query->found_posts,
            'total_pages' => $this->query->max_num_pages,
            'per_page' => 100
        ));
    }

    function ajax_response() {
 
        //check_ajax_referer( 'ajax-custom-list-nonce', '_ajax_custom_list_nonce' );
        global $post;
        
        $post = get_post($_REQUEST['post_id'] );

        $this->prepare_items();
     
        extract( $this->_args );
        extract( $this->_pagination_args, EXTR_SKIP );
     
        ob_start();
        if ( ! empty( $_REQUEST['no_placeholder'] ) )
            $this->display_rows();
        else
            $this->display_rows_or_placeholder();
        $rows = ob_get_clean();
     
        ob_start();
        $this->print_column_headers();
        $headers = ob_get_clean();
     
        ob_start();
        $this->pagination('top');
        $pagination_top = ob_get_clean();
     
        ob_start();
        $this->pagination('bottom');
        $pagination_bottom = ob_get_clean();
     
        $response = array( 'rows' => $rows );
        $response['pagination']['top'] = $pagination_top;
        $response['pagination']['bottom'] = $pagination_bottom;
        $response['column_headers'] = $headers;
     
        if ( isset( $total_items ) )
            $response['total_items_i18n'] = sprintf( _n( '1 item', '%s items', $total_items ), number_format_i18n( $total_items ) );
     
        if ( isset( $total_pages ) ) {
            $response['total_pages'] = $total_pages;
            $response['total_pages_i18n'] = number_format_i18n( $total_pages );
        }
     
        die( json_encode( $response ) );
    }

    function column_default( $item, $column_name ){
        switch ($column_name) {
            case 'thumbnail':
                return $item[$column_name];
            break;
            default:
                return print_r($item, true);
            break;
        }
    }
    function get_views() {
       
    }

    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }

    function extra_tablenav( $which ) {
        return '';
    }

    function current_action() {
      return '';
    }

    function has_items() {
        return $this->query->have_posts();
    }

    function no_items() {
        _e( 'No media attachments found.' );
    }

    function get_columns() {
        $posts_columns = array();
        $posts_columns['cb'] = '<input type="checkbox" />';
        $posts_columns['icon'] = '';
        /* translators: column name */
        $posts_columns['title'] = _x( 'File', 'column name' );
        $posts_columns['author'] = __( 'Author' );


        /* translators: column name */
        
        $posts_columns['parent'] = _x( 'Uploaded to', 'column name' );          
        

        /* translators: column name */
        $posts_columns['date'] = _x( 'Date', 'column name' );
        

        return $posts_columns;
    }

    function get_sortable_columns() {
        return array(
            'title'    => 'title',
            'author'   => 'author',
            'date'     => array( 'date', true ),
        );
    }

    function display_rows() {
        global $post;

        add_filter( 'the_title','esc_html' );
        $alt = '';

        while ( $this->query->have_posts() ) : $this->query->the_post();
        setup_postdata( $this->query->post );
            $user_can_edit = current_user_can( 'edit_post', $post->ID );

            if ( $this->is_trash && $post->post_status != 'trash'
            ||  !$this->is_trash && $post->post_status == 'trash' )
                continue;

            $alt = ( 'alternate' == $alt ) ? '' : 'alternate';
            $post_owner = ( get_current_user_id() == $post->post_author ) ? 'self' : 'other';
            $att_title = _draft_or_post_title();
?>
    <tr id='post-<?php echo $post->ID; ?>' class='<?php echo trim( $alt . ' author-' . $post_owner . ' status-' . $post->post_status ); ?>' valign="top">
<?php

list( $columns, $hidden ) =  $this->_column_headers ;
foreach ( $columns as $column_name => $column_display_name ) {
    $class = "class='$column_name column-$column_name'";

    $style = '';
    if ( in_array( $column_name, $hidden ) )
        $style = ' style="display:none;"';

    $attributes = $class . $style;

    switch ( $column_name ) {

    case 'cb':
?>
        <th scope="row" class="check-column">
            <?php if ( $user_can_edit ) { ?>
                <label class="screen-reader-text" for="cb-select-<?php the_ID(); ?>"><?php echo sprintf( __( 'Select %s' ), $att_title );?></label>
                <input type="checkbox" name="media[]" id="cb-select-<?php the_ID(); ?>" value="<?php the_ID(); ?>" />
            <?php } ?>
        </th>
<?php
        break;

    case 'icon':
        $attributes = 'class="column-icon media-icon"' . $style;
?>
        <td <?php echo $attributes ?>><?php
            if ( $thumb = wp_get_attachment_image( $post->ID, array( 80, 60 ), true ) ) {
                if ( $this->is_trash || ! $user_can_edit ) {
                    echo $thumb;
                } else {
?>
                <a href="<?php echo get_edit_post_link( $post->ID, true ); ?>" title="<?php echo esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $att_title ) ); ?>">
                    <?php echo $thumb; ?>
                </a>

<?php           }
            }
?>
        </td>
<?php
        break;

    case 'title':
?>
        <td <?php echo $attributes ?>><strong>
            <?php if ( $this->is_trash || ! $user_can_edit ) {
                echo $att_title;
            } else { ?>
            <a href="<?php echo get_edit_post_link( $post->ID, true ); ?>"
                title="<?php echo esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $att_title ) ); ?>">
                <?php echo $att_title; ?></a>
            <?php };
            _media_states( $post ); ?></strong>
            <p>
<?php
            if ( preg_match( '/^.*?\.(\w+)$/', get_attached_file( $post->ID ), $matches ) )
                echo esc_html( strtoupper( $matches[1] ) );
            else
                echo strtoupper( str_replace( 'image/', '', get_post_mime_type() ) );
?>
            </p>
<?php
        echo $this->row_actions( $this->_get_row_actions( $post, $att_title ) );
?>
        </td>
<?php
        break;

    case 'author':
?>
        <td <?php echo $attributes ?>><?php
            printf( '<a href="%s">%s</a>',
                esc_url( add_query_arg( array( 'author' => get_the_author_meta('ID') ), 'upload.php' ) ),
                get_the_author()
            );
        ?></td>
<?php
        break;

    case 'desc':
?>
        <td <?php echo $attributes ?>><?php echo has_excerpt() ? $post->post_excerpt : ''; ?></td>
<?php
        break;

    case 'date':
        if ( '0000-00-00 00:00:00' == $post->post_date ) {
            $h_time = __( 'Unpublished' );
        } else {
            $m_time = $post->post_date;
            $time = get_post_time( 'G', true, $post, false );
            if ( ( abs( $t_diff = time() - $time ) ) < DAY_IN_SECONDS ) {
                if ( $t_diff < 0 )
                    $h_time = sprintf( __( '%s from now' ), human_time_diff( $time ) );
                else
                    $h_time = sprintf( __( '%s ago' ), human_time_diff( $time ) );
            } else {
                $h_time = mysql2date( __( 'Y/m/d' ), $m_time );
            }
        }
?>
        <td <?php echo $attributes ?>><?php echo $h_time ?></td>
<?php
        break;

    case 'parent':
        if ( $post->post_parent > 0 )
            $parent = get_post( $post->post_parent );
        else
            $parent = false;

        if ( $parent ) {
            $title = _draft_or_post_title( $post->post_parent );
            $parent_type = get_post_type_object( $parent->post_type );
?>
            <td <?php echo $attributes ?>><strong>
                <?php if ( current_user_can( 'edit_post', $post->post_parent ) && $parent_type->show_ui ) { ?>
                    <a href="<?php echo get_edit_post_link( $post->post_parent ); ?>">
                        <?php echo $title ?></a><?php
                } else {
                    echo $title;
                } ?></strong>,
                <?php echo get_the_time( __( 'Y/m/d' ) ); ?>
            </td>
<?php
        } else {
?>
            <td <?php echo $attributes ?>><?php _e( '(Unattached)' ); ?><br />
            <?php if ( $user_can_edit ) { ?>
                <a class="hide-if-no-js"
                    onclick="findPosts.open( 'media[]','<?php echo $post->ID ?>' ); return false;"
                    href="#the-list">
                    <?php _e( 'Attach' ); ?></a>
            <?php } ?></td>
<?php
        }
        break;

    case 'comments':
        $attributes = 'class="comments column-comments num"' . $style;
?>
        <td <?php echo $attributes ?>>
            <div class="post-com-count-wrapper">
<?php
        $pending_comments = get_pending_comments_num( $post->ID );

        $this->comments_bubble( $post->ID, $pending_comments );
?>
            </div>
        </td>
<?php
        break;

    default:
        if ( 'categories' == $column_name )
            $taxonomy = 'category';
        elseif ( 'tags' == $column_name )
            $taxonomy = 'post_tag';
        elseif ( 0 === strpos( $column_name, 'taxonomy-' ) )
            $taxonomy = substr( $column_name, 9 );
        else
            $taxonomy = false;

        if ( $taxonomy ) {
            $taxonomy_object = get_taxonomy( $taxonomy );
            echo '<td ' . $attributes . '>';
            if ( $terms = get_the_terms( $post->ID, $taxonomy ) ) {
                $out = array();
                foreach ( $terms as $t ) {
                    $posts_in_term_qv = array();
                    $posts_in_term_qv['taxonomy'] = $taxonomy;
                    $posts_in_term_qv['term'] = $t->slug;

                    $out[] = sprintf( '<a href="%s">%s</a>',
                        esc_url( add_query_arg( $posts_in_term_qv, 'upload.php' ) ),
                        esc_html( sanitize_term_field( 'name', $t->name, $t->term_id, $taxonomy, 'display' ) )
                    );
                }
                /* translators: used between list items, there is a space after the comma */
                echo join( __( ', ' ), $out );
            } else {
                echo '&#8212;';
            }
            echo '</td>';
            break;
        }
?>
        <td <?php echo $attributes ?>>
            <?php do_action( 'manage_media_custom_column', $column_name, $post->ID ); ?>
        </td>
<?php
        break;
    }
}
?>
    </tr>
<?php endwhile;
    }

    function _get_row_actions( $post, $att_title ) {
        
        $actions = array();

        if ( $this->detached ) {
            if ( current_user_can( 'edit_post', $post->ID ) )
                $actions['edit'] = '<a href="' . get_edit_post_link( $post->ID, true ) . '">' . __( 'Edit' ) . '</a>';
            if ( current_user_can( 'delete_post', $post->ID ) )
                if ( EMPTY_TRASH_DAYS && MEDIA_TRASH ) {
                    $actions['trash'] = "<a class='submitdelete' href='" . wp_nonce_url( "post.php?action=trash&amp;post=$post->ID", 'trash-post_' . $post->ID ) . "'>" . __( 'Trash' ) . "</a>";
                } else {
                    $delete_ays = !MEDIA_TRASH ? " onclick='return showNotice.warn();'" : '';
                    $actions['delete'] = "<a class='submitdelete'$delete_ays href='" . wp_nonce_url( "post.php?action=delete&amp;post=$post->ID", 'delete-post_' . $post->ID ) . "'>" . __( 'Delete Permanently' ) . "</a>";
                }
            $actions['view'] = '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( sprintf( __( 'View &#8220;%s&#8221;' ), $att_title ) ) . '" rel="permalink">' . __( 'View' ) . '</a>';
            if ( current_user_can( 'edit_post', $post->ID ) )
                $actions['attach'] = '<a href="#the-list" onclick="findPosts.open( \'media[]\',\''.$post->ID.'\' );return false;" class="hide-if-no-js">'.__( 'Attach' ).'</a>';
        }
        else {
            if ( current_user_can( 'edit_post', $post->ID ) && !$this->is_trash )
                $actions['edit'] = '<a href="' . get_edit_post_link( $post->ID, true ) . '">' . __( 'Edit' ) . '</a>';
            if ( current_user_can( 'delete_post', $post->ID ) ) {
                if ( $this->is_trash )
                    $actions['untrash'] = "<a class='submitdelete' href='" . wp_nonce_url( "post.php?action=untrash&amp;post=$post->ID", 'untrash-post_' . $post->ID ) . "'>" . __( 'Restore' ) . "</a>";
                elseif ( EMPTY_TRASH_DAYS && MEDIA_TRASH )
                    $actions['trash'] = "<a class='submitdelete' href='" . wp_nonce_url( "post.php?action=trash&amp;post=$post->ID", 'trash-post_' . $post->ID ) . "'>" . __( 'Trash' ) . "</a>";
                if ( $this->is_trash || !EMPTY_TRASH_DAYS || !MEDIA_TRASH ) {
                    $delete_ays = ( !$this->is_trash && !MEDIA_TRASH ) ? " onclick='return showNotice.warn();'" : '';
                    $actions['delete'] = "<a class='submitdelete'$delete_ays href='" . wp_nonce_url( "post.php?action=delete&amp;post=$post->ID", 'delete-post_' . $post->ID ) . "'>" . __( 'Delete Permanently' ) . "</a>";
                }
            }
            if ( !$this->is_trash ) {
                $title =_draft_or_post_title( $post->post_parent );
                $actions['view'] = '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( sprintf( __( 'View &#8220;%s&#8221;' ), $title ) ) . '" rel="permalink">' . __( 'View' ) . '</a>';
            }
        }

        $actions = apply_filters( 'media_row_actions', $actions, $post, $this->detached );

        return $actions;
    }    

    function display_tablenav( $which = '' ) {
        return '';
    }
}
