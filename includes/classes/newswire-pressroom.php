<?php
/*
 * *********************************************************************
 *   Pressroom Class
 * =====================================================================
 * Lets use template_include instead of template_redirect
 * needed to instantiate this class from init hook to support callback from register_post_type calls
 *
 *
 * @todo: remove sorting feature from other built in post type like post and page
 */

class Newswire_Pressroom {

    var $tag__not_in;
    var $delete_posts;
    var $templates;
    /**
     * set this to true once pressroom page is done rendering content
     */
    public $newswire_pressroom_rendered = false;
    /**
     * Use standard template for pressroom
     */
    public $pressroom_theme = 'standard';
    /**
     * call all filters and action from here
     *
     */
    public function __construct() {

        global $pagenow, $typenow;

        if (is_admin()) {
            add_action('all_admin_notices', array($this, 'refresh_menu_order'));
            add_action('admin_init', array($this, 'init_admin'));
        } else {

            //set the hook late from wp_loaded
            //add_action('wp', array($this, 'init_frontend'));
        }
    }

    public function init_admin() {

        add_filter('parent_file', array($this, 'newswire_resolve_active_menus'));

        //?limit to what page to enqueu media?
        /*wp_enqueue_script( 'jquery-ui-core'      );
        wp_enqueue_script( 'jquery-ui-tabs'      );
        wp_enqueue_script( 'jquery-ui-mouse'     );
        wp_enqueue_script( 'jquery-ui-draggable' );
        wp_enqueue_script( 'jquery-ui-droppable' );
        wp_enqueue_script( 'jquery-ui-sortable'  );
         */
        add_action('pre_get_posts', array($this, 'modify_pressroom_post_listing'));

        //add_action('plupload_init', array($this, 'resolve_post_id_from_settigs_page'));
        //handler sorting of post by dd

        add_action('wp_ajax_update-menu-order', array(&$this, 'update_menu_order'));

        #add_filter('enter_title_here', array($this, 'modify_post_title_label'), 10, 2); //modify post title label based on block type
        #add_filter('user_can_richedit', array($this, 'remove_richedit_from_editor')); //toggle richedit capability based on block type from new/edit block/post

        #$this->templates = array_merge(wp_get_theme()->get_page_templates(), newswire_pressroom_templates());
        //add_filter('page_attributes_dropdown_pages_args', array($this, 'register_pressroom_templates'));

        add_action('restrict_manage_posts', array($this, 'restrict_listings_by_post_type'));
        add_action('save_post_pin_as_image', array($this, 'save_pin_as_image')); //Attachment - set uploaded media as attachment when user update image album block

        //Pressroom lsiting - add status filter from the table listing
        add_filter('views_edit-pressroom', array($this, 'fixed_all_pressroom_table_views'));
        add_filter('views_edit-pr', array($this, 'fixed_all_pressroom_table_views'));

        //@todo: if we ajaxified attachment listing from pin_as_image meta box . This handle the ajax reponse
        //       not fully implementedy. maybe soon
        add_action('wp_ajax__ajax_fetch_custom_list', array($this, '_ajax_fetch_custom_list_callback'));

        //set the template source to plugin templates when creating a new page
        //whenever we set pressroom page
        //@todo - convert newsroom page to this approach
        //add_filter('wp_insert_post_data', array($this, 'register_pressroom_templates'));

        /** add custom columns for newswire press release table
        foreach(array('pressroom') as $cpt) {
        add_filter( "manage_edit-{$cpt}_columns", array( $this, 'add_pressroom_column_block_type' ));
        }

        foreach( array_keys(newswire_pressroom_postypes()) as $cpt )
        add_action( "manage_{$cpt}_posts_custom_column", array($this, 'show_pressroom_block_type', 1, 2 ));
         */

        //wp_enqueue_media(); not working

        // handle attachment meta box listing
        //add_action('wp_ajax_pressroom_attachment_list', array($this, 'update_attachment_list'));
        //
    }
    /**
     * hook to wp_loaded
     */
    public function init_frontend() {

        // if not pressroom skip
        if (!is_newswire_pressroom()) {
            return;
        }

        add_action('wp_enqueue_scripts', 'add_thickbox');
        //wp_register_style('pressroom-css', plugins_url('assets/css/pressroom.css', dirname(__FILE__)));
        //wp_enqueue_style('pressroom-css');
        wp_enqueue_style('nivoslider-css');
        wp_enqueue_style('nivoslider-theme');

        wp_enqueue_script('nivoslider-js');
        wp_enqueue_script('newswire-zclip');
        wp_enqueue_script('newswire-zeroclip');
        wp_enqueue_script('newswire-jplugin');
        wp_enqueue_script('newswire-more');
        wp_enqueue_script('newswire-masonry');
        wp_enqueue_script('newswire-infinitescroll');
        //add hooks related to frontend page. we only support one page from front end
        //presspage that list all blocks
        //add_filter('template_include', array($this,'template_include'));
        add_filter('pressroom_morepin_link', array($this, 'pressroom_morepin_link_callback')); //handle next page loading
        add_action('newswire_pressroom_after', array($this, 'toggle_pressroom_render'));
        //add_filter('the_content', array($this, 'printif_pressroom_content'));
        //
        //maybe inclde masonry scripts?
        //add_action('wp_footer', array($this, 'print_masonry_scripts'), 100);
        //add_action('wp_head', array($this, 'pressroom_custom_style'));
//      add_filter('pressroom_pin_content', array($this, 'reduce_iframe_width'));
    //    add_action('wp_head', array($this, 'insert_header_scripts_from_blocks'));


    }

