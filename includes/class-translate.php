<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Load gettext translate for our text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
add_action( 'init', 'load_persian_elementor_text_domain' );
	function load_persian_elementor_text_domain() {
		load_plugin_textdomain( 'elementor-pro', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
		load_plugin_textdomain( 'elementor', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
	}


$text_domain = 'elementor-pro';
	$override_language_file = ABSPATH . DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'persian-elementor' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . 'elementor-pro-fa_IR.mo';
	$original_language_file = ABSPATH . DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'elementor-pro-fa_IR.mo';


	unload_textdomain($text_domain);
	load_textdomain($text_domain, $override_language_file );
	load_textdomain($text_domain, $original_language_file );
	
	
$text_domain = 'elementor';
	$override_language_file = ABSPATH . DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'persian-elementor' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . 'elementor-fa_IR.mo';
	$original_language_file = ABSPATH . DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'elementor-fa_IR.mo';


	unload_textdomain($text_domain);
	load_textdomain($text_domain, $override_language_file );
	load_textdomain($text_domain, $original_language_file );