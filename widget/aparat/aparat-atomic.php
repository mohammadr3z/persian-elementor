<?php

namespace Persian_Elementor\Widgets;

use Elementor\Modules\AtomicWidgets\Controls\Section;
use Elementor\Modules\AtomicWidgets\Controls\Types\Switch_Control;
use Elementor\Modules\AtomicWidgets\Controls\Types\Text_Control;
use Elementor\Modules\AtomicWidgets\Elements\Base\Atomic_Widget_Base;
use Elementor\Modules\AtomicWidgets\Elements\Base\Has_Template;
use Elementor\Modules\AtomicWidgets\PropTypes\Attributes_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Classes_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Primitives\Boolean_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Primitives\String_Prop_Type;
use Elementor\Modules\AtomicWidgets\Styles\Style_Definition;
use Elementor\Modules\AtomicWidgets\Styles\Style_Variant;
use Elementor\Modules\AtomicWidgets\PropTypes\Size_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Color_Prop_Type;
use Elementor\Modules\AtomicWidgets\Styles\Style_States;
use Elementor\Modules\Components\PropTypes\Overridable_Prop_Type;

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class Aparat_Widget extends Atomic_Widget_Base
{
	use Has_Template;

	public static $widget_description = 'نمایش ویدیوهای آپارات با پشتیبانی از تنظیمات مختلف.';

	public static function get_element_type(): string
	{
		return 'e-aparat-video';
	}

	public function get_title(): string
	{
		return esc_html__('آپارات', 'persian-elementor');
	}

	public function get_icon(): string
	{
		add_action('elementor/editor/after_enqueue_styles', [$this, 'enqueue_aparat_icon_styles']);
		return 'aparat-atomic-icon';
	}

	/**
	 * Enqueue custom style for the Aparat SVG icon in Elementor editor
	 */
	public function enqueue_aparat_icon_styles()
	{
		$icon_url = PERSIAN_ELEMENTOR_URL . 'assets/images/aparat.svg';

		echo '<style>
			.aparat-atomic-icon::after {
				content: "";
				background-image: url(' . esc_url($icon_url) . ') !important;
				background-size: contain;
				background-repeat: no-repeat;
				background-position: center;
				width: 28px;
				height: 28px;
				display: block;
				margin: 0 auto;
			}
			.aparat-atomic-icon i {
				display: none !important;
			}
		</style>';
	}

	public function get_categories(): array
	{
		return ['v4-elements'];
	}

	public function get_keywords()
	{
		return ['atomic', 'video', 'aparat', 'embed', 'player', 'media'];
	}

	protected static function define_props_schema(): array
	{
		return [
			'classes' => Classes_Prop_Type::make()
				->default([]),
			'aparat_url' => String_Prop_Type::make()
				->default('https://www.aparat.com/v/b491rbh'),
			'start_m' => String_Prop_Type::make()
				->default('0'),
			'start_s' => String_Prop_Type::make()
				->default('0'),
			'mute_aparat' => Boolean_Prop_Type::make()
				->default(false),
			'title_show_aparat' => Boolean_Prop_Type::make()
				->default(true),
			'recom_self' => Boolean_Prop_Type::make()
				->default(false),
			'autoplay' => Boolean_Prop_Type::make()
				->default(false),
			'attributes' => Attributes_Prop_Type::make()->meta(Overridable_Prop_Type::ignore()),
		];
	}

	protected function define_atomic_controls(): array
	{
		return [
			Section::make()
				->set_label(esc_html__('محتوا', 'persian-elementor'))
				->set_items([
					Text_Control::bind_to('aparat_url')
						->set_label(esc_html__('آدرس ویدیو', 'persian-elementor')),
					Text_Control::bind_to('start_m')
						->set_label(esc_html__('زمان شروع (دقیقه)', 'persian-elementor')),
					Text_Control::bind_to('start_s')
						->set_label(esc_html__('زمان شروع (ثانیه)', 'persian-elementor')),
					Switch_Control::bind_to('autoplay')
						->set_label(esc_html__('پخش خودکار', 'persian-elementor')),
					Switch_Control::bind_to('mute_aparat')
						->set_label(esc_html__('پخش بی‌صدا', 'persian-elementor')),
					Switch_Control::bind_to('title_show_aparat')
						->set_label(esc_html__('نمایش عنوان', 'persian-elementor')),
					Switch_Control::bind_to('recom_self')
						->set_label(esc_html__('ویدیوهای پیشنهادی خود کانال', 'persian-elementor')),
				]),
			Section::make()
				->set_label(__( 'Settings', 'elementor' ))
				->set_id('settings')
				->set_items($this->get_settings_controls()),
		];
	}

	protected function get_settings_controls(): array
	{
		return [
			Text_Control::bind_to('_cssid')
				->set_label(__( 'ID', 'elementor' ))
				->set_meta($this->get_css_id_control_meta()),
		];
	}

	protected function get_css_id_control_meta(): array
	{
		return [
			'layout' => 'two-columns',
			'topDivider' => false,
		];
	}

	protected function get_templates(): array
	{
		return [
			'aparat-atomic' => __DIR__ . '/aparat-atomic.html.twig',
		];
	}

	protected function define_base_styles(): array
	{
		return [
			'base' => Style_Definition::make()
				->add_variant(
					Style_Variant::make()
						->add_props([
							'width' => Size_Prop_Type::generate([
								'size' => 100,
								'unit' => '%',
							]),
						]),
				),
		];
	}
}