    public function reduce_iframe_width($content) {
        //width:
        //$content = preg_replace('|<iframe(.*?) width:\s*([\d]*)px|', 'width:100%', $content);
        //$content  = preg_replace('|width:([\d]*)px|', 'width:278px', $content );
        //width=""
        //$content = preg_replace('|<iframe .*? width\s*=\s*["\'][\d]*[px]*["\']|', 'width="100%"', $content);
        //$content  = preg_replace('|width\s*=["\'][\d]*[px]*["\']|', 'width="278px"', $content );
        return $content;
    }
    /**
     * PRessroom page front end route to actual template using template_include hook
     */
    public function template_include($template) {

        $page = get_queried_object();

        $option = newswire_options();

        if (!in_array(get_post_meta($page->ID, '_wp_page_template', true), array_keys(newswire_pressroom_templates()))) {
            return $template;
        }// end if

        $file = NEWSWIRE_DIR . '/templates/' . get_post_meta($page->ID, '_wp_page_template', true);

        // Just to be safe, we check if the file exist first
        if (file_exists($file)) {

            return $file;
        }// end if

        return $template;
    }

    public function pressroom_custom_style() {

        $opt = newswire_options();
        extract($opt);

        if ($pressroom_theme == 'standard'):
        ?><style type="text/css">
        .pressroom-header { background-color: <?php echo $pressroom_header_bgcolor?>;}
        #pressroom-blocks  li.block { border-color: <?php echo $pressroom_bordercolor?>;}
        #pressroom-blocks  li.block .title { background-color: <?php echo $pressroom_title_bgcolor?>; color: <?php echo $pressroom_title_textcolor?> ;}
        body.page-template-pressroom-template-standard-php,
        #pressroom-blocks,
        .pressroom-tabs li.active {
            background-color: <?php echo $pressroom_bgcolor?>
        }
        <?php if ($pressroom_bgimage != ''): ?>
        body {
            background: url(<?php echo $pressroom_bgimage?>) !important;
        }
        <?php endif;?>
        </style><?php
endif;
    }

    /**
     * print pressroom page if is not yet rendered from the_content hook
     */
    public function printif_pressroom_content() {

        global $post;

        if (!$this->newswire_pressroom_rendered):
            //first remove the_content filter to avoid infinite calls
            remove_filter('the_content', array($this, 'printif_pressroom_content'));

            ?><div id="pressroom-wrapper" class="container-fluid">
                    <ul class="pressroom-tabs ">
                        <li class="active"><a href="<?php echo apply_filters('pressroom_tab_link', 'pressroom_page')?>#">PressRoom</a></li>
                        <li><a target="_blank" href="<?php echo apply_filters('pressroom_tab_link', 'newsroom_page')?>">NewsRoom</a></li>
                        <?php if (current_user_can('administrator')): ?>
                        <li><a target="_blank" href="<?php echo apply_filters('pressroom_tab_link', 'write_release')?>">Write a Release</a></li>
                        <li><a target="_blank" href="<?php echo apply_filters('pressroom_tab_link', 'manage_page')?>">Manage Page</a></li>
                        <?php endif;?>
            </ul>

        <div class="row-fluid">
            <ul id="pressroom-blocks" class="unstyled">
            <?php

        $query = newswire_pressroom_query();

        while ($query->have_posts()):

            $query->the_post();

            $pin_type = $post->post_type;

            //if pr check tag
            if ( $pin_type == 'pr' ) {
                //continue if it is tagged as pressroom block
                if ( !has_term('pressroom', 'post_tag', $post)) {
                    continue;
                }
            }

            $file = realpath(sprintf('%s-%s.php', NEWSWIRE_DIR . '/includes/pressroom/templates/pressroom', $pin_type));

            echo '<li class="block" ><div class="' . $pin_type . '">';
            newswire_include($file);

            echo '<div></li>';

        endwhile;

        wp_reset_postdata();

        ?></ul>
        </div>

            <div id="scrolltotop" class="pull-right"><a href="#"><i class="icon-chevron-up"></i><br /><?php _e('Top', 'newswire');?></a></div>
        </div>

    <div>
    <?php
echo pressroom_morepin_html();
        endif;
    }

    public function toggle_pressroom_render() {
        $this->newswire_pressroom_rendered = true;
    }
    /* not working
    #
    function add_pressroom_column_block_type( $columns ) {
    $columns[ 'cpt' ] = 'Block Type';
    return $columns;
    }


    function show_pressroom_block_type( $column_name = '', $post_id ='')
    {
    var_dump('test');
    if ( 'cpt' != $column_name )
    return;
    $cpts = newswire_pressroom_postypes();

    echo $cpts[get_post_type( $post_id )];
    }
     */

    /**
     * Filter for next page link specific to pin type listing
     */
    public function pressroom_morepin_link_callback($link) {

        global $post;

        $page = intval((get_query_var('paged')) ? get_query_var('paged') : 1);
        $page++;
        $next_link = add_query_arg('page', $page, get_permalink($post->ID));
        return sprintf('<a href="%s">Next</a>', get_next_posts_page_link());
    }

