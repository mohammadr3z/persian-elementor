<?php

function get_persian_elementor_options(): array {
    static $options = null;
    if ($options === null) {
        $options = get_option('persian_elementor') ?: [];
        $options = is_array($options) ? array_map('sanitize_text_field', $options) : [];
    }
    return $options;
}

function add_farsi_fonts_to_elementor(): void {
    static $farsi_fonts = [
        'Estedad', 'EstedadFN', 'Gandom', 'IRANYekan', 'IRANYekanFN',
        'Kara', 'Mikhak', 'Nahid', 'Parastoo', 'Sahel', 'Samim', 
        'Shabnam', 'ShabnamFN', 'Tanha', 'TanhaFN', 'Vazir', 
        'VazirFN', 'VazirMatn', 'VazirMatnFN'
    ];

    add_filter('elementor/fonts/groups', function($font_groups) {
        $font_groups['FARSI'] = __('فونت فارسی');
        return $font_groups;
    });

    add_filter('elementor/fonts/additional_fonts', function($additional_fonts) use ($farsi_fonts) {
        return array_merge($additional_fonts, array_fill_keys($farsi_fonts, 'FARSI'));
    });
}

if (!empty(get_persian_elementor_options()['efa-all-font'])) {
    add_farsi_fonts_to_elementor();
}