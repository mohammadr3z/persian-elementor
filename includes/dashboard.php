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
	
		
		echo '<a target=_blank\" href="https://elementorfa.ir/"><img src="https://elementorfa.ir/wp-content/uploads/2019/03/elementor-farsi-logo.png" style=height:100px; /></a>';
		echo '<p>پشتیبانی فارسی افزونه صفحه ساز المنتور</p>';
		echo '<li><a target=_blank\" href="https://elementorfa.ir/%d8%ae%d8%b1%db%8c%d8%af-%d8%a7%d9%81%d8%b2%d9%88%d9%86%d9%87-%d8%a7%d9%84%d9%85%d9%86%d8%aa%d9%88%d8%b1-%d9%be%d8%b1%d9%88/">خرید افزونه المنتور فارسی</a></li>';
		echo '<li><a target=_blank\" href="https://elementorfa.ir/%D9%81%D8%B1%D9%88%D8%B4%DA%AF%D8%A7%D9%87/">فروشگاه محصولات المنتور</a></li>';
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

