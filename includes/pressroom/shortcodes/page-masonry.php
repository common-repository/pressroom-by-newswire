<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom shortcodes
 *
 * @author: Newswire
 *
 */
class Newswire_Pressroom_Masonry_Shortcodes {

    /**
     * query string for page number
     */
    const NEWSROOM_PAGE_KEY = 'page';

    /**
     * Class constructor
     */
    public function __construct() {

        //check shortcodes from post->post_content
        add_action('wp', array($this, 'find_newswire_shortcode'));

        add_shortcode('pressroom_blocks', array($this, 'shortcode_content_pressroom'));
        
        //add filters
        //add_filter('newsroom_fetch_json_article', array($this, 'fetch_json_article_from_newswire') );
        add_filter('newsroom_fetch_json_article', array($this, 'wp_query_pr'));

        //filter to reduce width
        add_filter('pressroom_pin_content', array($this, 'reduce_iframe_width'));
    }

    /**
     * Find  shortcode from page beforehand
     *
     */
    public function find_newswire_shortcode() {

        global $post;

        wp_reset_postdata();

        $test = has_shortcode($post->post_content, 'pressroom_blocks');

        $content = $post->post_content;

        if ($test) {

            //remove
            add_filter('comments_template', array($this, 'remove_comments_template'));

            $options = newswire_options();

            //get shortcode attributes
            $pattern = '/\[(\[?)(' . 'pressroom_blocks' . ')(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)/';

            preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

            foreach ($matches as $block) {
                $attr = shortcode_parse_atts($block[3]);
            }
            $attr['type'] = 'pressroom';
            //set global
            $this->attr = $attr;


            //load css
            //$css = sprintf('newsroom-theme-%s.css', 'bootstrap');
            $css = 'default.css';
            $url = plugins_url( basename(NEWSWIRE_DIR).'/assets/css/' . $css);
            wp_register_style('newsroom-theme', $url);
            wp_enqueue_style('newsroom-theme');

            wp_enqueue_script('newswire-masonry');
            wp_enqueue_script('newswire-infinitescroll');

            add_action('wp_footer', array($this, 'print_masonry_scripts'), 1000);

            //add_action('wp_head', array($this, 'pressroom_custom_style'));
            //  add_filter('pressroom_pin_content', array($this,'reduce_iframe_width'));
            add_action('wp_enqueue_scripts', 'add_thickbox');
            wp_enqueue_style('nivoslider-css');
            wp_enqueue_style('nivoslider-theme');
            wp_enqueue_script('nivoslider-js');
            wp_enqueue_script('newswire-zeroclip');
            wp_enqueue_script('newswire-jplugin');
            //wp_enqueue_script('newswire-more');
            add_action('wp_head', 'newswire_print_pressroom_styles', 1000);
        } //end test
    }

    /**
     * Reduce iframe width of embedded and social media items/blocks
     */
    public function reduce_iframe_width($content) {

        //width:
        $content = preg_replace('|width:\s*([\d]*)px|', 'width:100%', $content);
        //$content  = preg_replace('|width:([\d]*)px|', 'width:278px', $content );
        //width=""
        $content = preg_replace('|width\s*=\s*["\'][\d]*[px]*["\']|', 'width="100%"', $content);
        //$content  = preg_replace('|width\s*=["\'][\d]*[px]*["\']|', 'width="278px"', $content );
        return $content;
    }

