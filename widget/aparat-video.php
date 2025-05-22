<?php
/**
 * Aparat Integration for Elementor Video Widget
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Check if feature is enabled in settings
$options = get_option('persian_elementor', []);
if (!($options['efa-aparat-video'] ?? true)) {
    return; // Don't load if feature is disabled
}

class Persian_Elementor_Aparat_Integration {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        // Wait until Elementor is fully loaded
        add_action('elementor/init', [$this, 'init']);
    }
    
    public function init() {
        // Add Aparat as a video source and our custom controls
        add_action('elementor/element/video/section_video/before_section_end', [$this, 'add_aparat_controls']);
        
        // Hide irrelevant default controls when Aparat is selected
        add_action('elementor/element/video/section_video/after_section_end', [$this, 'modify_video_controls']);
        
        // Add Aparat as a video source
        add_filter('elementor/frontend/widget/before_render', [$this, 'before_render_video'], 10, 2);
        
        // Handle Aparat video rendering
        add_filter('elementor/widget/render_content', [$this, 'render_aparat_video'], 10, 2);
        
        // Add editor scripts for preview
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_editor_scripts']);
    }
    
    /**
     * Add Aparat specific controls to the video widget
     */
    public function add_aparat_controls($element) {
        // First add Aparat option to video_type dropdown
        $video_type = $element->get_controls('video_type');
        
        if ($video_type && is_array($video_type) && isset($video_type['options'])) {
            $video_type['options'] = array_merge(
                ['aparat' => esc_html__('آپارات', 'persian-elementor')],
                $video_type['options']
            );
            $element->update_control('video_type', ['options' => $video_type['options']]);
        }
        
        // Now add Aparat-specific controls
        $element->add_control(
            'aparat_url',
            [
                'label' => esc_html__('آدرس آپارات', 'persian-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                    'categories' => [
                        \Elementor\Modules\DynamicTags\Module::POST_META_CATEGORY,
                        \Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
                    ],
                ],
                'placeholder' => esc_html__('آدرس ویدئو آپارات را وارد کنید', 'persian-elementor'),
                'default' => 'https://www.aparat.com/v/b491rbh',
                'label_block' => true,
                'condition' => [
                    'video_type' => 'aparat',
                ],
                'frontend_available' => true,
            ]
        );
        
        $element->add_control(
            'start_m',
            [
                'label' => esc_html__('زمان شروع (دقیقه)', 'persian-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 59,
                'default' => 0,
                'condition' => [
                    'video_type' => 'aparat',
                ],
                'frontend_available' => true,
            ]
        );
        
        $element->add_control(
            'start_s',
            [
                'label' => esc_html__('زمان شروع (ثانیه)', 'persian-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 59,
                'default' => 0,
                'condition' => [
                    'video_type' => 'aparat',
                ],
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'mute_aparat',
            [
                'label' => esc_html__('پخش بی‌صدا', 'persian-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('بله', 'persian-elementor'),
                'label_off' => esc_html__('خیر', 'persian-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'video_type' => 'aparat',
                ],
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'title_show_aparat',
            [
                'label' => esc_html__('نمایش عنوان', 'persian-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('بله', 'persian-elementor'),
                'label_off' => esc_html__('خیر', 'persian-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'video_type' => 'aparat',
                ],
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'recom_self',
            [
                'label' => esc_html__('ویدیو های پیشنهادی', 'persian-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('بله', 'persian-elementor'),
                'label_off' => esc_html__('خیر', 'persian-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'video_type' => 'aparat',
                ],
                'frontend_available' => true,
            ]
        );
    }
    
    /**
     * Modify default video controls to hide irrelevant ones for Aparat
     */
    public function modify_video_controls($element) {
        // Hide irrelevant controls for Aparat
        $element->update_control(
            'youtube_url',
            [
                'condition' => [
                    'video_type' => 'youtube',
                ],
            ]
        );
        
        $element->update_control(
            'vimeo_url',
            [
                'condition' => [
                    'video_type' => 'vimeo',
                ],
            ]
        );
        
        $element->update_control(
            'dailymotion_url',
            [
                'condition' => [
                    'video_type' => 'dailymotion',
                ],
            ]
        );
        
        $element->update_control(
            'insert_url',
            [
                'condition' => [
                    'video_type' => 'hosted',
                ],
            ]
        );
        
        $element->update_control(
            'hosted_url',
            [
                'condition' => [
                    'video_type' => ['hosted'],
                    'insert_url' => '',
                ],
            ]
        );
        
        $element->update_control(
            'external_url',
            [
                'condition' => [
                    'video_type' => ['hosted'],
                    'insert_url' => 'yes',
                ],
            ]
        );
        
        $element->update_control(
            'start',
            [
                'condition' => [
                    'video_type!' => ['aparat'],
                ],
            ]
        );
        
        $element->update_control(
            'end',
            [
                'condition' => [
                    'video_type' => ['youtube', 'hosted'],
                ],
            ]
        );
        
        $element->update_control(
            'video_options',
            [
                'condition' => [
                    'video_type!' => ['aparat'],
                ],
            ]
        );
        
        // Update the play_on_mobile condition to exclude aparat
        $element->update_control(
            'play_on_mobile',
            [
                'condition' => [
                    'autoplay' => 'yes',
                    'video_type!' => ['aparat'],
                ],
            ]
        );
        
        // Update the mute control to exclude aparat (we have our own mute_vid control)
        $element->update_control(
            'mute',
            [
                'condition' => [
                    'video_type!' => ['aparat'],
                ],
            ]
        );
        
        // Hide other video-provider specific controls when Aparat is selected
        $controls_to_update = [
            'loop', 'controls', 'showinfo', 'modestbranding', 'logo', 
            'yt_privacy', 'lazy_load', 'rel', 'vimeo_title', 
            'vimeo_portrait', 'vimeo_byline', 'color', 
            'download_button', 'preload', 'poster' // Removed 'image_overlay'
        ];
        
        foreach ($controls_to_update as $control_name) {
            $control = $element->get_controls($control_name);
            if ($control) {
                $condition = isset($control['condition']) ? $control['condition'] : [];
                // Ensure 'video_type!' condition exists and includes 'aparat'
                if (!isset($condition['video_type!'])) {
                    $condition['video_type!'] = [];
                }
                if (!is_array($condition['video_type!'])) {
                    $condition['video_type!'] = (array) $condition['video_type!'];
                }
                if (!in_array('aparat', $condition['video_type!'])) {
                    $condition['video_type!'][] = 'aparat';
                }

                // Removed special handling for image_overlay
                // For other controls, just update the video_type condition
                $element->update_control($control_name, ['condition' => $condition]);
            }
        }
    }
    
    /**
     * Before rendering the video widget, check if we need to override settings
     */
    public function before_render_video($widget) {
        if ('video' !== $widget->get_name()) {
            return;
        }
        
        $settings = $widget->get_settings_for_display();
        
        if (!empty($settings['video_type']) && 'aparat' === $settings['video_type']) {
            // This is our hook that we'll use to intercept the rendering
            $widget->add_render_attribute('_wrapper', 'data-video-type', 'aparat');
        }
    }
    
    /**
     * Extract video hash from Aparat URL
     */
    private function extract_aparat_hash($url) {
        // Handle different URL formats
        $patterns = [
            // Standard format: https://www.aparat.com/v/HASH
            '~aparat\.com/v/([a-zA-Z0-9]+)~i',
            // Alternative format with showvideo: https://www.aparat.com/video/video/embed/videohash/HASH
            '~videohash/([a-zA-Z0-9]+)~i',
            // Fallback - try to get the last segment of the URL
            '~/([a-zA-Z0-9]+)$~'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }
        
        return '';
    }
    
    /**
     * Generate Aparat embed HTML
     * 
     * @param string $video_hash The Aparat video hash
     * @param array $params The parameters for the iframe
     * @return string The HTML for the embed
     */
    private function generate_aparat_embed_html($video_hash, $params) {
        $iframe_src = add_query_arg(
            $params,
            'https://www.aparat.com/video/video/embed/videohash/' . esc_attr($video_hash) . '/vt/frame'
        );

        ob_start();
        echo '<style>
            .h_iframe-aparat_embed_frame {
                position: relative;
                overflow: hidden;
                width: 100%;
            }
            .h_iframe-aparat_embed_frame .ratio {
                display: block;
                width: 100%;
                height: auto;
            }
            .h_iframe-aparat_embed_frame iframe {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                border: 0;
            }
        </style>';

        echo '<div class="h_iframe-aparat_embed_frame">';
        echo '<span style="display: block; padding-top: 57%"></span>';
        printf(
            '<iframe src="%s" allow="autoplay" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe>',
            esc_url($iframe_src)
        );
        echo '</div>';

        return ob_get_clean();
    }
    
    /**
     * Render Aparat video content
     */
    public function render_aparat_video($widget_content, $widget) {
        // Only process video widget
        if ('video' !== $widget->get_name()) {
            return $widget_content;
        }
        
        $settings = $widget->get_settings_for_display();
        
        // Only handle Aparat videos
        if (empty($settings['video_type']) || 'aparat' !== $settings['video_type']) {
            return $widget_content;
        }
        
        // Process Aparat video
        ob_start();

        // Sanitize and validate inputs
        $video_url = sanitize_text_field($settings['aparat_url']);
        $video_hash = $this->extract_aparat_hash($video_url);
        
        // Calculate start time (minutes + seconds)
        $start_m = isset($settings['start_m']) ? absint($settings['start_m']) : 0;
        $start_s = isset($settings['start_s']) ? absint($settings['start_s']) : 0;
        $start_time = ($start_m * 60) + $start_s;
        
        // Set default values for removed controls
        $autoplay = !empty($settings['autoplay']) ? 'true' : 'false';
        $mute_aparat = (!empty($settings['mute_aparat']) && $settings['mute_aparat'] === 'yes') ? 'true' : 'false';
        $title_show_aparat = (!empty($settings['title_show_aparat']) && $settings['title_show_aparat'] === 'yes') ? 'true' : 'false';
        $recom_self = (!empty($settings['recom_self']) && $settings['recom_self'] === 'yes') ? 'self' : null; // Check the new setting
        
        // Check if we have a valid video hash
        if (empty($video_hash)) {
            echo '<p>' . esc_html__('آدرس ویدیو آپارات معتبر نیست.', 'persian-elementor') . '</p>';
            return ob_get_clean();
        }

        // Build iframe URL with only relevant parameters
        $params = [];
        
        // Only add titleShow when explicitly enabled
        if ($title_show_aparat === 'true') {
            $params['titleShow'] = 'true';
        }
        
        // Only add muted when explicitly enabled
        if ($mute_aparat === 'true') {
            $params['muted'] = 'true';
        }
        
        // Always include autoplay setting
        $params['autoplay'] = $autoplay;
        
        // Add start time parameter if it's not zero
        if ($start_time > 0) {
            $params['t'] = $start_time;
        }

        // Add recom parameter if enabled
        if ($recom_self === 'self') {
            $params['recom'] = 'self';
        }
        
        echo $this->generate_aparat_embed_html($video_hash, $params);

        return ob_get_clean();
    }
    
    /**
     * Add script for handling Aparat in Elementor editor preview
     */
    public function enqueue_editor_scripts() {
        // Register script for editor preview
        wp_enqueue_script(
            'persian-elementor-aparat-editor',
            plugins_url('/assets/js/aparat-editor.js', dirname(__FILE__)),
            ['elementor-editor'],
            '1.0.0',
            true
        );
        
        // Localize script with translation strings
        wp_localize_script(
            'persian-elementor-aparat-editor',
            'persianElementorAparat',
            [
                'invalidUrl' => esc_html__('آدرس ویدیو آپارات معتبر نیست.', 'persian-elementor'),
            ]
        );
    }
}

// Initialize the class
Persian_Elementor_Aparat_Integration::get_instance();
