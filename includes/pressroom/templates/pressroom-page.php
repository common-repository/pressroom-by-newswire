<div class="pin pin_as_text">
    <div class="block-header">

    
    <h3 class="title">
        <?php echo apply_filters('pin_as_text_title', get_the_title() );?></h3>
    </div>
    <div class="block-content">
        <?php 
            the_content();
         ?>
    </div>
    <div class="block-footer">

    </div>
</div>