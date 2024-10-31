<?php
/**
*
*/
if ( !function_exists('newswire_metabox_pin_as_latestpr')):
/**
* Add metabox for pin_as_content
*/
function newswire_metabox_pin_as_latestpr() {
    add_meta_box(
        $id = 'newswire-pressroom-latespr',
        $title = 'Manually add content here',
        $callback = 'newswire_latestpr_block_interface',
        $screen = 'pin_as_latestpr', $context = 'advanced',
        $priority = 'core',
        $args = null
    );
}
function newswire_maybe_check($key, $meta, $default=false) {
    if ( isset($meta[$key]) && !empty($meta[$key]) ) {
        return 'checked="checked"';
    } else if ( isset($meta[$key]) && empty($meta[$key]) ) {
        return '';
    } else {
        if ( $default )
        //check by default if not set?
            return 'checked="checked"';
        else
            return '';
    }
    return '';
}
/**
* Metabox handler
*/
function newswire_latestpr_block_interface() {
    global $post;
    $post_meta = wp_parse_args( get_post_meta($post->ID, NEWSWIRE_POST_META_CUSTOM_FIELDS, $single = true), 
        array(
            'include_local_pressreleases'=> '1',
            'total_per_block' => 3,
            'page_listing_template' => '',
            'show_date' => 0,
            'show_source' => 0,
            'total_per_page' => 10
            ) );
//var_dump($post_meta);
    ?><div class="widefat" id="pin_as_latespr_metabox">
    <p><input type="checkbox" value="1" name="newswire_data[include_local_pressreleases]" <?php echo newswire_maybe_check('include_local_pressreleases', $post_meta, true) ?>> Automatically add all new press releases to this block
        <p><input type="checkbox" value="1"  name="newswire_data[show_date]" <?php echo newswire_maybe_check('show_date', $post_meta, false) ?>> Display date of press releases
            <p><input type="checkbox" value="1"  name="newswire_data[show_source]" <?php echo newswire_maybe_check('show_source', $post_meta, false) ?>> Display source of press releases
            <p><input type="text" value="<?php echo $post_meta['total_per_block'] ?>" name="newswire_data[total_per_block]" size="4"> Number of press releases to display on block
           
            <p><input type="checkbox" value="1" name="newswire_data[create_page_listing]" value="1" <?php echo newswire_maybe_check('create_page_listing', $post_meta, false) ?>>  Create  Press Release page on wordpress 
                Page: <?php 
                    $assigned_page_template = $post_meta['page_listing_template'];

                    echo wp_dropdown_pages(array(
                        'name' => 'newswire_data[page_listing_template]',
                        'echo' => false,
                        'show_option_none' => __('- None -', 'newswire'),
                        'selected' => !empty($assigned_page_template) ? $assigned_page_template : false,
                    ));

                ?>
            <br>or
            use this shortcode on a page of your own design to display your list of press releases <code>[latest_press_releases]</code>
                
               
            <p class="hidden"><input type="text" size="4" name="newswire_data[total_per_page]" value="<?php echo $post_meta['total_per_page'] ?>"> Number of press release to display on press release page
            
            <div>
                <div id="" class=""  >
                    <table class="form-table table-stripe" style="background-color: #eee">
                        <thead>
                            <tr>
                                <th style="width: 4%;" nowrap="true"><input type="checkbox"  class="select-all"> <span style="font-size:11px"><?php _e('Select All', 'newswire') ?></span></th>
                                
                                <th style="text-align: center; width: 10%"><?php _e('Thumbnail', 'newswire') ?></th>
                                <th style="text-align: center"><?php _e('Date', 'newswire') ?></th>
                                <th style="text-align: center;width: 15%"><?php _e('Source', 'newswire') ?></th>
                                <th style="text-align: center;width: 30%"><?php _e('Article Title', 'newswire') ?></th>
                                <th style="text-align: center; width: 30%"><?php _e('Press Release URL', 'newswire') ?></th>
                            </tr>
                            <tbody>
                                <?php
                                if ( empty($post_meta['custom_press_release']) ):
                                ?>
                                <tr data-counter="1">
                                    <td><input type="checkbox" class="check-list"></td>
                                    <td><button class="select-image">Select Image</button>
                                        <div class="img-preview hidden"></div>
                                        <input type="hidden" class="img-attachment-id" name="newswire_data[custom_press_release][0]["thumbnail"]" value="">
                                    </td>
                                    <td>
                                        <input type="text" size="10" class="date-picker calendar event_calendar" name="newswire_data[0]['date']">

                                    </td>
                                    <td><input type="text" name="newswire_data[custom_press_release][0][source]" style="width: 100%"></td>
                                    <td><input type="text" name="newswire_data[custom_press_release][0][title]" style="width: 100%"></td>
                                    <td><input type="text" name="newswire_data[custom_press_release][0][url]" style="width: 100%"></td>
                                </tr>
                                <?php else:
                                foreach($post_meta['custom_press_release'] as $i=>$row):
                                    if ( empty($row['thumbnail'] )) $row['thumbnail'] = '';
                                    if ( empty($row['date'] )) $row['date'] = '';
                                ?><tr data-counter="<?php echo $i; ?>">
                                    <td><input type="checkbox" class="check-list"></td>
                                    <td>
                                        <?php if ( empty($row['thumbnail']) ) : ?>
                                        <button class="select-image">Select Image</button>
                                        <?php endif; ?>
                                        <div class="img-preview ">
                                            <?php
                                            if ( !empty($row['thumbnail']) ) 
                                            printf('<img src="%s" width="100%%" class="thumbnail select-image">', wp_get_attachment_image_src($row['thumbnail'] , 'thumbnail' )[0]); ?>
                                        </div>
                                        <?php ?>
                                        <input type="hidden" class="img-attachment-id" name="newswire_data[custom_press_release][<?php echo $i; ?>][thumbnail]" value="<?php echo $row['thumbnail'] ?>">
                                    </td>
                                    <td>
                                         <input size="10" type="text" class="date-picker calendar event_calendar" name="newswire_data[custom_press_release][<?php echo $i; ?>][date]" value="<?php echo $row['date'] ?>">
                                    </td>
                                    <td><input type="text" name="newswire_data[custom_press_release][<?php echo $i; ?>][source]" style="width: 100%" value="<?php echo $row['source'] ?>"></td>
                                    <td><input type="text" name="newswire_data[custom_press_release][<?php echo $i; ?>][title]" style="width: 100%" value="<?php echo $row['title'] ?>"></td>
                                    <td><input type="text" name="newswire_data[custom_press_release][<?php echo $i; ?>][url]" style="width: 100%" value="<?php echo $row['url'] ?>"></td>
                                </tr>
                                <?php
                                endforeach;
                                endif;
                                ?>
                            </tbody>
                        </thead>
                    </table>
                    <a class="button delete-selected">Delete Selected</a>
                    <a class="button  button-primary add-new-row" >+Add another</a>
                </div>
            </div><?php
            wp_nonce_field( NEWSWIRE_POST_META_NONCE, NEWSWIRE_POST_META_NONCE);
            }
    endif;


    function newswire_recent_press_releases($meta= null) {
           /**
             * The WordPress Query class.
             * @link http://codex.wordpress.org/Function_Reference/WP_Query
             *
             */
            $args = array(
                
                //Type & Status Parameters
                'post_type'   => 'pr',
                'post_status' => 'publish',
        
                
                //Order & Orderby Parameters
                'order'               => 'DESC',
                'orderby'             => 'date',
             
                 //Pagination Parameters
                'posts_per_page'         => 20,
                'posts_per_archive_page' => 10,
                'nopaging'               => false,
                
                'meta_query' => array(
                    array(
                        'key' => 'rss_source_url',
                        'compare' => 'NOT EXISTS'
                    )
                ),

              
                //Parameters relating to caching
                'no_found_rows'          => false,
                'cache_results'          => true,
                'update_post_term_cache' => true,
                'update_post_meta_cache' => true,
                
            );
       

        return new WP_Query( $args );
        
    }

    function newswire_custom_press_releases($meta, $post) {
        //var_dump($meta);
        $prs = $meta['custom_press_release'];
        if ( !empty($prs) ) {
            foreach($prs as $pr ) :
                //if ($pr['title']) continue;
                if ( isset($pr['thumbnail']) && !empty($pr['thumbnail']))
                    $image = sprintf('<img src="%s" width="100px" >', wp_get_attachment_image_src($pr['thumbnail'])[0]);
                else
                    continue;
                   // $image = '<p>No Image</p>';

                ?><li>
                <div class="latest-thumbnail thumbnail"><?php echo $image; ?></div>
                <div class="info">
                    <div class="meta"><?php 
                    if ( !empty($meta['show_date']) )
                        echo date('F d, Y', strtotime($pr['date']));
                        //echo $pr['date'] 
                ?><?php
                        if ( !empty($meta['show_date']) && !empty($meta['show_source'])) echo '<br>';
                     ?><?php if ( !empty($meta['show_source'] )) echo $pr['source'] ?></div>
                    <p class="title"><a href="<?php echo $pr['url'] ?>"><?php echo $pr['title'] ?></a></p>
                </div></li><?php

            endforeach;
        }
    }

    function newswire_list_local_recent($meta) {
        global $post;
        $q = newswire_recent_press_releases();
       // Start the loop.
        while ( $q->have_posts() ) : $q->the_post();
        setup_postdata( $post );
        if ( !get_the_ID()) continue;
        if ( has_post_thumbnail( $post->ID ) )
            $image = get_the_post_thumbnail( $post->ID, 'pin_as_contact_thumb' );
        else {
            $image_id = newswire_get_image_id(newswire_data($post->ID, 'img_url'));
            $image = wp_get_attachment_image_src($image_id, 'pin_as_contact_thumb');
        
            $image = sprintf('<img src="%s" width="100px" >', $image[0]);
        }
            ?><li>
            <div class="latest-thumbnail thumbnail"><?php echo $image; ?></div>
            <div class="info">
                <div class="meta"><?php 
                    if ( !empty($meta['show_date']) )
                        echo get_the_date() ;
                    if ( !empty($meta['show_date']) && !empty($meta['show_source'])) echo '<br>';
                    if ( !empty($meta['show_source'] )) echo get_bloginfo(); ?></div>
                        
                <p class="title"><a href="<?php echo get_permalink() ?>"><?php the_title();?></a></p>
            </div></li><?php

        endwhile;
        wp_reset_query();
        wp_reset_postdata();

    } 


    add_shortcode('latest_press_releases', 'newswire_latestpr_shortcode' );
    function newswire_latestpr_shortcode($atts){
        global $post;

        $options = newswire_options();
        $page = get_post( $options['latest_press_releases'] );
//var_dump($page);

        if ( !$page ) return;

        $meta = newswire_data($page->ID);

        ?><ul class="list latestpr-<?php echo $itemID =  get_the_ID() ?>"><?php
        
        newswire_list_local_recent($meta);
        
        //print custom
        newswire_custom_press_releases($meta, $page);


       ?></ul><?php

        

    }




