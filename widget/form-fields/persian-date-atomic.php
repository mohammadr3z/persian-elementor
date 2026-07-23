<?php
namespace Persian_Elementor\Form_Fields;

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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Persian_Date_Picker extends Atomic_Widget_Base {
	use Has_Template;

	public static $widget_description = 'نمایش تقویم و انتخابگر تاریخ شمسی با قابلیت‌های شخصی‌سازی.';

	public static function get_element_type(): string {
		return 'e-form-persian-date-picker';
	}

	public function get_title(): string {
		return esc_html__( 'تاریخ شمسی', 'persian-elementor' );
	}

	public function get_icon(): string {
		return 'eicon-atomic-date-picker';
	}

	public function get_categories(): array {
		return [ 'atomic-form' ];
	}

	public function get_keywords() {
		return [ 'atomic', 'form', 'date', 'picker', 'persian', 'jalali', 'shamsi' ];
	}

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_assets' ] );
		add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_assets' ] );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_assets' ] );
	}

	public function register_assets() {
		$version = PERSIAN_ELEMENTOR_VERSION;

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

	public function enqueue_assets() {
		$this->register_assets();
		wp_enqueue_script('persian-elementor-datepicker');
		wp_enqueue_script('persian-elementor-datepicker-init');
	}

	protected static function define_props_schema(): array {
		if ( class_exists( 'ElementorPro\Modules\AtomicForm\Default_Id_Provider' ) ) {
			$cssid_prop = \ElementorPro\Modules\AtomicForm\Default_Id_Provider::get_default_id_prop( self::get_element_type() );
		} else {
			$cssid_prop = String_Prop_Type::make()->default( '' );
		}

		return [
			'classes' => Classes_Prop_Type::make()
				->default( [] ),
			'placeholder' => String_Prop_Type::make()
				->default( esc_html__( 'تاریخ را انتخاب کنید', 'persian-elementor' ) ),
			'required' => Boolean_Prop_Type::make()
				->default( false ),
			'attributes' => Attributes_Prop_Type::make()->meta( Overridable_Prop_Type::ignore() ),
			'_cssid' => $cssid_prop,
		];
	}

	protected function define_atomic_controls(): array {
		return [
			Section::make()
				->set_label( esc_html__( 'محتوا', 'persian-elementor' ) )
				->set_items( [
					Text_Control::bind_to( 'placeholder' )
						->set_label( esc_html__( 'توضیح نگهدارنده (Placeholder)', 'persian-elementor' ) ),
					Switch_Control::bind_to( 'required' )
						->set_label( esc_html__( 'الزامی', 'persian-elementor' ) ),
				] ),
			Section::make()
				->set_label( esc_html__( 'تنظیمات', 'persian-elementor' ) )
				->set_id( 'settings' )
				->set_items( $this->get_settings_controls() ),
		];
	}

	protected function get_settings_controls(): array {
		return [
			Text_Control::bind_to( '_cssid' )
				->set_label( esc_html__( 'شناسه فیلد', 'persian-elementor' ) )
				->set_meta( $this->get_css_id_control_meta() ),
		];
	}

	protected function get_templates(): array {
		return [
			'persian-date-picker' => __DIR__ . '/persian-date-picker.html.twig',
		];
	}

	protected function define_base_styles(): array {
		$border_radius_value = Size_Prop_Type::generate( [
			'size' => 0,
			'unit' => 'px',
		] );

		$height_value = Size_Prop_Type::generate( [
			'size' => 36,
			'unit' => 'px',
		] );

		$border_color_value = Color_Prop_Type::generate( '#D6D5D5' );

		return [
			'base' => Style_Definition::make()
				->add_variant(
					Style_Variant::make()
							->add_props( [
								'border-radius' => $border_radius_value,
								'height' => $height_value,
								'border-color' => $border_color_value,
								'border-width' => Size_Prop_Type::generate( [
									'size' => 1,
									'unit' => 'px',
								] ),
								'border-style' => String_Prop_Type::generate( 'solid' ),
								'font-size' => Size_Prop_Type::generate( [
									'size' => 12,
									'unit' => 'px',
								] ),
								'color' => Color_Prop_Type::generate( '#0c0d0e' ),
								'width' => Size_Prop_Type::generate( [
									'size' => 100,
									'unit' => '%',
								] ),
							] ),
				)
				->add_variant(
					Style_Variant::make()
						->set_state( Style_States::FOCUS )
						->add_props( [
							'border-color' => Color_Prop_Type::generate( '#706F6F' ),
							'outline-style' => String_Prop_Type::generate( 'none' ),
						] ),
				),
		];
	}

	protected function get_css_id_control_meta(): array {
		return [
			'layout' => 'two-columns',
			'topDivider' => false,
		];
	}
}
