<?php

defined('ABSPATH') || exit;

$options = get_option('persian_elementor', []);

if (!empty($options['efa-iranian-icon'])) {
    add_filter('elementor/icons_manager/native', 'PersianElementor_eicons_icon_manager');
}

function PersianElementor_eicons_icon_manager($settings) {
    static $json_file = null;

    if ($json_file === null) {
        $json_file = plugin_dir_path(__FILE__) . 'icons/efaicons/config.json';
    }

    if (!file_exists($json_file)) {
        return $settings;
    }

    $settings['eicons'] = [
        'name'          => 'آیکون های ایرانی',
        'label'         => esc_html__('آیکون های ایرانی', 'persian-elementor'),
        'url'           => false,
        'enqueue'       => false,
        'prefix'        => 'efa-',
        'displayPrefix' => '',
        'labelIcon'     => 'efa-EFA',
        'ver'           => '5.3.0',
        'fetchJson'     => plugins_url('icons/efaicons/config.json', __FILE__),
        'native'        => true,
    ];

    return $settings;
}