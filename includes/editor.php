<?php
/**
 * ElementorFa fonts.
 *
 *
 * @since 1.0.0
 */
 
// enqueue css file into editor
	add_action('elementor/editor/before_enqueue_scripts', function() {
		wp_enqueue_style( 'persian-elementor',plugins_url( 'assets/editor-rtl.min.css', __FILE__ ) );
});


//enqueue css file for front-end
	add_action('elementor/frontend/after_enqueue_styles', function() {
		wp_enqueue_style( 'persian-elementor',plugins_url( 'assets/frontend-persian.min.css', __FILE__ ) );
});
