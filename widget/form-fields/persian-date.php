<?php

namespace Persian_Elementor\Form_Fields;

use Elementor\Controls_Manager;
use ElementorPro\Modules\Forms\Fields\Field_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Persian_Date_Field extends Field_Base {
    
    public function __construct() {
        add_action('elementor/preview/init', [$this, 'editor_preview_footer']);
        parent::__construct();
    }
    
    public function editor_preview_footer(): void {
        add_action('wp_footer', [$this, 'content_template_script']);
    }
    
    public function content_template_script(): void {
        ?>
        <script>
        jQuery(document).ready(() => {
            elementor.hooks.addFilter(
                'elementor_pro/forms/content_template/field/<?php echo $this->get_type(); ?>',
                function(inputField, item, i) {
                    const fieldType = 'text';
                    const fieldId = `form_field_${i}`;
                    const fieldClass = `elementor-field-textual elementor-field persian-date-input ${item.css_classes}`;
                    const placeholder = item['persian-date-placeholder'] || '';
                    
                    return `<input type="${fieldType}" id="${fieldId}" class="${fieldClass}" data-jdp readonly="readonly" placeholder="${placeholder}">`;
                }, 10, 3
            );
        });
        </script>
        <?php
    }
    
    public function get_type() {
        return 'persian_date';
    }
    
    public function get_name() {
        return esc_html__('تاریخ شمسی', 'persian-elementor');
    }
    
    /**
     * Renders the Persian Date field on the frontend
     */
    public function render($item, $item_index, $form) {
        // Ensure assets are enqueued
        wp_enqueue_style('persian-elementor-datepicker-custom');
        wp_enqueue_script('persian-elementor-datepicker');
        wp_enqueue_script('persian-elementor-datepicker-init');
        
        // Generate a unique ID for the field
        $field_id = 'persian-date-' . uniqid();
        
        // Define datepicker options
        $datepicker_options = [
            'selector' => '#' . $field_id,
            'persianDigit' => true,
            'autoClose' => true,
            'format' => 'YYYY/MM/DD',
        ];
        
        // Set required attributes
        $form->add_render_attribute(
            'input' . $item_index,
            [
                'class' => 'elementor-field-textual persian-date-input',
                'name' => $form->get_attribute_name($item),
                'id' => $field_id,
                'type' => 'text',
                'data-jdp' => '',
                'data-jdp-option' => json_encode($datepicker_options),
                'readonly' => 'readonly',
                'placeholder' => $item['persian-date-placeholder'],
            ]
        );
        
        // Output the field
        echo '<input ' . $form->get_render_attribute_string('input' . $item_index) . '>';
        echo '<script>
            jQuery(document).ready(function($) {
                setTimeout(function() {
                    if (typeof window.jalaliDatepicker !== "undefined") {
                        window.jalaliDatepicker.attachDatepicker("#' . $field_id . '");
                    }
                }, 300);
            });
        </script>';
    }
    
    /**
     * Update the field controls in Elementor editor
     */
    public function update_controls($widget) {
        $elementor = $widget->get_controls('field_type');
        $elementor['options']['persian_date'] = esc_html__('تاریخ شمسی', 'persian-elementor');
        $widget->update_control('field_type', $elementor);
        
        $control_data = \ElementorPro\Plugin::elementor()->controls_manager->get_control_from_stack($widget->get_unique_name(), 'form_fields');
        
        if (is_wp_error($control_data)) {
            return;
        }
        
        $field_controls = [
            'persian-date-placeholder' => [
                'name' => 'persian-date-placeholder',
                'label' => esc_html__('Placeholder', 'persian-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('تاریخ را انتخاب کنید', 'persian-elementor'),
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'field_type' => $this->get_type(),
                ],
                'tab' => 'content',
                'inner_tab' => 'form_fields_content_tab',
                'tabs_wrapper' => 'form_fields_tabs',
            ],
        ];
        
        $control_data['fields'] = $this->inject_field_controls($control_data['fields'], $field_controls);
        $widget->update_control('form_fields', $control_data);
    }
    
    /**
     * Field validation
     */
    public function validation($field, $record, $ajax_handler) {
        if (empty($field['value']) && $field['required']) {
            $ajax_handler->add_error(
                $field['id'],
                esc_html__('تاریخ را وارد کنید.', 'persian-elementor')
            );
        }
    }
}
