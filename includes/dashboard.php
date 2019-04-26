<?php
/**
 * Snippet Name: Add custom RSS Feed to dashboard

 */
 function wpc_dashboard_widgets() {
     global $wp_meta_boxes;
     // remove unnecessary widgets  
     // var_dump( $wp_meta_boxes['dashboard'] ); // use to get all the widget IDs  
     unset(  
          $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'],  
          $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'],  
          $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']  
     );  
     // add a custom dashboard widget  
     wp_add_dashboard_widget( 'dashboard_custom_feed', 'المنتور فارسی', 'dashboard_custom_feed_output' ); //add new RSS feed output
}
function dashboard_custom_feed_output() {
	
		
		echo '<a target=_blank\" href="https://elementorfa.ir/"><img src="https://elementorfa.ir/wp-content/uploads/2019/04/PersianElementor.png" style=height:100px; /></a>';
		echo '<p>پشتیبانی فارسی افزونه صفحه ساز المنتور</p>';
		echo '<li><a target=_blank\" href="http://bit.ly/wp-dashboad-buy">خرید افزونه المنتور فارسی</a></li>';
		echo '<li><a target=_blank\" href="http://bit.ly/elementorfa-shop">فروشگاه محصولات المنتور</a></li>';
		echo '</br>';
		echo '<p>آخرین محصولات المنتور فارسی :</p>';

		echo '<div class="rss-widget">';
     wp_widget_rss_output(array(
          'url' => 'https://rss.app/feeds/csyDgxgCjckCT2AJ.xml',
          'title' => 'المنتور فارسی',
          'items' => 4,
          'show_summary' => 1,
          'show_author' => 0,
          'show_date' => 1 
     ));
		echo '<p style="border-top: 1px solid #CCC;">';
		echo '<p> اگر سوالی دارید از بخش پرسش و پاسخ المنتور فارسی  <a target=_blank\" href="https://elementorfa.ir/faq/">سوال کنید</a>';
		echo "</div>";
		
}
add_action('wp_dashboard_setup', 'wpc_dashboard_widgets');

