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
	'Estedad',
	'Gandom',
	'IRANYekanX',
	'Kara',
	'Mikhak',
	'Nahid',
	'Parastoo',
	'Sahel',
	'Samim',
	'Shabnam',
	'Tanha',
	'VazirMatn',
    ];

    add_filter('elementor/fonts/groups', function($font_groups) use ($farsi_fonts) {
        $font_groups = array_merge(['FARSI' => __('فونت فارسی', 'plugin-textdomain')], $font_groups);
        return $font_groups;
    });

    add_filter('elementor/fonts/additional_fonts', function($additional_fonts) use ($farsi_fonts) {
        $farsi_font_list = array_fill_keys($farsi_fonts, 'FARSI');
        $additional_fonts = array_merge($farsi_font_list, $additional_fonts);
        return $additional_fonts;
    });
}

$options = get_persian_elementor_options();
if (!empty($options['efa-all-font'])) {
    add_farsi_fonts_to_elementor();
}
