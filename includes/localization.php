<?php
$options = get_option('persian_elementor');

if ($options['efa-flatpickr']) {
    class PersianElementorLocalization {
        private $locale;
        private $format;
        private $time24;

        public function __construct() {
            add_action('init', [$this, 'init']);
        }

        public function init() {
            $this->locale = apply_filters('elementor/datepicker/locale', $this->get_locale());
            $this->format = apply_filters('elementor/datepicker/format', 'Y-m-d');
            $this->time24 = apply_filters('elementor/datepicker/24h', false) ? 'true' : 'false';

            if ($this->locale !== 'default') {
                add_action('wp_enqueue_scripts', [$this, 'script_register']);
                add_filter('elementor_pro/forms/render/item/date', [$this, 'script_enqueue']);
            }
            add_action('wp_footer', [$this, 'datepicker_settings'], 99);
        }

        public function script_register() {
            wp_register_script(
                'flatpickr_localize',
                plugin_dir_url(__DIR__) . "assets/js/flatpickr/{$this->locale}.js",
                ['flatpickr'],
                null,
                true
            );
        }

        public function script_enqueue($item) {
            if (!isset($item['use_native_date']) || $item['use_native_date'] !== 'yes') {
                wp_enqueue_script('flatpickr_localize');
                remove_filter('elementor_pro/forms/render/item/date', [$this, 'script_enqueue']);
            }
            return $item;
        }

        public function datepicker_settings() {
            if (wp_script_is('flatpickr', 'enqueued')) {
                $lang = wp_script_is('flatpickr_localize', 'enqueued') ? str_replace('-', '_', $this->locale) : '';

                echo '<script>' .
                    "flatpickr.setDefaults({dateFormat:'$this->format', time_24hr:$this->time24});" .
                    ($lang ? "flatpickr.localize(flatpickr.l10ns.$lang);" : '') .
                    ($this->format !== 'Y-m-d' ? "jQuery('.elementor-date-field').removeAttr('pattern');" : '') .
                    '</script>';
            }
        }

        private function get_locale() {
            $locales = [
                'fa_IR' => 'fa', // Persian
            ];

            $wp_locale = get_locale();
            $locale = $locales[$wp_locale] ?? 'default';

            return ($locale === 'en' || $locale === '') ? 'default' : $locale;
        }
    }

    $persian_elementor_localization = new PersianElementorLocalization();
}