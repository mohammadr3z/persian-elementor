<?php
$options = get_option('persian_elementor');
if ( $options['efa-flatpickr']) {
class PersianElementorLocalization {

	private $locale;
	private $format;
	private $time24;

	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	function init() {
		$this->locale = apply_filters( 'elementor/datepicker/locale', $this->get_locale() );
		$this->format = apply_filters( 'elementor/datepicker/format', 'Y-m-d' );
		$this->time24 = apply_filters( 'elementor/datepicker/24h', false ) ? 'true' : 'false';

		if ( 'default' !== $this->locale ) {
			// Register script
			add_action( 'wp_enqueue_scripts', array( $this, 'script_register' ) );
			// Enqueue if date field is present
			add_filter( 'elementor_pro/forms/render/item/date', array( $this, 'script_enqueue' ) );
		}
		// Apply locale and format
		add_action( 'wp_footer', array( $this, 'datepicker_settings' ), 99 );
	}

	function script_register() {
		wp_register_script( 'flatpickr_localize', plugin_dir_url( __DIR__ ) . "assets/js/flatpickr/{$this->locale}.js", [ 'flatpickr' ] );
	}

	function script_enqueue( $item ) {
		if ( ! isset( $item['use_native_date'] ) || 'yes' !== $item['use_native_date'] ) {
			wp_enqueue_script( 'flatpickr_localize' );
			remove_filter( 'elementor_pro/forms/render/item/date', array( $this, 'script_enqueue' ) );
		}

		return $item;
	}

	function datepicker_settings() {
		if ( wp_script_is( 'flatpickr', 'enqueued' ) ) {
			$lang = wp_script_is( 'flatpickr_localize', 'enqueued' ) ? str_replace( '-', '_', $this->locale ) : '';

			echo '<script>' .
				"flatpickr.setDefaults({dateFormat:'$this->format', time_24hr:$this->time24}); " .
				( $lang ? "flatpickr.localize(flatpickr.l10ns.$lang); " : '' ) .
				( 'Y-m-d' !== $this->format ? "jQuery('.elementor-date-field').removeAttr('pattern');" : '' ) .
				'</script>';
		}
	}

	function get_locale() {

		// Relation WordPress languages with flatpickr languages
		$locales = array(
			'fa_IR'          => 'fa', // 'Persian'

		);

		$wp_locale = get_locale();

		$locale = array_key_exists( $wp_locale, $locales ) ? $locales[ $wp_locale ] : 'default';

		// English or none use default (en) lang
		if ( $locale === 'en' || $locale === '' ) {
			$locale = 'default';
		}

		return $locale;
	}

}

$persian_elementor_localization = new PersianElementorLocalization();

}