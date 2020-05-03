<?php
/**
 * Plugin Name: المنتور فارسی
 * Plugin URI: http://elementorfa.ir
 * Description: بسته فارسی ساز افزونه المنتور پرو به همراه اضافه شدن 11 فونت فارسی، تقویم شمسی برای المنتور، قالب های آماده فارسی در کتابخانه المنتور و آیکون های ایرانی
 * Version: 2.0.0
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

define( 'PERSIAN_ELEMENTOR_VERSION', '2.0.0' );


define( 'PERSIAN_ELEMENTOR', plugin_dir_path(__FILE__));


require_once(PERSIAN_ELEMENTOR.'includes/class-elementor.php');
	

require_once(PERSIAN_ELEMENTOR.'includes/class-translate.php');


require_once(PERSIAN_ELEMENTOR.'includes/class-admin.php');


require_once(PERSIAN_ELEMENTOR.'includes/dashboard.php');


// Core Feature

require_once(PERSIAN_ELEMENTOR.'includes/lib/fonts.php');


require_once(PERSIAN_ELEMENTOR.'includes/lib/icon-control.php');


require_once(PERSIAN_ELEMENTOR.'includes/lib/icon.php');


require_once(PERSIAN_ELEMENTOR.'includes/lib/localization.php');


// Set our root directory
define( 'PERSIAN_ELEMENTOR_DIRECTORY', plugin_dir_path( __FILE__ ) );
define( 'PERSIAN_ELEMENTOR_URL', plugins_url( '/', __FILE__ ) );

// Add templates to library	
require PERSIAN_ELEMENTOR_DIRECTORY . 'inc/persian-elementor-templates.php';

