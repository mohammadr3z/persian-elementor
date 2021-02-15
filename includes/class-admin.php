<?php
add_action( 'admin_menu', 'persian_elementor_panel' );

function persian_elementor_panel() {
	add_menu_page(
	'المنتور فارسی',
	'المنتور فارسی',
	'manage_options',
	'persian_elementor',
	'efa_settings_section_callback',
	plugins_url( 'persian-elementor/includes/assets/images/icon.png' ),
	58);
	//add_submenu_page( 'persian_elementor', 'لایسنس', 'لایسنس', 'manage_options', 'persian_elementor_license', 'persian_elementor_func_license');

}

function efa_settings_section_callback(  ) { 

?>
	<div class="wrap about-wrap" style="font-family:vazir">
		<h1>به المنتور فارسی خوش آمديد</h1>
			<div class="about-text">لذت طراحی با زبان فارسی و ظاهر زيبا</div>

		<a class="wp-badge" href="#" target="_blank"	style="background-color:#93033C !important;background-image:url(<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/about.png'; ?>) !important;background-position: center center;background-size: 167px auto !important;"></a>

		<h2 class="nav-tab-wrapper">
			<a class="nav-tab nav-tab-active" href="#" target="_blank">تنظيمات</a>
		</h2>
<?php

}

function efa_persian_li() {
	?>
	<style>
.exopite-sof-wrapper-menu {
    margin-left: 0!important;
}
	.wrap-license-efa .pluginname {
    background: #f9f9f9;
    padding: 14px;
    border-bottom: 1px solid #ccc;
    margin: -14px -14px 20px;
    width: 100%;
}
.wrap-license-efa{
	margin: 25px 0px 10px 10px;
    background: #fff;
    border: 1px solid #ccc;
    max-width: 535px;
    padding: 15px;
    min-height: 220px;
    position: relative;
    box-sizing: border-box;
}
label.description {
    font-size: 13px;
    top: 9px;
    position: relative;
}
p.exopite-sof-description {
    font-size: 13px;
    text-align: right;
	font-family: 'Vazir';
}
.exopite-sof-content-nav {
    background-color: #fff0!important;
}
.exopite-sof-nav {
    background-color: #fff!important;
}
.exopite-sof-nav-list-item, .exopite-sof-nav-list-parent-item>span {
    color: #23282d!important;
    border-bottom: 1px solid #eee!important;
}
.exopite-sof-nav-list-item:hover, .exopite-sof-nav-list-parent-item>span:hover {
    color: #5f5f5f;
}
.exopite-sof-nav-list-item.active {
    border-right: 3px solid #0073aa!important;
    color: #5f5f5f!important;
    background-color: #fff0!important;
}
.exopite-sof-header {
    background-color: #93033c!important;
    background-image: none!important;
}
.checkbox__switch:after,.checkbox__input:checked+.checkbox__switch,.checkbox__switch {
    border-radius: 100px!important;
}
.checkbox__input:checked+.checkbox__switch {
    border-color: #01d28e26!important;
    background-color: #01d28e26!important;
}
.checkbox__switch {
    background-color: #fa163f26!important;
}
.exopite-sof-content .info {
    color: #062748!important;
    background-color: #1089ff26!important;
    border-color: #1089ff26!important;
}
.exopite-sof-sections,.exopite-sof-nav {
    box-shadow: 1px 1px 10px 0 rgba(0,0,0,0.05);
}
span.exopite-sof-search-wrapper {
    display: none;
}
@media (min-width:768px) {
.exopite-sof-sections {
    margin-right: 25px;
}}
	</style>
	<?php
}
add_action( 'admin_head', 'efa_persian_li' );