<?php
declare(strict_types=1);
namespace PersianElementor;

class PersianElementorCore {
    private static $instance = null;
    private $options;
    private $version = '2.7.6.1'; 

    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->options = get_option('persian_elementor', array());
        
        $defaults = [
            'efa-panel-font' => '1',
            'efa-flatpickr' => '1',
            'efa-iranian-icon' => '1',
            'efa-elementor-pro' => '1',
            'efa-elementor' => '1',
            'efa-all-font' => '1',
        ];
        $this->options = array_merge($defaults, $this->options);
        update_option('persian_elementor', $this->options);
        $this->init_hooks();
    }

    private function __clone() {}
    public function __wakeup() {}

    private function init_hooks() {
        if (!empty($this->options['efa-panel-font'])) {
            add_action('elementor/editor/before_enqueue_scripts', array($this, 'enqueue_rtl_styles'));
            add_action('elementor/preview/enqueue_styles', array($this, 'enqueue_rtl_styles'));
            add_action('elementor/app/init', array($this, 'enqueue_rtl_styles'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_rtl_styles'));
        }
        if (!empty($this->options['efa-all-font'])) {
            add_action('elementor/frontend/after_enqueue_styles', array($this, 'enqueue_cdn_font'));
        }
        if (!empty($this->options['efa-flatpickr'])) {
            add_action('elementor/frontend/before_enqueue_styles', array($this, 'enqueue_frontend_styles'));
        }
        add_action('elementor/editor/before_enqueue_scripts', array($this, 'enqueue_template_script'));
        if (!empty($this->options['efa-iranian-icon'])) {
            add_action('elementor/editor/before_enqueue_scripts', array($this, 'enqueue_icon_styles'));
            add_action('elementor/frontend/before_enqueue_styles', array($this, 'enqueue_icon_styles'));
        }
    }

    public function enqueue_rtl_styles() {
        $current_hook = current_filter();
        $style_suffix = 'common-rtl.css';
        if ($current_hook === 'elementor/editor/before_enqueue_scripts') {
            $style_suffix = 'editor-rtl.min.css';
        } elseif ($current_hook === 'elementor/preview/enqueue_styles') {
            $style_suffix = 'preview-rtl.css';
        }
        wp_enqueue_style('persian-elementor-rtl', plugins_url("assets/css/$style_suffix", __FILE__), array(), $this->version);
    }

    public function enqueue_cdn_font() {
        wp_enqueue_style('persian-elementor-font', plugins_url('assets/css/font.css', __FILE__), array(), $this->version);
    }

    public function enqueue_frontend_styles() {
        wp_enqueue_style('persian-elementor-front', plugins_url('assets/css/front-rtl.css', __FILE__), array(), $this->version);
        wp_enqueue_script('persian-elementor-flatpickr-mobile', plugins_url('assets/js/flatpickr/flatpickr-mobile.js', __FILE__), array('flatpickr'), $this->version, true);
    }

    public function enqueue_template_script() {
        wp_enqueue_script('persian-elementor-template', plugins_url('assets/js/editor.js', __FILE__), array(), $this->version, true);
    }

    public function enqueue_icon_styles() {
        wp_enqueue_style('persian-elementor-icon', plugins_url('includes/icons/efaicons/style.css', __FILE__), array(), $this->version);
    }
}

PersianElementorCore::instance();