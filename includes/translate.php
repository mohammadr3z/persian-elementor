<?php 
declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$options = get_option('persian_elementor');

function load_persian_textdomain(string $text_domain, string $locale, string $base_path): void {
    $persian_elementor_lang = "{$base_path}/languages/{$text_domain}/{$text_domain}-{$locale}.mo";
    $wordpress_lang = "wp-content/languages/plugins/{$text_domain}-{$locale}.mo";
    
    unload_textdomain($text_domain);
    load_textdomain($text_domain, $persian_elementor_lang);
}

if (get_locale() === 'fa_IR') {
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