<?php
/**
 * Plugin Name: المنتور فارسی
 * Plugin URI: https://elementorfa.ir
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
	



	