    /**
     * drop down menu to filter listing from pressroom wp table
     */
    public function restrict_listings_by_post_type() {
        global $typenow;
        if ($typenow == 'pressroom') {
            $selected = '';
            echo '<select name="block_type">';

            echo '<option value="">' . __('Show All Blocks', 'newswire') . '</option>';

            foreach (newswire_pressroom_postypes() as $type => $typename) {
                if ('pressroom' == $type) {
                    continue;
                }

                if ($_REQUEST['block_type'] == $type) {
                    $selected = 'selected';
                }

                printf('<option value="%s" %s>%s</option>', $type, $selected, $typename);
                $selected = '';
            }
            echo '</select>';
        }
    }

    /**
     *  @todo: ?
     */
    public function update_attachment_listing() {

        //require class
        try {
            require_once 'pressroom-attachment-list-table.php';
            $table = new Pressroom_Image_Attachment_List_Table;
            $table->prepare_items();
            $table->display();

        } catch (Exception $e) {
            var_dump($e->getMessages());
        }?>
       <?php    //exit;
    }

    /**
     * Refresh menu order
     */
    function refresh_menu_order() {

        global $wpdb;

        $object = join("','", array_keys(newswire_pressroom_postypes()));

        /*
        $result = $wpdb->get_results( "SELECT count(*) as cnt, max(menu_order) as max, min(menu_order) as min FROM $wpdb->posts WHERE post_type IN ( '".$object."' ) AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')" );
        if ( count( $result ) > 0 && $result[0]->cnt == $result[0]->max && $result[0]->min != 0 ) {
        return;
        }*/
        $sql = "SELECT ID, post_type, menu_order FROM $wpdb->posts WHERE post_type IN ( '" . $object . "' ) AND post_status IN ('publish', 'pending', 'draft', 'private', 'future') ORDER BY menu_order ASC, post_date DESC ";
        $results = $wpdb->get_results($sql);

        foreach ($results as $key => $result) {
            $wpdb->update($wpdb->posts, array('menu_order' => $key + 1), array('ID' => $result->ID));
        }
    }

    /**
     * Update sorting of press release and press room blocks when user drag and drops from edit page
     */
    public function update_menu_order() {

        global $wpdb;

        if (current_user_can('author') || current_user_can('editor') || current_user_can('administrator')):

            parse_str($_POST['order'], $data);

            $post_type = $_POST['post_type'];

            if (is_array($data)) {

                $id_arr = array();

                foreach ($data as $key => $values) {
                    foreach ($values as $position => $id) {
                        $id_arr[] = $id;
                    }
                }

                $menu_order_arr = array();
                foreach ($id_arr as $key => $id) {
                    $results = $wpdb->get_results("SELECT menu_order FROM $wpdb->posts WHERE ID = " . $id);
                    foreach ($results as $result) {
                        $menu_order_arr[] = $result->menu_order;
                    }
                }

                sort($menu_order_arr);

                foreach ($data as $key => $values) {
                    foreach ($values as $position => $id) {
                        //var_dump(array( 'menu_order' => $menu_order_arr[$position]));
                        $wpdb->update($wpdb->posts, array('menu_order' => $menu_order_arr[$position]), array('ID' => $id));
                    }
                }
            }

            //refresh menu order
            //
            if ($post_type == 'pin_as_image') {
                //
                $post_id = intval($_REQUEST['post_parent']);
                $sql = "SELECT ID FROM $wpdb->posts WHERE post_parent = " . $post_id . " AND post_type = 'attachment' AND post_status = 'inherit'  ORDER BY menu_order ASC, post_date DESC";
                $results = $wpdb->get_results($sql);
                foreach ($results as $key => $result) {
                    $wpdb->update($wpdb->posts, array('menu_order' => $key + 1), array('ID' => $result->ID));
                }
            }

        endif;
    }

    /**
     * This is always being run from admin make sure to continue running code only when needed
     *
     * hook to pre_get_posts
     */
    function modify_pressroom_post_listing($query) {

        global $typenow, $pagenow, $post_type;

        //filter by author if role is PRReporter
        if (current_user_can('PRReporter') || current_user_can('author')) {

            $user = wp_get_current_user();
            $query->set('author', $user->ID);
        }

        // run only from certain post types : pressroom listing (mix of different post types and we called them as pressroom blocks from this plugin)
        if (get_query_var('post_type') == 'pressroom' && $typenow == 'pressroom') {

            //if from edit or listing
            if ('edit.php' == $pagenow && (get_query_var('post_type') && 'pressroom' == get_query_var('post_type'))) {
                $query->set('post_type', array_keys(newswire_pressroom_postypes()));
            }

            $query->set('orderby', array('menu_order' => 'ASC', 'date' => 'DESC'));
            if ( @defined(NEWSWIREXPRESS) )
                $query->set('tag_slug__in', array('pressroom'));
            /*$tag = get_term_by('name', 'newsroom', 'post_tag');

            if ( $tag ) {

                $query->set('tag__not_in', $tag->term_id); //skip newsroom
                //save from class
                $this->tag__not_in = $tag->term_id;
            }*/

            if (!empty($_REQUEST['block_type'])) {
                $query->set('post_type', $_REQUEST['block_type']);
            }

            add_action('wp', array($this, 'reset_post_type'));
            //var_dump($query);


            //return apply_filters('newswire_all_pressroom_blocks', $query);

        } elseif (get_query_var('post_type') == 'pr' && $typenow == 'pr') {
            /*echo 'test';

            $query->set('tag', 'newsroom');

            if ( !empty($_REQUEST['block_type']) )
            $query->set('post_type', $_REQUEST['block_type'] );

            add_action('wp', array($this, 'reset_post_type'));
             */
            //$query->set('tag__not_in', $tag->term_id);//skip newsroom
            $this->tag__not_in = 0;
            return $query;

        }

    }

