<div class="block-header">
    <h2 class="title"><?php echo apply_filters('pin_as_text_title', get_the_title());?></h2>
</div>
<div class="block-content" id="content-uid-<?php echo get_the_ID()?>">

        <div class="full-text">
            <h2><span class="quote">“</span><?php echo get_the_content()?><span class="quote">”</span></h2>
        </div>

         <div class="less-text"><h2><span class="quote">“</span><?php
echo substr(get_the_content(), 0, 750);

if (strlen(get_the_content()) > 750) {

	echo ' ...';
}

?><span class="quote">”</span></h2></div>


</div>

<?php

$data = newswire_data();
if ($data['quote_source']) {
	echo '<div class="block-footer " >';
	$data['quote_source'];
	?><div itemscope itemprop="author" itemtype="http://schema.org/Person">
          <?php newswire_ifprint('<a href="' . $data['quote_source_url'] . '" target="_blank" rel="nofollow"><span itemprop="name" >%s</span></a>', $data['quote_source']);?>
        </div>
    <?php
echo '</div>';
}?>
<?php
/*
<div class="block-footer ">
<a class="full-text-btn">Full Text</a>
<a href="#"><span class="copy-to-clipboard" title="Copy to clipboard" data-target="content-uid-<?php echo get_the_ID() ?>" style="z-index: 1; ">Copy to Clipboard</span></a>
</div>
 */