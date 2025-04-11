<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register custom field types for Elementor Pro forms.
 *
 * @since 1.0.0
 */
class Persian_Elementor_Form_Fields {

    /**
     * Initialize form fields registration.
     *
     * @return void
     */
    public static function init() {
        // Check if form fields are enabled in settings
        $options = get_option('persian_elementor', []);
        if (!($options['efa-form-fields'] ?? true)) {
            return;
        }
        
        add_action( 'elementor_pro/forms/fields/register', [ self::class, 'register_form_fields' ] );
        add_action( 'wp_enqueue_scripts', [ self::class, 'register_assets' ] );
    }

    /**
     * Register all custom form fields.
     *
     * @param \ElementorPro\Modules\Forms\Registrars\Form_Fields_Registrar $form_fields_registrar
     * @return void
     */
    public static function register_form_fields( $form_fields_registrar ) {
        // Register Persian Date field
        self::register_persian_date_field( $form_fields_registrar );
        
        // Add filter to reorder the form fields
        add_filter('elementor_pro/forms/fields', [self::class, 'reorder_form_fields']);
    }
    
    /**
     * Reorder form fields to place persian date after the regular date field
     *
     * @param array $fields
     * @return array
     */
    public static function reorder_form_fields($fields) {
        if (isset($fields['persian_date']) && isset($fields['date'])) {
            $ordered_fields = [];
            
            foreach ($fields as $key => $field) {
                $ordered_fields[$key] = $field;
                
                // After adding date field, add our persian date field
                if ($key === 'date') {
                    $ordered_fields['persian_date'] = $fields['persian_date'];
                    // Then unset it so we don't add it twice
                    unset($fields['persian_date']);
                }
            }
            
            return $ordered_fields;
        }
        
        return $fields;
    }
    
    /**
     * Register necessary assets for custom form fields.
     * 
     * @return void
     */
    public static function register_assets() {
        // Use a fixed version number
        $version = '1.0.0';
        
        // Register our custom CSS that overrides and enhances the datepicker
        wp_register_style(
            'persian-elementor-datepicker-custom',
            PERSIAN_ELEMENTOR_URL . 'assets/css/datepicker-custom.css',
            [],
            $version . '.' . time() // Add timestamp to bypass cache during development
        );
        
        wp_register_script(
            'persian-elementor-datepicker',
            PERSIAN_ELEMENTOR_URL . 'assets/js/jalalidatepicker.min.js',
            ['jquery'],
            $version,
            true
        );
        
        wp_register_script(
            'persian-elementor-datepicker-init',
            PERSIAN_ELEMENTOR_URL . 'assets/js/datepicker-init.js',
            ['jquery', 'persian-elementor-datepicker'],
            $version . '.' . time(),
            true
        );
        
        // Load assets in both front-end and editor
        wp_enqueue_style('persian-elementor-datepicker-custom');
        wp_enqueue_script('persian-elementor-datepicker');
        wp_enqueue_script('persian-elementor-datepicker-init');
    }
    
    /**
     * Register the Persian Date field.
     *
     * @param \ElementorPro\Modules\Forms\Registrars\Form_Fields_Registrar $form_fields_registrar
     * @return void
     */ 
    private static function register_persian_date_field( $form_fields_registrar ) {
        $field_file = PERSIAN_ELEMENTOR . 'widget/form-fields/persian-date.php';
        
        if ( file_exists( $field_file ) ) {
            require_once( $field_file );
            
            if ( class_exists( '\Persian_Elementor\Form_Fields\Persian_Date_Field' ) ) {
                $form_fields_registrar->register( new \Persian_Elementor\Form_Fields\Persian_Date_Field() );
            } else {
                error_log( 'Persian Elementor: Persian date field class not found.' );
            }
        } else {
            error_log( 'Persian Elementor: Persian date field file not found at: ' . $field_file );
        }
    }
}

// Initialize the form fields
Persian_Elementor_Form_Fields::init();