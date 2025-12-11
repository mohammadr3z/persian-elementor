<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register settings
add_action('admin_init', function(): void {
    register_setting(
        'persian_elementor_group',
        'persian_elementor',
        ['sanitize_callback' => fn(array $input): array => array_map('sanitize_text_field', $input)]
    );
});

// Add settings page
add_action('admin_menu', function(): void {
    $page_title = (get_locale() === 'fa_IR') ? 'تنظیمات المنتور فارسی' : 'Persian Elementor Settings';
    $menu_title = (get_locale() === 'fa_IR') ? 'المنتور فارسی' : 'Persian Elementor';
    
    add_menu_page(
        $page_title,
        $menu_title,
        'manage_options',
        'persian_elementor',
        'persian_elementor_settings_page',
        plugin_dir_url(dirname(__FILE__)) . 'assets/images/icon.png',
        58
    );
});

function persian_elementor_settings_page(): void {
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['persian_elementor'])) {
        check_admin_referer('persian_elementor_nonce');
        $new_options = array_map('sanitize_text_field', $_POST['persian_elementor']);
        update_option('persian_elementor', $new_options);
        echo '<div class="notice notice-success is-dismissible"><p>تنظیمات با موفقیت ذخیره شدند.</p></div>';
    }

    $options = get_option('persian_elementor', []);
    $fields = [
        'efa-panel-font' => [
            'label' => 'فونت پنل ویرایشگر المنتور',
            'desc' => 'با فعال کردن این گزینه فونت فارسی به پنل ویرایشگر المنتور اضافه خواهد شد.',
            'icon' => 'dashicons-editor-textcolor',
        ],
        'efa-iranian-icon' => [
            'label' => 'آیکون های ایرانی',
            'desc' => 'با فعال کردن این گزینه، آیکون های ایرانی مانند آیکون بانک ها و شبکه های اجتماعی فعال خواهد شد.',
            'icon' => 'dashicons-images-alt2',
        ],
        'efa-all-font' => [
            'label' => 'فونت های فارسی',
            'desc' => 'با فعال کردن این گزینه فونت های فارسی به ویجت های المنتور اضافه خواهد شد.',
            'icon' => 'dashicons-admin-appearance',
        ],
        'efa-elementor-pro' => [
            'label' => 'ترجمه المنتور پرو',
            'desc' => 'با فعال کردن این گزینه ترجمه فارسی افزونه المنتور پرو فعال خواهد شد.',
            'icon' => 'dashicons-translation',
        ],
        'efa-elementor' => [
            'label' => 'ترجمه المنتور',
            'desc' => 'با فعال کردن این گزینه ترجمه فارسی افزونه المنتور فعال خواهد شد.',
            'icon' => 'dashicons-translation',
        ],
    ];

    // Add Widget Settings section
    $widget_fields = [
        'efa-form-fields' => [
            'label' => 'فیلد فرم تاریخ شمسی',
            'desc' => 'با فعال کردن این گزینه، فیلد تاریخ شمسی به فرم‌های المنتور اضافه می‌شود.',
            'icon' => 'dashicons-calendar-alt',
        ],
        'efa-aparat-video' => [
            'label' => 'ویجت آپارات',
            'desc' => 'با فعال کردن این گزینه، ویدیوی آپارات به ویجت ویدیو المنتور اضافه می‌شود.',
            'icon' => 'dashicons-format-video',
        ],
        'efa-neshan-map' => [
            'label' => 'ویجت نقشه نشان',
            'desc' => 'با فعال کردن این گزینه، ویجت نقشه نشان به المنتور اضافه می‌شود.',
            'icon' => 'dashicons-location-alt',
        ],
        'efa-zarinpal-button' => [
			'label' => 'ویجت دکمه زرین‌پال',
            'desc' => 'با فعال کردن این گزینه، امکان اتصال به درگاه پرداخت زرین‌پال در المنتور اضافه می‌شود.',
            'icon' => 'dashicons-money-alt',
        ],
    ];

    $plugin_url = plugin_dir_url(dirname(__FILE__));
    
    // Enqueue WordPress core CSS and admin options CSS
    wp_enqueue_style('dashicons');
    wp_enqueue_style(
        'persian-elementor-admin-options',
        $plugin_url . 'assets/css/admin-options.css',
        ['dashicons']
    );
    ?>
    <div class="wrap persian-elementor-settings">

        <div class="persian-elementor-header">
            <div class="persian-elementor-header-main">
                <div class="persian-elementor-logo">
                    <img src="<?php echo esc_url($plugin_url . 'assets/images/icon-256x256.png') ?>" alt="Persian Elementor" />
                </div>
                <div class="persian-elementor-header-title">
                    <h4>تنظیمات المنتور فارسی</h4>
                    <p style="color: #6d7882; font-size: 14px; margin: 15px 0 0;">
                        در این صفحه می‌توانید تنظیمات و امکانات افزونه المنتور فارسی را مدیریت کنید. گزینه‌های زیر به شما کمک می‌کنند تا تجربه کار با المنتور را برای سایت‌های فارسی بهبود دهید و قابلیت‌های بیشتری را فعال یا غیرفعال کنید.
                    </p>
                </div>
            </div>
        </div>

        <div class="persian-elementor-main">
            <div class="persian-elementor-content">
                <!-- Premium Banner 
                <a href="#" target="_blank" class="featured-banner">
                    <img src="<?php echo esc_url($plugin_url . '#'); ?>" alt="نسخه پریمیوم المنتور فارسی" />
                </a>-->

                <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=persian_elementor')); ?>">
                    <?php wp_nonce_field('persian_elementor_nonce'); ?>
                    
                    <div class="persian-elementor-card">
                        <div class="persian-elementor-card-header">
                            <h4>ویژگی ها</h4>
                        </div>
                        <div class="persian-elementor-card-body">
                            <?php foreach ($fields as $key => $field) : ?>
                                <div class="persian-elementor-settings-row">
                                    <div class="persian-elementor-settings-icon">
                                        <span class="dashicons <?php echo esc_attr($field['icon']); ?>"></span>
                                    </div>
                                    <div class="persian-elementor-settings-content">
                                        <div class="persian-elementor-settings-title"><?php echo esc_html($field['label']) ?></div>
                                        <p class="persian-elementor-settings-description"><?php echo esc_html($field['desc']) ?></p>
                                    </div>
                                    <div class="persian-elementor-settings-control">
                                        <label class="persian-elementor-toggle">
                                            <input type="hidden" name="persian_elementor[<?php echo esc_attr($key) ?>]" value="0" />
                                            <input type="checkbox" name="persian_elementor[<?php echo esc_attr($key) ?>]" value="1" <?php checked(1, $options[$key] ?? 0) ?> />
                                            <span class="persian-elementor-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- New Widget Settings Section -->
                    <div class="persian-elementor-card">
                        <div class="persian-elementor-card-header">
                            <h4>ویجت ها</h4>
                        </div>
                        <div class="persian-elementor-card-body">
                            <?php foreach ($widget_fields as $key => $field) : ?>
                                <div class="persian-elementor-settings-row">
                                    <div class="persian-elementor-settings-icon">
                                        <span class="dashicons <?php echo esc_attr($field['icon']); ?>"></span>
                                    </div>
                                    <div class="persian-elementor-settings-content">
                                        <div class="persian-elementor-settings-title"><?php echo esc_html($field['label']) ?></div>
                                        <p class="persian-elementor-settings-description"><?php echo esc_html($field['desc']) ?></p>
                                    </div>
                                    <div class="persian-elementor-settings-control">
                                        <label class="persian-elementor-toggle">
                                            <input type="hidden" name="persian_elementor[<?php echo esc_attr($key) ?>]" value="0" />
                                            <input type="checkbox" name="persian_elementor[<?php echo esc_attr($key) ?>]" value="1" <?php checked(1, $options[$key] ?? 1) ?> />
                                            <span class="persian-elementor-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <button type="submit" class="persian-elementor-submit">ذخیره تنظیمات</button>
                </form>
            </div>

            <div class="persian-elementor-sidebar">
                <div class="persian-elementor-premium-ad">
                    <div class="premium-ad-content">
                        <h5>نسخه پریمیوم المنتور فارسی</h5>
                            <p>با خرید نسخه پریمیوم به ۳۱ فونت فارسی حرفه‌ای دسترسی داشته باشید.</p>
                        <a href="#" target="_blank" class="premium-ad-button">خرید نسخه پریمیوم</a>
                    </div>
                </div>
                
                <div style="margin-top: 20px;">
                    <div class="persian-elementor-premium-ad" style="background: linear-gradient(135deg, #255AFA 0%, #6523FB 100%);">
                        <div class="premium-ad-content">
                            <h5>قالب‌های آماده اختصاصی</h5>
                            <p>دسترسی به قالب های آماده ایرانی تمپلی با کد تخفیف <strong style="color: #fff; background: rgba(255,255,255,0.1); padding: 2px 8px; border-radius: 3px;">PEFA</strong></p>
                            <a href="https://temply.ir" target="_blank" class="premium-ad-button" style="color: #255AFA;">مشاهده قالب‌ها</a>
                        </div>
                    </div>
                </div>
                
                <div style="margin-top: 20px;">
                    <div class="persian-elementor-premium-ad" style="background: #23282d;">
                        <div class="premium-ad-content">
                            <h5>فعال سازی تاریخ شمسی</h5>
                            <p>برای فعال سازی تاریخ شمسی و استفاده از تقویم شمسی در وردپرس، افزونه تقویم فارسی را نصب کنید.</p>
                            <?php
                                $plugin_slug = 'persian-calendar';
                                $install_url = wp_nonce_url(
                                    self_admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug),
                                    'install-plugin_' . $plugin_slug
                                );
                                $activate_url = wp_nonce_url(
                                    self_admin_url('plugins.php?action=activate&plugin=' . $plugin_slug . '/' . $plugin_slug . '.php'),
                                    'activate-plugin_' . $plugin_slug . '/' . $plugin_slug . '.php'
                                );

                                if ( ! function_exists( 'get_plugins' ) ) {
                                    require_once ABSPATH . 'wp-admin/includes/plugin.php';
                                }
                                $all_plugins = get_plugins();

                                if ( array_key_exists( $plugin_slug . '/' . $plugin_slug . '.php', $all_plugins ) ) {
                                    if ( is_plugin_active( $plugin_slug . '/' . $plugin_slug . '.php' ) ) {
                                        echo '<a href="https://wordpress.org/plugins/persian-calendar/" target="_blank" class="premium-ad-button" style="color: #23282d;">مشاهده افزونه</a>';
                                    } else {
                                        echo '<a href="' . esc_url( $activate_url ) . '" class="premium-ad-button" style="color: #23282d;">فعال سازی</a>';
                                    }
                                } else {
                                    echo '<a href="' . esc_url( $install_url ) . '" class="premium-ad-button" style="color: #23282d;">نصب افزونه</a>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}