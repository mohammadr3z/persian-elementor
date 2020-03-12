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
		wp_enqueue_style( 'persian-elementor',plugins_url( 'assets/preview-rtl.css', __FILE__ ) );
});


// Register StyleSheet
add_action( 'wp_enqueue_scripts', 'register_persian_elementor_font' );

// Enqueue Front
function register_persian_elementor_font() {
    wp_register_style( 'persian-elementor-font', 'https://rawcdn.githack.com/mohammadr3z/CDN_Font/6986c5023c5b098f734b2cbf97713e77f746507c/font.css' );
    wp_enqueue_style( 'persian-elementor-font' );
}