<?php
//Add font to editor
add_action('elementor/editor/before_enqueue_scripts', function() {
wp_enqueue_style( 'persian-elementor',plugins_url( 'css/admin-font.css', __FILE__ ) );
});


//Add font to frontend
add_action('elementor/frontend/after_enqueue_styles', function() {
wp_enqueue_style( 'persian-elementor',plugins_url( 'css/front-font.css', __FILE__ ) );
});



__( 'Persian Elementor', 'persian-elementor' );
