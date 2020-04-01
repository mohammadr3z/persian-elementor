<?php
/**
 * ElementorFa font.
 *
 *
 * @since 1.9.4
 */
 
// Enqueue Editor
	add_action('elementor/editor/before_enqueue_scripts', function() {
		wp_enqueue_style( 'persian-elementor',plugins_url( 'assets/editor-rtl.min.css', __FILE__ ) );
});

// Enqueue Preview
	add_action('elementor/preview/enqueue_styles', function() {
		wp_enqueue_style( 'persian-elementor-preview',plugins_url( 'assets/preview-rtl.css', __FILE__ ) );
});

// Enqueue Common
	add_action('admin_enqueue_scripts', function() {
		wp_enqueue_style( 'persian-elementor-common',plugins_url( 'assets/common-rtl.css', __FILE__ ) );
	});

// Enqueue Front End
	add_action('elementor/frontend/before_enqueue_styles', function() {
		wp_enqueue_style( 'persian-elementor-font', 
'https://rawcdn.githack.com/mohammadr3z/CDN_Font/ef8a61de664d764d279e00e60eed0df797d5764e/font.css' );
});