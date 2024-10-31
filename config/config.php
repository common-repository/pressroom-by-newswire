<?php
# prevent from accessign this file directly
if (!defined('ABSPATH')) {
	exit;
}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# constants
define('NEWSWIRE_OPTIONS', 'newswire_options');
define('NEWSWIRE_POST_META_CUSTOM_FIELDS', 'newswire_data');
define('NEWSWIRE_POST_META_NONCE', '_newswire_data_nonce');
define('NEWSWIRE_POST_META_PREFIX_PRIVATE', '_newswire_'); #reserve
define('NEWSWIRE_POST_META_PREFIX_PUBLIC', 'newswire_');
define('NEWSWIRE_ADMIN_ERROR', 'newswire_admin_error');
# api urls
if ( defined('NEWSWIRE_DEBUG') && NEWSWIRE_DEBUG ):

define('NEWSWIRE_URL', 'http://newswire.net');
define('NEWSWIRE_URL_SSL', 'https://newswire.net');
define('NEWSWIRE_API_URL', 'http://local.newswire.dev/api/v1');
define('NEWSWIRE_API_DEVURL', 'http://local.newswire.dev/api/v1');
define('NEWSWIRE_API_LINK', 'http://local.newswire.dev/members/settings/api');
define('NEWSWIRE_CHECKOUT_URL', 'http://local.newswire.dev/article/checkout/index.php');
else:

define('NEWSWIRE_URL', 'http://newswire.net');
define('NEWSWIRE_URL_SSL', 'https://newswire.net');
define('NEWSWIRE_API_URL', 'http://www.newswire.net/api/v1');
define('NEWSWIRE_API_DEVURL', 'http://www.newswire.net/api/v1');
define('NEWSWIRE_API_LINK', 'http://www.newswire.net/members/settings/api');
define('NEWSWIRE_CHECKOUT_URL', 'http://www.newswire.net/article/checkout/index.php');


endif;

//
# from newswire values
define('NEWSWIRE_ARTICLE_TITLE_MAXCOUNT', 70); // prevent from submitting to newswire
define('NEWSWIRE_ARTICLE_TITLE_LIMIT', 90); // SET red flag to article label

# wp post_meta needed by newswire plugin
define('NEWSWIRE_ARTICLE_SUBMITTED', 'newswire_article_submitted');
define('NEWSWIRE_ARTICLE_ID', 'newswire_article_id');
define('NEWSWIRE_ARTICLE_STATUS', 'newswire_article_status');
define('NEWSWIRE_ARTICLE_HISTORY', 'newswire_article_history'); //article history from newswire
# end constants
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

# newswire default settings
global $newswire_config;

#  default settings

#  general tab
$newswire_config['settings']['newswire_api_email'] = '';
$newswire_config['settings']['newswire_api_key'] = '';
$newswire_config['settings']['newswire_api_secret'] = '';
$newswire_config['settings']['force_submission'] = '1';
$newswire_config['settings']['api_validated'] = 0;
$newswire_config['settings']['newswire_approved_article'] = 'pull'; // pull or push
$newswire_config['settings']['article_submission_lock'] = '1';
$newswire_config['settings']['article_submission_mode'] = 'autosubmit'; // manual
$newswire_config['settings']['supported_post_types'] = array('pr'); // selected by default
$newswire_config['settings']['categories_maximum_import'] = 6; //per category

