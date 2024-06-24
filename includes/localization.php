<?php

$options = get_option('persian_elementor');
if (!($options['efa-flatpickr'] ?? false)) {
    return;
}

class PersianElementorLocalization {
    private string $locale;
    private string $format;
    private bool $time24;

    public function __construct() {
        add_action('init', $this->init(...));
    }

    public function init(): void {
        $this->locale = apply_filters('elementor/datepicker/locale', $this->getLocale());
        $this->format = apply_filters('elementor/datepicker/format', 'Y-m-d');
        $this->time24 = apply_filters('elementor/datepicker/24h', false);

        if ($this->locale !== 'default') {
            add_action('wp_enqueue_scripts', $this->scriptRegister(...));
            add_filter('elementor_pro/forms/render/item/date', $this->scriptEnqueue(...));
        }
        add_action('wp_footer', $this->datepickerSettings(...), 99);
    }

    public function scriptRegister(): void {
        wp_register_script('flatpickr_localize', plugin_dir_url(__DIR__) . "assets/js/flatpickr/{$this->locale}.js", ['flatpickr']);
    }

    public function scriptEnqueue(array $item): array {
        if (!isset($item['use_native_date']) || $item['use_native_date'] !== 'yes') {
            wp_enqueue_script('flatpickr_localize');
            remove_filter('elementor_pro/forms/render/item/date', $this->scriptEnqueue(...));
        }
        return $item;
    }

    public function datepickerSettings(): void {
        if (!wp_script_is('flatpickr', 'enqueued')) {
            return;
        }

        $lang = wp_script_is('flatpickr_localize', 'enqueued') ? str_replace('-', '_', $this->locale) : '';
        $time24 = $this->time24 ? 'true' : 'false';

        echo "<script>
            flatpickr.setDefaults({dateFormat:'$this->format', time_24hr:$time24});
            " . ($lang ? "flatpickr.localize(flatpickr.l10ns.$lang);" : '') . "
            " . ($this->format !== 'Y-m-d' ? "jQuery('.elementor-date-field').removeAttr('pattern');" : '') . "
        </script>";
    }

    private function getLocale(): string {
        $locales = [
            'fa_IR' => 'fa', // 'Persian'
        ];

        $wpLocale = get_locale();
        return $locales[$wpLocale] ?? 'default';
    }
}

new PersianElementorLocalization();