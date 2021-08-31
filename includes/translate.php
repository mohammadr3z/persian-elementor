<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Load gettext translate for our text domain.
 *
 * @since 2.3.10
 *
 * @return void
 */
 
$options = get_option('persian_elementor');


if ( $options['efa-elementor-pro']) {
if (get_locale() == 'fa_IR' ) {
// Elementor Pro
$text_domain = 'elementor-pro';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
}
}
if ( $options['efa-elementor']) {
if (get_locale() == 'fa_IR' ) {
// Elementor 
$text_domain = 'elementor';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
}
}	
if ( $options['efa-ele-custom-skin']) {
if (get_locale() == 'fa_IR' ) {
// Ele Custom Skin 
$text_domain = 'ele-custom-skin';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
}
}
if ( $options['efa-essential-addons-for-elementor-lite']) {
if (get_locale() == 'fa_IR' ) {
// Essential Addons Lite
$text_domain = 'essential-addons-for-elementor-lite';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
}
}
if ( $options['efa-dynamicconditions']) {
if (get_locale() == 'fa_IR' ) {
// Dynamic Conditions
$text_domain = 'dynamicconditions';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
}
}
if ( $options['efa-woolentor']) {
if (get_locale() == 'fa_IR' ) {
// Woolentor
$text_domain = 'woolentor';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
}
}
if ( $options['efa-metform']) {
if (get_locale() == 'fa_IR' ) {
// MetForm
$text_domain = 'metform';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
}
}

