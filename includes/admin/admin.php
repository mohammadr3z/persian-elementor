<?php
add_action( 'admin_menu', 'persian_elementor_panel' );

function persian_elementor_panel() {
    add_menu_page(
        esc_html__('المنتور فارسی', 'persian-elementor'), // Menu title
        esc_html__('المنتور فارسی', 'persian-elementor'), // Page title
        'manage_options',                                 // Capability
        'persian-elementor',                              // Menu slug
        'efa_settings_section_callback',                  // Callback function
        plugins_url('persian-elementor/assets/images/icon.png'), // Icon URL
        58                                                // Position
    );
}

function efa_settings_section_callback() {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'persian-elementor'));
    }
    ?>
    <div class="wrap about-wrap" style="font-family:vazir">
        <h1><?php echo esc_html__('به المنتور فارسی خوش آمديد', 'persian-elementor'); ?></h1>
        <div class="about-text"><?php echo esc_html__('لذت طراحی با زبان فارسی و ظاهر زيبا', 'persian-elementor'); ?></div>
        <a class="wp-badge" href="#" target="_blank" style="background-color:#93033C !important;background-image:url(<?php echo esc_url(plugins_url('persian-elementor/assets/images/about.png')); ?>) !important;background-position: center center;background-size: 167px auto !important;"></a>
        <h2 class="nav-tab-wrapper">
            <a class="nav-tab nav-tab-active" href="#" target="_blank"><?php echo esc_html__('تنظيمات', 'persian-elementor'); ?></a>
        </h2>
    </div>
    <?php
}
?>
