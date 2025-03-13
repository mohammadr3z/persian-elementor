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
        
        // Add back aparat_height control
        $element->add_control(
            'aparat_height',
            [
                'label' => esc_html__('ارتفاع ثابت', 'persian-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'description' => esc_html__('حداقل ارتفاع (اختیاری - در صورت خالی بودن، نسبت تصویر استفاده می‌شود)', 'persian-elementor'),
                'default' => '640',
                'condition' => [
                    'video_type' => 'aparat',
                ],
                'frontend_available' => true,
            ]
        );
        
        $element->add_control(
            'start_h',
            [
                'label' => esc_html__('زمان شروع (ساعت)', 'persian-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 0,
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
            'download_button', 'preload', 'poster'
        ];
        
        foreach ($controls_to_update as $control_name) {
            $control = $element->get_controls($control_name);
            if ($control) {
                $condition = isset($control['condition']) ? $control['condition'] : [];
                if (isset($condition['video_type']) && is_array($condition['video_type'])) {
                    // Keep existing video type conditions
                } else {
                    // Add condition to hide for aparat type
                    $condition['video_type!'] = array_merge(
                        isset($condition['video_type!']) ? (array)$condition['video_type!'] : [],
                        ['aparat']
                    );
                }
                
                $element->update_control($control_name, ['condition' => $condition]);
            }
        }
        
        // Hide image overlay section for Aparat
        $element->update_control(
            'show_image_overlay',
            [
                'condition' => [
                    'video_type!' => ['aparat'],
                ],
            ]
        );
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
        
        // Calculate start time (hours + minutes + seconds)
        $start_h = isset($settings['start_h']) ? absint($settings['start_h']) : 0;
        $start_m = isset($settings['start_m']) ? absint($settings['start_m']) : 0;
        $start_s = isset($settings['start_s']) ? absint($settings['start_s']) : 0;
        $start_time = ($start_h * 3600) + ($start_m * 60) + $start_s;
        
        // Set default values for removed controls
        $autoplay = !empty($settings['autoplay']) ? 'true' : 'false';
        
        // Check if we have a valid video hash
        if (empty($video_hash)) {
            echo '<p>' . esc_html__('آدرس ویدیو آپارات معتبر نیست.', 'persian-elementor') . '</p>';
            return ob_get_clean();
        }

        // Build iframe URL with default values for removed controls
        $iframe_src = add_query_arg(
            array(
                't' => $start_time,
                'titleShow' => 'true', // Default to showing title
                'muted' => 'false',   // Default to unmuted
                'autoplay' => $autoplay,
            ),
            'https://www.aparat.com/video/video/embed/videohash/' . esc_attr($video_hash) . '/vt/frame'
        );

        // Check if a fixed height is set
        $fixed_height = !empty($settings['aparat_height']) ? absint($settings['aparat_height']) : 0;
        
        // Get aspect ratio class from video settings or use default
        $aspect_ratio = !empty($settings['aspect_ratio']) ? $settings['aspect_ratio'] : '169';
        
        if ($fixed_height > 0) {
            // Generate fixed height iframe
            echo '<div class="elementor-video-wrapper">';
            printf(
                '<iframe src="%s" width="100%%" height="%d" frameborder="0" allow="autoplay" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe>',
                esc_url($iframe_src),
                $fixed_height
            );
            echo '</div>';
        } else {
            // Generate responsive wrapper with aspect ratio
            echo '<div class="elementor-video-wrapper">';
            echo '<div class="elementor-video-container elementor-fit-aspect-ratio" style="--video-aspect-ratio: ' . $this->get_aspect_ratio_value($aspect_ratio) . ';">';
            printf(
                '<iframe src="%s" width="100%%" frameborder="0" allow="autoplay" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe>',
                esc_url($iframe_src)
            );
            echo '</div>';
            echo '</div>';
        }

        return ob_get_clean();
    }
    
    /**
     * Convert aspect ratio setting to CSS value
     */
    private function get_aspect_ratio_value($setting) {
        $aspect_ratios = [
            '169' => '1.77777', // 16:9
            '219' => '2.33333', // 21:9
            '43' => '1.33333',  // 4:3
            '32' => '1.5',      // 3:2
            '11' => '1',        // 1:1
            '916' => '0.5625',  // 9:16
        ];
        
        return isset($aspect_ratios[$setting]) ? $aspect_ratios[$setting] : $aspect_ratios['169'];
    }
}

// Initialize the class
Persian_Elementor_Aparat_Integration::get_instance();