    /**
     * We need to reset post type to orignal to avoid invalid post type when updating post
     */
    function reset_post_type() {
        global $wp_query;
        $GLOBALS['post_type'] = 'pressroom';
//      var_dump($wp_query->request);
        //      exit;

    }

    /**
     * hook to views_edit-pressroom and modify views
     * copied from wp core. no other way since this is consolidating more than one post type
     */
    public function fixed_all_pressroom_table_views() {

        global $locked_post_status, $avail_post_stati;

        $post_type = $_REQUEST['post_type'];

        if ($post_type) {
            if (!empty($locked_post_status)) {
                return array();
            }
        }

        $status_links = array();
        $num_posts = $this->wp_count_posts($post_type, 'readable');
        $class = '';
        $allposts = '';

        $current_user_id = get_current_user_id();
/*
if ( $this->user_posts_count ) {
if ( isset( $_GET['author'] ) && ( $_GET['author'] == $current_user_id ) )
$class = ' class="current"';
$status_links['mine'] = "<a href='edit.php?post_type=$post_type&author=$current_user_id'$class>" . sprintf( _nx( 'Mine <span class="count">(%s)</span>', 'Mine <span class="count">(%s)</span>', $this->user_posts_count, 'posts' ), number_format_i18n( $this->user_posts_count ) ) . '</a>';
$allposts = '&all_posts=1';
}*/

        $total_posts = array_sum((array) $num_posts);

        // Subtract post types that are not included in the admin all list.
        foreach (get_post_stati(array('show_in_admin_all_list' => false)) as $state) {
            $total_posts -= $num_posts->$state;
        }

        $class = empty($class) && empty($_REQUEST['post_status']) && empty($_REQUEST['show_sticky']) ? ' class="current"' : '';
        $status_links['all'] = "<a href='edit.php?post_type=$post_type{$allposts}'$class>" . sprintf(_nx('All <span class="count">(%s)</span>', 'All <span class="count">(%s)</span>', $total_posts, 'posts'), number_format_i18n($total_posts)) . '</a>';

        foreach (get_post_stati(array('show_in_admin_status_list' => true), 'objects') as $status) {
            $class = '';

            $status_name = $status->name;

            if (!in_array($status_name, $avail_post_stati)) {
                continue;
            }

            if (empty($num_posts->$status_name)) {
                continue;
            }

            if (isset($_REQUEST['post_status']) && $status_name == $_REQUEST['post_status']) {
                $class = ' class="current"';
            }

            $status_links[$status_name] = "<a href='edit.php?post_status=$status_name&amp;post_type=$post_type'$class>" . sprintf(translate_nooped_plural($status->label_count, $num_posts->$status_name), number_format_i18n($num_posts->$status_name)) . '</a>';
        }
/*
if ( ! empty( $this->sticky_posts_count ) ) {
$class = ! empty( $_REQUEST['show_sticky'] ) ? ' class="current"' : '';

$sticky_link = array( 'sticky' => "<a href='edit.php?post_type=$post_type&amp;show_sticky=1'$class>" . sprintf( _nx( 'Sticky <span class="count">(%s)</span>', 'Sticky <span class="count">(%s)</span>', $this->sticky_posts_count, 'posts' ), number_format_i18n( $this->sticky_posts_count ) ) . '</a>' );

// Sticky comes after Publish, or if not listed, after All.
$split = 1 + array_search( ( isset( $status_links['publish'] ) ? 'publish' : 'all' ), array_keys( $status_links ) );
$status_links = array_merge( array_slice( $status_links, 0, $split ), $sticky_link, array_slice( $status_links, $split ) );
}
 */
        return $status_links;

    }

