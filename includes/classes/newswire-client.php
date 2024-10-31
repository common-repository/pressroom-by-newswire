<?php
/**
 * Newswire API Rest client
 *
 * Singleton class
 *
 * @todo: Follow generic convention for REST api
 * @todo: remove valid calls validation - this should be handle from REST api endpoint
 * @todo: domain whitelisting
 *
 */
Final class Newswire_Client {

    #custom http headers
    const HTTP_HEADER_AUTHORIZATION = 'x-newswire-signature';
    const HTTP_HEADER_USER_AGENT = 'x-newswire publisher 1.0 ';

    # singleton
    private static $_instance = null;

    protected $data;
    protected $api_key;
    protected $email;
    protected $secret_key;
    protected $url;
    protected $options;
    protected $query = array();
    protected $headers;
    protected $hash;
    protected $valid_calls = array('/user/me', // use for testing api call/validation
        '/article/lists', // use for testing api call/validation
        '/article/submit', // submit and create new article
        '/article/update', // submit and create new article
        '/article/details', // check article information
        '/article/delete', // delete article
        '/article/status', // check article status 'pending|publish'
        '/article/sentbacklist',
        '/article/approvedlist',
    );

    /*
     * Class contructor
     */
    final private function __construct() {

        //set api url
        $this->url = newswire_api_url();

        // get option and set email and api key
        $this->options = $options = newswire_options();

        $this->setEmail($options['newswire_api_email']);
        $this->setApiKey($options['newswire_api_key']);
        $this->setSecretKey($options['newswire_api_secret']);

        #add api_key and email to api url
        add_filter('newswire_url', array($this, 'resolve_api_url'));
    }

    /**
     * add email and api_key to api end point
     *
     * @param $url string
     * @return $url string
     */
    public function resolve_api_url($url) {

        $this->query = array_merge(array('email' => $this->email, 'api_key' => $this->api_key, 'domain'=> site_url()));

        $url = $url . '?' . http_build_query($this->query);
        //add domain
        //$url = add_query_arg(array('domain'=> site_url()));
        return $url;
    }

    /**
     * validate api endpoint calls
     *
     * @todo: validate api method
     *
     * @param $call string path
     */
    public function validate_call($call) {

        return true;

        if (in_array($call, $this->valid_calls)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Get instance - Singleton
     */
    final public static function getInstance() {
        if (self::$_instance === null) {
            return new Newswire_Client;
        } else {
            return self::$_instance;
        }
    }

    /**
     * Set api email
     *
     * @param $value string email
     * @return void
     */
    public function setEmail($value) {
        $this->email = $value;
    }

    /**
     * Set api_key
     *
     * @param $value string
     * @return void
     */
    public function setApiKey($value) {
        $this->api_key = $value;
    }

    /**
     * SEt secret key
     *
     * @param $value string
     * @return void
     */
    public function setSecretKey($value = '') {
        $this->secret_key = ''; //dont use
    }

    /**
     * Sanitaize api call
     *
     * @call path
     * @return string
     */
    public function sanitize($call) {
        $raw_key = $call;
        $key = str_replace('/', '_', strtolower($call));
        return apply_filters('sanitize_remote_call', $key, $raw_key);
    }

    /**
     * newswire http get wrapper
     *
     * @param $call string resource/method
     * @param $data array  http body
     *
     * @return array $response return wordpress http wrapper response object
     */
    public function remote_get($call, $data = array()) {

        $data = apply_filters('newswire_prepare_data_' . $this->sanitize($call), wp_parse_args($this->data, $data));

        if ($this->validate_call($call)) {

            $url = apply_filters('newswire_url', $this->url . $call);

            $this->digitally_sign_request($data, $call, $url);

            //var_dump($url);

            $response = wp_remote_get($url, newswire_http_default_args($data));

            //var_dump($response);
            //exit;
            return $response;
        }
    }

    /**
     * newswire http post wrapper
     *
     * @param $call string resource/method article/[method]
     * @param $data array http data
     *
     * @return $response wordpress http wrapper response object
     */
    public function remote_post($call, $data = array()) {

        $data = apply_filters('newswire_prepare_data_' . $this->sanitize($call), wp_parse_args($this->data, $data));

        if ($this->validate_call($call)) {

            $url = apply_filters('newswire_url', $this->url . $call);

            $this->digitally_sign_request($data, $call, $url);

            $response = wp_remote_post($url, newswire_http_default_args($data));
            
            return $response;
        }
    }

    /**
     * Sign request based on http data and secret key
     * @todo: use more advance hash
     *        add domain whitelisting
     *
     * @param $data pass by reference
     * @param $call
     * @param $url
     */
    protected function digitally_sign_request(&$data, $call = '', $url) {

        $signature = '';
        
        /*$request = array();
        $request['temphash'] = 'newswire.net';
        $this->hash = $request;
         */
        // $data['body']['website'] = site_url(); //for whitelisting later
        /*if ( !isset($data['body']) ) {
        throw new Exception('Invalid api call');
        }*/
        if ( !empty($data['body']) ):
            $data['body'] = (array) $data['body'];

            ksort($data['body'], SORT_STRING);
            $signature = '';
            foreach ($data['body'] as $key => $val) {
                if (is_array($val)) {
                    $signature .= $key . serialize($val);
                } else {
                    $signature .= $key . $val;
                }

            }
        endif;

        //var_dump($signature);
        //echo '<hr>';
        //exit;
        # $signature =  base64_encode(hash_hmac('sha256', serialize( $request ), $this->secret_key , true));
        $signature = md5($this->secret_key . $signature);

        # ('newswire_pre_sign_request', $this->hash, $call );
        # send the data signature via http header
        $data['headers'][self::HTTP_HEADER_AUTHORIZATION] = $signature;
    }

    /**
     * invoke api test call
     * Use to fetch user information too and save to wp option table.
     * THis is called when user click validate api button from plugin settings page
     *
     * @param
     * @return void
     */
    public function test() {

        return $this->remote_post('/user/me');
    }

    /**
     * Submit article to newswire.net
     *
     * @param $data mix http body
     *
     * @return object
     */
    public function submit_article($data = array()) {

        return $this->remote_post('/article/submit', $data);
    }

    /**
     * Wrapper method to post to newswire.net api
     * alias for remote_post/http post
     *
     * @param $path string resource/method
     * @param $data http body based on wordpress http wrapper class
     *
     * @return object http response
     */
    public function post($path, $data = null) {
        return $this->remote_post($path, $data);
    }
} //end class