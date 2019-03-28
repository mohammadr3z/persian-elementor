<?php
/**
 * Plugin Name: المنتور فارسی
 * Plugin URI: http://elementorfa.ir/contact
 * Description: افزونه فارسی ساز صفحه ساز المنتور و بهبود ویرایشگر زنده
 * Version: 1.0.0
 * Author: المنتور فارسی
 * Author URI: https://elementorfa.ir
 * Text Domain: persian-elementor
 * License: GPL2
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


define('PERSIAN_ELEMENTOR', plugin_dir_path(__FILE__));
include( PERSIAN_ELEMENTOR . 'includes/editor.php');
require_once(PERSIAN_ELEMENTOR.'includes/editor.php');


add_action( 'init', 'load_persian_elementor_text_domain' );
function load_persian_elementor_text_domain() {
load_plugin_textdomain( 'elementor-pro', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
load_plugin_textdomain( 'persian-elementor', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
        }