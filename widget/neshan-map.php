<?php
/**
 * Neshan Map Widget for Elementor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Check if feature is enabled in settings
$options = get_option('persian_elementor', []);
if (!($options['efa-neshan-map'] ?? true)) {
    return; // Don't load if feature is disabled
}

// Register scripts and styles for all contexts: frontend, editor, and preview
add_action('wp_enqueue_scripts', 'register_neshan_map_assets');
add_action('elementor/editor/before_enqueue_scripts', 'register_neshan_map_assets');
add_action('elementor/preview/enqueue_scripts', 'register_neshan_map_assets');

// Enqueue in editor context
add_action('elementor/editor/after_enqueue_scripts', 'enqueue_neshan_map_assets');
add_action('elementor/editor/after_enqueue_styles', 'enqueue_neshan_map_styles');

// Enqueue in preview context
add_action('elementor/preview/enqueue_scripts', 'enqueue_neshan_map_assets');
add_action('elementor/preview/enqueue_styles', 'enqueue_neshan_map_styles');

/**
 * Register the Neshan Map assets
 */
function register_neshan_map_assets() {
    wp_register_style('neshan-map-sdk', 'https://static.neshan.org/sdk/mapboxgl/v1.13.2/neshan-sdk/v1.0.8/index.css', [], '1.0.8');
    wp_register_script('neshan-map-sdk', 'https://static.neshan.org/sdk/mapboxgl/v1.13.2/neshan-sdk/v1.0.8/index.js', [], '1.0.8', true);
    
    // Register custom editor script to initialize map in editor
    wp_register_script(
        'neshan-map-editor', 
        plugins_url('../assets/js/neshan-map-editor.js', __FILE__),
        ['jquery', 'neshan-map-sdk'],
        '1.0.0',
        true
    );
}

/**
 * Enqueue the Neshan Map scripts
 */
function enqueue_neshan_map_assets() {
    wp_enqueue_script('neshan-map-sdk');
    wp_enqueue_script('neshan-map-editor');
}

/**
 * Enqueue the Neshan Map styles
 */
function enqueue_neshan_map_styles() {
    wp_enqueue_style('neshan-map-sdk');
}

// Register widget
add_action('elementor/widgets/register', 'register_persian_elementor_neshan_map_widget');

