<?php 
/*
Plugin Name: Disable WordPress REST API
Plugin URI: https://github.com/ScottTravisHartley/Disable-WP-REST-API
Description: Disables the WP REST API for visitors not logged into WordPress.
Author: SERT Media
Author URI: https://www.sertmedia.com
Version: 1.0
*/

/* Disable REST API link in HTTP headers */
remove_action('template_redirect', 'rest_output_link_header', PHP_INT_MAX);

/* Disable REST API links in HTML */
remove_action('wp_head', 'rest_output_link_wp_head', PHP_INT_MAX);
remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');

/* Disable REST API */
if (version_compare(get_bloginfo('version'), '4.7', '>=')) {
	
	add_filter('rest_authentication_errors', 'disable_wp_rest_api');	
} else {	
	disable_wp_rest_api_legacy();	
}

function disable_wp_rest_api($access) {	
	if (!is_user_logged_in()) {		
		$message = apply_filters('disable_wp_rest_api_error', __('REST API restricted to logged in users.', 'disable-wp-rest-api'));
		
		return new WP_Error('rest_login_required', $message, array('status' => rest_authorization_required_code()));		
	}	
	return $access;	
}

function disable_wp_rest_api_legacy() {
	
    // REST API 1.x
    add_filter('json_enabled', '__return_false');
    add_filter('json_jsonp_enabled', '__return_false');
	
    // REST API 2.x
    add_filter('rest_enabled', '__return_false');
    add_filter('rest_jsonp_enabled', '__return_false');	
}

