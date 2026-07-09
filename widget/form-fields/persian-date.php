<?php

namespace Persian_Elementor\Form_Fields;

use Elementor\Controls_Manager;
use ElementorPro\Modules\Forms\Fields\Field_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Persian_Date_Field extends Field_Base
{

    public function __construct()
    {
        parent::__construct();
        add_action('elementor/preview/init', [$this, 'editor_preview_footer']);
        add_action('wp_enqueue_scripts', [$this, 'register_assets']);
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('elementor/preview/enqueue_styles', [$this, 'enqueue_assets']);
        add_action('elementor/frontend/after_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Register necessary assets for Persian date field.
     * 
     * @return void
     */
    public function register_assets()
    {
        // Use a fixed version number
        $version = '1.3.0';

        wp_register_script(
            'persian-elementor-datepicker',
            PERSIAN_ELEMENTOR_URL . 'assets/js/persian-calendar-standalone.js',
            [],
            $version,
            true
        );

        wp_register_script(
            'persian-elementor-datepicker-init',
            PERSIAN_ELEMENTOR_URL . 'assets/js/datepicker-init.js',
            ['persian-elementor-datepicker'],
            $version,
            true
        );
    }

    /**
     * Enqueue assets for Elementor editor and preview.
     * 
     * @return void
     */
    public function enqueue_assets()
    {
        $this->register_assets();
        wp_enqueue_script('persian-elementor-datepicker');
        wp_enqueue_script('persian-elementor-datepicker-init');
    }

    public function editor_preview_footer(): void
    {
        add_action('wp_footer', [$this, 'content_template_script']);
    }

    public function content_template_script(): void
    {
?>
        <script>
            jQuery(document).ready(() => {
                elementor.hooks.addFilter(
                    'elementor_pro/forms/content_template/field/<?php echo esc_js($this->get_type()); ?>',
                    function(inputField, item, i) {
                        const fieldType = 'text';
                        const fieldId = `form_field_${i}`;
                        const baseCssClasses = 'elementor-field-textual elementor-field persian-date-input';
                        const additionalClasses = (item.css_classes || '').replace(/[<>"']/g, '');
                        const fieldClass = `${baseCssClasses} ${additionalClasses}`.trim();
                        const placeholder = (item['persian-date-placeholder'] || '').replace(/[<>"']/g, '');

                        // Initialize PersianCalendar after element is added to DOM
                        setTimeout(function() {
                            if (typeof window.initPersianDateField === 'function') {
                                window.initPersianDateField(fieldId);
                            }
                        }, 300);

                        // Create input element
                        const input = document.createElement('input');
                        input.type = fieldType;
                        input.id = fieldId;
                        input.className = fieldClass;
                        input.setAttribute('data-persian-date', '');
                        input.readOnly = true;
                        input.placeholder = placeholder;

                        return input.outerHTML;
                    }, 10, 3
                );
            });
        </script>
<?php
    }

    public function get_type()
    {
        return 'persian_date';
    }

    public function get_name()
    {
        return esc_html__('تاریخ شمسی', 'persian-elementor');
    }

    /**
     * Renders the Persian Date field on the frontend
     */
    public function render($item, $item_index, $form)
    {
        // Enqueue assets only when field is rendered
        wp_enqueue_script('persian-elementor-datepicker');
        wp_enqueue_script('persian-elementor-datepicker-init');

        // Sanitize placeholder value
        $placeholder = isset($item['persian-date-placeholder']) ?
            sanitize_text_field($item['persian-date-placeholder']) :
            esc_attr__('تاریخ را انتخاب کنید', 'persian-elementor');

        // Set required attributes
        $form->add_render_attribute(
            'input' . $item_index,
            [
                'class' => 'elementor-field-textual persian-date-input',
                'name' => $form->get_attribute_name($item),
                'type' => 'text',
                'data-persian-date' => '',
                'readonly' => 'readonly',
                'placeholder' => $placeholder,
            ]
        );

        // Output the field safely
        echo '<input ' . $form->get_render_attribute_string('input' . $item_index) . '>';
    }

    /**
     * Update the field controls in Elementor editor
     */
    public function update_controls($widget)
    {
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
    public function validation($field, $record, $ajax_handler)
    {
        // Check if field is required and empty
        if (empty($field['value']) && $field['required']) {
            $ajax_handler->add_error(
                $field['id'],
                esc_html__('تاریخ را وارد کنید.', 'persian-elementor')
            );
            return;
        }

        // If field has value, validate Persian date format
        if (!empty($field['value'])) {
            $date_value = sanitize_text_field($field['value']);

            // Basic Persian date format validation (YYYY/MM/DD)
            if (!preg_match('/^\d{4}\/\d{1,2}\/\d{1,2}$/', $date_value)) {
                $ajax_handler->add_error(
                    $field['id'],
                    esc_html__('فرمت تاریخ صحیح نیست. لطفاً تاریخ را از تقویم انتخاب کنید.', 'persian-elementor')
                );
                return;
            }

            // Additional validation: check if date parts are in valid range
            $date_parts = explode('/', $date_value);
            if (count($date_parts) === 3) {
                $year = (int) $date_parts[0];
                $month = (int) $date_parts[1];
                $day = (int) $date_parts[2];

                // Basic range validation for Persian calendar
                if ($year < 1300 || $year > 1500 || $month < 1 || $month > 12 || $day < 1 || $day > 31) {
                    $ajax_handler->add_error(
                        $field['id'],
                        esc_html__('تاریخ وارد شده معتبر نیست.', 'persian-elementor')
                    );
                }
            }
        }
    }
}