    /**
     * Footer scripts, infinitescroll and masonry
     */
    public function print_masonry_scripts() {

        global $post;

        $options = newswire_options();

        $this->attr['type'] == 'pressroom';

//        $pin_settings = $options[$this->attr['type'] . '_pin_settings'];

        //@extract($pin_settings);

        $container = '#tiles';
        $itemclass = '.pressroom-article';

//masonry
        ?><script type="text/javascript">
        (function($){

            //set body width for IE8
            if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
                var ieversion=new Number(RegExp.$1)
                if (ieversion==8) {
                    $('body').css('max-width', $(window).width());
                }
            }

            var $masonry = $('<?php echo $container?>'),
                $pinitems = $('<?php echo $itemclass?>', $masonry);

            var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            //$('#navigation').css({'visibility':'hidden', 'height':'1px'});
            //console.log($masonry.parent().width() );
            //check parent width
            if ( $masonry.parent().width() < 800 && w > 1024 ) {
                $masonry.parent().css('padding', 0);
                $masonry.parent().css('margin', '0' );
                $masonry.parent().css('max-width', '100%');
                $masonry.css('margin', '0 auto');
                //set li width
                var itemWidth = $masonry.parent().width()/3 - 25;
                 $("<style type='text/css'>#tiles > li h2 { } #tiles > li { width: "+itemWidth+"px}</style>").appendTo("head");
                //$pinitems.css('width', itemWidth+'px');

            } else if ( $masonry.parent().width() < 1200 && w > 1024) {
                $masonry.parent().css('padding', 0);
                $masonry.parent().css('margin', '0' );
                $masonry.css('margin', '0 auto');
                $masonry.parent().css('max-width', '100%');
                //set li width
                var itemWidth = $masonry.parent().width()/4 - 30;
                 $("<style type='text/css'>li h2 {  }  #tiles > li { width: "+itemWidth+"px}</style>").appendTo("head");
                //$pinitems.css('width', itemWidth+'px');
            }



            if ($(document).width() <= 480) {
                $masonry.imagesLoaded( function(){
                    $pinitems.show();
                    $masonry.masonry({
                        itemSelector : '<?php echo $itemclass?>',
                        isFitWidth: true
                    }).css('visibility', 'visible');
                   // $('#ajax-loader-masonry').hide();
                    $masonry.animate({opacity: 1});
                    $pinitems.animate({opacity: 1});
                });
            } else {


                $masonry.imagesLoaded( function(){
                   $pinitems.show();
                    $masonry.masonry({
                        itemSelector : '<?php echo $itemclass?>',
                        isFitWidth: true
                    }).css('visibility', 'visible');
                    $('#ajax-loader-masonry').hide();
                    $masonry.animate({opacity: 1});
                    $pinitems.animate({opacity: 1});
                });
            }
        })(jQuery);

//infinte scroll
        jQuery(document).ready(function($){

            var $masonry = $('<?php echo $container?>'),
                $pinitems = $('<?php echo $itemclass?>');

            $masonry.infinitescroll({
                navSelector : '#newswire-pin-navigation',
                nextSelector : '#newswire-pin-navigation #newswire-navigation-next a',
                itemSelector : '<?php echo $itemclass?>',
                 loading: {
                    finishedMsg: "<em>No more content...</em>",
                    msgText:    "<em>Loading...</em>",
                },
            }, function(newElements) {

                var $newElems = $(newElements).css({opacity: 0});

                if ($(document).width() <= 480) {
                    $newElems.imagesLoaded(function(){
                        //$('#infscr-loading').fadeOut('normal');
                        $newElems.animate({opacity: 1});
                        $masonry.masonry('appended', $newElems, true);
                        $masonry.find('<?php echo $itemclass?>').show();
                    });
                } else {
                    $newElems.imagesLoaded(function(){
                        //$('#infscr-loading').fadeOut('normal');
                        $newElems.animate({opacity: 1});
                        $masonry.masonry('appended', $newElems, true);
                        $masonry.find('<?php echo $itemclass?>').show();
                    });
                }
            });
            setInterval(function(){ $masonry.masonry('reload'); }, 3000);


           <?php if ($this->attr['type'] == 'pressroom') {?>
            $(window).load(function() {
                $('.pin_as_image_slider').nivoSlider({
                    effect: 'random',
                    controlNav: true,
                    afterChange: function(){
                        //$masonry.masonry('reload');
                    }
                });
            });

            var pluginsurl = '<?php echo NEWSWIRE_PLUGIN_ASSETS_URL?>';
            //ZeroClipboard.setMoviePath( pluginsurl + '/zeroclipboard/ZeroClipboard.swf');
            
            var client = new ZeroClipboard( $('.copy-to-clipboard') );

            client.on( 'ready', function(event) {
                //console.log( 'movie is loaded' );
            

                client.on( 'copy', function(event) {

                    alert('Content has been copied to clipboard');
                    
                } );

                client.on( 'aftercopy', function(event) {
                   // console.log('Copied text to clipboard: ' + event.data['text/html']);
                } );
            } );

          client.on( 'error', function(event) {
            // console.log( 'ZeroClipboard error of type "' + event.name + '": ' + event.message );
            ZeroClipboard.destroy();
          } );

            //var clip = new ZeroClipboard.Client($('.copy-to-clipboard'));

            /*$('.copy-to-clipboard').each(function(){

                    //Create a new clipboard client
                var clip = new ZeroClipboard.Client();

                //Cache the last td and the parent row
                var lastTd = $(this);
                //var parentRow = lastTd.parent("tr");


                //Glue the clipboard client to the last td in each row
                clip.glue(lastTd[0]);

                //Grab the text from the parent row of the icon
                var txt = $(this).parents('.pressroom-article').find('#'+$(this).attr('data-target')).find('.full-text').html();


                clip.setText(txt);

                //Add a complete event to let the user know the text was copied
                clip.addEventListener('complete', function(client, text) {
                   ;// alert("Copied text to clipboard:\n" + text);
                });

            });*/




            // add select text on click
            $('.pin_as_embed .block-footer textarea', $masonry ).click(function(e){
                 $(this).select();
                 e.preventDefault();
            });


            $('.pin_as_text, .pin_as_quote', $masonry ).each(function(i,e){
                var item = $(this);
                $('.full-text-btn', item).click(function(){

                    $('.full-text',item).toggle();
                    $('.less-text',item).toggle();



                    if ( $('.full-text:hidden', item).length ){
                        $('.full-text-btn',item).text('Full Text');
                    } else {
                        $('.full-text-btn',item).text('Collapse');

                    }

                    $masonry.masonry('reload');
                })
            });

            setInterval(function(){ $masonry.masonry('reload'); }, 3000);

            //set body width for IE8
            if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
                var ieversion=new Number(RegExp.$1)
                if (ieversion==8) {
                    $('body').css('max-width', $(window).width());
                }
            }


           $masonry.masonry('reload');
           setTimeout(function(){$masonry.masonry('reload')}, 2500);

          <?php } //endif ?>
        });
        </script><?php

        $page = intval((get_query_var('paged')) ? get_query_var('paged') : 1);
        $page++;
        $next_link = add_query_arg('page', $page, get_permalink($post->ID));
        $morepin = sprintf('<div class=""><a href="%s">Next</a></div>', get_next_posts_page_link());

        $html = '<div class="hidethis" id="newswire-pin-navigation">' .
        '<ul class="pager">'
        . '<li id="newswire-navigation-next">' . $morepin . '</li>'
        . ' <li id="newswire-navigation-previous"><a href="#"></a></li>'
        . '</ul>'
        . '</div>';
        if ( is_front_page() || is_home() ) {
            echo '';
        } else {
            echo $html;
        }

        
    } //end masonry scripts

    public function shortcode_content_pressroom($atts, $content = null) {

         $options = newswire_options();

        $final_attrs = shortcode_atts(array(
            'type' => 'pressroom', //pressroom or newsroom,
            'category' => 0,
            'layout' => 'default', //pin(default) or blog
        ), $atts, 'pressroom_blocks');

        /*if (!in_array('type', array_keys($atts))) {
            return 'Invalid Shortcode';
        }*/
/*
       

      

        //empty should work and default newsroom
        if ($final_attrs['type'] !='' && !in_array($final_attrs['type'], array('newsroom', 'pressroom'))) {
        
            return 'Invalid Shortcode';
        }

        
        if ('newsroom' == $final_attrs['type']) {

            $final_attrs = wp_parse_args(array('layout' => $options['newsroom_layout']), $final_attrs);

            return $this->newsroom($final_attrs, $content);

        } elseif ('pressroom' == $final_attrs['type']) {

            $final_attrs = wp_parse_args(array('layout' => $options['pressroom_layout']), $final_attrs);

            return $this->pressroom($final_attrs, $content);
        }
        */

        $final_attrs = wp_parse_args(array('layout' => $options['pressroom_layout']), $final_attrs);

        return $this->pressroom($final_attrs, $content);

    }

    /**
    * Create the pressroom page
    */
    public function pressroom($atts, $content = null) {

        global $post;

        $html = '';
        $template = '';

    
        //pins
        $css = 'default.css';
        $url = plugins_url(basename(NEWSWIRE_DIR).'/assets/css/' . $css);
        wp_register_style('newsroom-theme', $url);
        wp_enqueue_style('newsroom-theme');
        wp_enqueue_script('newswire-masonry');
        wp_enqueue_script('newswire-infinitescroll');
        add_action('wp_footer', array($this, 'print_masonry_scripts'), 1000);
        
        $template .= '<ul id="tiles" class="unstyled">';
        //run query pressroom
        $query = newswire_pressroom_query();
        while ($query->have_posts()):
            $query->the_post();
            $pin_type = $query->post->post_type;
            $file = sprintf('%s-%s.php', NEWSWIRE_DIR . '/includes/pressroom/templates/pressroom', $pin_type);
            $template .= '<li class="pressroom-article" ><div class="' . $pin_type . '">';
            $template .= get_pressroom_template($file);
            $template .= '<div></li>';
        endwhile;
        wp_reset_postdata();
        $template .= '</ul>';

        return $template;
    }

    /*
    *
    */
    public function wp_query_pr($attrs) {

        //get current page
        $page = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $tag = get_term_by('name', 'pressroom', 'post_tag');

        $args = array(
            'posts_per_page' => $attrs['totalperpage'],
            'posts_per_archive_page' => $attrs['totalperpage'],
            'nopaging' => false,
            'paged' => $page,
            'post_type' => 'pr',
            'tag__not_in' => $tag->term_id,
        );

        $query = new WP_Query($args);

        $this->totalitems = $query->found_posts / $attrs['totalperpage'] + 1;

        $articles = $query->get_posts();
        $results = array();
        foreach ($articles as $article) {
            $meta = get_post_meta($article->ID, 'newswire_data', true);

            $data = array();
            foreach ($meta as $k => $val) {
                $data[$k] = $val;
            }
            $data['article_id'] = $article->ID;
            $data['title'] = $article->post_title;
            $data['description'] = $article->post_excerpt;
            $data['photo_url'] = $data['img_url'];
            $data['img_caption'] = $meta['img_caption'];
            $data['permalink'] = get_post_meta($article->ID, 'rss_source_url', true);
            $results[] = $data;
        }

        return $results;
    }

    /**
     * implement new newsroom_loop
     * articles should be coming from plugin site
     */
    public function newsroom_loop_shortcode_callback_localonly($atts, $content = null) {

        //get pagination
        //$query = new WP_Query(array('post_type'=>'pr'=> 'tag'=> 'newsroom'));

        extract(newswire_options());

        $final_attrs = shortcode_atts(array(
            'as' => 'json', //defult as json - html, json , blog
            'excludecat' => '1,2', //category or subcat - not
            'page' => 1, //page number
            'parsequery' => true, //load next page based http query vars, like category filter and page
            'totalperpage' => 25, //newswire default
            'pinwidth' => $newsroom_pinwidth, //newswire default
            'container' => '#pin_items', //newswire default
            'itemclass' => '.pin_box', //newswire default
        ), $atts, 'newsroom_loop');

        extract($final_attrs);

        if ('json' == $as && $content != '') {

            $final_attrs = shortcode_atts(array(
                'as' => 'json', //defult as json - html, json , blog
                'excludecat' => '1,2', //category or subcat - not
                'page' => 1, //page number
                'parsequery' => true, //load next page based http query vars, like category filter and page
                'totalperpage' => 25, //newswire default
                'pinwidth' => $newsroom_pinwidth, //newswire default
                'container' => '#pin_items', //newswire default
                'itemclass' => '.pin_box', //newswire default
            ), $atts, 'newsroom_loop');

            extract($final_attrs);

            //set for later use in wp_footer
            newswire_var('shortcode_pin_settings', $final_attrs); //via shortcode
            newswire_var('newsroom_pin_is_on', 1); //via shortcode

            wp_enqueue_script('newswire-masonry');
            wp_enqueue_script('newswire-infinitescroll');

            //as json
            $html = $this->pintype_newsroom_custom($final_attrs, $content);
        } elseif ('blog' == $as && $content != '') {

            $final_attrs = shortcode_atts(array(
                'as' => 'json', //defult as json - html, json , blog
                'excludecat' => '1,2', //category or subcat - not
                'page' => 1, //page number
                'parsequery' => true, //load next page based http query vars, like category filter and page
                'totalperpage' => 10, //newswire default
                'pinwidth' => $newsroom_pinwidth, //newswire default
                'container' => '#pin_items', //newswire default
                'itemclass' => '.pin_box', //newswire default
            ), $atts, 'newsroom_loop');

            extract($final_attrs);

            //set global bar for later use in wp_footer
            //@todo - set scope this pluign only
            newswire_var('shortcode_pin_settings', $final_attrs); //via shortcode
            newswire_var('newsroom_pin_is_on', 1); //via shortcode

            wp_enqueue_script('newswire-masonry');
            wp_enqueue_script('newswire-infinitescroll');

            $html = $this->blog_newsroom($final_attrs, $content);

        } elseif ('pin' == $as) {

            //set for later use in wp_footer
            newswire_var('shortcode_pin_settings', $final_attrs); //via shortcode
            newswire_var('newsroom_pin_is_on', 1); //via shortcode

            wp_enqueue_script('newswire-masonry');
            wp_enqueue_script('newswire-infinitescroll');

            if ($newsroom_theme == 'newswire') {
                $html = $this->pintype_newsroom($final_attrs, $content);
            } else {
                $html = $this->pintype_newsroom_custom($final_attrs, $content);
            }

        }

        return $html;

    }

    /**
     * Loop through fetched newswire article
     *
     * [newsroom_loop as=blog] your-template-here [/newsroom_loop]
     * [newsroom_loop as=pin]
     */
    public function newsroom_loop_shortcode_callback($atts, $content = null) {

        extract(newswire_options());

        $final_attrs = shortcode_atts(array(
            'as' => 'json', //defult as json - html, json , blog
            'excludecat' => '1,2', //category or subcat - not
            'page' => 1, //page number
            'parsequery' => true, //load next page based http query vars, like category filter and page
            'totalperpage' => 20, //newswire default
            'pinwidth' => $newsroom_pinwidth, //newswire default
            'container' => '#pin_items', //newswire default
            'itemclass' => '.pin_box', //newswire default
        ), $atts, 'newsroom_loop');

        extract($final_attrs);

        //Loop as blog - must be fetchar article as json
        if ('blog' == $as && $content != '') {

            //set global bar for later use in wp_footer
            //@todo - set scope this pluign only
            newswire_var('shortcode_pin_settings', $final_attrs); //via shortcode
            newswire_var('newsroom_pin_is_on', 1); //via shortcode

            wp_enqueue_script('newswire-masonry');
            wp_enqueue_script('newswire-infinitescroll');

            $html = $this->blog_newsroom($final_attrs, $content);

        } elseif ('pin' == $as) {

            //set for later use in wp_footer
            newswire_var('shortcode_pin_settings', $final_attrs); //via shortcode
            newswire_var('newsroom_pin_is_on', 1); //via shortcode

            wp_enqueue_script('newswire-masonry');
            wp_enqueue_script('newswire-infinitescroll');

            $html = $this->pintype_newsroom($final_attrs, $content);
        } else {

            //set for later use in wp_footer
            newswire_var('shortcode_pin_settings', $final_attrs); //via shortcode
            newswire_var('newsroom_pin_is_on', 1); //via shortcode

            wp_enqueue_script('newswire-masonry');
            wp_enqueue_script('newswire-infinitescroll');

            //as json
            $html = $this->pintype_newsroom_custom($final_attrs, $content);

        }

        return $html;
    }

    public function fetch_json_article_from_newswire() {
        return json_decode($this->fetch_newswire_articles($shortcode_atts, 'json'), true);
    }

    public function pintype_newsroom_custom($shortcode_atts, $template) {

        //fetch articles as json
        $data = apply_filters('newsroom_fetch_json_article', $shortcode_atts);

        //extract($data);

        $this->totalitems = $totalitems;

        foreach ($data as $article) {

            $html .= newswire_article_template($article, $template);
        }

        //enqueue to footer

        return $html;

    }

    public function blog_newsroom($shortcode_atts, $content) {

        //$data = json_decode($this->fetch_newswire_articles( $shortcode_atts , 'json'), true );

        $data = apply_filters('newsroom_fetch_json_article', $shortcode_atts);

        extract($data);

        //$this->totalitems = $totalitems;

        foreach ($data as $article) {

            $html .= $this->parse_blog_item_template($article, $content);
        }

        return $html;
    }

    /**
     *
     * @todo - add category filter later
     */
    public function fetch_newswire_articles($settings, $call_format = 'html') {

        extract($settings);

        $options = newswire_options();

        $page = (get_query_var('paged')) ? get_query_var('paged') : 1;

        //get page number from query string or $settings?
        if ($parsequery) {

            $query_data = array('format' => $call_format, 'page' => $page, 'totalperpage' => $settings['totalperpage']);

        } else {

            // override query string and based content on whatever $settings has
            // can print newsroom as segmented using number of items and/or offset
            $query_data = array('format' => $call_format, 'page' => $page, 'totalperpage' => $settings['totalperpage']);
        }

        //setup category
        $query_data['category'] = urldecode($_GET['category'] ? $_GET['category'] : '');
        $query_data['subcat'] = urldecode($_GET['subcat'] ? $_GET['subcat'] : '');

        if (!empty($options['exclude_categories'])) {
            $query_data['exclude_cats'] = implode(',', $options['exclude_categories']);
        }

        //subcategory

        $qs = http_build_query($query_data);

        $url = newswire_api_url() . '/article/browse?' . $qs;

        $response = wp_remote_get($url, newswire_http_default_args());

        if (!is_wp_error($response)) {

            $html = wp_remote_retrieve_body($response);

            return $html;

        } else {

            //log or return errors
            //var_dump($response);
        }
    }

    /**
     * @todo - Call some templating engine
     */
    public function parse_blog_item_template($article, $template) {

        extract($article);
        $more_link = sprintf('<div><a href="%s" >Read More...</a></div>', $permalink);
        $template = str_replace('{title}', apply_filters('the_title', $article['title']), $template);
        $template = str_replace('{description}', apply_filters('the_excerpt', ($article['description'] ? $description . $more_link : wp_html_excerpt($article['body'], apply_filters('excerpt_length', 350), $more_link))), $template);
        $template = str_replace('{photo_url}', $article['photo_url'], $template);
        $template = str_replace('{author}', $article['author'], $template);
        $template = str_replace('{publish_date}', $article['publish_date'], $template);
        $template = str_replace('{permalink}', $article['permalink'], $template);
        $template = str_replace('{article_id}', $article['article_id'], $template);

        return $template;
        //need pagination and cats
    }

    /**
     * fetch newsroom as default html from newswire
     */
    public function pintype_newsroom($settings, $content) {
        //two js
        wp_enqueue_script('newswire-masonry');
        wp_enqueue_script('newswire-infinitescroll');

        return $this->fetch_newswire_articles($settings, 'html');
    }

    /**
     * Print categories as filter for newswire content
     *
     * @todo : apply walker class
     */
    public function newsroom_cat_shortcode_callback($atts, $content = null) {

        $options = newswire_options();

        $final_attrs = shortcode_atts(array(
            'position' => 'topfixed', //top or sidebar
            'excludecat' => '',
            'showchildren' => false,
            'callback' => '',
            'item_class' => 'category',
        ), $atts, 'newsroom_cat');

        extract($final_attrs);

        $categories = newswire_fetch_categories(0);

        if ('topfixed' == $position) {

            //Complete bootstrap navigation
            $html = apply_filters('newsroom_cat_topfixed', $categories);

        } elseif ('sidebar' == $position) {

            //Print top categories for now
            $html = apply_filters('newsroom_cat_sidebar', $categories);

        } else {

            $html = apply_filters($callback, $categories);
        }

        return $html;
    }

    /**
     * Newsroom Pagination
     *
     */
    public function newsroom_pagination_shortcode_callback($atts, $content) {

        //catch pagination and cats filters from $_GET

        extract(shortcode_atts(array(
            'as' => 'pin',
        ), $atts, 'newsroom_pagination'));

        if ('pin' == $as) {

            //call css and script enqueu here

            // as pin type infinite scrolling
            // print some js to make this work
            $html .= '<div id="newswire-pin-navigation">' .
            '<ul class="pager">'
            . '<li id="newswire-navigation-next">' . apply_filters('newsroom_morepin_link', '') . '</li>'
            . ' <li id="newswire-navigation-previous"><a href="#"></a></li>'
            . '</ul>'
            . '</div>';

            return $html;
        } else {
            // default wp nav
            return newsroom_pagination($this->totalitems ? $this->totalitems : 1);
        }
    } //end method

    /**
     * remove comments from template page
     */
    public function remove_comments_template($file) {

        $file = plugin_dir_path(dirname(__FILE__)) . 'templates/comments.php';

        return $file;
    }

} //end class

/**
 * Final binding of shortcode
 */
add_action('init', 'newswire_shortcodes_init');
function newswire_shortcodes_init() {
    //we dont need this from admin
    if (is_admin()) {
        return;
    }
    $shortcode = new Newswire_Pressroom_Masonry_Shortcodes();
}

