<?php
namespace PersianElementor;

/**
 * Class PersianElementorCore
 *
 * Main PersianElementorCore class
 * @since 2.3.10
 */
 
class PersianElementorCore {
    
	/**
	 * Instance
	 *
	 * @since 2.3.10
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;


	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 2.3.10
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Editor scripts
	 *
	 * Enqueue plugin javascripts and css integrations for Persian Elementor.
	 *
	 * @since 2.3.10
	 * @access public
	 */
	   // Enqueue Editor RTL
		public function editor_rtl_css()
	{
		wp_enqueue_style( 'persian-elementor-editor',plugins_url( 'assets/css/editor-rtl.min.css',  __FILE__ ) );
	}
	
	    // Enqueue Preview RTL
		public function preview_rtl_css()
	{
		wp_enqueue_style( 'persian-elementor-preview',plugins_url( 'assets/css/preview-rtl.css',  __FILE__ ) );
	}
	
	    // Enqueue App RTL
	    public function app_rtl_css()
	{
		wp_enqueue_style( 'persian-elementor-app',plugins_url( 'assets/css/common-rtl.css',  __FILE__ ) );
	}
	
	    // Enqueue Persian Font
		public function persian_elementor_cdn_font()
	{
		wp_enqueue_style( 'persian-elementor-font',plugins_url( 'assets/css/font.css',  __FILE__ ) );
	}
	
		// Enqueue Front RTL
		public function persian_elementor_front()
	{
		wp_enqueue_style( 'persian-elementor-front',plugins_url( 'assets/css/front-rtl.css',  __FILE__ ) );
	}

		// Enqueue Flatpickr Mobile
		public function persian_elementor_flatpickr_mobile()
	{
		wp_enqueue_script( 'persian-elementor-flatpickr-mobile',plugins_url( 'assets/js/flatpickr/flatpickr-mobile.js', __FILE__ ) , [ 'flatpickr' ]  );
	}
	
		// Enqueue Template Editor
		// public function persian_elementor_template()
	// {
		// wp_enqueue_script( 'persian-elementor-template',plugins_url( 'assets/js/editor.js', __FILE__ ) );
	// }
		// Enqueue Preview Icon
		public function persian_elementor_editor_icon()
	{
		wp_enqueue_style( 'persian-elementor-editor-icon',plugins_url( 'includes/library/icons/efaicons/style.css',  __FILE__ ) );
	}
	
		// Enqueue Preview Icon
		public function persian_elementor_preview_icon()
	{
		wp_enqueue_style( 'persian-elementor-preview-icon',plugins_url( 'includes/library/icons/efaicons/style.css',  __FILE__ ) );
	}

	/**
	 * Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 2.3.10
	 * @access public
	 */
	public function __construct() {
	    
	     $options = get_option('persian_elementor');

        if ($options['efa-panel-font']) {
        // Register Editor RTL
        add_action('elementor/editor/before_enqueue_scripts', [	$this,	'editor_rtl_css']);
        
        // Register Preview RTL
        add_action('elementor/preview/enqueue_styles', [ $this,	'preview_rtl_css']);
        
        // Register App RTL
        add_action('elementor/app/init', [ $this,	'app_rtl_css']);
        	
		// Register Admin RTL
        add_action('admin_enqueue_scripts', [ $this,	'app_rtl_css']);
        }
        
        // Register Persian Font
        if ( $options['efa-all-font']) {
        add_action('elementor/frontend/after_enqueue_styles', [ $this,	'persian_elementor_cdn_font']);
        }
        
        if ( $options['efa-flatpickr']) {
        // Register Front RTL
        add_action('elementor/frontend/before_enqueue_styles', [ $this,	'persian_elementor_front']);
		
        // Register Flatpickr Mobile
        add_action('elementor/frontend/before_enqueue_styles', [ $this,	'persian_elementor_flatpickr_mobile']);
		}
		
		// if ( $options['efa-templates-kits']) {
		// Register template
        // add_action('elementor/editor/before_enqueue_scripts', [ $this,	'persian_elementor_template']);
        // }
        
        if ( $options['efa-iranian-icon']) {
        // Register Editor Icon
        add_action('elementor/editor/before_enqueue_scripts', [ $this,	'persian_elementor_editor_icon']);
        
        // Register Preview Icon
        add_action('elementor/frontend/before_enqueue_styles', [ $this,	'persian_elementor_preview_icon']);
        }
		
	}
}

new PersianElementorCore;