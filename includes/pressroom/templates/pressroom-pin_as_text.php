
   <div class="block-header">
        <h2 class="title"><?php echo apply_filters('pin_as_text_title', get_the_title() );?></h2>
    </div>
    <div class="block-content" id="content-uid-<?php echo get_the_ID() ?>">
        <div class="full-text">
            <?php the_content() ?>
        </div>
        <div class="less-text"><?php 
            echo substr(get_the_content(), 0, 500);
            if ( strlen(get_the_content()) > 500 ) {
                echo ' ...';
            }    
        ?></div>
       
    </div>
    <div class="block-footer">
        <a class="full-text-btn">Full Text</a>
        <a class="copy-to-clipboard" title="Copy to clipboard" data-clipboard-target="content-uid-<?php echo get_the_ID() ?>" style="z-index: 1; ">Copy to Clipboard</a>
    </div>
