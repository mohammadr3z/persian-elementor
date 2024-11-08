<?php
/**
 * Plugin Name: المنتور فارسی
 * Plugin URI: 
 * Description: بسته فارسی ساز المنتور پرو با 13 فونت فارسی، تقویم شمسی و آیکون‌های ایرانی.
 * Version: 2.7.6.7
 * Author: المنتور فارسی
 * Author URI: 
 * Text Domain: persian-elementor
 * License: GPL2
 * Elementor tested up to: 3.25
 * Elementor Pro tested up to: 3.25
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
            'includes/options.php'
        ];
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
        
        if ($this->options['efa-all-font'] ?? false) {
            add_action('elementor/controls/controls_registered', [$this, 'persian_elementor_typography_control']);
            $this->persian_elementor_typography_init();
        }
    }

    public function persian_elementor_typography_init() {
        if (did_action('elementor/loaded')) {
            include_once PERSIAN_ELEMENTOR . 'widget/class-group-control-typography.php';
        }
    }

    public function persian_elementor_typography_control($controls_manager) {
        require_once PERSIAN_ELEMENTOR . 'widget/class-group-control-typography.php';
        $controls_manager->add_group_control('typography', new \Elementor\Group_Control_Typography());
    }
}

Persian_Elementor::get_instance();