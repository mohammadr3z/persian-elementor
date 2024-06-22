<?php

defined('ABSPATH') || exit;

$options = get_option('persian_elementor');
if (!is_array($options)) {
    $options = [];
}

if (!empty($options['efa-iranian-icon'])) {

    add_filter('elementor/icons_manager/native', 'add_eicons_to_icon_manager');

    function add_eicons_to_icon_manager($settings) {
        static $json_url = null;
        static $json_path_exists = null;

        if ($json_url === null) {
            $json_url = esc_url(plugin_dir_url(__FILE__) . 'icons/efaicons/config.json');
        }

        if ($json_path_exists === null) {
            $json_path_exists = file_exists(plugin_dir_path(__FILE__) . 'icons/efaicons/config.json');
        }

        if (!$json_path_exists) {
            return $settings;
        }

        $settings['eicons'] = [
            'name'          => 'آیکون های ایرانی',
            'label'         => esc_html__('آیکون های ایرانی', 'text-domain'),
            'url'           => false,
            'enqueue'       => false,
            'prefix'        => 'efa-',
            'displayPrefix' => '',
            'labelIcon'     => 'efa-EFA',
            'ver'           => '5.3.0',
            'fetchJson'     => $json_url,
            'native'        => true,
        ];

        return $settings;
    }
}