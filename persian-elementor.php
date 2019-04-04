<?php
/**
 * Plugin Name: المنتور فارسی
 * Plugin URI: http://elementorfa.ir
 * Description: افزونه فارسی ساز صفحه ساز المنتور به همراه اضافه شدن فونت های فارسی در بخش تایپوگرافی و بهبود ظاهر ویرایشگر برای زبان فارسی
 * Version: 1.2
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
include( PERSIAN_ELEMENTOR . 'includes/fonts.php');
require_once(PERSIAN_ELEMENTOR.'includes/fonts.php');


add_action( 'init', 'load_persian_elementor_text_domain' );
	function load_persian_elementor_text_domain() {
		load_plugin_textdomain( 'elementor-pro', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
		load_plugin_textdomain( 'persian-elementor', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
}
		
		

function wp_admin_dashboard_add_new_widget() {
	global $wp_meta_boxes;
 
	wp_add_dashboard_widget( 'dashboard_mw_widget', 'المنتور فارسی', 'dashboard_mw_widget_output' );
}
	add_action('wp_dashboard_setup', 'wp_admin_dashboard_add_new_widget');
 
	function dashboard_mw_widget_output() {
		echo '<div>';
		echo '<a target=_blank\" href="https://elementorfa.ir/"><img src="https://elementorfa.ir/wp-content/uploads/2019/03/elementor-farsi-logo.png" style=height:100px; /></a>';
		echo '<p>پشتیبانی فارسی افزونه صفحه ساز المنتور</p>';
		echo '<li><a target=_blank\" href="https://elementorfa.ir/%d8%ae%d8%b1%db%8c%d8%af-%d8%a7%d9%81%d8%b2%d9%88%d9%86%d9%87-%d8%a7%d9%84%d9%85%d9%86%d8%aa%d9%88%d8%b1-%d9%be%d8%b1%d9%88/">خرید افزونه المنتور فارسی</a></li>';
		echo '<li><a target=_blank\" href="https://elementorfa.ir/%D9%81%D8%B1%D9%88%D8%B4%DA%AF%D8%A7%D9%87/">فروشگاه محصولات المنتور</a></li>';
		echo '<p style="border-top: 1px solid #CCC;">';
		echo '<p> اگر سوالی دارید از بخش پرسش و پاسخ المنتور فارسی  <a target=_blank\" href="https://elementorfa.ir/faq/">سوال کنید</a>';
		echo "</p>";
		echo "</div>";
}		
		