# newsroom tab
//$newswire_config['settings']['newsroom_page_template']  =  '';
//
$newswire_config['settings']['newsroom_layout'] = 'default';
$newswire_config['settings']['newsroom_theme'] = 'newswire';
$newswire_config['settings']['newsroom_feedsource'] = 'live';
$newswire_config['settings']['newsroom_pinperpage'] = 20; // Total number pin items per page
$newswire_config['settings']['newsroom_pinwidth'] = 300; // show comments from newsroom feeds
$newswire_config['settings']['newsroom_exluded_cats'] = ''; // Exclude categoreies from category navs
$newswire_config['settings']['newsroom_local_sticky_total'] = 5; // total number of locally published article that shows on top of the newsroom feeds
$newswire_config['settings']['newsroom_show_featured_content'] = 1; // show sticky post/article from local site
$newswire_config['settings']['newsroom_show_category_nav'] = 1; // Sho Main categories
$newswire_config['settings']['newsroom_disabled'] = 0; // show/hide newsroom
$newswire_config['settings']['newsroom_show_author'] = 1; // show author
$newswire_config['settings']['newsroom_show_likes'] = 1; // show liks from newsroom feeds
$newswire_config['settings']['newsroom_show_comments'] = 1; // show comments from newsroom feeds
$newswire_config['settings']['newsroom_rss_maxpost'] = 30;
$newswire_config['settings']['newsroom_rss_enable'] = 1;
$newswire_config['settings']['rss_importstatus'] = 'publish';

#press room page
$newswire_config['settings']['pressroom_cpt'] = array('text', 'embed', 'image', 'quote', 'social', 'link', 'contact');
$newswire_config['settings']['pressroom_rss'] = ''; // show comments from newsroom feeds
$newswire_config['settings']['pressroom_bgimage'] = '';
$newswire_config['settings']['pressroom_bgcolor'] = '';
$newswire_config['settings']['pressroom_title_bgcolor'] = '#000';
$newswire_config['settings']['pressroom_title_textcolor'] = '#fff';
$newswire_config['settings']['pressroom_bordercolor'] = '#FF0';
$newswire_config['settings']['pressroom_header_bgcolor'] = '#fff';
$newswire_config['settings']['pressroom_company_logo'] = '';
$newswire_config['settings']['pressroom_page_title_url'] = '';
$newswire_config['settings']['pressroom_page_title'] = '';
$newswire_config['settings']['pressroom_custom_header'] = '';
$newswire_config['settings']['pressroom_custom_footer'] = '';
$newswire_config['settings']['pressroom_header_theme'] = 0;
$newswire_config['settings']['pressroom_footer_theme'] = 0;
$newswire_config['settings']['pressroom_custom_css'] = '';
$newswire_config['settings']['pressroom_theme'] = 'default';
$newswire_config['settings']['pressroom_layout'] = 'default';
$newswire_config['settings']['pressroom_rss_maxpost'] = 30;
$newswire_config['settings']['pressroom_rss_enable'] = 1;
$newswire_config['settings']['newswire_live_links'] = 0;
$newswire_config['settings']['pressroom_styles'] = array(
		'body' => array(
				'bg'=> '_none',
				'shadow_color' => '',
				'box_shadow'=> '',
				'h_shadow' => '',
				'v_shadow' => '',
				'spread_shadow' => '',
				'blur_shadow' => '',				
				'opacity_shadow' => '',		
				'border_color'	=> '',
				'border_thickness'=> '',
				'top_radius'=> '',
				'right_radius'=> '',
				'bottom_radius'=> '',
				'left_radius'=> '',
				'border'=> '',
				'bg_repeat'=> '',
				'bg_image_url'=> '',
				'bg_color'=> '',
			),
		'footer' => array(

				'bg'=> '_none',
				'bg_color' => '',
				'bg_image_url' => '',
				
			),
		'header' => array(

				'bg'=> '_none',
				'bg_color' => '',
				'bg_image_url' => ''
				
			)
	);

