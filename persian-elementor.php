<?php
/**
 * Plugin Name: المنتور فارسی
 * Plugin URI: http://elementorfa.ir
 * Description: بسته فارسی ساز افزونه المنتور پرو به همراه اضافه شدن 10 فونت فارسی محبوب در بخش تایپوگرافی و بهبود ظاهر ویرایشگر با اضافه شدن فونت یکان برای زبان فارسی
 * Version: 1.7.1
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
	

require_once(PERSIAN_ELEMENTOR.'includes/class-translate.php');


require_once(PERSIAN_ELEMENTOR.'includes/dashboard.php');


// Core Features

require_once(PERSIAN_ELEMENTOR.'includes/core/icon-control.php');

require_once(PERSIAN_ELEMENTOR.'includes/core/icon.php');

require_once(PERSIAN_ELEMENTOR.'includes/core/fonts.php');




