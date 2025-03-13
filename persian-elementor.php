<?php
/**
 * Plugin Name: المنتور فارسی
 * Plugin URI: 
 * Description: بسته فارسی ساز المنتور پرو با 13 فونت فارسی، فارسی ساز المنتور، المنتور پرو و آیکون‌های ایرانی.
 * Version: 2.7.8
 * Author: المنتور فارسی
 * Author URI: 
 * Text Domain: persian-elementor
 * License: GPL2
 * Elementor tested up to: 3.27
 * Elementor Pro tested up to: 3.27
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
        add_action('admin_notices', [$this, 'admin_notice']);
        add_action('admin_init', [$this, 'dismiss_admin_notice']);
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
            'includes/options.php',
        ];
        
        // Load optional components based on settings
        $options = get_option('persian_elementor', []);
        
        // Add Aparat video integration if enabled (default to enabled if option doesn't exist)
        if ($options['efa-aparat-video'] ?? true) {
            $includes[] = 'widget/aparat-video.php';
        }
        
        // Add Neshan map widget if enabled (default to enabled if option doesn't exist)
        if ($options['efa-neshan-map'] ?? true) {
            $includes[] = 'widget/neshan-map.php';
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
    
    /**
     * Display an admin notice for plugin configuration
     */
    public function admin_notice() {
        // Check if user has dismissed the notice
        $dismissed = get_transient('persian_elementor_notice_dismissed');
        
        // Don't show notice on the plugin's settings page or if dismissed
        if ($dismissed || (isset($_GET['page']) && 'persian_elementor' === $_GET['page'])) {
            return;
        }
        
        // Check if Elementor is active
        if (did_action('elementor/loaded')) {
            $dismiss_url = wp_nonce_url(
                add_query_arg('persian-elementor-action', 'dismiss-notice'),
                'persian_elementor_dismiss_notice'
            );
            
            echo '<div class="notice notice-info is-dismissible persian-elementor-notice">';
            echo '<p>';
            
            if (get_locale() === 'fa_IR') {
                printf(
                    'المنتور فارسی فعال شد، ممکن است نیاز به پیکربندی داشته باشید. <a href="%s">رفتن به صفحه تنظیمات</a> &ndash; <a href="%s">بستن این پیام</a>',
                    esc_url(admin_url('admin.php?page=persian_elementor')),
                    esc_url($dismiss_url)
                );
            } else {
                printf(
                    'Persian Elementor is activated. You may need to configure it to work properly. <a href="%s">Go to settings page</a> &ndash; <a href="%s">Dismiss</a>',
                    esc_url(admin_url('admin.php?page=persian_elementor')),
                    esc_url($dismiss_url)
                );
            }
            
            echo '</p></div>';
        }
    }
    
    /**
     * Handle the notice dismissal
     */
    public function dismiss_admin_notice() {
        if (isset($_GET['persian-elementor-action']) && 'dismiss-notice' === $_GET['persian-elementor-action']) {
            check_admin_referer('persian_elementor_dismiss_notice');
            
            // Set transient for 1 year (technically forever until plugin is deactivated)
            set_transient('persian_elementor_notice_dismissed', true, YEAR_IN_SECONDS);
            
            // Redirect back to where user was
            wp_safe_redirect(remove_query_arg(['persian-elementor-action', '_wpnonce']));
            exit;
        }
    }
}

Persian_Elementor::get_instance();