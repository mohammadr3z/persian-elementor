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