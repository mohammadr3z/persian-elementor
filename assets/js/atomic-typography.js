(function () {

    const registerPersianTypographyReplacement = () => {
        if (!window.elementorV2 || !window.elementorV2.editorControls) {
            return false;
        }

        const { registerControlReplacement, useBoundProp } = window.elementorV2.editorControls;
        const React = window.React;
        const { Switch, Grid, Typography } = window.elementorV2.ui;

        const fontFeaturesMap = {
            'ss01': ['AradVF', 'BonyadeKoodakVF', 'DibajVF', 'DoranVF', 'IRANSansXV', 'IRANYekanXV', 'LahzehVF', 'RaviVF', 'VazirmatnVF', 'YekanBakhVF'],
            'ss02': ['FarhangVF'],
            'ss05': ['AnjomanMaxVF'],
            'ss07': ['DarvishVF'],
            'ss10': ['DAMOONVF'],
            'numr': ['Anjoman', 'Arad', 'Artin', 'Aviny', 'Daal', 'Damavand', 'Dana', 'Emkan', 'Estedad', 'Farhang', 'Gandom', 'IRANSansDN', 'IRANSansX', 'IRANSharp', 'IRANYekanX', 'Kalameh', 'Kara', 'Katibeh', 'Maneli', 'Mikhak', 'Nahid', 'Noora', 'Parastoo', 'Pelak', 'Peyda', 'Ravi', 'Sahel', 'Samim', 'Shabnam', 'Tanha', 'VazirMatn', 'YekanBakh', 'VazirFN']
        };

        const allPersianFonts = [...new Set(Object.values(fontFeaturesMap).flat())];

        const getActiveFeature = (fontName) => {
            for (const [feature, fonts] of Object.entries(fontFeaturesMap)) {
                if (fonts.includes(fontName)) {
                    return feature;
                }
            }
            return null;
        };

        const getStringValue = (val) => {
            if (!val) return null;
            let str = null;
            if (typeof val === 'string') {
                str = val;
            } else if (typeof val === 'object') {
                if (val.value !== undefined) {
                    str = getStringValue(val.value);
                }
            }
            if (str) {
                // Strip single and double quotes (e.g. '"VazirMatn"' -> 'VazirMatn')
                return str.replace(/['"]/g, '').trim();
            }
            return null;
        };

        const isPersianFont = (rawVal) => {
            const val = getStringValue(rawVal);
            if (typeof val !== 'string') return false;
            return allPersianFonts.includes(val.trim());
        };

        const getSelectedElementId = () => {
            try {
                const _elementor = window.elementor || window.top.elementor;
                if (_elementor && _elementor.selection) {
                    const elements = _elementor.selection.getElements();
                    if (elements && elements.length > 0) {
                        return elements[0].id;
                    }
                }
            } catch (err) { }
            return null;
        };

        const applyFontFeatureToModel = (elementId, feature, enabled) => {
            try {
                const _elementor = window.elementor || window.top.elementor;
                if (!_elementor) return;

                const elements = _elementor.selection.getElements();
                const container = elements.find(el => el.id === elementId);
                if (!container) return;

                if (container.model && container.model.get('styles')) {
                    const styles = container.model.get('styles');
                    const styleKeys = Object.keys(styles);
                    let localStyleKey = styleKeys.find(key => styles[key].label === 'local');

                    if (localStyleKey) {
                        let clonedStyles = JSON.parse(JSON.stringify(styles));
                        let props = clonedStyles[localStyleKey].variants[0].props;

                        let changed = false;
                        if (enabled) {
                            if (!props['font-feature-settings'] || props['font-feature-settings'].value !== '"' + feature + '" 1') {
                                props['font-feature-settings'] = { "$$type": "string", "value": '"' + feature + '" 1' };
                                changed = true;
                            }
                        } else {
                            if (props['font-feature-settings'] && props['font-feature-settings'].value !== 'normal') {
                                // Explicitly set it to normal instead of deleting it, to force Elementor to overwrite the CSS class
                                props['font-feature-settings'] = { "$$type": "string", "value": 'normal' };
                                changed = true;
                            } else if (!props['font-feature-settings']) {
                                // If it didn't exist, we still set it to normal to ensure the reset happens in the model
                                props['font-feature-settings'] = { "$$type": "string", "value": 'normal' };
                                changed = true;
                            }
                        }

                        if (changed) {
                            try {
                                container.model.set('styles', clonedStyles);
                                container.model.trigger('change:styles', container.model, clonedStyles);
                                container.model.trigger('change', container.model); // Force element re-render
                                if (_elementor.saver) {
                                    _elementor.saver.setFlagEditorChange(true);
                                }
                            } catch (e) {
                                console.error('Persian Elementor: Failed to update model:', e);
                            }

                            // Apply immediately with the exact target class
                            applyFontFeatureToDOM(elementId, feature, enabled, localStyleKey);
                            
                            // Apply again after slight delays
                            setTimeout(() => { applyFontFeatureToDOM(elementId, feature, enabled, localStyleKey); }, 50);
                            setTimeout(() => { applyFontFeatureToDOM(elementId, feature, enabled, localStyleKey); }, 150);
                            setTimeout(() => { applyFontFeatureToDOM(elementId, feature, enabled, localStyleKey); }, 300);
                        }
                    }
                }
            } catch (err) {
                console.error('Persian Elementor: Error applying font feature to model:', err);
            }
        };

        const applyFontFeatureToDOM = (elementId, feature, enabled, localStyleKey) => {
            try {
                const iframe = window.document.getElementById('elementor-preview-iframe') || window.top.document.getElementById('elementor-preview-iframe');
                if (!iframe || !iframe.contentDocument) return;
                const previewDoc = iframe.contentDocument;
                const wrapper = previewDoc.querySelector('[data-id="' + elementId + '"]');
                if (!wrapper) return;

                let target = wrapper;
                if (localStyleKey) {
                    const styledEl = wrapper.classList.contains(localStyleKey) ? wrapper : wrapper.querySelector('.' + localStyleKey);
                    if (styledEl) {
                        target = styledEl;
                    } else {
                        const innerEl = wrapper.querySelector('h2, h1, h3, h4, h5, h6, p, a, span');
                        target = innerEl || wrapper;
                    }
                } else {
                    const innerEl = wrapper.querySelector('h2, h1, h3, h4, h5, h6, p, a, span');
                    target = innerEl || wrapper;
                }

                if (enabled) {
                    target.style.fontFeatureSettings = '"' + feature + '" 1';
                } else {
                    target.style.fontFeatureSettings = 'normal';
                }
            } catch (err) { }
        };

        const getFontFeatureFromModel = (elementId, targetFeature) => {
            try {
                const _elementor = window.elementor || window.top.elementor;
                if (!_elementor) return false;
                
                const elements = _elementor.selection.getElements();
                const container = elements.find(el => el.id === elementId);
                if (!container) return false;

                if (container.model && container.model.get('styles')) {
                    const styles = container.model.get('styles');
                    const styleKeys = Object.keys(styles);
                    let localStyleKey = styleKeys.find(key => styles[key].label === 'local');
                    
                    if (localStyleKey) {
                        const props = styles[localStyleKey].variants[0].props;
                        if (props['font-feature-settings']) {
                            const val = props['font-feature-settings'].value;
                            return val && val !== 'normal' && val.indexOf(targetFeature) !== -1;
                        }
                    }
                }
            } catch (err) {}
            return false;
        };

        const PersianFontFamilyWrapper = (props) => {
            const { OriginalControl, ...controlProps } = props;

            let boundPropData;
            try {
                boundPropData = useBoundProp();
            } catch (err) {
                return React.createElement(OriginalControl, controlProps);
            }

            const selectedFont = getStringValue(boundPropData ? (boundPropData.value || boundPropData.placeholder) : null);
            const targetFeature = getActiveFeature(selectedFont);

            const [isChecked, setIsChecked] = React.useState(() => {
                const elementId = getSelectedElementId();
                if (elementId && targetFeature) {
                    return getFontFeatureFromModel(elementId, targetFeature);
                }
                return false;
            });

            // React to font change and persist state
            React.useEffect(() => {
                const elementId = getSelectedElementId();
                if (!elementId) return;

                window._persianFontFeatures = window._persianFontFeatures || {};
                
                // 1. Check actual Elementor model first (persists page refresh)
                const isEnabledInModel = getFontFeatureFromModel(elementId, targetFeature);
                
                if (isEnabledInModel) {
                    setIsChecked(true);
                    window._persianFontFeatures[elementId] = {
                        enabled: true,
                        feature: targetFeature
                    };
                    
                    // Force apply style to preview DOM on mount
                    try {
                        const elements = (window.elementor || window.top.elementor).selection.getElements();
                        const container = elements.find(el => el.id === elementId);
                        if (container && container.model) {
                            const styles = container.model.get('styles');
                            const styleKeys = Object.keys(styles);
                            let localStyleKey = styleKeys.find(key => styles[key].label === 'local');
                            setTimeout(() => {
                                applyFontFeatureToDOM(elementId, targetFeature, true, localStyleKey);
                            }, 100);
                        }
                    } catch (e) {}
                } else {
                    // 2. Fallback to session state during font transitions
                    const storedState = window._persianFontFeatures[elementId];
                    if (storedState && storedState.enabled) {
                        setIsChecked(true);
                        if (targetFeature) {
                            setTimeout(() => {
                                applyFontFeatureToModel(elementId, targetFeature, true);
                            }, 50);
                        }
                    } else {
                        setIsChecked(false);
                    }
                }
            }, [selectedFont, targetFeature]);

            if (!targetFeature) {
                return React.createElement(OriginalControl, controlProps);
            }

            const handleToggle = (checked) => {
                setIsChecked(checked);

                const elementId = getSelectedElementId();
                if (elementId) {
                    window._persianFontFeatures = window._persianFontFeatures || {};
                    window._persianFontFeatures[elementId] = {
                        enabled: checked,
                        feature: targetFeature
                    };
                    applyFontFeatureToModel(elementId, targetFeature, checked);
                }
            };

            return React.createElement(
                React.Fragment,
                null,
                React.createElement(OriginalControl, controlProps),
                React.createElement(
                    Grid,
                    { container: true, alignItems: 'center', justifyContent: 'space-between', sx: { mt: 1, px: 0.5 } },
                    React.createElement(
                        Grid,
                        { item: true },
                        React.createElement(Switch, {
                            checked: isChecked,
                            onChange: (e) => handleToggle(e.target.checked),
                            size: 'small'
                        })
                    ),
                    React.createElement(
                        Grid,
                        { item: true },
                        React.createElement(Typography, { variant: 'caption', color: 'text.secondary' }, 'اعداد فارسی')
                    )
                )
            );
        };

        registerControlReplacement({
            component: PersianFontFamilyWrapper,
            condition: ({ value, placeholder }) => {
                return isPersianFont(value || placeholder);
            }
        });

        return true;
    };

    const interval = setInterval(() => {
        if (registerPersianTypographyReplacement()) {
            clearInterval(interval);
        }
    }, 50);

    setTimeout(() => {
        clearInterval(interval);
    }, 10000);
})();
