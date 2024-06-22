<?php

function get_persian_elementor_options(): array {
    $options = get_option('persian_elementor');
    if (!is_array($options)) {
        return [];
    }

    return array_map('sanitize_text_field', $options);
}

function add_farsi_fonts_to_elementor(): void {
    $farsi_fonts = [
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
        foreach ($farsi_fonts as $font) {
            $additional_fonts[$font] = 'FARSI';
        }
        return $additional_fonts;
    });
}

$options = get_persian_elementor_options();

if (!empty($options['efa-all-font'])) {
    add_farsi_fonts_to_elementor();
}
