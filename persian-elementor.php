<?php
/**
 * Plugin Name: المنتور فارسی
 * Plugin URI: 
 * Description: بسته فارسی ساز افزونه المنتور پرو به همراه اضافه شدن 13 فونت فارسی، تقویم شمسی برای المنتور، قالب های آماده فارسی در کتابخانه المنتور و آیکون های ایرانی
 * Version: 2.7.2
 * Author: المنتور فارسی
 * Author URI: 
 * Text Domain: persian-elementor
 * License: GPL2
 * Elementor tested up to: 3.22
 * Elementor Pro tested up to: 3.22
 */

if (!defined('ABSPATH')) {
    exit;
}

class Persian_Elementor {
    private $version;

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {
        if (!defined('PERSIAN_ELEMENTOR_VERSION')) {
            $plugin_data = get_file_data(__FILE__, ['Version' => 'Version'], false);
            define('PERSIAN_ELEMENTOR', plugin_dir_path(__FILE__));
            define('PERSIAN_ELEMENTOR_VERSION', $plugin_data['Version']);
        }
        $this->version = PERSIAN_ELEMENTOR_VERSION;

        add_action('init', [$this, 'i18n']);

        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return;
        }

        $this->include_files();

        add_action('elementor/widgets/register', [$this, 'register_new_widgets']);
    }

    public function i18n() {
        load_plugin_textdomain('persian-elementor');
    }

    private function include_files() {
        $includes = [
            'plugin.php',
            'includes/translate.php',
            'includes/localization.php',
            'includes/admin/admin.php',
            'includes/admin/codestar-framework/codestar-framework.php',
            'includes/admin/options.php',
            'includes/fonts.php',
            'includes/icon.php'
        ];

        foreach ($includes as $file) {
            $path = plugin_dir_path(__FILE__) . $file;
            if (file_exists($path)) {
                require_once $path;
            } else {
                error_log("File missing: " . $path);
            }
        }
    }

    public function admin_notice_missing_main_plugin() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'persian-elementor'),
            '<strong>' . esc_html__('Persian Elementor', 'persian-elementor') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'persian-elementor') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function register_new_widgets($widgets_manager) {
        require_once __DIR__ . '/widget/video-widget.php';
        $widgets_manager->register(new \Persian_Elementor_Video_Widget());
    }
}

new Persian_Elementor();