<?php
declare(strict_types=1);
namespace PersianElementor;

/**
 * Persian Elementor Core Class
 * Handles RTL styles, Persian fonts, and template library integration
 */
class PersianElementorCore {
    /** @var self Singleton instance */
    private static $instance = null;
    
    /** @var string Plugin version */
    private const VERSION = '2.7.11.5';
    
    /** @var array Default plugin options */
    private const DEFAULT_OPTIONS = [
        'efa-panel-font' => '1',
        'efa-iranian-icon' => '1',
        'efa-elementor-pro' => '1',
        'efa-elementor' => '1',
        'efa-all-font' => '1',
        'efa-zarinpal-button' => '1'
    ];
    
    /** @var array Plugin options */
    private $options;

    /** @return self Get singleton instance */
    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /** Constructor */
    private function __construct() {
        $this->load_options();
        $this->init_hooks();
    }

    /** Load and initialize plugin options */
    private function load_options() {
        $saved_options = get_option('persian_elementor', []);
        $this->options = array_merge(self::DEFAULT_OPTIONS, $saved_options);
        
        // Only update if options are different from saved options
        if ($saved_options !== $this->options) {
            update_option('persian_elementor', $this->options);
        }
    }

    /** Prevent cloning of the instance */
    private function __clone() {}

    /** Prevent unserializing of the instance */
    public function __wakeup() {}

    /** Initialize hooks based on plugin options */
    private function init_hooks() {
        // RTL styles hooks
        if (!empty($this->options['efa-panel-font'])) {
            $this->register_rtl_style_hooks();
        }
        
        // Persian font hooks
        if (!empty($this->options['efa-all-font'])) {
            add_action('elementor/frontend/after_enqueue_styles', [$this, 'enqueue_persian_font']);
        }
        
        // Iranian icons hooks
        if (!empty($this->options['efa-iranian-icon'])) {
            add_action('elementor/editor/before_enqueue_scripts', [$this, 'enqueue_icon_styles']);
            add_action('elementor/frontend/before_enqueue_styles', [$this, 'enqueue_icon_styles']);
        }
        
        // Template library integration
        $this->init_template_library();
        
        // Dashboard widget version display
        add_action('elementor/admin/dashboard_overview_widget/after_version', [$this, 'add_version_to_dashboard_header']);
    }
    
    /** Register RTL style hooks for different contexts */
    private function register_rtl_style_hooks() {
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'enqueue_rtl_styles']);
        add_action('elementor/preview/enqueue_styles', [$this, 'enqueue_rtl_styles']);
        add_action('elementor/app/init', [$this, 'enqueue_rtl_styles']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_rtl_styles']);
    }

    // ASSETS ENQUEUING

    /** Enqueue RTL styles based on current context */
    public function enqueue_rtl_styles() {
        $current_hook = current_filter();
        $style_suffix = 'common-rtl.css';
        
        if ($current_hook === 'elementor/editor/before_enqueue_scripts') {
            $style_suffix = 'editor-rtl.min.css';
        } elseif ($current_hook === 'elementor/preview/enqueue_styles') {
            $style_suffix = 'preview-rtl.css';
        }
        
        wp_enqueue_style(
            'persian-elementor-rtl', 
            plugins_url("assets/css/$style_suffix", __FILE__), 
            [], 
            self::VERSION
        );
    }

    /** Enqueue Persian font styles */
    public function enqueue_persian_font() {
        wp_enqueue_style(
            'persian-elementor-font', 
            plugins_url('assets/css/font.css', __FILE__), 
            [], 
            self::VERSION
        );
    }

    /** Enqueue frontend styles */
    public function enqueue_frontend_styles() {
        wp_enqueue_style(
            'persian-elementor-front', 
            plugins_url('assets/css/front-rtl.css', __FILE__), 
            [], 
            self::VERSION
        );
    }

    /** Enqueue icon styles */
    public function enqueue_icon_styles() {
        wp_enqueue_style(
            'persian-elementor-icon', 
            plugins_url('includes/icons/efaicons/style.css', __FILE__), 
            [], 
            self::VERSION
        );
    }
    
    // TEMPLATE LIBRARY
    
    /** Initialize the template library functionality */
    private function init_template_library() {
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_template_editor_scripts']);
    }
    
    /** 
     * Enqueue editor scripts for template library
     * Injects JavaScript to add the Temply tab to Elementor's template library
     */
    public function enqueue_template_editor_scripts() {
        wp_add_inline_script('elementor-editor', '
            elementor.on("document:loaded", function() {
                if ($e.components.get("library").hasTab("templates/temply")) {
                    return;
                }
                $e.components.get("library").addTab("templates/temply", {
                    title: "<a href=\"https://temply.ir/\" target=\"_blank\" style=\"color: #0c0d0e; padding:17px 25px\">قالب های ایرانی</a>",
                }, 5);
            });
        ');
    }
    
    /** @deprecated Use enqueue_template_editor_scripts() instead */
    public function enqueue_template_script() {
        $this->enqueue_template_editor_scripts();
    }

    /** Add Persian Elementor version to Elementor dashboard widget header */
    public function add_version_to_dashboard_header() {
        echo '<span class="e-overview__version">المنتور فارسی v' . esc_html(self::VERSION) . '</span>';
    }
    
    /** @return string The plugin version */
    public function get_version() {
        return self::VERSION;
    }
}

// Initialize plugin
PersianElementorCore::instance();