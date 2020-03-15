<?php
/**
 * ElementorFa font.
 *
 *
 * @since 1.9.3
 */
 
// Enqueue Editor
	add_action('elementor/editor/before_enqueue_scripts', function() {
		wp_enqueue_style( 'persian-elementor',plugins_url( 'assets/editor-rtl.min.css', __FILE__ ) );
});

// Enqueue Preview
	add_action('elementor/preview/enqueue_styles', function() {
		wp_enqueue_style( 'persian-elementor-preview',plugins_url( 'assets/preview-rtl.css', __FILE__ ) );
});

// Enqueue Front End
	add_action('elementor/frontend/before_enqueue_styles', function() {
		wp_enqueue_style( 'persian-elementor-font', 
'https://rawcdn.githack.com/mohammadr3z/CDN_Font/d74f5cd32f8c16d6449bc671237920bc47ff5b83/font.css' );
});