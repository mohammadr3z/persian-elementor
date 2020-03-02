<?php
/**
 * ElementorFa font.
 *
 *
 * @since 1.9.1
 */
 
// enqueue css file into editor
	add_action('elementor/editor/before_enqueue_scripts', function() {
		wp_enqueue_style( 'persian-elementor',plugins_url( 'assets/editor-rtl.min.css', __FILE__ ) );
});


// Register style sheet.
add_action( 'wp_enqueue_scripts', 'register_persian_elementor_font' );

/**
 * Enqueue style sheet.
 */
function register_persian_elementor_font() {
    wp_register_style( 'persian-elementor-font', 'https://rawcdn.githack.com/mohammadr3z/CDN_Font/aa30dc93b0f2f94ca29521d33a9d32dac88795af/font.css' );
    wp_enqueue_style( 'persian-elementor-font' );
}