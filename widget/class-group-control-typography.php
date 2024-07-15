<?php
class Persian_Elementor_Extended_Group_Control_Typography extends \Elementor\Group_Control_Typography {

    protected function init_fields() {
        $fields = parent::init_fields();

        $new_field = [
            'font-feature-settings' => [
                'label' => esc_html_x('اعداد فارسی', 'Typography Control', 'persian-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'description' => 'تغییر اعداد انگلیسی به اعداد فارسی',
                'label_on' => esc_html__('بله', 'persian-elementor'),
                'label_off' => esc_html__('خیر', 'persian-elementor'),
                'return_value' => '"numr"',
				'condition' => [
                    'font_family' => ['Estedad','Gandom','IRANSharp','IRANYekanX','Kara','Mikhak','Nahid','Parastoo','Sahel','Samim','Shabnam','Tanha','VazirMatn',]
					],
            ]
        ];

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

    protected function get_default_options() {
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

add_action('elementor/controls/register', function($controls_manager) {
    $controls_manager->add_group_control(Persian_Elementor_Extended_Group_Control_Typography::get_type(), new Persian_Elementor_Extended_Group_Control_Typography());
});