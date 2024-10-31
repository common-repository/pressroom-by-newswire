<div class="block-header">
    <h2 class="title"><?php echo apply_filters('pin_as_contact_title', get_the_title() );?></h2>
</div>
<div class="block-content" id="content-uid-<?php echo get_the_ID() ?>">
<?php 
    $data = get_post_meta(get_the_ID(), 'newswire_data', true );
    echo '<ul>';   
    if ( is_array($data['text']) ) 
        foreach($data['text'] as $i=>$text){
            if ( $data['link'][$i] != '' && $text != '')
            printf('<li class="link"><a href="%s" target="_blank" title="%s" rel="nofollow">%s</a></li>', $data['link'][$i], $data['link'][$i], $text);
        }
    echo '</ul> ';
?>
</div>
