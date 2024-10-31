<div class="pin pin_as_text">
    <h3 class="title"><?php echo apply_filters('pin_as_text_title', get_the_title() );?></h3>
    <div class="block-content">
        <?php 
            the_excerpt();
         ?>
    </div>
    <div class="block-footer">

    </div>
</div>