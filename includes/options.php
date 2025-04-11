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
        echo '<div class="updated"><p>تنظیمات ذخیره شدند.</p></div>';
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
                font-family: IRANYekanXVF, Tahoma, Arial, sans-serif;
            }
            .persian-elementor-header {
                display: flex;
                align-items: center;
                margin-bottom: 25px;
                background: linear-gradient(135deg, #b74573 0%, #940040 100%);
                border-radius: 8px;
                padding: 20px;
                color: #fff;
                box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
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
            }
            .persian-elementor-header h1 {
                color: white;
                margin: 0;
                font-size: 24px;
                font-weight: bold;
            }
            .persian-elementor-header h2 {
                color: rgba(255, 255, 255, 0.9);
                margin: 5px 0 0;
                font-size: 16px;
                font-weight: normal;
                font-size: 22px;
                font-family: 'IRANYekanX';
            }
            .persian-elementor-header p {
                color: rgba(255, 255, 255, 0.9);
                line-height: 1.6;
                margin: 5px 0 0;
                font-size: 14px;
            }
            .persian-elementor-logo {
                margin-left: 20px;
                background: white;
                padding: 10px;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
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
                background: linear-gradient(135deg, #ffbc7d 0%, #ff7a3f 100%);
                border-radius: 8px;
                padding: 20px;
                color: #fff;
                box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
                position: relative;
                overflow: hidden;
                transition: transform 0.3s ease;
                cursor: pointer;
            }
            .persian-elementor-premium-ad:hover {
                transform: translateY(-5px);
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
            .premium-ad-content h3 {
                margin-top: 0;
                color: white;
                font-size: 18px;
                margin-bottom: 15px;
                font-family: 'IRANYekanX';
            }
            .premium-ad-content p {
                margin-bottom: 20px;
                color: rgba(255, 255, 255, 0.9);
                font-size: 14px;
            }
            .premium-ad-button {
                display: inline-block;
                background: white;
                color: #ed8936;
                font-weight: bold;
                padding: 10px 20px;
                border-radius: 5px;
                text-decoration: none;
                transition: all 0.3s ease;
                box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
            }
            .premium-ad-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
                background: #f8f9fa;
                color: #dd6b20;
            }
            .persian-elementor-card {
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
                margin: 20px 0px;
                overflow: hidden;
            }
            .persian-elementor-card-header {
                padding: 15px 20px;
                background: #f8f9fa;
                border-bottom: 1px solid #edf2f7;
            }
            .persian-elementor-card-header h2 {
                margin: 0;
                color: #4a5568;
                font-size: 16px;
                font-weight: bold;
                font-family: 'IRANYekanX';
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
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
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
                background: #ebf8ff;
                width: 36px;
                height: 36px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #4299e1;
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
                background-color: #cbd5e0;
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
                background-color: #05047e;
            }
            input:focus + .persian-elementor-slider {
                box-shadow: 0 0 1px #05047e;
            }
            input:checked + .persian-elementor-slider:before {
                transform: translateX(26px);
            }
            .persian-elementor-submit {
                background: #05047e;
                color: white;
                border: none;
                padding: 10px 20px;
                font-size: 14px;
                font-weight: bold;
                border-radius: 4px;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            }
            .persian-elementor-submit:hover {
                background: #5454bd;
                transform: translateY(-2px);
                box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
            }
            .featured-banner {
                margin: 20px 0;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
                transition: transform 0.3s ease;
            }
            .featured-banner:hover {
                transform: translateY(-5px);
            }
            .featured-banner img {
                width: 100%;
                height: auto;
                display: block;
            }
            .features-grid {
                display: flex;
                flex-wrap: wrap;
                gap: 15px;
                margin-top: 20px;
            }
            .feature-item {
                background: white;
                border-radius: 8px;
                padding: 20px;
                flex: 1 0 calc(33.333% - 15px);
                min-width: 250px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
                transition: all 0.3s cubic-bezier(.25,.8,.25,1);
            }

            .feature-item h4 {
                margin-top: 0;
                color: #2d3748;
                font-size: 16px;
                font-family: 'IRANYekanX';
            }
            .feature-item p {
                color: #718096;
                margin-bottom: 0;
            }
            @media (max-width: 768px) {
                .persian-elementor-main {
                    flex-direction: column;
                }
                .persian-elementor-sidebar,
                .persian-elementor-content {
                    min-width: 100%;
                }
            }
        </style>

        <div class="persian-elementor-header">
            <div class="persian-elementor-header-main">
                <div class="persian-elementor-logo">
                    <img src="<?php echo esc_url($plugin_url . 'assets/images/icon-256x256.png') ?>" alt="Persian Elementor" />
                </div>
                <div class="persian-elementor-header-title">
                    <h1>تنظیمات المنتور فارسی</h1>
                    <h2>درباره المنتور فارسی</h2>
                </div>
            </div>
            <p>این صفحه برای تنظیم ویژگی‌های مختلف افزونه المنتور به زبان فارسی است. با فعال کردن هر گزینه، قابلیت‌های مختلفی مانند افزودن آیکون‌های ایرانی، استفاده از تقویم شمسی و اضافه کردن فونت‌های فارسی به ویجت‌های المنتور فعال خواهد شد.</p>
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
                            <h2>تنظیمات المنتور فارسی</h2>
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
                            <h2>تنظیمات ویجت ها</h2>
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
                <a href="#" target="_blank" style="text-decoration: none;">
                    <div class="persian-elementor-premium-ad">
                        <div class="premium-ad-content">
                            <h3>نسخه پریمیوم المنتور فارسی</h3>
                            <p>با خرید نسخه پریمیوم به ۳۱ فونت فارسی حرفه‌ای، قالب‌های آماده اختصاصی و امکانات بیشتر دسترسی داشته باشید.</p>
                            <a href="#" target="_blank" class="premium-ad-button">خرید نسخه پریمیوم</a>
                        </div>
                    </div>
                </a>
                
                <div class="persian-elementor-card" style="margin-top: 20px;">
                    <div class="persian-elementor-card-header">
                        <h2>ویژگی‌های نسخه پریمیوم</h2>
                    </div>
                </div>
                
                <!-- Features grid moved outside of card body -->
                <div class="features-grid">
                    <div class="feature-item">
                        <h4>۳۱ فونت حرفه‌ای فارسی</h4>
                        <p>دسترسی به فونت‌های محبوب ایران سنس، یکان، فرهنگ و بیشتر</p>
                    </div>
                    <div class="feature-item">
                        <h4>قالب‌های آماده اختصاصی</h4>
                        <p>دسترسی یه قالب های تمپلی <a href="https://temply.ir/" target="_blank" style="color: #05047e; font-weight: bold; text-decoration: none;">https://temply.ir</a> با کد تخفیف EFA20</p>
                    </div>
                    <div class="feature-item">
                        <h4>پشتیبانی حرفه‌ای</h4>
                        <p>دسترسی به پشتیبانی اختصاصی برای رفع مشکلات فنی</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}