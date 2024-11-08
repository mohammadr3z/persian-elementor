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
    add_menu_page(
        'تنظیمات المنتور فارسی',
        'المنتور فارسی',
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
        ],
        'efa-iranian-icon' => [
            'label' => 'آیکون های ایرانی',
            'desc' => 'با فعال کردن این گزینه، آیکون های ایرانی مانند آیکون بانک ها و شبکه های اجتماعی فعال خواهد شد.',
        ],
        'efa-all-font' => [
            'label' => 'فونت های فارسی',
            'desc' => 'با فعال کردن این گزینه فونت های فارسی به ویجت های المنتور اضافه خواهد شد.',
        ],
        'efa-elementor-pro' => [
            'label' => 'ترجمه المنتور پرو',
            'desc' => 'با فعال کردن این گزینه ترجمه فارسی افزونه المنتور پرو فعال خواهد شد.',
        ],
        'efa-elementor' => [
            'label' => 'ترجمه المنتور',
            'desc' => 'با فعال کردن این گزینه ترجمه فارسی افزونه المنتور فعال خواهد شد.',
        ],

    ];

    $plugin_url = plugin_dir_url(dirname(__FILE__));
    ?>
    <div class="wrap">
        <h1>تنظیمات المنتور فارسی</h1>

        <div class="about-wrap" style="max-width:100%!important">
            <div style="display:flex; align-items:center; justify-content:center; margin-bottom:20px;">
                <img src="<?php echo esc_url($plugin_url . 'assets/images/about.png') ?>" alt="Persian Elementor Logo" style="max-width:100px; margin-left:20px; background-color:#ffbc7d">
                <h2 style="margin:0;">درباره المنتور فارسی</h2>
            </div>
            <p style="text-align:center!important">این صفحه برای تنظیم ویژگی‌های مختلف افزونه المنتور به زبان فارسی است. با فعال کردن هر گزینه، قابلیت‌های مختلفی مانند افزودن آیکون‌های ایرانی، استفاده از تقویم شمسی و اضافه کردن فونت‌های فارسی به ویجت‌های المنتور فعال خواهد شد.</p>
            <p style="text-align:center!important">لطفا تغییرات خود را انجام دهید و پس از اتمام، دکمه "ذخیره تنظیمات" را فشار دهید تا تنظیمات ذخیره شوند.</p>
        </div>

        <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=persian_elementor')); ?>">
            <?php wp_nonce_field('persian_elementor_nonce'); ?>
            <table class="form-table" style="background-color: #fff; color: rgba(0, 0, 0, 0.87); transition: box-shadow 300ms cubic-bezier(0.4, 0, 0.2, 1) 0ms; border-radius: 4px; box-shadow: none; display: flex; justify-content: space-between; padding: 24px 32px;">
                <?php foreach ($fields as $key => $field) : ?>
                    <tr valign="top">
                        <th scope="row"><?php echo esc_html($field['label']) ?></th>
                        <td>
                            <input type="hidden" name="persian_elementor[<?php echo esc_attr($key) ?>]" value="0" />
                            <input type="checkbox" name="persian_elementor[<?php echo esc_attr($key) ?>]" value="1" <?php checked(1, $options[$key] ?? 0) ?> />
                            <p class="description"><?php echo esc_html($field['desc']) ?></p>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <input type="submit" name="submit" id="submit" class="button button-primary" value="ذخیره تنظیمات">
        </form>
    </div>
    <?php
}