<?php
namespace PersianElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * ZarinPal Button Widget.
 *
 * Elementor widget that creates a ZarinPal payment button with configurable options.
 *
 * @since 1.0.0
 */
class ZarinPal_Button extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve ZarinPal button widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name(): string {
		return 'zarinpal-button';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve ZarinPal button widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title(): string {
		return esc_html__( 'دکمه زرین پال', 'persian-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve ZarinPal button widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon(): string {
		return 'eicon-button';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the ZarinPal button widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories(): array {
		return [ 'general' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the ZarinPal button widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords(): array {
		return [ 'zarinpal', 'payment', 'پرداخت', 'زرین پال', 'ایرانی' ];
	}

	/**
	 * Whether the widget requires inner wrapper.
	 * We set this to true so Elementor adds the .elementor-widget-container div,
	 * which is needed for alignment and potentially margins.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return bool Whether the widget requires an inner container.
	 */
	public function get_widget_wrapper_class(): string {
		return parent::get_widget_wrapper_class() . ' elementor-widget-button'; // Add standard button widget class
	}

	/**
	 * Whether the widget is dynamic content.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return bool Whether to cache the element output.
	 */
	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Get custom help URL.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget help URL.
	 */
	public function get_custom_help_url() {
		return 'https://zarinpal.com';
	}

	/**
	 * Register Elementor controls for this widget.
	 *
	 * @since 1.0.0
	 */
	protected function register_controls(): void {
		// Price and Payment Section
		$this->start_controls_section(
			'section_product',
			[
				'label' => esc_html__('قیمت و پرداخت', 'persian-elementor'),
			]
		);

		$this->add_control(
			'merchant_id',
			[
				'label' => esc_html__('شناسه مرچنت', 'persian-elementor'),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'ai' => [
					'active' => false,
				],
				'description' => esc_html__('شناسه مرچنت زرین‌پال خود را وارد کنید', 'persian-elementor'),
				'label_block' => true,
				'placeholder' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'product_name',
			[
				'label' => esc_html__('نام محصول', 'persian-elementor'),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'default' => 'محصول',
			]
		);

		$this->add_control(
			'product_price',
			[
				'label' => esc_html__('قیمت واحد (هزار تومان)', 'persian-elementor'),
				'type' => Controls_Manager::NUMBER,
				'default' => '10',
				'min' => 1,
				'dynamic' => [
					'active' => true,
				],
				'description' => esc_html__('مبلغ را به هزار تومان وارد کنید (مثال: 10 = 10,000 تومان)', 'persian-elementor'),
			]
		);

		$this->add_control(
			'product_quantity',
			[
				'label' => esc_html__('تعداد', 'persian-elementor'),
				'type' => Controls_Manager::NUMBER,
				'default' => '1',
				'min' => 1,
				'max' => 9999,
				'step' => 1,
				'dynamic' => [
					'active' => true,
				],
				'description' => esc_html__('تعداد محصول را مشخص کنید.', 'persian-elementor'),
			]
		);

		$this->end_controls_section();

		// Button Section (now includes options that were previously in "Additional Options")
		$this->start_controls_section(
			'section_button',
			[
				'label' => esc_html__('دکمه', 'persian-elementor'),
			]
		);

		// Moved from Additional Options section
		$this->add_control(
			'open_in_new_window',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__('باز کردن در تب جدید', 'persian-elementor'),
				'default' => 'yes',
				'label_off' => esc_html__('خیر', 'persian-elementor'),
				'label_on' => esc_html__('بله', 'persian-elementor'),
				'separator' => 'after',
			]
		);

		$this->add_control(
			'show_product_info_on_button',
			[
				'label' => esc_html__('نمایش نام محصول و قیمت', 'persian-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => esc_html__('بله', 'persian-elementor'),
				'label_off' => esc_html__('خیر', 'persian-elementor'),
				'description' => esc_html__('متن دکمه با نام محصول و قیمت جایگزین می‌شود', 'persian-elementor'),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__('متن', 'persian-elementor'),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('پرداخت با زرین‌پال', 'persian-elementor'),
				'placeholder' => esc_html__('پرداخت با زرین‌پال', 'persian-elementor'),
				'condition' => [
					'show_product_info_on_button' => '',
				],
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => esc_html__('آیکون', 'persian-elementor'),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fa fa-credit-card',
					'library' => 'fa-solid',
				],
				'fa4compatibility' => 'icon',
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label' => esc_html__('موقعیت آیکون', 'persian-elementor'),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__('قبل', 'persian-elementor'),
					'right' => esc_html__('بعد', 'persian-elementor'),
				],
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'button_css_id',
			[
				'label' => esc_html__('شناسه دکمه', 'persian-elementor'),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'title' => esc_html__('شناسه اختیاری برای دکمه را وارد کنید', 'persian-elementor'),
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Use Elementor's default button styling controls
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__('دکمه', 'persian-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__('تراز', 'persian-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('چپ', 'persian-elementor'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('وسط', 'persian-elementor'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('راست', 'persian-elementor'),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__('کشیده', 'persian-elementor'),
						'icon' => 'eicon-text-align-justify', // Justify applies to the button itself, not the wrapper alignment
					],
				],
				'prefix_class' => 'elementor%s-align-', // This applies alignment to the widget wrapper
				'default' => '', // Default alignment is usually handled by the column/container
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				// Add the global typography setting
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .elementor-button', // Target the button directly
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->start_controls_tabs('tabs_button_style');

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__('عادی', 'persian-elementor'),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__('رنگ متن', 'persian-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button .elementor-button-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button .elementor-button-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => esc_html__('رنگ پس زمینه', 'persian-elementor'),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'default' => '#ffd700',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__('هاور', 'persian-elementor'),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => esc_html__('رنگ متن', 'persian-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button:hover .elementor-button-icon, {{WRAPPER}} .elementor-button:focus .elementor-button-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button:hover .elementor-button-icon svg, {{WRAPPER}} .elementor-button:focus .elementor-button-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => esc_html__('رنگ پس زمینه', 'persian-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__('رنگ حاشیه', 'persian-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'border_border!' => '',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__('انیمیشن', 'persian-elementor'),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .elementor-button', // Target the button directly
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__('شعاع کادر', 'persian-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', // Target the button directly
				],
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => esc_html__('پدینگ', 'persian-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					// Apply padding to the button itself, not the wrapper
					'{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render ZarinPal button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render(): void {
		$settings = $this->get_settings_for_display();

		// Add wrapper class for alignment and potentially other styles
		// The 'elementor-widget-container' is added automatically by Elementor
		// The alignment class (e.g., 'elementor-align-center') is added via the 'align' control's prefix_class

		if ('yes' === $settings['open_in_new_window']) {
			$target = '_blank';
		} else {
			$target = '_top';
		}

		// Get the current page URL reliably
		global $wp;
		$current_url = home_url(add_query_arg(array(), $wp->request));
		// Remove potential Zarinpal query args from the base callback URL to ensure a clean URL is sent
		$callback_url = remove_query_arg( array('Authority', 'Status', 'transaction_id'), $current_url );
		$callback_url = esc_url($callback_url); // Sanitize the final URL

		// Calculate total price: unit price × quantity
		$quantity = max(1, intval($settings['product_quantity']));
		$unit_price = intval($settings['product_price']);
		$total_price = $unit_price * $quantity * 1000; // Convert to thousands (multiply by 1000)

		// Format total price for display
		$formatted_price = number_format($total_price) . ' ' . esc_html__('تومان', 'persian-elementor');

		// Form attributes
		$this->add_render_attribute([
			'button' => [
				'class' => [
					'elementor-button', // Keep standard button class
					'elementor-zarinpal-button', // Keep custom class if needed for specific JS/CSS
					// Add justify class if selected
					'justify' === $settings['align'] ? 'elementor-button--justify' : '',
				],
				'type' => 'submit',
			],
			'form' => [ // Add attributes for the form element
				'action' => esc_url(admin_url('admin-ajax.php')),
				'method' => 'post',
				'target' => esc_attr($target),
				'class' => 'elementor-zarinpal-form', // Add a class to the form if needed
			],
		]);

		if (!empty($settings['button_css_id'])) {
			$this->add_render_attribute('button', 'id', $settings['button_css_id']);
		}

		if (!empty($settings['hover_animation'])) {
			$this->add_render_attribute('button', 'class', 'elementor-animation-' . $settings['hover_animation']);
		}

		// Add size class to the button if needed (though padding handles size)
		// Example: $this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );

		?>
		
		<form <?php $this->print_render_attribute_string('form'); ?>>
			<input type="hidden" name="action" value="zarinpal_payment_request" />
			<input type="hidden" name="merchant_id" value="<?php echo esc_attr($settings['merchant_id']); ?>" />
			<input type="hidden" name="amount" value="<?php echo esc_attr($total_price); ?>" />
			<input type="hidden" name="description" value="<?php echo esc_attr($settings['product_name']) . ' (' . esc_attr($quantity) . ' ' . esc_html__('عدد', 'persian-elementor') . ')'; ?>" />
			<input type="hidden" name="callback_url" value="<?php echo $callback_url; // Already escaped ?>" />
			<input type="hidden" name="quantity" value="<?php echo esc_attr($quantity); ?>" />
			<?php wp_nonce_field('zarinpal_payment_request', 'zarinpal_nonce'); ?>

			<button <?php $this->print_render_attribute_string('button'); ?>>
				<span class="elementor-button-content-wrapper">
					<?php if (! empty($settings['selected_icon']['value']) && 'left' === $settings['icon_align']) : ?>
						<span class="elementor-button-icon elementor-align-icon-left">
							<?php Icons_Manager::render_icon($settings['selected_icon']); ?>
						</span>
					<?php endif; ?>

					<span class="elementor-button-text">
						<?php if ('yes' === $settings['show_product_info_on_button']) : ?>
							<span class="zarinpal-product-name">
								<?php echo esc_html__('خرید', 'persian-elementor'); ?>
								<?php echo esc_html($settings['product_name']); ?>
								<?php if ($quantity > 1) : ?>
									(<?php echo esc_html($quantity) . ' ' . esc_html__('عدد', 'persian-elementor'); ?>)
								<?php endif; ?>
							</span>
							<span>-</span>
							<span class="zarinpal-product-price"><?php echo esc_html($formatted_price); ?></span>
						<?php else : ?>
							<?php echo esc_html($settings['button_text']); ?>
						<?php endif; ?>
					</span>

					<?php if (! empty($settings['selected_icon']['value']) && 'right' === $settings['icon_align']) : ?>
						<span class="elementor-button-icon elementor-align-icon-right">
							<?php Icons_Manager::render_icon($settings['selected_icon']); ?>
						</span>
					<?php endif; ?>
				</span>
			</button>
		</form>
		
		<?php
	}

	/**
	 * Render ZarinPal button widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template(): void {
		?>
		<#
		var target = 'yes' === settings.open_in_new_window ? '_blank' : '_top';
		var quantity = Math.max(1, parseInt(settings.product_quantity || 1));
		var unitPrice = parseInt(settings.product_price || 0);
		var totalPrice = unitPrice * quantity * 1000;
		var formattedPrice = new Intl.NumberFormat('fa-IR').format(totalPrice) + ' تومان';

		// Note: In template we always use current page URL (handled server-side)
		var callbackUrl = '#'; // Placeholder for template, actual URL generated server-side

		// Remove the wrapper attribute, alignment is handled by prefix_class on the main wrapper
		view.addRenderAttribute('button', {
			'class': [
				'elementor-button',
				'elementor-zarinpal-button',
				// Add justify class if selected
				'justify' === settings.align ? 'elementor-button--justify' : '',
			],
			'type': 'submit'
		});

		if ('' !== settings.button_css_id) {
			view.addRenderAttribute('button', 'id', settings.button_css_id);
		}

		if ('' !== settings.hover_animation) {
			view.addRenderAttribute('button', 'class', 'elementor-animation-' + settings.hover_animation);
		}

		view.addRenderAttribute('form', {
			'action': '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
			'method': 'post',
			'target': target,
			'class': 'elementor-zarinpal-form',
		});

		// Define iconHTML *before* using it
		var iconHTML = elementor.helpers.renderIcon(view, settings.selected_icon, { 'aria-hidden': true }, 'i', 'object');
		#>

		<form {{{ view.getRenderAttributeString('form') }}}>
			<input type="hidden" name="action" value="zarinpal_payment_request" />
			<input type="hidden" name="merchant_id" value="{{ settings.merchant_id }}" />
			<input type="hidden" name="amount" value="{{ totalPrice }}" />
			<input type="hidden" name="description" value="{{ settings.product_name + ' (' + quantity + ' عدد)' }}" />
			<input type="hidden" name="callback_url" value="{{ callbackUrl }}" />
			<input type="hidden" name="quantity" value="{{ quantity }}" />

			<button {{{ view.getRenderAttributeString('button') }}}>
				<span class="elementor-button-content-wrapper">
					<# if (iconHTML.value && 'left' === settings.icon_align) { #>
						<span class="elementor-button-icon elementor-align-icon-left">
							{{{ iconHTML.value }}}
						</span>
					<# } #>

					<span class="elementor-button-text">
						<# if ('yes' === settings.show_product_info_on_button) { #>
							<span class="zarinpal-product-name">
								خرید {{ settings.product_name }}
								<# if (quantity > 1) { #>
									({{ quantity }} عدد)
								<# } #>
							</span>
							<span>-</span>
							<span class="zarinpal-product-price">{{ formattedPrice }}</span>
						<# } else { #>
							{{{ settings.button_text }}} <# /* Use triple braces for HTML entities */ #>
						<# } #>
					</span>

					<# if (iconHTML.value && 'right' === settings.icon_align) { #>
						<span class="elementor-button-icon elementor-align-icon-right">
							{{{ iconHTML.value }}}
						</span>
					<# } #>
				</span>
			</button>
		</form>

		<?php
	}
}
