<?php
//Add font to editor
add_action('elementor/editor/before_enqueue_scripts', function() {
wp_enqueue_style( 'persian-elementor','/wp-content/plugins/persian-elementor/includes/css/admin-font.css' );
});


//Add font to frontend
add_action('elementor/frontend/after_enqueue_styles', function() {
wp_enqueue_style( 'persian-elementor','/wp-content/plugins/persian-elementor/includes/css/front-font.css' );
});



__( 'Persian Elementor', 'persian-elementor' );

