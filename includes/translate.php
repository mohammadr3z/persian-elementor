<?php
declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

function load_persian_textdomain(string $text_domain, string $locale, string $base_path): void {
    $persian_elementor_lang = "{$base_path}/languages/{$text_domain}-{$locale}.mo";
    unload_textdomain($text_domain);
    load_textdomain($text_domain, $persian_elementor_lang);
}

function load_persian_elementor_translations(): void {
    if (get_locale() !== 'fa_IR') {
        return;
    }

    $options = get_option('persian_elementor');
    $base_path = PERSIAN_ELEMENTOR;
    $locale = 'fa_IR';
    
    $domains = [
        'efa-elementor-pro' => 'elementor-pro',
        'efa-elementor' => 'elementor',
    ];
    
    foreach ($domains as $option_key => $text_domain) {
        if (!empty($options[$option_key])) {
            load_persian_textdomain($text_domain, $locale, $base_path);
        }
    }
}

add_action('init', 'load_persian_elementor_translations');