<?php 
/**
*  Custom validator - make simple class to validate article fields no fancy stuff here for now
*  @todo: add mark on each field with error
*
*/
class Newswire_Validator {

    /**
    * array of fields to validate
    */
    protected $_fields = array();

    /**
    * array of error messages;
    */
    public $_errors = array();

    /**
    * pass data to contructor
    */
    public function __construct( ) {
    }
    
    public function is_valid_article_tags( $value, $data =null ) {
    
        if ( strlen(strip_tags($value)) > 150 ) {
            return 'Meta News Keywords too long. Please enter no more than 120 characters';
        } else {
            return true;
        }

    }

    public function is_valid_article_profile_url( $value, $data =null ) {
        
        if ( !$data['link_name'] ) return true;

        // Author is using a secretkey
        if ( $value != '' && preg_match('|^[a-f0-9]+$|', $value)) {
          return true;
        }

        if ( $value != '' && filter_var($value, FILTER_VALIDATE_URL ) === false ) { 
            return sprintf('Link Name: Invalid URL. Link name must start with http:// or https://', $value);
        } else {
            return true;
        }

    }
    
    public function is_valid_article_company_email( $value, $data =null ) {
        
        return true;

        //return false;
        if ( $value != '' && filter_var($value, FILTER_VALIDATE_EMAIL ) === false ) { 
            return sprintf('Company Email: Please enter a valid email address.', $value);
        } else {
            return true;
        }

        return true;

    }

    public function is_valid_article_contact_email( $value, $data =null ) {

        if ( $value != '' && filter_var($value, FILTER_VALIDATE_EMAIL ) === false ) { 
            return sprintf('Contact Email: Please enter a valid email address.', $value);
        } else {
            return true;
        }

        return true;

    }

    public function is_valid_article_img_caption( $value , $data =null ) {


        if ( strlen(strip_tags($value)) > 35 ) {

            return 'Image Caption: Please enter no more than 35 characters';
        }
        return true;

    }


    public function is_valid_article_img_alt_tag_link( $value , $data =null ) {
    
        if ( $value != '' && filter_var($value, FILTER_VALIDATE_URL ) === false ) { 
            return sprintf('Caption Link: %s is not a valid URL. It must start with http(s):// to be considered valid.', $value);
        } else {
            return true;
        }

    }

    public function is_valid_article_img_caption_link( $value = '', $data =null ) {

        if ( $value != '' && filter_var($value, FILTER_VALIDATE_URL ) === false ) { 
            return sprintf('Hyperlink Image: %s is not a valid URL. It must start with http(s):// to be considered valid.', $value);
        } else {
            return true;
        }
    }

    /**
    * Validate article title at least 10 charaters
    */
    public function is_valid_article_title( $value = null  , $data =null ) {
    
        $value = $_POST['post_title'];

        $message[0]  =  'Article Title: Please enter at least 10 characters';

        $message[1]  =  'Article Title: Please complete this field - it is required.';
        
        if ( $value == '' || in_array( strtolower($value), array( strtolower('Untitled'), strtolower('Auto Draft'))) ) {

            return $message[1];

        } elseif ( strlen($value) < 10 ) {

            return $message[0];
        }

        return true;
    }

    /**
    * Validate bonus category
    */
    public function is_valid_article_category_id2( $value = null , $data =null ) {  

        $message[0]  =  'Category: Please complete this field - it is required.';

        if ( $value == '') {

            return $message[0];

        } 

        return true;
    }

    public function is_valid_article_body( $value , $data =null ) {
        
        if ( strlen(strip_tags($value )) < 1500 ) {
            $message  =  'Article Body: Please enter at least 1500 characters.';    
            return $message;
        }

        return true;
    }

    public function is_valid_article_description( $value , $data =null ) {
        
        $value = $_POST['post_excerpt'];

        if ( strlen(strip_tags($value )) < 100 ) {
            
            $message = 'Abstract/Meta Description/Excerpt: Please enter at least 100 characters';
            return $message;
        }

        if ( strlen(strip_tags($value)) > 196 ) {

            return 'Abstract/Meta Description: Please enter no more than 196 characters';
        }

        return true;

    }

    //company information
    /*
    public function is_valid_article_company_id( $value ) {
        $message = 'Company ID: Please enter a valid value';

        if ( !$value && $this->_article['show_company_info'] ) {
        
            return $message;
        }

        return true;
    }*/
    public function is_valid_article_schema_id ( $value , $data =null ) {
        $message = 'Invalid schema: Please enter a valid value';
                
        if ( empty($value) && intval($this->_article['show_company_info'])  ) {
        
            return $message;
        }

        return true;
    }
    public function is_valid_article_company_name( $value , $data =null ) {
        
        return true;

        $message = 'Company Name: Please enter a valid value';

        if ( $value == '' && $this->_article['show_company_info'] ) {
        
            return $message;
        }

        return true;

    }

    public function is_valid_article_company_address( $value , $data =null ) {
        
        return true;

        $message = 'Company Address: Please enter a valid value';
        if ( $value == ''  && $this->_article['show_company_info'] ) {
        
            return $message;
        }

        return true;

    }

    public function is_valid_article_company_city( $value , $data =null ) {
        
        return true;

        $message = 'City: Please enter a valid value';
        
        if ( $value == '' && $this->_article['show_company_info'] ) {
        
            return $message;
        }

        return true;

    }

    public function is_valid_article_company_zip( $value , $data =null ) {
        
        return true;

        $message = 'Postal Code: Please enter a valid value';
        
        if ( $value == '' && $this->_article['show_company_info'] ) {
        
            return $message;
        }

        return true;

    }

    public function is_valid_article_company_state( $value , $data =null ) {
        
        return true;

        $message = 'Company State or Province: Please enter a valid value';
        
        if ( $value == '' && $this->_article['show_company_info'] ) {
        
            return $message;
        }

        return true;

    }

    public function is_valid_article_img_url( $value ='' , $data =null ) {
        
        
        $message = 'Press Release image is required.';
        
        if ( empty($value) ) {
        
            return $message;
        }
        return true;
    }

    /**
    * Apply filters to validate fields
    *
    */
    public function isValid( $post ) {
        
        global $newswire_config;

        $this->post = $post;

        $callbacks = newswire_config('article_fields_handler');

        $post_meta = newswire_get_post_meta( $post->ID );

        foreach( array_keys($newswire_config['settings']['article_fields'])  as $field ) {              

            if (!empty($callbacks[$field]) && function_exists($callbacks[$field]) )
                $body[$field] = call_user_func( $callbacks[$field], $post );
            elseif( isset($post_meta[$field]) )
                $body[$field] = $post_meta[$field];
            else
            //  unset($body[$field]);
                $body[$field] = !empty($post_meta[$field]) ? $post_meta[$field] : '';

        }

        $this->_article = $body;


        foreach($body as $field => $value ) {
            
            $value = apply_filters('filter_article_'. $field, $value, $field, $post_meta, $post );
            
            $tag = 'is_valid_article_'.$field;

//var_dump($tag);
//echo '<br>';

            if( method_exists($this, $tag ) ) {
                
                $error = $this->$tag($value, $body);
                
                if ( $error !== true ) {
                    array_push($this->_errors, $error );
                }
            } 
            

        }//endforeach
//var_dump($this->_errors);
//exit;
        if ( !empty( $this->_errors) ) 
        {
            return false;

        } else {

            return true;
        }
    }


    public function write_error_notice(){
        $html  = '';

        if ( !empty($this->_errors) ) 
        {
            
            newswire_admin_write_error( $this->_errors );
        }
    }


}