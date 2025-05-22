<?php
namespace PersianElementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Register ZarinPal Components
 */
class ZarinPal_Register {
    /**
     * Constructor
     */
    public function __construct() {
        // Register widget
        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
        
        // Initialize AJAX handler
        $this->init_ajax_handler();
    }
    
    /**
     * Register ZarinPal widget
     * 
     * @param \Elementor\Widgets_Manager $widgets_manager Widgets manager instance
     */
    public function register_widgets($widgets_manager) {
		require_once plugin_dir_path(__FILE__) . 'zarinpal-button.php';
        
        $widgets_manager->register_widget_type(new Widgets\ZarinPal_Button());
    }
    
    /**
     * Initialize AJAX handler
     */
    private function init_ajax_handler() {
		require_once plugin_dir_path(__FILE__) . 'zarinpal-ajax.php';
        // ZarinPal_Ajax class self-initializes in its constructor
		
    }
}

// Initialize registration
new ZarinPal_Register();
