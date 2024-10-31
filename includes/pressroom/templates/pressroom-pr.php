<div class="block-header">
    <h2 class="title"> <?php echo apply_filters('pin_as_text_title', get_the_title());?> - <span class="pr-date"><?php echo get_the_date();?></span></h2>
</div>
<?php
//enter thumbnail
$meta = get_post_meta(get_the_ID(), NEWSWIRE_POST_META_CUSTOM_FIELDS, true);

if (!is_wp_error($meta) && $meta['img_url']) {
	//echo 'test';
	printf('<a href="%s"  class="pin-thumb" title="%s"><img src="%s" border="0" width="300px" /></a>', get_permalink(), @$meta['img_caption'], (string) $meta['img_url']);
}

?>
<div class="block-content" id="content-uid-<?php echo get_the_ID()?>">

    <?php
/* $_options = newswire_options();

if ( $link =  get_post_meta(  get_the_ID(), 'rss_source_url', true) && $_options['pressroom_rss_linkto'] != 'wp' ) {

$permalink = get_post_meta(  get_the_ID(), 'rss_source_url', true);

} else {

$permalink = get_permalink();
} */
$permalink = get_permalink();
?><div class="full-text">
        <?php the_excerpt();?>
    </div>
</div>
