
   <div class="block-header">
        <h2 class="title"><?php echo apply_filters('pin_as_latestpr_title', get_the_title() );?></h2>
    </div>
    <div class="block-content" id="content-uid-<?php echo get_the_ID() ?>">
       <ul class="list latestpr-<?php echo $itemID =  get_the_ID() ?>"><?php
        global $post;
        $meta = newswire_data();
        
        if ( $meta['include_local_pressreleases'] ) {
            newswire_list_local_recent($meta);
        }
        //print custom
        newswire_custom_press_releases($meta, $post);

       ?></ul>
    </div>
    <div class="block-footer" >
        <?php if ( !empty($meta['create_page_listing']) && $meta['page_listing_template'] ): ?>
            <p class="aligncenter"><a href="<?php echo get_permalink( $meta['page_listing_template']) ?>" id="see-more-<?php echo $itemID ?>" class="">See More</a></p>     
        <?php else: ?>
            <p class="aligncenter"><a href="#" id="see-more-<?php echo $itemID ?>" class="">See More</a></p>
        <?php endif;?>

        
    </div>
    <script type="text/javascript">
    <!-- 
    jQuery(document).ready(function($){
        jQuery(".pin_as_latestpr ul.latestpr-<?php echo $itemID ?>  li:lt('<?php echo $meta['total_per_block'] ?>')").css('display', 'block'); 
        <?php if ( empty($meta['create_page_listing']) ): ?>
        jQuery('#see-more-<?php echo $itemID ?>').click(function(e){
            e.preventDefault();
            var btn = jQuery(e.target);
           
            if ( window.$masonry )
            $masonry.masonry('reload');
            if ( btn.text() == 'See More') {
                jQuery(".pin_as_latestpr ul.latestpr-<?php echo $itemID ?>  li:lt('<?php echo $meta['total_per_page'] ?>')").css('display', 'block'); 
                btn.text('Less');
            } else {
                jQuery(".pin_as_latestpr ul.latestpr-<?php echo $itemID ?>  li").hide(); 
                jQuery(".pin_as_latestpr ul.latestpr-<?php echo $itemID ?>  li:lt('<?php echo $meta['total_per_block'] ?>')").css('display', 'block'); 
                btn.text('See More');
            }


        });
        <?php endif; ?>
    });
    //-->
    </script>
