<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Load gettext translate for our text domain.
 *
 * @since 1.9.3
 *
 * @return void
 */
 
// Elementor Pro
$text_domain = 'elementor-pro';
	$override_language_file = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$original_language_file = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $override_language_file );

// Elementor 
$text_domain = 'elementor';
	$override_language_file = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$original_language_file = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $override_language_file );
	
// Ele Custom Skin 
$text_domain = 'ele-custom-skin';
	$override_language_file = PERSIAN_ELEMENTOR . "/languages/$text_domain/$text_domain-fa_IR.mo";
	$original_language_file = "wp-content/languages/plugins/$text_domain-fa_IR.mo";
	unload_textdomain($text_domain);
	load_textdomain($text_domain, $override_language_file );