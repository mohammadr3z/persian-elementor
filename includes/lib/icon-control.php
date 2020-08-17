<?php

/**
 * Elementorfa icons.
 *
 *
 * @since 2.0.8
 */


// Enqueue Editor
	add_action('elementor/editor/before_enqueue_scripts', function() {
		wp_enqueue_style( 'persian-elementor-icon',plugins_url( 'icons\efaicons\style.css',  __FILE__ ) );
});

// Enqueue Front End
	add_action('elementor/frontend/before_enqueue_styles', function() {
		wp_enqueue_style( 'persian-elementor-icon',plugins_url( 'icons\efaicons\style.css',  __FILE__ ) );
});