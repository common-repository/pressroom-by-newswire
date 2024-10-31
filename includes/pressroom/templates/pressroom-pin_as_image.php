<div class="block-header">
    <h2 class="title"><?php echo apply_filters('pressroom_block_title', get_the_title(), 'pin_as_image' );?></h2>
</div>
<!-- image album slider //-->
<div class="slider-wrapper theme-default block-content">
        <div class="ribbon"></div>
        <div class="pin_as_image_slider nivoSlider" style="height:300px: overflow: hidden">
            <?php
                $meta = newswire_data();
                $images = get_children( 'post_type=attachment&post_mime_type=image&post_parent='.get_the_ID() );
                foreach($images as $image) {

                   // $meta_data = wp_get_attachment_metadata( $image->ID, $unfiltered = true);
                    $alt_text = get_post_meta( $image->ID, '_wp_attachment_image_alt', true );
                    $title = sprintf('%s %s %s', $image->post_excerpt, htmlentities('<br/>'), $image->post_content );
                    $attr = array("title"=> $title, 'alt'=>$alt_text );
                    if ( $title == '' ) $attr = array();
                    //var_dump($image);
                    //exit;
                    if ( !empty($meta['thumbnail_size'] ) )
                        echo wp_get_attachment_image( $image->ID, $meta['thumbnail_size'], false, $attr);
                    else
                        echo wp_get_attachment_image( $image->ID, 'pin_as_image_thumb', false, $attr);
                }
            ?>           
        </div> 
        <div class="nivo-controlNav"></div>      
    </div>  

<!--<div class="content" id="content-uid-<?php echo get_the_ID() ?>">

    <?php 
        //album text
        //
    ?>
    
</div>//-->
<div class="block-footer">    
    <a title="Image Album - <?php echo get_the_title() ?>" class="thickbox" href="<?php echo  pin_as_image_download_link(get_the_ID()) ?>" class="" target="_blank">View All and Download</a>
</div>


