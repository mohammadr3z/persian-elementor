(function () {
    'use strict';

    // Track active calendar instances
    var activeCalendars = new WeakMap();
    var calendarPopup = null;
    var currentInput = null;
    var lastOpenTime = 0; // Track when calendar was opened

    /**
     * Create popup container for calendar
     */
    function getOrCreatePopup() {
        if (!calendarPopup) {
            calendarPopup = document.createElement('div');
            calendarPopup.className = 'persian-date-popup';
            calendarPopup.style.cssText = 'position:absolute;z-index:999999;display:none;';
            document.body.appendChild(calendarPopup);
        }
        return calendarPopup;
    }

    /**
     * Position popup near input element
     */
    function positionPopup(input) {
        var popup = getOrCreatePopup();
        var rect = input.getBoundingClientRect();
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        var scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

        popup.style.top = (rect.bottom + scrollTop + 5) + 'px';
        popup.style.left = (rect.left + scrollLeft) + 'px';

        // Check if popup goes off screen
        setTimeout(function () {
            var popupRect = popup.getBoundingClientRect();
            if (popupRect.right > window.innerWidth) {
                popup.style.left = (window.innerWidth - popupRect.width - 10 + scrollLeft) + 'px';
            }
            if (popupRect.bottom > window.innerHeight) {
                popup.style.top = (rect.top + scrollTop - popupRect.height - 5) + 'px';
            }
        }, 0);
    }


    /**
     * Check if calendar is open
     */
    function isCalendarOpen() {
        return calendarPopup && calendarPopup.style.display !== 'none';
    }

    /**
     * Show calendar popup
     */
    function showCalendar(input) {
        if (typeof PersianElementorCalendar === 'undefined') {
            console.error('Persian Elementor: PersianElementorCalendar not loaded');
            return;
        }

        // If already open for a different input, close it first
        if (isCalendarOpen() && currentInput !== input) {
            hideCalendar();
        }

        // If already open for this input, don't reopen
        if (isCalendarOpen() && currentInput === input) {
            return;
        }

        var popup = getOrCreatePopup();
        currentInput = input;
        lastOpenTime = Date.now();

        // Clear previous calendar and destroy instance
        if (activeCalendars.has(popup)) {
            var oldCalendar = activeCalendars.get(popup);
            if (oldCalendar && typeof oldCalendar.destroy === 'function') {
                oldCalendar.destroy();
            }
            activeCalendars.delete(popup);
        }
        popup.innerHTML = '';

        // Parse existing value if any (format: YYYY/MM/DD)
        var targetInput = input;
        var selectedDate = new Date();
        var existingValue = targetInput.value;
        if (existingValue && typeof PersianElementorDateConverter !== 'undefined') {
            var parts = existingValue.split('/');
            if (parts.length === 3) {
                var jy = parseInt(parts[0], 10);
                var jm = parseInt(parts[1], 10);
                var jd = parseInt(parts[2], 10);
                var gregorian = PersianElementorDateConverter.jalaliToGregorian(jy, jm, jd);
                if (gregorian[0] > 0) {
                    selectedDate = new Date(gregorian[0], gregorian[1] - 1, gregorian[2]);
                }
            }
        }

        // Create calendar with closure for this specific input
        var calendar = new PersianElementorCalendar(popup, {
            selectedDate: selectedDate,
            title: 'انتخاب تاریخ',
            showCloseButton: true,
            onDateSelect: function (dateInfo) {
                var jalali = dateInfo.jalali;
                var formatted = jalali.year + '/' +
                    String(jalali.month).padStart(2, '0') + '/' +
                    String(jalali.day).padStart(2, '0');

                // Use the captured targetInput reference
                targetInput.value = formatted;

                // Trigger change event
                var event = new Event('change', { bubbles: true });
                targetInput.dispatchEvent(event);

                hideCalendar();
            },
            onClose: function () {
                hideCalendar();
            }
        });

        activeCalendars.set(popup, calendar);

        // Read font from .elementor-field-group > label and apply to calendar
        var labelElement = targetInput.closest('.elementor-field-group');
        if (labelElement) {
            var label = labelElement.querySelector('label');
            if (label) {
                var computedStyle = window.getComputedStyle(label);
                popup.style.fontFamily = computedStyle.fontFamily;
            }
        }

        // Prevent clicks inside popup from closing other popups
        popup.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        // Show popup
        popup.style.display = 'block';
        positionPopup(input);

        // Mark input as active
        input.setAttribute('data-calendar-open', 'true');
    }

    /**
     * Hide calendar popup
     */
    function hideCalendar() {
        if (calendarPopup) {
            calendarPopup.style.display = 'none';
            calendarPopup.innerHTML = '';
        }
        if (currentInput) {
            currentInput.removeAttribute('data-calendar-open');
            currentInput = null;
        }
    }

    /**
     * Initialize a single date field
     */
    function initPersianDateField(inputOrId) {
        var input = typeof inputOrId === 'string' ?
            document.getElementById(inputOrId) : inputOrId;

        if (!input) return false;
        if (input.getAttribute('data-persian-date-initialized')) return true;

        input.setAttribute('data-persian-date-initialized', 'true');

        // Only use click event, not focus
        input.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            // Toggle calendar
            if (isCalendarOpen() && currentInput === input) {
                hideCalendar();
            } else {
                showCalendar(input);
            }
        });

        // Prevent focus from doing anything
        input.addEventListener('focus', function (e) {
            // Don't auto-open on focus, only on click
        });

        return true;
    }

    /**
     * Initialize all date fields on page
     */
    function initAllDateFields() {
        var fields = document.querySelectorAll('[data-persian-date]:not([data-persian-date-initialized])');
        fields.forEach(function (field) {
            initPersianDateField(field);
        });
    }

    /**
     * Close calendar when clicking outside
     */
    document.addEventListener('click', function (e) {
        if (!isCalendarOpen()) return;

        // Don't close if clicked within 100ms of opening (prevents race condition)
        if (Date.now() - lastOpenTime < 100) return;

        // Don't close if clicking inside calendar
        if (calendarPopup.contains(e.target)) return;

        // Don't close if clicking the input that opened it
        if (currentInput && e.target === currentInput) return;

        hideCalendar();
    });

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initAllDateFields();
        });
    } else {
        initAllDateFields();
    }

    // MutationObserver for dynamic content
    var observer = new MutationObserver(function (mutations) {
        var shouldReinit = false;

        mutations.forEach(function (mutation) {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(function (node) {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        if (node.matches && node.matches('[data-persian-date]')) {
                            shouldReinit = true;
                        } else if (node.querySelector && node.querySelector('[data-persian-date]')) {
                            shouldReinit = true;
                        }
                    }
                });
            }
        });

        if (shouldReinit) {
            initAllDateFields();
        }
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    // Expose functions globally
    window.initPersianDateField = initPersianDateField;
    window.initAllPersianDateFields = initAllDateFields;
    window.hidePersianCalendar = hideCalendar;

})();
