<?php
$options = get_option('persian_elementor');
if (!isset($options['efa-flatpickr']) || $options['efa-flatpickr'] === false) {
    return;
}

class PersianElementorLocalization {
    private $locale;
    private $format;
    private $time24;

    public function __construct() {
        add_action('init', array($this, 'init'));
    }

    public function init() {
        $this->locale = apply_filters('elementor/datepicker/locale', $this->getLocale());
        $this->format = apply_filters('elementor/datepicker/format', 'Y-m-d');
        $this->time24 = apply_filters('elementor/datepicker/24h', false);

        if ($this->locale !== 'default') {
            add_action('wp_enqueue_scripts', array($this, 'scriptRegister'));
            add_filter('elementor_pro/forms/render/item/date', array($this, 'scriptEnqueue'));
        }
        add_action('wp_footer', array($this, 'datepickerSettings'), 99);
    }

    public function scriptRegister() {
        wp_register_script('flatpickr_localize', plugin_dir_url(__DIR__) . "assets/js/flatpickr/{$this->locale}.js", array('flatpickr'));
    }

    public function scriptEnqueue($item) {
        if (!isset($item['use_native_date']) || $item['use_native_date'] !== 'yes') {
            wp_enqueue_script('flatpickr_localize');
            remove_filter('elementor_pro/forms/render/item/date', array($this, 'scriptEnqueue'));
        }
        return $item;
    }

    public function datepickerSettings() {
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

    private function getLocale() {
        $locales = array(
            'fa_IR' => 'fa', // 'Persian'
        );
        $wpLocale = get_locale();
        return isset($locales[$wpLocale]) ? $locales[$wpLocale] : 'default';
    }
}

new PersianElementorLocalization();