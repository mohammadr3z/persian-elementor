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
	
		
		echo '<a target=_blank\" href="http://bit.ly/Elementorfa"><img src="https://elementorfa.ir/wp-content/uploads/2019/04/PersianElementor2.png" style=height:100px; /></a>';
		echo '<p style="font-weight: 700; border-bottom: 1px solid #eee; margin: 0 -12px; padding: 6px 12px;	">پشتیبانی فارسی افزونه صفحه ساز المنتور</p>';
		echo '<li style="margin-top: 10px;"><a target=_blank\" href="http://bit.ly/wp-dashboad-buy">خرید افزونه المنتور پرو</a></li>';
		echo '<li><a target=_blank\" href="http://bit.ly/elementorfa-shop">افزونه های جانبی المنتور</a></li>';
		echo '</br>';
		echo '<p style ="font-weight: 700; border-bottom: 1px solid #eee; margin: 0 -12px; padding: 6px 12px;">آخرین مقالات المنتور فارسی :</p>';

		echo '<div style="margin-top: 10px;" class="rss-widget">';
     wp_widget_rss_output(array(
          'url' => 'https://elementorfa.ir/feed',
          'title' => 'المنتور فارسی',
          'items' => 3,
          'show_summary' => 1,
          'show_author' => 0,
          'show_date' => 0 
     ));
		echo '<p style="border-top: 1px solid #CCC;">';
		echo '<p> اگر سوالی دارید از بخش پرسش و پاسخ المنتور فارسی  <a target=_blank\" href="http://bit.ly/elementorfaq">سوال کنید</a>';
		echo "</div>";
		
}
add_action('wp_dashboard_setup', 'wpc_dashboard_widgets');

