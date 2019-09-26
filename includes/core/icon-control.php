<?php

/**
 * ElementorFa icons.
 *
 *
 * @since 1.0.0
 */

// enqueue css file into editor
function elementorfa_new_icon(){
    wp_enqueue_style( 'new-icon', plugins_url( 'icons\efaicons\style.css',  __FILE__ )  );
}
add_action( 'elementor/editor/after_enqueue_styles', 'elementorfa_new_icon' );
//enqueue css file for front-end
function elementorfa_core_assets() {
    wp_enqueue_style( 'new-icon', plugins_url( 'icons\efaicons\style.css',  __FILE__ )  );
}
add_action( 'wp_enqueue_scripts', 'elementorfa_core_assets' );

