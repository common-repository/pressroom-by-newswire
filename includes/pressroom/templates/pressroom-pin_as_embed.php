<div class="block-header">
    <h2 class="title"><?php echo apply_filters('pin_as_text_title', get_the_title() );?></h2>
</div>
<div class="block-content" id="content-uid-<?php echo get_the_ID() ?>">
    <?php 
        echo apply_filters('pressroom_pin_content', get_the_content());
    ?>
      
</div>
<div class="block-footer">
     <span class="embed"> <> Embed</span>
    <p><textarea><?php echo htmlentities(get_the_content()) ?></textarea></p>
</div>
