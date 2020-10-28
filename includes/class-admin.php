<?php
add_action( 'admin_menu', 'persian_elementor_panel' );
add_action( 'admin_init', 'efa_settings_init' );

function persian_elementor_panel() {
	add_menu_page(
	'المنتور فارسی',
	'المنتور فارسی',
	'manage_options',
	'persian_elementor',
	'checking_options_page',
	plugins_url( 'persian-elementor/includes/assets/images/icon.png' ),
	58);
}

function efa_checkbox_field_0_render(  ) { 

	$options = get_option( 'efa_settings' );
?>
		<input type='checkbox' name='efa_settings[efa_checkbox_field_0]' <?php checked( $options['efa_checkbox_field_0'], 1 ); ?> value='1'>
<?php
}

function efa_settings_section_callback(  ) { 

?>
	<div class="wrap about-wrap" style="font-family:vazir">
		<h1>به المنتور فارسی خوش آمديد</h1>
			<div class="about-text">لذت طراحی با زبان فارسی و ظاهر زيبا</div>

		<a class="wp-badge" href="https://elementorfa.ir/" target="_blank"	style="background-color:#93033C !important;background-image:url(<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/about.png'; ?>) !important;background-position: center center;background-size: 167px auto !important;"></a>

		<h2 class="nav-tab-wrapper">
			<a class="nav-tab nav-tab-active" href="https://elementorfa.ir/" target="_blank">تنظيمات</a>
			<a href="https://elementorfa.ir/shop/" class="nav-tab" target="_blank">فروشگاه</a>
			<a href="https://elementorfa.ir/faq" class="nav-tab" target="_blank">انجمن پرسش و پاسخ</a>
		</h2>
 <h3>تنظیمات نمایش فونت های فارسی</h3>
 <p>با روشن کردن این گزینه تمامی فونت های فارسی از المنتور حذف شده و فونت ها دیگر بارگذاری نخواهند شد.</p>
<?php

}

function efa_settings_init(  ) { 

register_setting( 'my_option', 'efa_settings' );

add_settings_section(
    'efa_setting_section', 
    (''), 
    'efa_settings_section_callback', 
    'disable_font'
);

add_settings_field( 
    'efa_checkbox_field_0', 
    __( 'غير فعال کردن فونت ها', 'wp' ), 
    'efa_checkbox_field_0_render', 
    'disable_font', 
    'efa_setting_section' 
);
}

function checking_options_page(  ) {
?>
<form action='options.php' method='post'>
    <?php
    settings_fields( 'my_option' );
    do_settings_sections( 'disable_font' );
    submit_button();
    ?>
</form>
</div>

<?php
}

function efa_persian_li() {
	?>
	<style>
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
	</style>
	<?php
}
add_action( 'admin_head', 'efa_persian_li' );