<?php

$options = get_option('persian_elementor');
if ( $options['efa-iranian-icon']) {
	
add_filter( 'elementor/icons_manager/native', 'add_eicons_to_icon_manager');

function add_eicons_to_icon_manager( $settings ) {
		$json_url = plugin_dir_url( __FILE__ ) . '/icons/efaicons/config.json';

		$settings['eicons'] = [
			'name'          => 'آیکون های ایرانی',
			'label'         => esc_html__( 'آیکون های ایرانی', 'text-domain' ),
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