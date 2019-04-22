<?php
/**
 * Plugin Name: المنتور فارسی
 * Plugin URI: http://elementorfa.ir
 * Description: افزونه فارسی ساز صفحه ساز المنتور به همراه اضافه شدن فونت های فارسی در بخش تایپوگرافی و بهبود ظاهر ویرایشگر برای زبان فارسی
 * Version: 1.3
 * Author: المنتور فارسی
 * Author URI: https://elementorfa.ir
 * Text Domain: persian-elementor
 * License: GPL2
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'PERSIAN_ELEMENTOR_URL' ) ) {
	define( 'PERSIAN_ELEMENTOR_URL', plugins_url( '', __FILE__ ) . '/' );
}

define( 'PERSIAN_ELEMENTOR', plugin_dir_path(__FILE__));


require_once(PERSIAN_ELEMENTOR.'includes/editor.php');
	

require_once(PERSIAN_ELEMENTOR.'includes/fonts.php');


require_once(PERSIAN_ELEMENTOR.'includes/class-translate.php');


require_once(PERSIAN_ELEMENTOR.'includes/dashboard.php');


	
// Autoupdate Plugin

$api_url = 'https://elementorfa.ir/api/';
$plugin_slug = basename(dirname(__FILE__));


// Take over the update check
add_filter('pre_set_site_transient_update_plugins', 'check_for_plugin_update');

function check_for_plugin_update($checked_data) {
	global $api_url, $plugin_slug, $wp_version;
	
	//Comment out these two lines during testing.
	if (empty($checked_data->checked))
		return $checked_data;
	
	$args = array(
		'slug' => $plugin_slug,
		'version' => $checked_data->checked[$plugin_slug .'/'. $plugin_slug .'.php'],
	);
	$request_string = array(
			'body' => array(
				'action' => 'basic_check', 
				'request' => serialize($args),
				'api-key' => md5(get_bloginfo('url'))
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);
	
	// Start checking for an update
	$raw_response = wp_remote_post($api_url, $request_string);
	
	if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
		$response = unserialize($raw_response['body']);
	
	if (is_object($response) && !empty($response)) // Feed the update data into WP updater
		$checked_data->response[$plugin_slug .'/'. $plugin_slug .'.php'] = $response;
	
	return $checked_data;
}


// Take over the Plugin info screen
add_filter('plugins_api', 'plugin_api_call', 10, 3);

function plugin_api_call($def, $action, $args) {
	global $plugin_slug, $api_url, $wp_version;
	
	if (!isset($args->slug) || ($args->slug != $plugin_slug))
		return false;
	
	// Get the current version
	$plugin_info = get_site_transient('update_plugins');
	$current_version = $plugin_info->checked[$plugin_slug .'/'. $plugin_slug .'.php'];
	$args->version = $current_version;
	
	$request_string = array(
			'body' => array(
				'action' => $action, 
				'request' => serialize($args),
				'api-key' => md5(get_bloginfo('url'))
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);
	
	$request = wp_remote_post($api_url, $request_string);
	
	if (is_wp_error($request)) {
		$res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
	} else {
		$res = unserialize($request['body']);
		
		if ($res === false)
			$res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
	}
	
	return $res;
}
?>
