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
                    'placeholder' => esc_html__('web.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'persian-elementor'), // Added placeholder
                    'default' => '', // Changed default to empty string
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
            $api_key = !empty($settings['api_key']) ? trim($settings['api_key']) : ''; // Trim whitespace
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
            
            // Check if API key is missing or is the default placeholder (or the old default)
            if (empty($api_key) || $api_key === 'web.') { // Keep check for 'web.' for safety/backward compatibility
                // Display placeholder message if API key is missing
                ?>
                <div class="neshan-map-api-key-missing" style="height: <?php echo esc_attr($height); ?>; width: <?php echo esc_attr($width); ?>; display: flex; align-items: center; justify-content: center; background-color: #f0f0f0; border: 1px dashed #ccc; text-align: center; padding: 20px; box-sizing: border-box;">
                    <?php esc_html_e('برای نمایش نقشه باید کلید API نشان را در تنظیمات ویجت وارد کنید.', 'persian-elementor'); ?>
                </div>
                <?php
                return; // Stop further execution for this widget instance
            }
            
            // Create truly unique ID for this map instance
            $widget_id = $this->get_id();
            $unique_id = uniqid('neshanMap_');
            $map_id = 'neshanMap_' . $widget_id . '_' . $unique_id;
            
            // Always enqueue in frontend context (already done for editor and preview)
            if (!is_admin()) {
                wp_enqueue_style('neshan-map-sdk');
                wp_enqueue_script('neshan-map-sdk');
            }
            
            // Use Elementor's render attribute system instead of hardcoding attributes
            $this->add_render_attribute('map-container', [
                'class' => 'neshan-map-container',
                'id' => $map_id,
                'data-widget-id' => $widget_id,
                'data-instance-id' => $unique_id,
                'data-api-key' => $api_key,
                'data-lat' => $latitude,
                'data-lng' => $longitude,
                'data-marker-color' => $marker_color,
                'data-height' => $height,
                'data-width' => $width,
                'data-zoom' => $map_zoom,
                'data-map-type' => $map_type,
                'data-poi' => $show_poi ? 'true' : 'false',
                'data-traffic' => $show_traffic ? 'true' : 'false',
                'style' => 'height:' . $height . '; width:' . $width . ';', // Style applied here too
            ]);
            
            // The map container with all necessary data attributes
            ?>
            <div <?php echo $this->get_render_attribute_string('map-container'); ?>></div>

            <style>
                /* Instance-specific selectors to prevent conflicts */
                #<?php echo esc_attr($map_id); ?> {
                    overflow: hidden;
                    /* Height and width are now primarily set via inline style from add_render_attribute */
                    /* height: <?php echo esc_attr($height); ?> !important; 
                    min-height: <?php echo esc_attr($height); ?> !important;
                    width: <?php echo esc_attr($width); ?> !important;
                    min-width: <?php echo esc_attr($width); ?> !important; */
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
                        // Check if SDK is loaded
                        if (typeof nmp_mapboxgl === 'undefined') {
                            console.warn('Neshan SDK (nmp_mapboxgl) not loaded yet, retrying...');
                            setTimeout(initMap_<?php echo esc_js($unique_id); ?>, 300);
                            return;
                        }
                        
                        const mapContainer = document.getElementById('<?php echo esc_js($map_id); ?>');
                        // Ensure container exists and hasn't been initialized already
                        if (!mapContainer || mapContainer.neshanMapInitialized) {
                            // console.log('Map container <?php echo esc_js($map_id); ?> not found or already initialized.');
                            return;
                        }
                        
                        try {
                            // Get data from DOM - instance specific
                            const apiKey = mapContainer.getAttribute('data-api-key');
                            // Double check API key here in JS as well, though PHP check should prevent this script from running if missing
                            if (!apiKey || apiKey === 'web.') { // Keep check for 'web.' for safety/backward compatibility
                                console.error('Neshan API Key is missing for map: <?php echo esc_js($map_id); ?>');
                                mapContainer.innerHTML = '<p style="padding:20px; text-align:center;">' + <?php echo json_encode(esc_html__('خطا: کلید API نشان یافت نشد.', 'persian-elementor')); ?> + '</p>';
                                return;
                            }

                            const latitude = parseFloat(mapContainer.getAttribute('data-lat'));
                            const longitude = parseFloat(mapContainer.getAttribute('data-lng'));
                            const markerColor = mapContainer.getAttribute('data-marker-color');
                            const mapHeight = mapContainer.getAttribute('data-height'); // Already applied via style
                            const mapWidth = mapContainer.getAttribute('data-width');   // Already applied via style
                            const mapZoom = parseInt(mapContainer.getAttribute('data-zoom') || '15');
                            const mapTypeStr = mapContainer.getAttribute('data-map-type') || 'neshanVector';
                            const poi = mapContainer.getAttribute('data-poi') === 'true';
                            const traffic = mapContainer.getAttribute('data-traffic') === 'true';
                            
                            // Apply dimensions explicitly (redundant if style attribute works, but safe fallback)
                            // mapContainer.style.height = mapHeight;
                            // mapContainer.style.minHeight = mapHeight;
                            // mapContainer.style.width = mapWidth;
                            // mapContainer.style.minWidth = mapWidth;
                            
                            // Location coordinates [lng, lat] for MapboxGL
                            const mapCenterLocation = [longitude, latitude]; // Correct order for MapboxGL
                            
                            // Create map for this specific instance
                            const map = new nmp_mapboxgl.Map({
                                container: mapContainer,
                                mapKey: apiKey,
                                mapType: nmp_mapboxgl.Map.mapTypes[mapTypeStr] || nmp_mapboxgl.Map.mapTypes.neshanVector, // Fallback type
                                zoom: mapZoom,
                                pitch: 0,
                                center: mapCenterLocation, // Use directly
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
                            .setLngLat(mapCenterLocation) // Use directly
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
                            // console.log('Neshan map initialized: <?php echo esc_js($map_id); ?>');

                        } catch (e) {
                            console.error('Error initializing Neshan map (<?php echo esc_js($map_id); ?>):', e);
                             mapContainer.innerHTML = '<p style="padding:20px; text-align:center;">' + <?php echo json_encode(esc_html__('خطا در بارگذاری نقشه رخ داد. لطفاً کنسول مرورگر را بررسی کنید.', 'persian-elementor')); ?> + '</p>';
                        }
                    }
                    
                    // Initialize map when page loads or when element becomes visible (for tabs, accordions etc.)
                    function scheduleMapInit_<?php echo esc_js($unique_id); ?>() {
                         const mapContainer = document.getElementById('<?php echo esc_js($map_id); ?>');
                         if (mapContainer && mapContainer.offsetParent !== null) { // Check if visible
                             initMap_<?php echo esc_js($unique_id); ?>();
                         } else {
                             // If not visible yet, check again later or use IntersectionObserver
                             // console.log('Map <?php echo esc_js($map_id); ?> not visible yet, delaying init.');
                             setTimeout(scheduleMapInit_<?php echo esc_js($unique_id); ?>, 500); 
                         }
                    }

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', scheduleMapInit_<?php echo esc_js($unique_id); ?>);
                    } else {
                        scheduleMapInit_<?php echo esc_js($unique_id); ?>();
                    }

                    // Also try initializing if the element enters viewport (useful for dynamic content)
                    if ('IntersectionObserver' in window) {
                        const observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    initMap_<?php echo esc_js($unique_id); ?>();
                                    observer.unobserve(entry.target); // Initialize only once
                                }
                            });
                        }, { threshold: 0.1 }); // Trigger when 10% visible

                        const target = document.getElementById('<?php echo esc_js($map_id); ?>');
                        if (target) {
                            observer.observe(target);
                        }
                    }

                })();
            </script>
            <?php
        }
        
        /**
         * Render map in Elementor editor
         * This method uses JS template to render the map in the editor preview
         */
        protected function content_template() {
            ?>
            <#
            // Get values from settings
            var apiKey = settings.api_key ? settings.api_key.trim() : ''; // Trim whitespace
            var markerColor = settings.marker_color ? settings.marker_color : '#FF8330';
            var latitude = settings.map_latitude ? settings.map_latitude : '35.699789639952414';
            var longitude = settings.map_longitude ? settings.map_longitude : '51.33748508581425';
            var mapZoom = settings.map_zoom && settings.map_zoom.size ? parseInt(settings.map_zoom.size) : 15;
            var mapType = settings.map_type ? settings.map_type : 'neshanVector';
            var showPoi = settings.show_poi === 'yes';
            var showTraffic = settings.show_traffic === 'yes';
            
            // Calculate heights and widths
            var height = settings.map_height && settings.map_height.size ? settings.map_height.size + settings.map_height.unit : '400px';
            var width = settings.map_width && settings.map_width.size ? settings.map_width.size + settings.map_width.unit : '100%';
            
            // Check if API key is missing or is the default placeholder (or the old default)
            if (!apiKey || apiKey === 'web.') { // Keep check for 'web.' for safety/backward compatibility
                #>
                <div class="neshan-map-api-key-missing elementor-nerd-box" style="height: {{ height }}; width: {{ width }}; display: flex; align-items: center; justify-content: center; text-align: center; padding: 20px; box-sizing: border-box;">
                    <div class="elementor-nerd-box-message">
                        <?php esc_html_e('برای نمایش نقشه باید کلید API نشان را در تنظیمات ویجت وارد کنید.', 'persian-elementor'); ?>
                    </div>
                </div>
                <#
            } else {
                // Create unique ID for this map instance in editor
                var widgetId = view.getID();
                // Generate a more robust unique ID for the editor context
                var uniqueId = widgetId + '_' + Math.random().toString(36).substring(2, 9); 
                var mapId = 'neshanMap_' + uniqueId + '_editor';
                
                // Use Elementor's JS render attribute system
                view.addRenderAttribute('map-container', {
                    'class': 'neshan-map-container elementor-neshan-map-editor',
                    'id': mapId,
                    'data-widget-id': widgetId,
                    'data-instance-id': uniqueId,
                    'data-api-key': apiKey,
                    'data-lat': latitude,
                    'data-lng': longitude,
                    'data-marker-color': markerColor,
                    'data-height': height, // Pass height/width for JS init
                    'data-width': width,
                    'data-zoom': mapZoom,
                    'data-map-type': mapType,
                    'data-poi': showPoi ? 'true' : 'false',
                    'data-traffic': showTraffic ? 'true' : 'false',
                    'style': 'height:' + height + '; width:' + width + ';', // Apply style directly
                });
                #>
                
                <div {{{ view.getRenderAttributeString('map-container') }}}>
                    <div class="elementor-custom-embed-play" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 2; pointer-events: none;">
                        <i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
                    </div>
                </div>
                
                <style>
                    /* Styles specific to the editor map instance */
                    #{{ mapId }} {
                        overflow: hidden;
                        position: relative; /* Needed for overlay and spinner */
                        /* height/width set by inline style */
                    }
                    #{{ mapId }} a {
                        text-decoration: none !important; border: none; padding: 0; margin: 0;
                    }
                    #{{ mapId }} a:hover,
                    #{{ mapId }} a:active {
                        text-decoration: none !important; background: none; border: none; padding: 0; margin: 0;
                    }
                    /* Editor overlay to prevent interaction issues */
                    .elementor-editor-active .elementor-neshan-map-editor:before {
                        content: "";
                        position: absolute;
                        top: 0; left: 0; right: 0; bottom: 0;
                        background: rgba(0,0,0,0.05); /* Slight overlay */
                        z-index: 10; /* Above map controls but below spinner */
                        pointer-events: none; /* Allow clicks to pass through to Elementor controls */
                    }
                     /* Hide spinner once map is initialized */
                    #{{ mapId }}[data-initialized="true"] .elementor-custom-embed-play {
                        display: none;
                    }
                </style>
                
                <# 
                // Use a self-executing function to avoid polluting global scope in editor
                (function() {
                    var currentMapId = mapId; // Capture mapId in this scope
                    var currentUniqueId = uniqueId; // Capture uniqueId

                    // Define the initialization function specific to this instance
                    function initEditorMap() {
                        if (typeof nmp_mapboxgl === 'undefined') {
                            // console.warn('Editor: Neshan SDK not ready for ' + currentMapId + ', retrying...');
                            setTimeout(initEditorMap, 300);
                            return;
                        }

                        var $mapContainer = jQuery('#' + currentMapId);
                        if (!$mapContainer.length || $mapContainer.data('neshanMapInitialized')) {
                            // console.log('Editor: Map container ' + currentMapId + ' not found or already initialized.');
                            return; // Already initialized or container not found
                        }

                        var mapContainerEl = $mapContainer[0];

                        try {
                            // Get data from attributes again (safer in editor context)
                            var apiKey = $mapContainer.data('api-key');
                            if (!apiKey || apiKey === 'web.') { // Keep check for 'web.' for safety/backward compatibility
                                console.error('Editor: Neshan API Key missing for map: ' + currentMapId);
                                $mapContainer.html('<p style="padding:20px; text-align:center;">' + <?php echo json_encode(esc_html__('خطا: کلید API نشان یافت نشد.', 'persian-elementor')); ?> + '</p>');
                                return;
                            }
                            var latitude = parseFloat($mapContainer.data('lat'));
                            var longitude = parseFloat($mapContainer.data('lng'));
                            var markerColor = $mapContainer.data('marker-color');
                            var mapZoom = parseInt($mapContainer.data('zoom') || '15');
                            var mapTypeStr = $mapContainer.data('map-type') || 'neshanVector';
                            var poi = $mapContainer.data('poi') === true; // data() might convert 'true'/'false'
                            var traffic = $mapContainer.data('traffic') === true;
                            
                            // Location coordinates [lng, lat]
                            var mapCenterLocation = [longitude, latitude];
                            
                            // Create map
                            var map = new nmp_mapboxgl.Map({
                                container: mapContainerEl,
                                mapKey: apiKey,
                                mapType: nmp_mapboxgl.Map.mapTypes[mapTypeStr] || nmp_mapboxgl.Map.mapTypes.neshanVector,
                                zoom: mapZoom,
                                pitch: 0,
                                center: mapCenterLocation,
                                minZoom: 2,
                                maxZoom: 21,
                                trackResize: true, // Important for editor resizing
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
                                draggable: false // Usually false in editor preview
                            })
                            .setLngLat(mapCenterLocation)
                            .addTo(map);

                            // Add a slight delay before marking as initialized to allow rendering
                            setTimeout(function() {
                                $mapContainer.data('neshanMapInitialized', true);
                                $mapContainer.attr('data-initialized', 'true'); // For CSS selector
                                // console.log('Editor: Neshan map initialized: ' + currentMapId);
                            }, 100); 

                        } catch (e) {
                            console.error('Error initializing Neshan map in editor (' + currentMapId + '):', e);
                            $mapContainer.html('<p style="padding:20px; text-align:center;">' + <?php echo json_encode(esc_html__('خطا در بارگذاری نقشه در ویرایشگر.', 'persian-elementor')); ?> + '</p>');
                        }
                    }

                    // Use Elementor frontend hooks for initialization in the editor
                    if (elementorFrontend && elementorFrontend.hooks) {
                         // Use a timeout to ensure the element is fully rendered in the DOM
                         // Especially important when adding the widget for the first time
                        setTimeout(function() {
                            initEditorMap();
                        }, 150); // Small delay

                        // Re-initialize on view refresh (e.g., after changing settings)
                        elementor.channels.editor.on('change', function( controlView, model ) {
                            // Check if the changed control belongs to this widget instance
                            if ( model.cid === view.cid ) {
                                var $mapContainer = jQuery('#' + currentMapId);
                                if ($mapContainer.length) {
                                     // Clear previous instance if exists (simple way)
                                     $mapContainer.empty().removeData('neshanMapInitialized').removeAttr('data-initialized');
                                     // Re-render the container (might be needed if attributes changed significantly)
                                     // This part is tricky, ideally Elementor handles re-rendering the template.
                                     // For now, just re-run init after a short delay.
                                     setTimeout(initEditorMap, 150); 
                                }
                            }
                        });

                    } else {
                        // Fallback if hooks aren't available (less likely)
                        jQuery(document).ready(initEditorMap);
                    }
                })();
                #>
            <# 
            } // End else block (API key exists)
            #>
            <?php
        }
    }
    
    $widgets_manager->register(new Persian_Elementor_Neshan_Map_Widget());
}
