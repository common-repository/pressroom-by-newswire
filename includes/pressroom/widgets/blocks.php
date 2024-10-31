<?php
/**
* Widgets are classses extended from WP_Widget
*
*/
if ( !defined( 'ABSPATH' ) ) exit;

/** 
*  Pressroom 
* 
*/
class Newswire_Pressroom_Widget extends WP_Widget {
    
    /**
    *
    */
    public function __construct() {
        parent::__construct($base_id ='newswire-widget-pressroom',
            $name = __('Pressroom Pins', 'newswire'), 
            $args = array('description'=> __('Show Pressroom pins/blocks', 'newswire'))
        );
    }
    
    /**
    *
    */
    public function widget($args, $instance) {
        $template = '';
        $title = apply_filters( 'widget_title', $instance['title'] );

        
        echo $args['before_widget'];
        
        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];

        //echo sprintf('<ul>%s</ul>', $items);
        //echo widget body
            $template .='<ul id="widget-tiles" class="unstyled">';
                  
            $id = $instance['pressroom_block'];

            global $post;
            
            $post = get_post($id);
            
            setup_postdata( $post );

            $pin_type = get_post_type( $post->ID );
                
            $file = sprintf('%s-%s.php', NEWSWIRE_DIR.'templates/pressroom', $pin_type);

            $template .= '<li class="pressroom-article-widget" ><div class="'.$pin_type.'">';
            $template .= get_pressroom_template($file);               
            $template .= '<div></li>';  
            

            $template .= '</ul>';

        echo $template;

        echo $args['after_widget'];
        wp_reset_postdata();
    }

    /**
    * 
    */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['pressroom_block'] = ( ! empty( $new_instance['pressroom_block'] ) ) ? strip_tags( $new_instance['pressroom_block'] ) : '';
      
        return $instance;

    }

    /**
    *
    */
    public function form( $instance ) {

        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'newswire' );
        }

        $pressroom_block = !empty($instance['pressroom_block']) ? $instance['pressroom_block']: '';

         $tag = get_term_by('name', 'newsroom', 'post_tag');

    $args = array(          

        //Choose ^ 'any' or from below, since 'any' cannot be in an array
        'post_type' => array(
            'pr',
            'pin_as_text',
            'pin_as_link',
            'pin_as_contact',
            'pin_as_social',
            'pin_as_quote',
            'pin_as_embed',
            'pin_as_image'          
                
            
        ),
        'tag__not_in'=> !empty($tag) ? $tag->term_id : '',
        'post_status' => array(
            'publish'           
            ),
        
        //Order & Orderby Parameters
        'order'               => 'ASC',
        'orderby'             => 'menu_order date',
        'ignore_sticky_posts' => true,
        
        
        //Pagination Parameters
        'posts_per_page'         => -1,
        'nopaging'               => true
        
    );
        $posts = get_posts($args);

        ?><div class="pressroom-widget-admin">
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
            <input class="widefat title" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'pressroom_block' ); ?>"><?php _e( 'Pressroom Block:' ); ?></label> 
            <select class="widefat pressroom-widget-select"  id="<?php echo $this->get_field_id( 'pressroom_block' ); ?>" name="<?php echo $this->get_field_name( 'pressroom_block' ); ?>" >
                <option value="">-select-</option>
            <?php 
                foreach($posts as $post) :
                        if ( $pressroom_block == $post->ID ) $selected ="selected"; else $selected = '';
                    printf('<option value="%s" %s>%s</option>', $post->ID, $selected, $post->post_title);
                endforeach;
            ?>
            </select>
           
            <span class="description"></span>
        </p>
        </div>
<?php /*
        <p>      
            <?php if ( $instance['featuredonly'] ): ?>
                <input class="widefat" id="<?php echo $this->get_field_id( 'featuredonly' ); ?>" name="<?php echo $this->get_field_name( 'featuredonly' ); ?>" type="checkbox" value="1" checked="checked">
                <label for="<?php echo $this->get_field_id( 'featuredonly' ); ?>"><?php _e( 'Show Featured Articles Only' ); ?></label> 
            <?php else: ?>
                <input class="widefat" id="<?php echo $this->get_field_id( 'featuredonly' ); ?>" name="<?php echo $this->get_field_name( 'featuredonly' ); ?>" type="checkbox" value="1" >
                <label for="<?php echo $this->get_field_id( 'featuredonly' ); ?>"><?php _e( 'Show Featured Articles Only' ); ?></label> 
            <?php endif; ?>
  
    */
  ?>     
        <?php 
    }
}//end class




/**
* Register all the widgets now
*/
add_action('widgets_init', 'newswire_register_widgets' );
function newswire_register_widgets() {
    register_widget('Newswire_Pressroom_Widget');    
}

