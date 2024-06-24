<?php
/**
 * Plugin Name: المنتور فارسی
 * Plugin URI: 
 * Description: بسته فارسی ساز افزونه المنتور پرو به همراه اضافه شدن 13 فونت فارسی، تقویم شمسی برای المنتور و آیکون های ایرانی
 * Version: 2.7.4
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
final class Persian_Elementor {
    private static $instance = null;
    private $version;
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
        $this->version = $this->get_plugin_version();
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return;
        }
        add_action('init', [$this, 'i18n']);
        $this->include_files();
        add_action('elementor/widgets/register', [$this, 'register_new_widgets']);
    }
    private function define_constants() {
        define('PERSIAN_ELEMENTOR', plugin_dir_path(__FILE__));
    }
    private function get_plugin_version() {
        $plugin_data = get_file_data(__FILE__, ['Version' => 'Version'], false);
        return $plugin_data['Version'];
    }
    public function i18n() {
        load_plugin_textdomain('persian-elementor');
    }
    private function include_files() {
        $includes = [
            'plugin.php',
            'includes/translate.php',
            'includes/localization.php',
            'includes/fonts.php',
            'includes/icon.php',
            'includes/options.php'
        ];
        foreach ($includes as $file) {
            $path = PERSIAN_ELEMENTOR . $file;
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
        require_once PERSIAN_ELEMENTOR . 'widget/video-widget.php';
        $widgets_manager->register(new \Persian_Elementor_Video_Widget());
    }
}
Persian_Elementor::get_instance();