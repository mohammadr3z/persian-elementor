<?php
/**
 * ElementorFa font.
 *
 *
 * @since 2.2.0
 */
 
// Enqueue Editor
	add_action('elementor/editor/before_enqueue_scripts', function() {
		wp_enqueue_style( 'persian-elementor',plugins_url( 'assets/css/editor-rtl.min.css', __FILE__ ) );
});

// Enqueue Preview
	add_action('elementor/preview/enqueue_styles', function() {
		wp_enqueue_style( 'persian-elementor-preview',plugins_url( 'assets/css/preview-rtl.css', __FILE__ ) );
});

// Enqueue Common
	add_action('admin_enqueue_scripts', function() {
		wp_enqueue_style( 'persian-elementor-common',plugins_url( 'assets/css/common-rtl.css', __FILE__ ) );
	});

/**
 * Enqueue Persian Fonr Front End
 */
$options = get_option( 'efa_settings' );
	if ( !$options['efa_checkbox_field_0'] == '1' ) {
		function efa_persian_font() {
			wp_enqueue_style( 'persian-elementor-font', 'https://rawcdn.githack.com/mohammadr3z/CDN_Font/884a6df66545c0f982fef877d193d47ab3dc4079/font.css' );
	}
	add_action( 'elementor/frontend/before_enqueue_styles', 'efa_persian_font' );
}

/**
 * Enqueue RTL Flatpickr Front End
 */
function efa_persian_flatpickr() {
	wp_enqueue_style( 'persian-elementor-flatpickr',plugins_url( 'assets/css/flatpickr-rtl.css', __FILE__ ) );
}
add_action( 'elementor/frontend/before_enqueue_styles', 'efa_persian_flatpickr' );