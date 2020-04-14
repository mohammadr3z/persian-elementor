<?php
/**
 * ElementorFa font.
 *
 *
 * @since 1.9.12
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

// Enqueue Front End
	add_action('elementor/frontend/before_enqueue_styles', function() {
		wp_enqueue_style( 'persian-elementor-font', 
'https://rawcdn.githack.com/mohammadr3z/CDN_Font/9117012537a9c706e4628504917225a92edec155/font.css' );
		wp_enqueue_style( 'persian-elementor-flatpickr',plugins_url( 'assets/css/flatpickr-rtl.css', __FILE__ ) );
});
