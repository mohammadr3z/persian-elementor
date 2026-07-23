<?php
// Check if Elementor is loaded before proceeding
if (!did_action('elementor/loaded')) {
    return;
}

class Persian_Elementor_Extended_Group_Control_Typography extends \Elementor\Group_Control_Typography
{

    protected function init_fields()
    {
        $fields = parent::init_fields();

        $font_features = [
            '"ss01"' => [
                'AradVF',
                'BonyadeKoodakVF',
                'DibajVF',
                'DoranVF',
                'IRANSansXV',
                'IRANYekanXV',
                'LahzehVF',
                'RaviVF',
                'VazirmatnVF',
                'YekanBakhVF',
            ],
            '"ss02"' => [
                'FarhangVF',
            ],
            '"ss05"' => [
                'AnjomanMaxVF',
            ],
            '"ss07"' => [
                'DarvishVF',
            ],
            '"ss10"' => [
                'DAMOONVF',
            ],
            '"numr"' => [
                'Anjoman',
                'Arad',
                'Artin',
                'Aviny',
                'Daal',
                'Damavand',
                'Dana',
                'Emkan',
                'Estedad',
                'Farhang',
                'Gandom',
                'IRANSansDN',
                'IRANSansX',
                'IRANSharp',
                'IRANYekanX',
                'Kalameh',
                'Kara',
                'Katibeh',
                'Maneli',
                'Mikhak',
                'Nahid',
                'Noora',
                'Parastoo',
                'Pelak',
                'Peyda',
                'Ravi',
                'Sahel',
                'Samim',
                'Shabnam',
                'Tanha',
                'VazirMatn',
                'YekanBakh'
            ],
        ];

        $new_field = [];
        foreach ($font_features as $feature_val => $fonts) {
            $clean_feature_name = str_replace('"', '', $feature_val);
            $control_id = 'font-feature-settings-' . $clean_feature_name;

            $new_field[$control_id] = [
                'label' => esc_html_x('اعداد فارسی', 'Typography Control', 'persian-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'description' => 'تغییر اعداد انگلیسی به اعداد فارسی',
                'label_on' => esc_html__('بله', 'persian-elementor'),
                'label_off' => esc_html__('خیر', 'persian-elementor'),
                'return_value' => $feature_val,
                'selector_value' => 'font-feature-settings: {{VALUE}};',
                'condition' => [
                    'font_family' => $fonts,
                ],
            ];
        }

        $position = 'after';
        $target_field = 'font_family';

        $new_fields = [];
        foreach ($fields as $field_name => $field) {
            if ($position === 'before' && $field_name === $target_field) {
                $new_fields = array_merge($new_fields, $new_field);
            }

            $new_fields[$field_name] = $field;

            if ($position === 'after' && $field_name === $target_field) {
                $new_fields = array_merge($new_fields, $new_field);
            }
        }

        return $new_fields;
    }

    protected function get_default_options()
    {
        return [
            'popover' => [
                'starter_name' => 'typography',
                'starter_title' => esc_html__('تایپوگرافی', 'persian-elementor'),
                'settings' => [
                    'render_type' => 'ui',
                    'groupType' => 'typography',
                    'global' => [
                        'active' => true,
                    ],
                ],
            ],
        ];
    }
}

// Register the extended typography group control for Elementor 3.5.0+ and 4.x
add_action('elementor/controls/register', function ($controls_manager) {
    $controls_manager->add_group_control(
        Persian_Elementor_Extended_Group_Control_Typography::get_type(),
        new Persian_Elementor_Extended_Group_Control_Typography()
    );
});

// Support for Elementor v4 Atomic Widgets style schema
add_filter('elementor/atomic-widgets/styles/schema', function (array $schema) {
    if (class_exists('\Elementor\Modules\AtomicWidgets\PropTypes\Primitives\String_Prop_Type')) {
        $schema['font-feature-settings'] = \Elementor\Modules\AtomicWidgets\PropTypes\Primitives\String_Prop_Type::make()
            ->description('CSS font-feature-settings property');
    }
    return $schema;
});


// Enqueue script in Elementor Editor for V4 React control replacement
add_action('elementor/editor/after_enqueue_scripts', function () {
    if (class_exists('\Elementor\Modules\AtomicWidgets\Module') && \Elementor\Modules\AtomicWidgets\Module::is_active()) {
        wp_enqueue_script(
            'persian-elementor-atomic-typography',
            PERSIAN_ELEMENTOR_URL . 'assets/js/atomic-typography.js',
            ['elementor-editor'],
            PERSIAN_ELEMENTOR_VERSION,
            true
        );
    }
});


