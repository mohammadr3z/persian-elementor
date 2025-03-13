/**
 * Neshan Map Editor Integration
 */
(function($) {
    'use strict';
    
    // Initialize maps in editor
    function initEditorMaps() {
        // Make sure we're in elementor
        if (!window.elementor) return;
        
        // Create a MutationObserver to watch for DOM changes in the editor
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length) {
                    handleAddedNodes(mutation.addedNodes);
                }
            });
        });
        
        // Start observing the editor preview
        const editorPreview = document.querySelector('#elementor-preview-iframe')?.contentDocument;
        if (editorPreview) {
            observer.observe(editorPreview.body, {
                childList: true,
                subtree: true
            });
        }
        
        // Check existing nodes
        const mapContainers = document.querySelectorAll('.neshan-map-container');
        if (mapContainers.length) {
            mapContainers.forEach(initSingleMap);
        }
        
        // Also handle iframe load events
        const previewFrame = document.getElementById('elementor-preview-iframe');
        if (previewFrame) {
            previewFrame.addEventListener('load', function() {
                const frameMaps = previewFrame.contentDocument.querySelectorAll('.neshan-map-container');
                if (frameMaps.length) {
                    frameMaps.forEach(initSingleMap);
                }
            });
        }
    }
    
    // Handle added DOM nodes
    function handleAddedNodes(nodes) {
        nodes.forEach(function(node) {
            if (node.nodeType === 1) { // Element node
                // Check if the added node is a map container
                if (node.classList && node.classList.contains('neshan-map-container')) {
                    initSingleMap(node);
                }
                
                // Check children
                const mapContainers = node.querySelectorAll('.neshan-map-container');
                if (mapContainers.length) {
                    mapContainers.forEach(initSingleMap);
                }
            }
        });
    }
    
    // Initialize a single map instance
    function initSingleMap(mapContainer) {
        // Skip if already initialized or SDK not loaded
        if (mapContainer.getAttribute('data-initialized') === 'true' || typeof nmp_mapboxgl === 'undefined') return;
        
        try {
            // Create a unique identifier for this map instance
            const instanceId = mapContainer.getAttribute('data-instance-id') || mapContainer.id || Date.now().toString();
            
            // Get map data
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
            
            if (!apiKey || !latitude || !longitude) return;
            
            // Apply dimensions explicitly to this specific instance only
            if (mapHeight) {
                mapContainer.style.height = mapHeight;
                mapContainer.style.minHeight = mapHeight;
            }
            
            if (mapWidth) {
                mapContainer.style.width = mapWidth;
                mapContainer.style.minWidth = mapWidth;
            }
            
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
                color: markerColor || '#FF8330',
                draggable: false
            })
            .setLngLat([...mapCenterLocation])
            .addTo(map);
            
            // Mark as initialized in a way that won't conflict
            mapContainer.setAttribute('data-initialized', 'true');
            
        } catch (e) {
            console.error('Error initializing Neshan map in editor:', e);
        }
    }
    
    // Wait for DOM ready and Neshan SDK to load
    $(document).ready(function() {
        // Add listeners to editor events
        if (window.elementor) {
            // Init when editor is loaded
            elementor.on('preview:loaded', initEditorMaps);
            
            // Re-init when preview is refreshed
            elementor.channels.editor.on('section:activated', function() {
                setTimeout(initEditorMaps, 500);
            });
            
            // Re-init when panel changes
            elementor.channels.editor.on('change', function() {
                setTimeout(initEditorMaps, 500);
            });
        }
        
        // Initial call
        initEditorMaps();
    });

})(jQuery);
