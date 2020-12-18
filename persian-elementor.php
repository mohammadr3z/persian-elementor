<?php
/**
 * Plugin Name: المنتور فارسی
 * Plugin URI: http://persianefa.ir/
 * Description: بسته فارسی ساز افزونه المنتور پرو به همراه اضافه شدن 12 فونت فارسی، تقویم شمسی برای المنتور، قالب های آماده فارسی در کتابخانه المنتور و آیکون های ایرانی
 * Version: 2.3.3
 * Author: المنتور فارسی
 * Author URI: http://persianefa.ir/
 * Text Domain: persian-elementor
 * License: GPL2
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PERSIAN_ELEMENTOR_VERSION', '2.3.3' );


define( 'PERSIAN_ELEMENTOR', plugin_dir_path(__FILE__));
define( 'PERSIAN_ELEMENTOR_DIRECTORY', plugin_dir_path( __FILE__ ) );
define( 'PERSIAN_ELEMENTOR_URL', plugins_url( '/', __FILE__ ) );


require_once(PERSIAN_ELEMENTOR.'includes/class-elementor.php');
	

require_once(PERSIAN_ELEMENTOR.'includes/class-translate.php');


require_once(PERSIAN_ELEMENTOR.'includes/class-admin.php');


require_once(PERSIAN_ELEMENTOR.'includes/dashboard.php');


require_once(PERSIAN_ELEMENTOR.'includes/class-options.php');


// Core Feature

require_once(PERSIAN_ELEMENTOR.'includes/lib/fonts.php');


require_once(PERSIAN_ELEMENTOR.'includes/lib/icon-control.php');


require_once(PERSIAN_ELEMENTOR.'includes/lib/icon.php');


require_once(PERSIAN_ELEMENTOR.'includes/lib/localization.php');


require_once(PERSIAN_ELEMENTOR.'includes/lib/exopite-simple-options/exopite-simple-options-framework-class.php');


require PERSIAN_ELEMENTOR_DIRECTORY . 'inc/persian-elementor-templates.php';
