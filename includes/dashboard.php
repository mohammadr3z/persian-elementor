<?php
/**
 * Add RSS Feed to dashboard

 */
 function wpc_dashboard_widgets_elementor() {
     global $wp_meta_boxes;
     // remove unnecessary widgets  
     // var_dump( $wp_meta_boxes['dashboard'] ); // use to get all the widget IDs  
     unset(  
          $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'],  
          $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'],  
          $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']  
     );  
     // add dashboard widget  
     wp_add_dashboard_widget( 'dashboard_custom_feed', 'المنتور فارسی', 'dashboard_persian_elementor_feed_output' ); //add new RSS feed output
}
function dashboard_persian_elementor_feed_output() { ?>	
        <div style="display:flex;align-items: center;margin: 0 -12px 8px;padding: 0 12px 12px;">
            <img src='<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/dashboard.png'; ?>' style="height:50px"; /> 
            <p style="padding: 0 10px;"> المنتور فارسی v<?php  echo  PERSIAN_ELEMENTOR_VERSION; ?> </p>
        </div>
            <p style="font-weight: 700; border-bottom: 1px solid #eee; margin: 0 -12px; padding: 6px 12px;">پشتیبانی فارسی افزونه صفحه ساز المنتور</p>
            <a style="margin-top: 20px;margin-bottom: 20px;margin-left: 5px" target=_blank\" href="http://bit.ly/wp-dashboad-buy" class="button button-primary">نسخه اورجینال المنتور پرو</a>
            <a style="margin-top: 20px;margin-bottom: 20px;" target=_blank\" href="http://bit.ly/elementorfa-shop" class="button">افزونه های جانبی المنتور</a>
            </br>
            <p style ="font-weight: 700; border-bottom: 1px solid #eee; margin: 0 -12px; padding: 6px 12px;">اخبار و بروزرسانی ها</p>
            <div style="margin-top: 10px;" class="rss-widget"> </div>
        
<?php
     wp_widget_rss_output(array(
          'url' => 'https://elementorfa.ir/feed',
          'title' => 'المنتور فارسی',
          'items' => 3,
          'show_summary' => 1,
          'show_author' => 0,
          'show_date' => 0 
     ));
?>
		<p style ="border-top: 1px solid #eee; margin: 12px -12px 12px; padding:0px;>
		
		<div class="p-overview__footer" style="">
				<ul style="display: flex;list-style: none;margin: 0;padding: 0;">
					<li style="padding: 0 10px;margin: 0;" class="p-overview__blog"><a href="http://bit.ly/elementorfablog" target="_blank">وبلاگ <span class="screen-reader-text">(باز کردن در پنجره جدید)</span><span aria-hidden="true" class="dashicons dashicons-external"></span></a></li>
					<li style="padding: 0 10px; margin: 0; border-right: 1px solid #ddd;" class="p-overview__faq"><a href="http://bit.ly/elementorfaq" target="_blank">پرسش و پاسخ <span class="screen-reader-text">(باز کردن در پنجره جدید)</span><span aria-hidden="true" class="dashicons dashicons-external"></span></a></li>
				</ul>
	<?php	
}

add_action('wp_dashboard_setup', 'wpc_dashboard_widgets_elementor');
