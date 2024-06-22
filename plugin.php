<?php
namespace PersianElementor;

class PersianElementorCore {
    private static ?self $_instance = null;

    public static function instance(): self {
        return self::$_instance ??= new self();
    }

    private function __construct() {
        $options = get_option('persian_elementor') ?? [];

        if (!empty($options['efa-panel-font'])) {
            $this->enqueue_editor_styles();
        }

        if (!empty($options['efa-all-font'])) {
            add_action('elementor/frontend/after_enqueue_styles', [$this, 'persian_elementor_cdn_font']);
        }

        if (!empty($options['efa-flatpickr'])) {
            $this->enqueue_flatpickr_styles();
        }

        if (!empty($options['efa-iranian-icon'])) {
            $this->enqueue_iranian_icons();
        }

        add_action('elementor/editor/before_enqueue_scripts', [$this, 'persian_elementor_template']);
    }

    private function enqueue_editor_styles(): void {
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'editor_rtl_css']);
        add_action('elementor/preview/enqueue_styles', [$this, 'preview_rtl_css']);
        add_action('elementor/app/init', [$this, 'app_rtl_css']);
        add_action('admin_enqueue_scripts', [$this, 'app_rtl_css']);
    }

    private function enqueue_flatpickr_styles(): void {
        add_action('elementor/frontend/before_enqueue_styles', [$this, 'persian_elementor_front']);
        add_action('elementor/frontend/before_enqueue_styles', [$this, 'persian_elementor_flatpickr_mobile']);
    }

    private function enqueue_iranian_icons(): void {
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'persian_elementor_editor_icon']);
        add_action('elementor/frontend/before_enqueue_styles', [$this, 'persian_elementor_preview_icon']);
    }

    public function editor_rtl_css(): void {
        if (!current_user_can('edit_posts')) {
            return;
        }
        wp_enqueue_style('persian-elementor-editor', plugins_url('assets/css/editor-rtl.min.css', __FILE__));
    }

    public function preview_rtl_css(): void {
        wp_enqueue_style('persian-elementor-preview', plugins_url('assets/css/preview-rtl.css', __FILE__));
    }

    public function app_rtl_css(): void {
        wp_enqueue_style('persian-elementor-app', plugins_url('assets/css/common-rtl.css', __FILE__));
    }

    public function persian_elementor_cdn_font(): void {
        wp_enqueue_style('persian-elementor-font', plugins_url('assets/css/font.css', __FILE__));
    }

    public function persian_elementor_front(): void {
        wp_enqueue_style('persian-elementor-front', plugins_url('assets/css/front-rtl.css', __FILE__));
    }

    public function persian_elementor_flatpickr_mobile(): void {
        wp_enqueue_script('persian-elementor-flatpickr-mobile', plugins_url('assets/js/flatpickr/flatpickr-mobile.js', __FILE__), ['flatpickr']);
    }

    public function persian_elementor_template(): void {
        wp_enqueue_script('persian-elementor-template', plugins_url('assets/js/editor.js', __FILE__));
    }

    public function persian_elementor_editor_icon(): void {
        wp_enqueue_style('persian-elementor-editor-icon', plugins_url('includes/library/icons/efaicons/style.css', __FILE__));
    }

    public function persian_elementor_preview_icon(): void {
        wp_enqueue_style('persian-elementor-preview-icon', plugins_url('includes/library/icons/efaicons/style.css', __FILE__));
    }
}

PersianElementorCore::instance();