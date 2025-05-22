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
    
    // Enqueue WordPress core CSS
    wp_enqueue_style('dashicons');
    ?>
    <div class="wrap persian-elementor-settings">
        <style>
            .persian-elementor-settings {
                max-width: 1200px;
                margin: 0 auto;
                padding: 20px;
            }
            .persian-elementor-header {
                display: flex;
                align-items: center;
                margin-bottom: 25px;
                background: #fff;
                border-radius: 3px;
                padding: 20px;
                color: #495157;
                box-shadow: 0 1px 4px rgba(0,0,0,.15);
                flex-wrap: wrap;
            }
            .persian-elementor-header-main {
                display: flex;
                align-items: center;
                width: 100%;
                margin-bottom: 15px;
            }
            .persian-elementor-header-title {
                display: flex;
                flex-direction: column;
                align-items: start;
            }
            .persian-elementor-header h1 {
                color: white;
                margin: 0;
                font-size: 24px;
                font-weight: bold;
            }
            .persian-elementor-header h4 {
                color: #495157;
                margin: 5px 0 0;
                font-size: 16px;
                font-weight: normal;
                font-size: 22px;
            }
            .persian-elementor-header p {
                color: #6d7882;
                line-height: 1.6;
                margin: 5px 0 0;
                font-size: 14px;
            }
            .persian-elementor-logo {
                margin-left: 20px;
                background: white;
                padding: 10px;
                border-radius: 8px;
                box-shadow: 0 1px 4px rgba(0,0,0,.15);
            }
            .persian-elementor-logo img {
                width: 60px;
                height: 60px;
                display: block;
            }
            .persian-elementor-main {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                margin-bottom: 20px;
            }
            .persian-elementor-sidebar {
                flex: 1;
                min-width: 300px;
            }
            .persian-elementor-content {
                flex: 3;
                min-width: 500px;
            }
            .persian-elementor-premium-ad {
                background: linear-gradient(135deg, #93003c 0%, #800035 100%);
                border-radius: 3px;
                padding: 20px;
                color: #fff;
                box-shadow: 0 1px 4px rgba(0,0,0,.15);
                position: relative;
                overflow: hidden;
                transition: none;
                cursor: default;
            }
            .persian-elementor-premium-ad::before {
                content: "";
                position: absolute;
                top: -10px;
                right: -10px;
                background: rgba(255, 255, 255, 0.1);
                width: 60px;
                height: 60px;
                border-radius: 50%;
                z-index: 0;
            }
            .persian-elementor-premium-ad::after {
                content: "";
                position: absolute;
                bottom: -40px;
                left: -40px;
                background: rgba(255, 255, 255, 0.1);
                width: 80px;
                height: 80px;
                border-radius: 50%;
                z-index: 0;
            }
            .premium-ad-content {
                position: relative;
                z-index: 1;
            }
            .premium-ad-content h5 {
                margin-top: 0;
                color: white;
                font-size: 18px;
                margin-bottom: 15px;
            }
            .premium-ad-content p {
                margin-bottom: 20px;
                color: rgba(255, 255, 255, 0.9);
                font-size: 14px;
            }
            .premium-ad-button {
                display: inline-block;
                background: white;
                color: #890038;
                font-weight: bold;
                padding: 10px 20px;
                border-radius: 5px;
                text-decoration: none;
                transition: all 0.3s ease;
                box-shadow: 0 1px 4px rgba(0,0,0,.15);
            }
            .premium-ad-button:hover {
                background: #f8f9fa;
                color: #800035;
            }
            .persian-elementor-card {
                background: #fff;
                border-radius: 3px;
                box-shadow: 0 1px 4px rgba(0,0,0,.15);
                margin: 0px 0px 20px 0px;
                overflow: hidden;
            }
            .persian-elementor-card-header {
                padding: 15px 20px;
                background: #f8f9fa;
                border-bottom: 1px solid #edf2f7;
            }
            .persian-elementor-card-header h4 {
                margin: 0;
                color: #4a5568;
                font-size: 16px;
                font-weight: bold;
            }
            .persian-elementor-card-body {
                padding: 20px;
            }
            .persian-elementor-about {
                background-color: #fff;
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 20px;
                text-align: center;
                box-shadow: 0 1px 4px rgba(0,0,0,.15);
            }
            .persian-elementor-about-header {
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 15px;
            }
            .persian-elementor-about-header img {
                width: 60px;
                margin-left: 15px;
                border-radius: 8px;
            }
            .persian-elementor-about p {
                color: #4a5568;
                line-height: 1.8;
                margin-bottom: 15px;
            }
            .persian-elementor-settings-row {
                display: flex;
                align-items: center;
                padding: 15px 0;
                border-bottom: 1px solid #edf2f7;
            }
            .persian-elementor-settings-row:last-child {
                border-bottom: none;
            }
            .persian-elementor-settings-icon {
                margin-left: 15px;
                background: #f1f3f5;
                width: 36px;
                height: 36px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #a4afb7;
            }
            .persian-elementor-settings-content {
                flex-grow: 1;
            }
            .persian-elementor-settings-title {
                font-weight: bold;
                margin-bottom: 5px;
                color: #2d3748;
            }
            .persian-elementor-settings-description {
                color: #718096;
                font-size: 13px;
                margin: 0;
            }
            .persian-elementor-settings-control {
                margin-right: 15px;
            }
            .persian-elementor-toggle {
                position: relative;
                display: inline-block;
                width: 52px;
                height: 26px;
            }
            .persian-elementor-toggle input {
                opacity: 0;
                width: 0;
                height: 0;
            }
            .persian-elementor-slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #a4afb7;
                transition: .4s;
                border-radius: 34px;
            }
            .persian-elementor-slider:before {
                position: absolute;
                content: "";
                height: 18px;
                width: 18px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                transition: .4s;
                border-radius: 50%;
            }
            input:checked + .persian-elementor-slider {
                background-color: #93003c;
            }
            input:focus + .persian-elementor-slider {
                box-shadow: 0 0 1px #93003c;
            }
            input:checked + .persian-elementor-slider:before {
                transform: translateX(26px);
            }
            .persian-elementor-submit {
                background: #93003c;
                color: white;
                border: none;
                padding: 10px 20px;
                font-size: 14px;
                font-weight: bold;
                border-radius: 4px;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 1px 4px rgba(0,0,0,.15);
            }
            .persian-elementor-submit:hover {
                background: #800035;
            }
            .featured-banner {
                margin: 20px 0;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 1px 4px rgba(0,0,0,.15);
                transition: none;
            }
            .featured-banner:hover {
                transform: none;
            }
            .featured-banner img {
                width: 100%;
                height: auto;
                display: block;
            }
            @media (max-width: 768px) {
                .persian-elementor-main {
                    flex-direction: column;
                }
                .persian-elementor-sidebar,
                .persian-elementor-content {
                    min-width: 100%;
                }
                .persian-elementor-header {
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                }
                .persian-elementor-header-main {
                    flex-direction: column;
                    align-items: center;
                    width: 100%;
                    margin-bottom: 0;
                }
                .persian-elementor-header-title {
                    align-items: center;
                }
                .persian-elementor-header h4 {
                    margin: 15px 0 0 !important;
                }
            }
            
        </style>

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
            </div>
        </div>
    </div>
    <?php
}