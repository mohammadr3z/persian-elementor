<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Load gettext translate for our text domain.
 *
 * @since 2.1.2
 *
 * @return void
 */
 
$options = get_option('persian_elementor');


if ( $options['efa-elementor-pro'] != 'no') {
// Elementor Pro
$text_domain = 'elementor-pro';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
}
if ( $options['efa-elementor'] != 'no') {
// Elementor 
$text_domain = 'elementor';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
}	
if ( $options['efa-ele-custom-skin'] != 'no') {
// Ele Custom Skin 
$text_domain = 'ele-custom-skin';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
}
if ( $options['efa-essential-addons-for-elementor-lite'] != 'no') {
// Essential Addons Lite
$text_domain = 'essential-addons-for-elementor-lite';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
}
if ( $options['efa-dynamicconditions'] != 'no') {
// Dynamic Conditions
$text_domain = 'dynamicconditions';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
}
if ( $options['efa-woolentor'] != 'no') {
// Woolentor
$text_domain = 'woolentor';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
}
if ( $options['efa-metform'] != 'no') {
// MetForm
$text_domain = 'metform';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
}