    /**
     * Admin - Part of views link from pressroom listing
     *  copied from wp core too
     */
    public function wp_count_posts($type = 'pressroom', $perm = '') {

        global $wpdb, $wp_query, $typenow;

        /*
        SELECT SQL_CALC_FOUND_ROWS wp_posts.ID FROM wp_posts
        INNER JOIN wp_term_relationships
        ON (wp_posts.ID = wp_term_relationships.object_id) WHERE 1=1 AND ( wp_term_relationships.term_taxonomy_id IN (3) )
        AND wp_posts.post_type IN ('pr', 'pin_as_text', 'pin_as_embed', 'pin_as_image', 'pin_as_quote', 'pin_as_social', 'pin_as_link', 'pin_as_contact', 'pressroom') AND (wp_posts.post_status = 'publish'
        OR wp_posts.post_status = 're-do' OR wp_posts.post_status = 'sponsored' OR wp_posts.post_status = 'archived' OR wp_posts.post_status = 'future'
        OR wp_posts.post_status = 'draft' OR wp_posts.post_status = 'pending' OR wp_posts.post_author = 1 AND wp_posts.post_status = 'private')
        GROUP BY wp_posts.ID ORDER BY wp_posts.menu_order ASC, wp_posts.post_date ASC LIMIT 0, 100"
         */

        if (!post_type_exists($type)) {
            return new stdClass;
        }

        /*
        $user = wp_get_current_user();

        $cache_key = 'posts-' . $type;

        $options = newswire_options();

        //$csv_post_types = array_unique(array_merge( explode(',', 'pin_as_text,pin_as_embed,pin_as_image,pin_as_quote,pin_as_social,pin_as_link,pin_as_contact'), $options['supported_post_types']));
        $csv_post_types  = array_diff(array_unique(array_merge(array_keys(newswire_pressroom_postypes()), $options['supported_post_types'] )), array('pressroom'));

        $csv_post_types = join("','", $csv_post_types );

        //$query = "SELECT post_status, COUNT( * ) AS num_posts FROM {$wpdb->posts} WHERE post_type IN ( '$csv_post_types' )";
        //$query .= ' GROUP BY post_status';

        list($query, $null) = explode('LIMIT 0', $wp_query->request);
        $query = str_replace('GROUP BY '.$wpdb->posts.'.ID',  'group by post_status', $query);
        //echo 'SQL_CALC_FOUND_ROWS '.$wpdb->posts.'.ID';
        $query = str_replace('SQL_CALC_FOUND_ROWS' , ' post_status as post_status, COUNT( * ) as num_posts, ', $query);
         */
        if ( !$this->tag__not_in ) $this->tag__not_in = 0;
        //
        if ($type == 'pressroom') {
            $post_type_list = "'pr', 'pin_as_text', 'pin_as_embed', 'pin_as_image', 'pin_as_quote', 'pin_as_social', 'pin_as_link', 'pin_as_contact', 'pressroom', 'pin_as_latestpr' ";
        } elseif ($type == 'pr') {
            $post_type_list = "'pr'";
        }
        //$post_type_list = "'pr', 'pin_as_text', 'pin_as_embed', 'pin_as_image', 'pin_as_quote', 'pin_as_social', 'pin_as_link', 'pin_as_contact', 'pressroom'";
        if (current_user_can('PRReporter') || current_user_can('author')) {
            $user = wp_get_current_user();
            $query = " SELECT post_status as post_status, COUNT( * ) as num_posts  FROM {$wpdb->posts} WHERE 1=1  AND post_author={$user->ID} AND ( ID NOT IN ( SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN ({$this->tag__not_in}) ) ) AND post_type IN ({$post_type_list}) AND (post_status = 'processing' OR post_status = 'trash' OR post_status = 'publish' OR post_status = 're-do' OR post_status = 'sponsored' OR post_status = 'archived' OR post_status = 'future' OR post_status = 'draft' OR post_status = 'pending' OR post_author = 1 AND post_status = 'private') group by post_status ORDER BY menu_order ASC, post_date DESC ";
        } else {
            $query = " SELECT post_status as post_status, COUNT( * ) as num_posts  FROM {$wpdb->posts} WHERE 1=1 AND ( ID NOT IN ( SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN ({$this->tag__not_in}) ) ) AND post_type IN ({$post_type_list}) AND ( post_status = 'processing' OR post_status = 'trash' OR  post_status = 'publish' OR post_status = 're-do' OR post_status = 'sponsored' OR post_status = 'archived' OR post_status = 'future' OR post_status = 'draft' OR post_status = 'pending' OR post_author = 1 AND post_status = 'private') group by post_status ORDER BY menu_order ASC, post_date DESC ";
        }

        $counts = false;

        if (false === $counts) {

            $results = (array) $wpdb->get_results($query, ARRAY_A);

            $counts = array_fill_keys(get_post_stati(), 0);

            foreach ($results as $row) {
                $counts[$row['post_status']] = $row['num_posts'];
            }

            $counts = (object) $counts;
            //  wp_cache_set( $cache_key, $counts, 'counts' );
        }

        /**
         * Modify returned post counts by status for the current post type.
         *
         * @since 3.7.0
         *
         * @param object $counts An object containing the current post_type's post counts by status.
         * @param string $type   The post type.
         * @param string $perm   The permission to determine if the posts are 'readable' by the current user.
         */
        return $counts;

    }