$newswire_config['settings']['pressroom_default_layout'] = array(

	'pin_footer_background_none' => 'false', //can be url, none or hex
	'pin_footer_background_color' => '#f5f5f5', //can be url, none or hex
	'pin_footer_background_url' => '', //can be url, none or hex
	'pin_footer_background_repeat' => 'no-repeat',

	'pin_header_background_none' => 'false',
	'pin_header_background_color' => '#f5f5f5',
	'pin_header_background_url' => '',
	'pin_header_background_repeat' => 'no-repeat',

	//pin content
	'pin_background_none' => '#fff',
	'pin_background_color' => '#fff',
	'pin_background_url' => '#fff',
	'pin_background_repeat' => '#fff',

	'pin_border_color' => '#fff',
	'pin_border_thickness' => '',
	'pin_border_corner' => '',

	'pin_shadow_color' => '',
	'pin_shadow_offset' => '',
	'pin_shadow_opacity' => '',
	'pin_shadow_blur' => '',

);

# callback handler /mapping of wp --> newswire article
$newswire_config['settings']['article_fields_handler'] = array(
	'title' => 'newswire_get_the_title_fieldmap',
	'description' => 'newswire_get_wp_excerpt_fieldmap',
	'body' => 'newswire_get_the_content_fieldmap',
	'status' => 'newswire_pr_get_post_status',
	'publish_date' => 'newswire_publish_date_element',
	'show_company_info' => 'newswire_show_company_info_fieldmap', //,
	//'contact_email'     => 'newswire_get_current_user_email',
	//'pen_name' => 'newswire_get_current_user_displayname'
);

# wp post custom fields
$newswire_config['settings']['fields_validators'] = array(
	/* actual name posted to newswire api as key */
	'title' => array('string' => array('required' => true, 'maxchar' => 5, 'minchar' => 0)),
	'description' => array('string' => array('required' => true, 'maxchar' => 5, 'minchar' => 0)),
);

# readonly information from newswire
$newswire_config['settings']['readonly_data'] = array(
	'newswire_user_id' => 'newswire_get_user_id',
);

# dev mode settings
if (NEWSWIRE_DEVELOPMENT_MODE == true) {
	define('NEWSWIRE_POST_META_PREFIX', NEWSWIRE_POST_META_PREFIX_PUBLIC);
} else {
	define('NEWSWIRE_POST_META_PREFIX', NEWSWIRE_POST_META_PREFIX_PRIVATE);
}

/**
* This is what gets submitted to newswire stsriping other keys from post meta newswire_data
*/
$newswire_config['settings']['article_fields'] = array(

	'article_id' => '', 'title' => '',
	'description' => '', 'body' => '',
	'owner_id' => '', 'category_id' => '',
	'category_id2' => '', 'photo_id' => '',
	'creation_date' => '', 'modified_date' => '',
	'view_count' => '', 'comment_count' => '',
	'like_count' => '', 'search' => '',
	'featured' => '', 'sponsored' => '',
	'status' => '', 'img_caption' => '',
	'img_url' => '', 'img_alt_tag' => '',
	'img_caption_link' => '', 'img_alt_tag_link' => '',
	'contact_email' => '', 'pen_name' => '',
	'link_name' => '', 'profile_url' => '',
	'show_company_info' => 1, 'company_profile' => '',
	'company_id' => '', 'schema_id' => '',
	'company_name' => '', 'company_tickers' => '',
	'company_address' => '', 'company_city' => '',
	'company_state' => '', 'company_country' => '',
	'company_zip' => '', 'company_telephone' => '',
	'company_email' => '', 'company_website' => '',
	'hashtags' => '',
	'styles' => '',
	'url' => '',
	'tags' => '',
	'_set_as_featured' => '',
	'publish_date' => '',
	'language' => '',
	'page_id' => '',
	//'status'=> '',
	'embed_type' => '',
	'embed_url' => '',
	'embed' => '',
	'mapsembed' => '',
	'fbembed' => '',
	'adsembed' => '',
	'webembed' => '',
	'postback_url' => ''
);
$newswire_config['exclude_categories'] = array();
$newswire_config['default_categories'] = array(
	'Business',
	'eBusiness',
	'Finance',
	'Health',
	'Local',
	'Real Estate',
	'Science',
	'Sports',
	'Technology',
	'Travel',
	'US',
	'World',

);