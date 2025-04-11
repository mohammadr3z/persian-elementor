/**
 * Neshan Map Editor Integration
 * This script handles the initialization of Neshan Map in Elementor editor context
 */
(function($) {
    'use strict';

    /**
     * Initialize map in Elementor editor
     */
    function initNeshanMapInEditor() {
        // If we're in the editor context
        if (window.elementor || window.elementorFrontend) {
            // Make sure we have the map library
            if (typeof nmp_mapboxgl === 'undefined') {
                return;
            }

            // Hook into Elementor's element ready event
            if (window.elementorFrontend && window.elementorFrontend.hooks) {
                window.elementorFrontend.hooks.addAction('frontend/element_ready/neshan_map.default', function($scope) {
                    initializeMap($scope);
                });
            }
            
            // Also attempt to initialize any existing maps in the editor
            $('.neshan-map-container').each(function() {
                const $mapContainer = $(this);
                if (!$mapContainer.data('initialized')) {
                    initializeMap($mapContainer);
                }
            });
        }
    }

    /**
     * Initialize a specific map instance
     */
    function initializeMap($scope) {
        const $mapContainer = $scope.find('.neshan-map-container');
        if ($mapContainer.length === 0) return;

        const mapContainer = $mapContainer[0];
        if (mapContainer.neshanMapInitialized) return;

        try {
            // Get data from DOM attributes
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
            
            // Apply dimensions
            mapContainer.style.height = mapHeight;
            mapContainer.style.minHeight = mapHeight;
            mapContainer.style.width = mapWidth;
            mapContainer.style.minWidth = mapWidth;
            
            // Location coordinates - Make sure we have valid coordinates
            if (isNaN(latitude) || isNaN(longitude)) {
                console.error('Invalid coordinates for Neshan map');
                return;
            }

            const mapCenterLocation = [latitude, longitude];
            
            // Create map
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
            
            // Mark as initialized
            mapContainer.neshanMapInitialized = true;
            mapContainer.setAttribute('data-initialized', 'true');
            
            // Handle resize in editor
            if (window.elementor) {
                $(window).on('resize', function() {
                    setTimeout(function() {
                        map.resize();
                    }, 300);
                });
            }
        } catch (e) {
            console.error('Error initializing Neshan map in editor:', e);
        }
    }

    // Initialize when document is ready
    $(document).ready(function() {
        initNeshanMapInEditor();
    });

    // Also listen for panel changes in Elementor
    if (window.elementor) {
        window.elementor.channels.editor.on('section:activated', function() {
            setTimeout(initNeshanMapInEditor, 300);
        });
    }

})(jQuery);
