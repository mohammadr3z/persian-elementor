<?php
/**
 * Plugin Name: Persian Language For Elementor
 * Plugin URI: https://themeboutique.ir/contact
 * Description: افزونه فارسی ساز صفحه ساز المنتور
 * Version: 1.0.0
 * Author: Persian Elementor
 * Author URI: https://themeboutique.ir
 * Text Domain: persian-elementor
 * License: GPL2
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


define('PERSIAN_ELEMENTOR', plugin_dir_path(__FILE__));
include( PERSIAN_ELEMENTOR . 'includes/editor.php');
require_once(PERSIAN_ELEMENTOR.'includes/editor.php');



add_action( 'init', 'myplugin_load_textdomain' );
function myplugin_load_textdomain() {
load_plugin_textdomain( 'elementor-pro', false, basename( dirname( __FILE__ ) ) . '/languages/elementor-pro' ); 
load_plugin_textdomain( 'persian-elementor', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}


