<?php
/**
 * Plugin Name: المنتور فارسی
 * Plugin URI: 
 * Description: بسته کامل فارسی‌ساز المنتور با 13 فونت ایرانی، ترجمه المنتور و المنتور پرو، آیکون‌های ایرانی، تقویم شمسی، ویجت‌های نقشه نشان و آپارات.
 * Version: 2.7.12
 * Author: المنتور فارسی
 * Author URI: 
 * Text Domain: persian-elementor
 * License: GPL2
 * Elementor tested up to: 3.30
 * Elementor Pro tested up to: 3.30
 */
if (!defined('ABSPATH')) {
    exit;
}

final class Persian_Elementor {
    private static $instance = null;
    private $options = [];

    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {
        $this->define_constants();
        if (!did_action('elementor/loaded')) {
            return;
        }
        $this->load_textdomain();
        $this->include_files();
        $this->register_hooks();
    }

    private function define_constants() {
        define('PERSIAN_ELEMENTOR', plugin_dir_path(__FILE__));
        define('PERSIAN_ELEMENTOR_URL', plugin_dir_url(__FILE__));
    }

    public function load_textdomain() {
        load_plugin_textdomain('persian-elementor');
    }

    private function include_files() {
        $includes = [
            'plugin.php',
            'includes/translate.php',
            'includes/fonts.php',
            'includes/icon.php',
            'includes/options.php',
        ];
        
        // Load optional components based on settings
        $options = get_option('persian_elementor', []);
        
        // Add form fields functionality if enabled (default to enabled if option doesn't exist)
        if ($options['efa-form-fields'] ?? true) {
            $includes[] = 'includes/form-fields.php';
        }
        
        // Add Aparat video integration if enabled (default to enabled if option doesn't exist)
        if ($options['efa-aparat-video'] ?? true) {
            $includes[] = 'widget/aparat-video.php';
        }
        
        // Add Neshan map widget if enabled (default to enabled if option doesn't exist)
        if ($options['efa-neshan-map'] ?? true) {
            $includes[] = 'widget/neshan-map.php';
        }
        
        // Add typography control if enabled (default to enabled if option doesn't exist)
        if ($options['efa-all-font'] ?? true) {
            $includes[] = 'widget/class-group-control-typography.php';
        }
        
        // Add ZarinPal payment button if enabled (default to enabled if option doesn't exist)
        if ($options['efa-zarinpal-button'] ?? true) {
            $includes[] = 'widget/zarinpal/zarinpal-handler.php';
            $includes[] = 'widget/zarinpal/zarinpal-register.php';
        }
        
        foreach ($includes as $file) {
            $path = PERSIAN_ELEMENTOR . $file;
            if (file_exists($path)) {
                require_once $path;
            } else {
                error_log("File missing: " . esc_html($path));
            }
        }
    }

    public function register_hooks() {
        $this->options = get_option('persian_elementor', []);
    }
}

Persian_Elementor::get_instance();