function register_persian_elementor_neshan_map_widget($widgets_manager) {
    class Persian_Elementor_Neshan_Map_Widget extends \Elementor\Widget_Base {
        
        public function get_name() {
            return 'neshan_map';
        }
        
        public function get_title() {
            return esc_html__('نقشه نشان', 'persian-elementor');
        }
        
        public function get_icon() {
            // Use the SVG file as background image
            add_action('elementor/editor/after_enqueue_styles', [$this, 'enqueue_neshan_icon_styles']);
            return 'neshan-map-icon';
        }
        
        /**
         * Enqueue styles for the custom Neshan icon
         */
        public function enqueue_neshan_icon_styles() {
            $icon_url = plugins_url('../assets/images/neshan.svg', __FILE__);
            
            // Output inline CSS for the custom icon
            echo '<style>
                .elementor-element .neshan-map-icon::after {
                    content: "";
                    background-image: url(' . esc_url($icon_url) . ');
                    background-size: contain;
                    background-repeat: no-repeat;
                    background-position: center center;
                    width: 28px;
                    height: 28px;
                    display: block;
                    margin: 0 auto;
                }
                [data-elementor-type="kit"] .elementor-element .neshan-map-icon::after,
                .elementor-panel-menu-item .neshan-map-icon::after {
                    filter: brightness(0) invert(1);
                }
            </style>';
        }
        
        public function get_categories() {
            return ['basic'];
        }
        
        public function get_keywords() {
            return ['map', 'neshan', 'نقشه', 'نشان', 'مکان'];
        }
        
        protected function register_controls() {
            // Map Settings Section (Combined API and Map settings)
            $this->start_controls_section(
                'section_map',
                [
                    'label' => esc_html__('تنظیمات نقشه', 'persian-elementor'),
                ]
            );
            
            $this->add_control(
                'api_key',
                [
                    'label' => esc_html__('کلید API نشان', 'persian-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'description' => sprintf(
                        esc_html__('کلید API نشان خود را وارد کنید. برای دریافت کلید به %s مراجعه کنید.', 'persian-elementor'),
                        '<a href="https://platform.neshan.org/panel/api-key" target="_blank">پنل نشان</a>'
                    ),
                    'default' => 'web.',
                    'separator' => 'after',
                ]
            );
            
            $this->add_control(
                'map_latitude',
                [
                    'label' => esc_html__('عرض جغرافیایی', 'persian-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '35.699789639952414',
                    'placeholder' => '35.699789639952414',
                ]
            );
            
            $this->add_control(
                'map_longitude',
                [
                    'label' => esc_html__('طول جغرافیایی', 'persian-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '51.33748508581425',
                    'placeholder' => '51.33748508581425',
                ]
            );
            
            $this->add_control(
                'map_zoom',
                [
                    'label' => esc_html__('بزرگنمایی نقشه', 'persian-elementor'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 15,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 2,
                            'max' => 21,
                            'step' => 1,
                        ],
                    ],
                ]
            );
            
            $this->add_control(
                'map_type',
                [
                    'label' => esc_html__('نوع نقشه', 'persian-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'neshanVector',
                    'options' => [
                        'neshanVector' => esc_html__('برداری روز', 'persian-elementor'),
                        'neshanVectorNight' => esc_html__('برداری شب', 'persian-elementor'),
                        'neshanRaster' => esc_html__('پیکسلی روز', 'persian-elementor'),
                        'neshanRasterNight' => esc_html__('پیکسلی شب', 'persian-elementor'),
                    ],
                ]
            );
            
            $this->add_control(
                'show_poi',
                [
                    'label' => esc_html__('نمایش مکان‌های منتخب', 'persian-elementor'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('بله', 'persian-elementor'),
                    'label_off' => esc_html__('خیر', 'persian-elementor'),
                    'default' => 'yes',
                ]
            );
            
            $this->add_control(
                'show_traffic',
                [
                    'label' => esc_html__('نمایش ترافیک', 'persian-elementor'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('بله', 'persian-elementor'),
                    'label_off' => esc_html__('خیر', 'persian-elementor'),
                    'default' => '',
                ]
            );
            
            $this->end_controls_section();
            
            // Style Settings Tab
            $this->start_controls_section(
                'section_style',
                [
                    'label' => esc_html__('استایل نقشه', 'persian-elementor'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );
            
            // Move marker color to Style tab
            $this->add_control(
                'marker_heading',
                [
                    'label' => esc_html__('نشانگر', 'persian-elementor'),
                    'type' => \Elementor\Controls_Manager::HEADING,
                ]
            );
            
            $this->add_control(
                'marker_color',
                [
                    'label' => esc_html__('رنگ نشانگر', 'persian-elementor'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#FF8330',
                    'selectors' => [
                        '{{WRAPPER}} .neshan-map-container .mapboxgl-marker svg g g[fill]' => 'fill: {{VALUE}} !important;',
                    ],
                ]
            );
            
            $this->add_control(
                'map_dimensions_heading',
                [
                    'label' => esc_html__('ابعاد', 'persian-elementor'),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            
            $this->add_responsive_control(
                'map_height',
                [
                    'label' => esc_html__('ارتفاع نقشه', 'persian-elementor'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', 'vh', '%'],
                    'range' => [
                        'px' => [
                            'min' => 100,
                            'max' => 1000,
                            'step' => 10,
                        ],
                        'vh' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                        '%' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'size' => 400,
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .neshan-map-container' => 'height: {{SIZE}}{{UNIT}} !important; min-height: {{SIZE}}{{UNIT}} !important;',
                    ],
                    'render_type' => 'template',
                    'frontend_available' => true,
                ]
            );
            
            $this->add_responsive_control(
                'map_width',
                [
                    'label' => esc_html__('عرض نقشه', 'persian-elementor'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', 'vw', '%'],
                    'range' => [
                        'px' => [
                            'min' => 100,
                            'max' => 1500,
                            'step' => 10,
                        ],
                        'vw' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                        '%' => [
                            'min' => 10,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'size' => 100,
                        'unit' => '%',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .neshan-map-container' => 'width: {{SIZE}}{{UNIT}} !important; min-width: {{SIZE}}{{UNIT}} !important;',
                    ],
                    'render_type' => 'template',
                    'frontend_available' => true,
                ]
            );
            
            $this->add_control(
                'map_style_heading',
                [
                    'label' => esc_html__('استایل ظاهری', 'persian-elementor'),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            
            $this->add_control(
                'map_border_radius',
                [
                    'label' => esc_html__('گردی گوشه‌ها', 'persian-elementor'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors' => [
                        '{{WRAPPER}} .neshan-map-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'map_border',
                    'selector' => '{{WRAPPER}} .neshan-map-container',
                    'separator' => 'before',
                ]
            );
            
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'map_box_shadow',
                    'selector' => '{{WRAPPER}} .neshan-map-container',
                ]
            );
            
            $this->end_controls_section();
        }
        
        protected function render() {
            $settings = $this->get_settings_for_display();
            
            // Get values from settings
            $api_key = !empty($settings['api_key']) ? $settings['api_key'] : 'web.';
            $marker_color = !empty($settings['marker_color']) ? $settings['marker_color'] : '#FF8330';
            $latitude = !empty($settings['map_latitude']) ? $settings['map_latitude'] : '35.699789639952414';
            $longitude = !empty($settings['map_longitude']) ? $settings['map_longitude'] : '51.33748508581425';
            $map_zoom = isset($settings['map_zoom']['size']) ? (int) $settings['map_zoom']['size'] : 15;
            $map_type = !empty($settings['map_type']) ? $settings['map_type'] : 'neshanVector';
            $show_poi = ($settings['show_poi'] === 'yes');
            $show_traffic = ($settings['show_traffic'] === 'yes');
            
            // Calculate heights and widths - prioritize the responsive controls if set
            $height = isset($settings['map_height']['size']) ? $settings['map_height']['size'] . $settings['map_height']['unit'] : '400px';
            $width = isset($settings['map_width']['size']) ? $settings['map_width']['size'] . $settings['map_width']['unit'] : '100%';
            
            // Create truly unique ID for this map instance
            $widget_id = $this->get_id();
            $unique_id = uniqid('neshanMap_');
            $map_id = 'neshanMap_' . $widget_id . '_' . $unique_id;
            
            // Pass data to JS using custom data attributes instead of wp_localize_script
            // to ensure completely isolated instances
            
            // Always enqueue in frontend context (already done for editor and preview)
            if (!is_admin()) {
                wp_enqueue_style('neshan-map-sdk');
                wp_enqueue_script('neshan-map-sdk');
            }
            
            // The map container with all necessary data attributes
            ?>
            <div class="neshan-map-container" 
                id="<?php echo esc_attr($map_id); ?>"
                data-widget-id="<?php echo esc_attr($widget_id); ?>"
                data-instance-id="<?php echo esc_attr($unique_id); ?>"
                data-api-key="<?php echo esc_attr($api_key); ?>"
                data-lat="<?php echo esc_attr($latitude); ?>"
                data-lng="<?php echo esc_attr($longitude); ?>"
                data-marker-color="<?php echo esc_attr($marker_color); ?>"
                data-height="<?php echo esc_attr($height); ?>"
                data-width="<?php echo esc_attr($width); ?>"
                data-zoom="<?php echo esc_attr($map_zoom); ?>"
                data-map-type="<?php echo esc_attr($map_type); ?>"
                data-poi="<?php echo $show_poi ? 'true' : 'false'; ?>"
                data-traffic="<?php echo $show_traffic ? 'true' : 'false'; ?>"
                style="height:<?php echo esc_attr($height); ?>; width:<?php echo esc_attr($width); ?>;">
            </div>

            <style>
                /* Instance-specific selectors to prevent conflicts */
                #<?php echo esc_attr($map_id); ?> {
                    overflow: hidden;
                    height: <?php echo esc_attr($height); ?> !important; 
                    min-height: <?php echo esc_attr($height); ?> !important;
                    width: <?php echo esc_attr($width); ?> !important;
                    min-width: <?php echo esc_attr($width); ?> !important;
                }
                #<?php echo esc_attr($map_id); ?> a {
                    text-decoration: none !important;
                    border: none;
                    padding: 0;
                    margin: 0;
                }
                #<?php echo esc_attr($map_id); ?> a:hover,
                #<?php echo esc_attr($map_id); ?> a:active {
                    text-decoration: none !important;
                    background: none;
                    border: none;
                    padding: 0;
                    margin: 0;
                }
                
                /* Force visible in editor - instance specific */
                .elementor-editor-active #<?php echo esc_attr($map_id); ?> {
                    position: relative;
                    z-index: 1;
                }
            </style>

            <script type="text/javascript">
                (function() {
                    function initMap_<?php echo esc_js($unique_id); ?>() {
                        if (typeof nmp_mapboxgl === 'undefined') {
                            setTimeout(initMap_<?php echo esc_js($unique_id); ?>, 300);
                            return;
                        }
                        
                        const mapContainer = document.getElementById('<?php echo esc_js($map_id); ?>');
                        if (!mapContainer || mapContainer.neshanMapInitialized) return;
                        
                        try {
                            // Get data from DOM - instance specific
                            const apiKey = mapContainer.getAttribute('data-api-key');
                            const latitude = parseFloat(mapContainer.getAttribute('data-lat'));
                            const longitude = parseFloat(mapContainer.getAttribute('data-lng'));
                            const markerColor = mapContainer.getAttribute('data-marker-color');
                            const mapHeight = mapContainer.getAttribute('data-height');
                            const mapWidth = mapContainer.getAttribute('data-width');
                            const mapZoom = parseInt(mapContainer.getAttribute('data-zoom') || '15');
                            const mapTypeStr = mapContainer.getAttribute('data-map-type') || 'neshanVector';
                            const poi = mapContainer.getAttribute('data-poi') === 'true';
                            const traffic = mapContainer.getAttribute('data-traffic') === 'true';
                            
                            // Apply dimensions explicitly to this specific instance
                            mapContainer.style.height = mapHeight;
                            mapContainer.style.minHeight = mapHeight;
                            mapContainer.style.width = mapWidth;
                            mapContainer.style.minWidth = mapWidth;
                            
                            // Location coordinates
                            const mapCenterLocation = [latitude, longitude];
                            
                            // Create map for this specific instance
                            const map = new nmp_mapboxgl.Map({
                                container: mapContainer,
                                mapKey: apiKey,
                                mapType: nmp_mapboxgl.Map.mapTypes[mapTypeStr],
                                zoom: mapZoom,
                                pitch: 0,
                                center: mapCenterLocation.reverse(),
                                minZoom: 2,
                                maxZoom: 21,
                                trackResize: true,
                                poi: poi,
                                traffic: traffic,
                                mapTypeControllerStatus: {
                                    show: true,
                                    position: 'bottom-right'
                                }
                            });
                            
                            // Add marker
                            new nmp_mapboxgl.Marker({
                                color: markerColor,
                                draggable: false
                            })
                            .setLngLat([...mapCenterLocation])
                            .addTo(map);
                            
                            // Add user location control
                            map.addControl(new nmp_mapboxgl.GeolocateControl({
                                positionOptions: {enableHighAccuracy: true},
                                trackUserLocation: true,
                                showUserHeading: true
                            }));
                            
                            // Mark as initialized with instance-specific flag
                            mapContainer.neshanMapInitialized = true;
                            mapContainer.setAttribute('data-initialized', 'true');
                        } catch (e) {
                            console.error('Error initializing Neshan map:', e);
                        }
                    }
                    
                    // Initialize map when page loads - use instance-specific function
                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', initMap_<?php echo esc_js($unique_id); ?>);
                    } else {
                        initMap_<?php echo esc_js($unique_id); ?>();
                    }
                })();
            </script>
            <?php
        }
    }
    
    $widgets_manager->register(new Persian_Elementor_Neshan_Map_Widget());
}