    /**
     * Front end pressroom page
     *
     * Print masonry scripts
     */
    public function print_masonry_scripts() {

        $options = newswire_options();

        ?><script type="text/javascript">
        <!--
        jQuery(document).ready(function($){

            var $masonry = $('#pressroom-blocks'), $pinitems = $('.block', $masonry );

            //infinitescxroll
            $masonry.infinitescroll({
                navSelector : '#pressroom-pin-navigation',
                nextSelector : '#pressroom-pin-navigation #pressroom-navigation-next a',
                itemSelector : '.block',
                loading: {
                    finishedMsg: "<em>No more content...</em>",
                    msgText:    "<em>Loading...</em>",
                },
            }, function(newElements) {
                //console.log('test');
                var $newElems = $(newElements).css({opacity: 0});

                if ($(document).width() <= 480) {
                    $newElems.imagesLoaded(function(){
                        //$('#infscr-loading').fadeOut('normal');
                        $newElems.animate({opacity: 1});
                        $masonry.masonry('appended', $newElems, true);
                        //$masonry.find('.block').show();
                    });
                } else {
                    $newElems.imagesLoaded(function(){
                        //$('#infscr-loading').fadeOut('normal');
                        $newElems.animate({opacity: 1}, 2000);
                        $masonry.masonry('appended', $newElems, true);
                        //$masonry.find('.block').show();
                    });
                }
            });

        });

        (function($){
            //masonry
            var $masonry = $('#pressroom-blocks'), $pinitems = $('.block', $masonry );
            //masonry call
            if ($(document).width() <= 480) {
                $masonry.imagesLoaded( function(){
                    $pinitems.show();
                    $masonry.masonry({
                        itemSelector : '.block',
                        isFitWidth: true
                    }).css('visibility', 'visible');
                    $('#ajax-loader-masonry').hide();
                    //$pinitems.animate({opacity: 1});
                });
            } else {
                $masonry.imagesLoaded( function(){
                    $pinitems.show();
                    $masonry.masonry({
                        itemSelector : '.block',
                        isFitWidth: true
                    }).css('visibility', 'visible');
                    $('#ajax-loader-masonry').hide();
                    $pinitems.animate({opacity: 1}, 2000);
                });
            }


            // nivo slider
            $(window).load(function() {


                $('.pin_as_image_slider').nivoSlider({
                    effect: 'random',
                    animSpeed: 700,
                    controlNav: true,
                    afterChange: function(){
                        //$masonry.masonry('reload');
                    }
                });
            });

            //set body width for IE8
            if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
                var ieversion=new Number(RegExp.$1)
                if (ieversion==8) {
                    $('body').css('max-width', $(window).width());
                }
            }

            //navigation

            $('#navigation').css({'visibility':'hidden', 'height':'1px'});

            setTimeout(function(){$masonry.masonry('reload')}, 1500);

            //redraw layout after few seconds to make sure everthing is okay
            setTimeout(function(){
                $masonry.masonry('reload').then(function(){
                    $('#pressroom-blocks > li.block').animate({'opacity': 1}, 1000);
                    var pluginsurl = '<?php echo NEWSWIRE_PLUGIN_ASSETS_URL?>';
                    ZeroClipboard.setMoviePath( pluginsurl + '/ZeroClipboard.swf');

                    $('.copy-to-clipboard').each(function(){

                            //Create a new clipboard client
                        var clip = new ZeroClipboard.Client();

                        //Cache the last td and the parent row
                        var lastTd = $(this);
                        //var parentRow = lastTd.parent("tr");


                        //Glue the clipboard client to the last td in each row
                        clip.glue(lastTd[0]);

                        //Grab the text from the parent row of the icon
                        var txt = $(this).parents('.block').find('#'+$(this).attr('data-target')).find('.full-text').html();


                        clip.setText(txt);

                        //Add a complete event to let the user know the text was copied
                        clip.addEventListener('complete', function(client, text) {
                            //alert("Copied text to clipboard:\n" + text);
                        });

                    });
                });
            }, 3500);



            // add select text on click
            $('.pin_as_embed .block-footer textarea', $masonry ).click(function(e){
                 $(this).select();
                 e.preventDefault();
            });


            $('.pin_as_text, .pin_as_quote', $masonry ).each(function(i,e){
                var item = $(this);
                $('.full-text-btn', item).click(function(){

                    $('.full-text',item).toggle();
                    $('.less-text',item).toggle();
                    $masonry.masonry('reload');

                    if ( $('.full-text:hidden', item).length ){
                        $('.full-text-btn',item).text('Full Text');
                    } else {
                        $('.full-text-btn',item).text('Collapse');

                    }

                })
            });

        })(jQuery);

