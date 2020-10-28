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
 
 
// Elementor Pro
$text_domain = 'elementor-pro';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );

// Elementor 
$text_domain = 'elementor';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
	
// Ele Custom Skin 
$text_domain = 'ele-custom-skin';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
	
// Essential Addons Lite
$text_domain = 'essential-addons-for-elementor-lite';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
	
// Dynamic Conditions
$text_domain = 'dynamicconditions';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
	
// Woolentor
$text_domain = 'woolentor';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
	
// MetForm
$text_domain = 'metform';
	$persian_elementor_lang = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$wordpress_lang = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $persian_elementor_lang );
