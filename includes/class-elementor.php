<?php
/**
 * ElementorFa font.
 *
 *
 * @since 2.3.0
 */
 
class PersianElementorCore
{

	public function option($option, $bool = false, $default = true)
	{
		$options = get_option('persian_elementor');

		if (!isset($options[$option]) && $default === true)
		{
			return true;
		}
		if (!isset($options[$option]) && $default === false)
		{
			return false;
		}
		if ($bool === true)
		{
			if ($options[$option] == 'yes'):
				return true;
			else:
				return false;
			endif;
		}
		else
		{
			return $options[$option];
		}
	}
	
}

new PersianElementorCore;


$options = get_option('persian_elementor');

if ( $options['efa-panel-font'] != 'no') {
// Enqueue Editor
	add_action('elementor/editor/before_enqueue_scripts', function() {
		wp_enqueue_style( 'persian-elementor',plugins_url( 'assets/css/editor-rtl.min.css', __FILE__ ) );
});

// Enqueue Preview
	add_action('elementor/preview/enqueue_styles', function() {
		wp_enqueue_style( 'persian-elementor-preview',plugins_url( 'assets/css/preview-rtl.css', __FILE__ ) );
});

// Enqueue Common
	add_action('admin_enqueue_scripts', function() {
		wp_enqueue_style( 'persian-elementor-common',plugins_url( 'assets/css/common-rtl.css', __FILE__ ) );
	});
	
// Enqueue Common app
	add_action('elementor/app/init', function() {
		wp_enqueue_style( 'persian-elementor-common',plugins_url( 'assets/css/common-rtl.css', __FILE__ ) );
	});
	
}

if ( $options['efa-all-font'] != 'no') {
/**
 * Enqueue Persian Font Front End
 */
    function efa_persian_font() {
			wp_enqueue_style( 'persian-elementor-font', 'https://cdn.statically.io/gh/mohammadr3z/CDN_Font/884a6df66545c0f982fef877d193d47ab3dc4079/font.css' );
	}
	add_action( 'elementor/frontend/before_enqueue_styles', 'efa_persian_font' );

}

if ( $options['efa-flatpickr'] != 'no') {
/**
 * Enqueue RTL Flatpickr Front End
 */
function efa_persian_flatpickr() {
	wp_enqueue_style( 'persian-elementor-flatpickr',plugins_url( 'assets/css/flatpickr-rtl.css', __FILE__ ) );
}
add_action( 'elementor/frontend/before_enqueue_styles', 'efa_persian_flatpickr' );

/**
 * Enqueue Flatpickr Mobile
 */
function efa_persian_flatpickr_Mobile() {
	wp_enqueue_script( 'persian-elementor-flatpickr',plugins_url( 'assets/js/flatpickr-mobile.js', __FILE__ ) , [ 'flatpickr' ]  );
}
add_action( 'elementor/frontend/before_enqueue_styles', 'efa_persian_flatpickr_Mobile' );

}