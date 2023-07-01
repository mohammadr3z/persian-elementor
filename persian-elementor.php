<?php
/**
 * Plugin Name: المنتور فارسی
 * Plugin URI: 
 * Description: بسته فارسی ساز افزونه المنتور پرو به همراه اضافه شدن 13 فونت فارسی، تقویم شمسی برای المنتور، قالب های آماده فارسی در کتابخانه المنتور و آیکون های ایرانی
 * Version: 2.6.3
 * Author: المنتور فارسی
 * Author URI: 
 * Text Domain: persian-elementor
 * License: GPL2
 * Elementor tested up to: 3.14.0
 * Elementor Pro tested up to: 3.14.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Persian Elementor Class
 *
 * The init class that runs the Persian Elementor plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * You should only modify the constants to match your plugin's needs.
 *
 * Any custom code should go inside Plugin Class in the plugin.php file.
 * @since 2.3.10
 */
 
add_action( 'plugins_loaded', 'persian_elementor_init' );
 
function persian_elementor_init() {

    define( 'PERSIAN_ELEMENTOR', plugin_dir_path(__FILE__));
    define( 'PERSIAN_ELEMENTOR_VERSION', '2.6.3' );
}
 
final class Persian_Elementor {

	/**
	 * Plugin Version
	 *
	 * @since 2.3.10
	 * @var string The plugin version.
	 */
	const VERSION = '2.6.3';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 2.3.10
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.9.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 2.3.10
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '5.6';

	/**
	 * Constructor
	 *
	 * @since 2.3.10
	 * @access public
	 */
	public function __construct() {

		// Load translation
		add_action( 'init', array( $this, 'i18n' ) );

		// Init Plugin
		add_action( 'plugins_loaded', array( $this, 'init' ) );
		
		// Plugin Action
        add_filter('plugin_action_links_' . plugin_basename(__FILE__) , array( $this,'efa_plugin_action_links' ) );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'persian-elementor' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once( 'plugin.php' );
		require_once( 'includes/translate.php' );
		require_once('includes/localization.php'); 
        require_once('includes/admin/admin.php'); 
        require_once('includes/admin/codestar-framework/codestar-framework.php'); 
        require_once('includes/admin/options.php');
        require_once('includes/library/fonts.php'); 
        require_once('includes/library/icon.php');
        //require_once('includes/template/module.php');
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 2.3.10
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'persian-elementor' ),
			'<strong>' . esc_html__( 'Persian Elementor', 'persian-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'persian-elementor' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 2.3.10
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'persian-elementor' ),
			'<strong>' . esc_html__( 'Persian Elementor', 'persian-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'persian-elementor' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 2.3.10
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'persian-elementor' ),
			'<strong>' . esc_html__( 'Persian Elementor', 'persian-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'persian-elementor' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}
	
	/**
	 * Plugin Action
	 *
	 * The dynamic portion of the hook name, $plugin_file, refers to the path to the plugin file, relative to the plugins directory.
	 *
	 * @since 2.4.4
	 * @access public
	 */
	  public function efa_plugin_action_links($links) {
	      $settings_link = '<a href="' . get_admin_url() . 'admin.php?page=persian-elementor">' . ('تنظیمات');
                array_push($links, $settings_link);
	                return $links;
        }
}


// Instantiate Persian_Elementor.
new Persian_Elementor();