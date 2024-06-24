<?php
declare(strict_types=1);

namespace PersianElementor;

class PersianElementorCore {
    private static ?self $_instance = null;
    private array $options;

    public static function instance(): self {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        $this->options = get_option('persian_elementor', []);
        $this->init_hooks();
    }

    private function __clone() {}
    private function __wakeup() {}

    private function init_hooks(): void {
        if ($this->options['efa-panel-font'] ?? false) {
            add_action('elementor/editor/before_enqueue_scripts', [$this, 'enqueue_rtl_styles']);
            add_action('elementor/preview/enqueue_styles', [$this, 'enqueue_rtl_styles']);
            add_action('elementor/app/init', [$this, 'enqueue_rtl_styles']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_rtl_styles']);
        }

        if ($this->options['efa-all-font'] ?? false) {
            add_action('elementor/frontend/after_enqueue_styles', [$this, 'enqueue_cdn_font']);
        }

        if ($this->options['efa-flatpickr'] ?? false) {
            add_action('elementor/frontend/before_enqueue_styles', [$this, 'enqueue_frontend_styles']);
        }

        add_action('elementor/editor/before_enqueue_scripts', [$this, 'enqueue_template_script']);

        if ($this->options['efa-iranian-icon'] ?? false) {
            add_action('elementor/editor/before_enqueue_scripts', [$this, 'enqueue_icon_styles']);
            add_action('elementor/frontend/before_enqueue_styles', [$this, 'enqueue_icon_styles']);
        }
    }

    public function enqueue_rtl_styles(): void {
        $current_hook = current_filter();
        $style_suffix = match ($current_hook) {
            'elementor/editor/before_enqueue_scripts' => 'editor-rtl.min.css',
            'elementor/preview/enqueue_styles' => 'preview-rtl.css',
            default => 'common-rtl.css',
        };
        wp_enqueue_style('persian-elementor-rtl', plugins_url("assets/css/$style_suffix", __FILE__));
    }

    public function enqueue_cdn_font(): void {
        wp_enqueue_style('persian-elementor-font', plugins_url('assets/css/font.css', __FILE__));
    }

    public function enqueue_frontend_styles(): void {
        wp_enqueue_style('persian-elementor-front', plugins_url('assets/css/front-rtl.css', __FILE__));
        wp_enqueue_script('persian-elementor-flatpickr-mobile', plugins_url('assets/js/flatpickr/flatpickr-mobile.js', __FILE__), ['flatpickr']);
    }

    public function enqueue_template_script(): void {
        wp_enqueue_script('persian-elementor-template', plugins_url('assets/js/editor.js', __FILE__));
    }

    public function enqueue_icon_styles(): void {
        wp_enqueue_style('persian-elementor-icon', plugins_url('includes/icons/efaicons/style.css', __FILE__));
    }
}

PersianElementorCore::instance();