<?php

add_action('elementor/editor/before_enqueue_scripts', function() {
wp_enqueue_style( 'persian-elementor','/wp-content/plugins/persian-elementor/includes/css/admin-font.css' );
});


__( 'Persian Elementor', 'persian-elementor' );

