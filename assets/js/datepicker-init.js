(function($) {
    'use strict';
    
    // Track initialization status
    var isInitialized = false;
    var initializationAttempts = 0;
    var MAX_ATTEMPTS = 5;
    
    // Function to check if library is loaded
    function isJalaliDatepickerLoaded() {
        return typeof window.jalaliDatepicker !== 'undefined';
    }
    
    // Main initialization function
    function initJalaliDatepicker() {
        if (initializationAttempts >= MAX_ATTEMPTS) {
            console.error('Persian Elementor: Maximum initialization attempts reached');
            return false;
        }
        
        initializationAttempts++;
        
        if (!isJalaliDatepickerLoaded()) {
            console.error('Persian Elementor: jalaliDatepicker library not loaded (attempt ' + initializationAttempts + ')');
            
            // Try to load the library dynamically
            var script = document.createElement('script');
            script.src = 'https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js';
            script.onload = function() {
                console.log('Persian Elementor: Loaded jalaliDatepicker library');
                setTimeout(applyDatepicker, 300);
            };
            document.head.appendChild(script);
            
            return false;
        }
        
        applyDatepicker();
        return true;
    }
    
    // Apply datepicker to elements
    function applyDatepicker() {
        if (!isJalaliDatepickerLoaded()) return false;
        
        try {
            // Clear any initialization flags
            $('[data-jdp]').each(function() {
                $(this).removeAttr('data-jdp-initialized');
            });
            
            // Initialize with custom settings
            jalaliDatepicker.startWatch({
                selector: '[data-jdp]',
                persianDigit: true,
                autoClose: true,
                position: 'auto',
                observer: true,
                format: 'YYYY/MM/DD'
            });
            
            console.log('Persian Elementor: Datepicker initialized successfully');
            isInitialized = true;
            
            // Force re-init on click for problematic fields
            $(document).off('click', '.shamsi-date-input');  // Remove any duplicate handlers
            $(document).on('click', '.shamsi-date-input', function() {
                var $this = $(this);
                if (!$this.attr('data-jdp-initialized') || $this.attr('data-jdp-initialized') === "false") {
                    jalaliDatepicker.attachDatepicker($this[0]);
                }
            });
            
            // Initialize existing fields directly
            $('.shamsi-date-input').each(function() {
                jalaliDatepicker.attachDatepicker(this);
            });
            
            return true;
        } catch (error) {
            console.error('Persian Elementor: Datepicker initialization error', error);
            return false;
        }
    }
    
    // Initialize when document is ready
    $(document).ready(function() {
        // Try initialization with delay to ensure library is loaded
        setTimeout(initJalaliDatepicker, 500);
    });
    
    // Handle Elementor frontend initialization
    $(window).on('elementor/frontend/init', function() {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/form.default', function() {
                setTimeout(initJalaliDatepicker, 300);
            });
        }
    });
    
    // Handle dynamic content changes
    $(document).on('DOMNodeInserted', function(e) {
        var $target = $(e.target);
        
        // Check if our target contains datepicker fields
        if ($target.find('[data-jdp]').length > 0 || $target.is('[data-jdp]')) {
            setTimeout(initJalaliDatepicker, 300);
        }
    });
    
    // Make functions available globally for debugging
    window.persianElementor = {
        initDatepicker: initJalaliDatepicker,
        isJalaliLoaded: isJalaliDatepickerLoaded,
        manualInit: function(selector) {
            if (isJalaliDatepickerLoaded() && selector) {
                var element = document.querySelector(selector);
                if (element) {
                    jalaliDatepicker.attachDatepicker(element);
                    return true;
                }
            }
            return false;
        }
    };
    
})(jQuery);
