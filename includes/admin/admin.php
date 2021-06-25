<?php
add_action( 'admin_menu', 'persian_elementor_panel' );

function persian_elementor_panel() {
	add_menu_page(
	'المنتور فارسی',
	'المنتور فارسی',
	'manage_options',
	'persian-elementor',
	'efa_settings_section_callback',
	plugins_url( 'persian-elementor/assets/images/icon.png' ),
	58);
	//add_submenu_page( 'persian_elementor', 'لایسنس', 'لایسنس', 'manage_options', 'persian_elementor_license', 'persian_elementor_func_license');

}

function efa_settings_section_callback(  ) { 

?>
	<div class="wrap about-wrap" style="font-family:vazir">
		<h1>به المنتور فارسی خوش آمديد</h1>
			<div class="about-text">لذت طراحی با زبان فارسی و ظاهر زيبا</div>

		<a class="wp-badge" href="#" target="_blank"	style="background-color:#93033C !important;background-image:url(<?php echo plugins_url('persian-elementor/assets/images/about.png'); ?>) !important;background-position: center center;background-size: 167px auto !important;"></a>

		<h2 class="nav-tab-wrapper">
			<a class="nav-tab nav-tab-active" href="#" target="_blank">تنظيمات</a>
		</h2>
<?php

}