        //-->
        </script><?php
}//end method

    /**
     * Update cached template list - wp page attributes
     *
     * not being used since version dev.0.0.15
     */
    public function register_pressroom_templates($atts) {

        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());

        // Retrieve the cache list. If it doesn't exist, or it's empty prepare an array
        $templates = wp_cache_get($cache_key, 'themes');
        if (empty($templates)) {
            $templates = array();
        }// end if

        // Since we've updated the cache, we need to delete the old cache
        wp_cache_delete($cache_key, 'themes');

        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge($templates, $this->templates);

        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add($cache_key, $templates, 'themes', 1800);

        return $atts;

    }

    /**
     *  Add body class
     *
     *  @todo: not being hook  yet
     **/
    public function body_class($classes) {
        $classes[] = 'pressroom-template-' . $this->pressroom_theme;
        return $classes;
    }

    /**
     * Admin-  Modify post title label fro mdifferent block type (pressroom )
     */
    public function modify_post_title_label($title, $post) {
        switch ($post->post_type) {
            case 'pin_as_social':
                add_filter('the_editor', array($this, 'add_placeholder_text_pin_as_social'));
                remove_all_actions('media_buttons');
                return 'Enter Social Media Name - <span>i.e. Facebook</span> ';
                break;
            case 'pin_as_quote':
                add_filter('the_editor', array($this, 'add_placeholder_text_pin_as_quote'));
                remove_all_actions('media_buttons');
                return 'Enter title here - <span>i.e. Quote from CEO</span> ';

                break;
            case 'pin_as_link':
                return 'Enter title here - i.e. Links ';
                break;
            case 'pin_as_embed':
                add_filter('the_editor', array($this, 'add_placeholder_text_pin_as_embed'));
                remove_all_actions('media_buttons');
                return $title;
                break;
            default:
                return $title;
                break;
        }
    }

    /**
     * Admin
     * removed richedit capability when editing specific post
     */
    function remove_richedit_from_editor($c) {

        global $post_type;

        if (in_array($post_type, array('pin_as_embed',  'pin_as_social'))) {
            return false;
        }

        return $c;
    }

    public function add_placeholder_text_pin_as_social($editor) {
        return '<label id="placeholder-text-editor-pin-as-social" for="wp-content-editor-container">Enter social media embed code. Make sure to size to 300px wide.</label>' . $editor;
    }
    public function add_placeholder_text_pin_as_quote($editor) {
        return '<label id="placeholder-text-editor-pin-as-quote" for="wp-content-editor-container">Enter quote here.</label>' . $editor;
    }
    public function add_placeholder_text_pin_as_embed($editor) {
        return '<label id="placeholder-text-editor-pin-as-embed" for="wp-content-editor-container">Enter embed code here. Make sure to size to 300px wide.</label>' . $editor;
    }

    public function add_meta_box_quote() {
        add_meta_box($id = 'newswire-pressroom-quote', $title = 'Enter Quote source, i.e DE Brown, Neswire Network, LTD', $callback = array($this, 'print_quote_metabox'), $screen = 'pin_as_quote', $context = 'advanced', $priority = 'core', $callback_args = null);
    }

    /**
     * Admin Metabox - Quote
     */
    function print_quote_metabox() {
        global $post;
        $post_meta = get_post_meta($post->ID, NEWSWIRE_POST_META_CUSTOM_FIELDS, $single = true);
        ?><div class="widefat">

                <textarea rows="6" style="width: 100%" name="newswire_data[quote_source]"><?php echo $post_meta['quote_source']?></textarea><br/>
                 <div class="description">Quote Source Link:</div>
                <input style="width: 100%" name="newswire_data[quote_source_url]" type="text" value="<?php echo $post_meta['quote_source_url']?>">
               

            </div>
        <?php
        wp_nonce_field(basename(dirname(dirname(__FILE__))), NEWSWIRE_POST_META_NONCE);
    }

    /**
     * Create contact pin metabox
     */
    public function init_contact_metabox() {
        add_meta_box($id = 'newswire-pressroom-contact', $title = 'Contact Details', $callback = array($this, 'print_contact_metabox'), $screen = 'pin_as_contact', $context = 'advanced', $priority = 'core', $callback_args = null);

    }//end method

    /**
     * link metabox callback from register_post_type
     */
    public function init_link_metabox() {
        add_meta_box($id = 'newswire-pressroom-link', $title = 'Add Links', $callback = array($this, 'print_link_metabox'), $screen = 'pin_as_link', $context = 'advanced', $priority = 'core', $callback_args = null);
    }

    public function add_metabox_header_script() {

        //add_meta_box($id = 'newswire-pressroom-scripts', $title = 'Header Scripts', $callback = array($this, 'print_header_metabox'), $screen = 'pin_as_social', $context = 'advanced', $priority = 'core', $callback_args = null);
    }

    /**
     * metabox callbock specific for pin_as_image block/post type
     */
    public function add_metabox_image_gallery() {

        add_meta_box($id = 'newswire-pressroom-image', $title = 'Upload Images', $callback = array($this, 'print_imagegallery_metabox'), $screen = 'pin_as_image', $context = 'advanced', $priority = 'core', $callback_args = null);
        wp_enqueue_script('plupload-all');
        wp_enqueue_script('pin_as_image_js', NEWSWIRE_PLUGIN_ASSETS_URL . '/js/pin_as_image.js', 'jquery', null, true);
    }

    /**
     * ajax callback for custom wp list table
     */
    public function _ajax_fetch_custom_list_callback() {
        require_once 'pressroom-attachment-list-table.php';
        $wp_list_table = new Pressroom_Image_Attachment_List_Table;
        $wp_list_table->ajax_response();
    }

    /**
     * Admin hook callback
     *
     * attach uploaded images from pin_as_image post type
     */
    public function save_pin_as_image($post_ID, $post = null, $update = null) {
        //from pin_as_image
        // unhook this function so it doesn't loop infinitely

        remove_action('save_post_pin_as_image', array($this, 'save_pin_as_image'));

        if (isset($_POST['pin_attachment']) && is_array($_POST['pin_attachment'])) {
            foreach ($_POST['pin_attachment'] as $attachment_id) {
                $attachment = array('ID' => $attachment_id, 'post_parent' => $post_ID);
                //  var_dump($attachment)   ;
                wp_update_post($attachment);
            }
        }
        //exit;
    }

    public function print_header_metabox() {

        global $post;
        $post_meta = get_post_meta($post->ID, NEWSWIRE_POST_META_CUSTOM_FIELDS, $single = true);
        
            ?><div class="widefat">
                <textarea cols="100" name="newswire_data[header_scripts]"><?php echo $post_meta['header_scripts']?></textarea><br/>
        
            </div>
        <?php
        wp_nonce_field(basename(dirname(dirname(__FILE__))), NEWSWIRE_POST_META_NONCE);

    }
    /**
     * Admin Metabox - Add Image Album
     *
     */
    public function print_imagegallery_metabox() {
        //media_upload_form( );

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

        <?php
$this->update_attachment_listing();

        wp_reset_query();

        ?><?php
}//end method

    /**
     * Add contact
     *
     * Metabox
     */
    public function print_contact_metabox() {

        global $post;

        $post_meta = get_post_meta($post->ID, NEWSWIRE_POST_META_CUSTOM_FIELDS, $single = true);

        ?><div><?php
wp_nonce_field(basename(dirname(dirname(__FILE__))), NEWSWIRE_POST_META_NONCE);
        ?><div>
                <div id="nwire-company-info" class=""  >
                    <p class="howto"><?php echo $newswire_config['tooltip']['pressroom_contact_pin']?></p>
                    <?php echo newswire_generate_meta_box_fields(newswire_post_meta_box_elements('pressroom_contact_pin'), $post_meta);?>
                </div>
            </div>
        </div>
    <?php
}

    /**
     * Admin Metabox - Add link Block
     *
     */
    public function print_link_metabox() {

        global $post;

        $post_meta = get_post_meta($post->ID, NEWSWIRE_POST_META_CUSTOM_FIELDS, $single = true);

        ?><div>
            <?php
wp_nonce_field(basename(dirname(dirname(__FILE__))), NEWSWIRE_POST_META_NONCE);
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

                    <tr>
                        <td style="width: 3%">Delete</td>
                        <th style="text-align: center">Anchor Text</th>
                        <th class="no-background"> </th>
                        <th style="text-align: center">Target URL</th>
                    </tr>
                <?php
$total = count($post_meta['text']);
        if ($total < 5) {
            $total = 5;
        }

        for ($ctr = 0; $ctr < $total; $ctr++) {?>
                    <tr class="pin_as_link_row">
                        <td style="width: 3%"><input type="checkbox" name="delete_index[]" value="<?php echo $ctr?>"></td>
                        <td><input type="text" name="newswire_data[text][]" id="company_tickers" value="<?php echo (isset($post_meta['text'][$ctr])) ? $post_meta['text'][$ctr] : ''?>" style="width:100%;"></td>
                        <td style="width: 5%">&nbsp;</td>
                        <td><input type="text" name="newswire_data[link][]" id="company_tickers" value="<?php echo (isset($post_meta['link'][$ctr])) ? $post_meta['link'][$ctr] : ''?>" style="width:100%;"></td>
                    </tr>
                <?php }?>

                </tbody></table>
                    <a class="button" id="pin_as_link_add_row">+Add another link</a>
                </div>
            </div>
        </div>
    <?php
}

    /*
     * Admin - Hack admin active menu
     */
    function newswire_resolve_active_menus($parent_file) {

        //return $parent_file;
        global $typenow, $pagenow, $submenu_file, $post;

        //specific to PRReporter
        if (current_user_can('PRReporter')) {

            if ($typenow == 'pr' && $pagenow == 'edit.php') {

                $parent_file = 'edit.php?post_type=pressroom';

                return $parent_file;
            }

            if ($typenow == 'pr' && $pagenow == 'post.php') {

                $parent_file = 'edit.php?post_type=pressroom';

                return $parent_file;
            }

        } elseif (current_user_can('author') || current_user_can('editor')) {

            if ($typenow == 'pr' && $pagenow == 'post.php') {

                $parent_file = 'edit.php?post_type=pressroom';

                $submenu_file = 'edit.php?post_type=pressroom';

                return $parent_file;
            }
        }

        //all other roles goes here

        if ($typenow == 'pr' && $pagenow == 'post-new.php') {

            $submenu_file = 'post-new.php?post_type=pr';

            $parent_file = 'edit.php?post_type=pressroom';

            return $parent_file;

        } elseif ($parent_file == 'edit.php?post_type=pr' && $submenu_file == 'edit.php?post_type=pr' && $pagenow== 'post.php') {

            //lastchanges
            //$submenu_file = ('edit.php?post_type=pr');
            $submenu_file = 'post-new.php?post_type=pr';

            $parent_file = 'edit.php?post_type=pressroom';

            return $parent_file;

        } elseif ($parent_file == 'edit.php?post_type=pr' && $submenu_file == 'post-new.php?post_type=pr') {
            $submenu_file = ('post-new.php?post_type=pr');
            $parent_file = 'edit.php?post_type=pressroom';

        } elseif ($submenu_file == 'post-new.php?post_type=pr' || ($submenu_file == 'edit.php?post_type=pr')) {
            //$submenu_file = str_replace('=press, replace, subject)
            //   echo $submenu_file;
            //   echo $parent_file;
            $parent_file = 'edit.php?post_type=pressroom';

        } elseif ( !empty($_REQUEST['page']) && $_REQUEST['page'] == 'newsroom-settings') {
            
            return $parent_file;

        } elseif ('post-new.php' == $pagenow && in_array($typenow, newswire_pressroom_blocks())) {
            
            $submenu_file = ('post-new.php?post_type=' . $_GET['post_type']);
            $parent_file = 'edit.php?post_type=pressroom';

        } elseif ('edit.php' == $pagenow && (get_query_var('post_type') && $typenow == 'pr')) {
            //edit.php?post_type=pr
            $submenu_file = ('edit.php?post_type=pr');
            $parent_file = 'edit.php?post_type=pr';
        } elseif ($typenow == 'pr') {
            //edit.php?post_type=pr
            $submenu_file = ('edit.php?post_type=pr&page=newsroom-settings');
            $parent_file = 'edit.php?post_type=pr';

        } elseif ('edit.php' == $pagenow && (get_query_var('post_type') && $typenow == 'pressroom')) {
            //edit.php?post_type=pr
            $submenu_file = ('edit.php?post_type=pressroom');
            $parent_file = 'edit.php?post_type=pressroom';

        } elseif ('post.php' == $pagenow && in_array($typenow, newswire_pressroom_blocks())) {

            $post_type = get_post_type($post->ID);

            $submenu_file = ('edit.php?post_type=pressroom');
            $parent_file = 'edit.php?post_type=pressroom';
        } elseif ('post.php' == $pagenow && in_array($typenow, array('pr'))) {
            $submenu_file = ('edit.php?post_type=pressroom');
            $parent_file = 'edit.php?post_type=pressroom';
        } elseif ($parent_file == 'edit.php?post_type=pr') {
            $submenu_file = ('post-new.php?post_type=pr');
            $parent_file = 'edit.php?post_type=pressroom';

        }
        //var_dump($submenu_file);
        //var_dump($parent_file);
        //echo $submenu_file;
        return $parent_file;
    }

}//end class