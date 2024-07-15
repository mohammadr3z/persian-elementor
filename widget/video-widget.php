<?php

use Elementor\Modules\DynamicTags\Module as TagsModule;

class Persian_Elementor_Video_Widget extends \Elementor\Widget_Video {

	public function get_name() {
		return 'video';
	}

	public function get_title() {
		return __( 'Video', 'elementor' );
	}

	protected function register_controls() {
		$widget_video = $this;

		$widget_video->start_controls_section(
			'section_video',
			[
				'label' => esc_html__( 'Video', 'elementor' ),
			]
		);

		$widget_video->add_control(
			'video_type',
			[
				'label'              => esc_html__( 'Source', 'elementor' ),
				'type'               => \Elementor\Controls_Manager::SELECT,
				'default'            => 'aparat',
				'options'            => [
					'aparat'      => esc_html__( 'آپارات', 'elementor' ),
					'youtube'     => esc_html__( 'YouTube', 'elementor' ),
					'vimeo'       => esc_html__( 'Vimeo', 'elementor' ),
					'dailymotion' => esc_html__( 'Dailymotion', 'elementor' ),
					'hosted'      => esc_html__( 'Self Hosted', 'elementor' ),
				],
				'frontend_available' => true,
			]
		);

		$widget_video->add_control(
			'aparat_url',
			[
				'label'              => esc_html__( 'url', 'elementor' ),
				'type'               => \Elementor\Controls_Manager::TEXT,
				'dynamic'            => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder'        => esc_html__( 'آدرس ویدئو آپارات', 'elementor' ),
				'default'            => 'https://www.aparat.com/v/b491rbh',
				'label_block'        => true,
				'condition'          => [
					'video_type' => 'aparat',
				],
				'ai'                 => [
					'active' => false,
				],
				'frontend_available' => true,
			]
		);

		$widget_video->add_control(
			'aparat_height',
			[
				'label'              => esc_html__( 'Height', 'elementor' ),
				'type'               => \Elementor\Controls_Manager::NUMBER,
				'description'        => esc_html__( 'حداقل ارتفاع', 'elementor' ),
				'default'            => '640',
				'condition'          => [
					'video_type' => 'aparat',
				],
				'frontend_available' => true,
			]
		);

		$widget_video->add_control(
			'mute_vid',
			[
				'label'     => esc_html__( 'Mute', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'condition' => [
					'video_type' => 'aparat',
				],
				'ai'        => [
					'active' => false,
				],
			]
		);

		$widget_video->add_control(
			'title_show',
			[
				'label'     => esc_html__( 'نمایش عنوان', 'elementor-pro' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'condition' => [
					'video_type' => 'aparat',
				],
				'ai'        => [
					'active' => false,
				],
			]
		);

		$widget_video->add_control(
			'start_h',
			[
				'label'              => esc_html__( 'زمان شروع (ساعت)', 'elementor-pro' ),
				'type'               => \Elementor\Controls_Manager::NUMBER,
				'description'        => esc_html__( 'ساعت شروع ویدئو را مشخص کنید', 'elementor-pro' ),
				'frontend_available' => true,
				'condition'          => [
					'video_type' => 'aparat',
				],
			]
		);

		$widget_video->add_control(
			'start_m',
			[
				'label'              => esc_html__( 'زمان شروع (دقیقه)', 'elementor-pro' ),
				'type'               => \Elementor\Controls_Manager::NUMBER,
				'description'        => esc_html__( 'ساعت شروع ویدئو را مشخص کنید', 'elementor-pro' ),
				'frontend_available' => true,
				'condition'          => [
					'video_type' => 'aparat',
				],
			]
		);

		$widget_video->add_control(
			'start_s',
			[
				'label'              => esc_html__( 'زمان شروع (ثانیه)', 'elementor-pro' ),
				'type'               => \Elementor\Controls_Manager::NUMBER,
				'description'        => esc_html__( 'ساعت شروع ویدیو را مشخص کنید', 'elementor-pro' ),
				'frontend_available' => true,
				'condition'          => [
					'video_type' => 'aparat',
				],
			]
		);

		$widget_video->add_control(
			'youtube_url',
			[
				'label'              => esc_html__( 'Link', 'elementor' ),
				'type'               => \Elementor\Controls_Manager::TEXT,
				'dynamic'            => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder'        => esc_html__( 'Enter your URL', 'elementor' ) . ' (YouTube)',
				'default'            => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
				'label_block'        => true,
				'condition'          => [
					'video_type' => 'youtube',
				],
				'ai'                 => [
					'active' => false,
				],
				'frontend_available' => true,
			]
		);

		$widget_video->add_control(
			'vimeo_url',
			[
				'label'       => esc_html__( 'Link', 'elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'dynamic'     => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => esc_html__( 'Enter your URL', 'elementor' ) . ' (Vimeo)',
				'default'     => 'https://vimeo.com/235215203',
				'label_block' => true,
				'condition'   => [
					'video_type' => 'vimeo',
				],
				'ai'          => [
					'active' => false,
				],
			]
		);

		$widget_video->add_control(
			'dailymotion_url',
			[
				'label'       => esc_html__( 'Link', 'elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'dynamic'     => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => esc_html__( 'Enter your URL', 'elementor' ) . ' (Dailymotion)',
				'default'     => 'https://www.dailymotion.com/video/x6tqhqb',
				'label_block' => true,
				'condition'   => [
					'video_type' => 'dailymotion',
				],
				'ai'          => [
					'active' => false,
				],
			]
		);

		$widget_video->add_control(
			'insert_url',
			[
				'label'     => esc_html__( 'External URL', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'condition' => [
					'video_type' => 'hosted',
				],
				'ai'        => [
					'active' => false,
				],
			]
		);

		$widget_video->add_control(
			'hosted_url',
			[
				'label'      => esc_html__( 'Choose File', 'elementor' ),
				'type'       => \Elementor\Controls_Manager::MEDIA,
				'dynamic'    => [
					'active'     => true,
					'categories' => [
						TagsModule::MEDIA_CATEGORY,
					],
				],
				'media_type' => 'video',
				'condition'  => [
					'video_type' => 'hosted',
					'insert_url' => '',
				],
				'ai'         => [
					'active' => false,
				],
			]
		);

		$widget_video->add_control(
			'external_url',
			[
				'label'        => esc_html__( 'URL', 'elementor' ),
				'type'         => \Elementor\Controls_Manager::URL,
				'autocomplete' => false,
				'options'      => false,
				'label_block'  => true,
				'show_label'   => false,
				'dynamic'      => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'media_type'   => 'video',
				'placeholder'  => esc_html__( 'Enter your URL', 'elementor' ),
				'condition'    => [
					'video_type' => 'hosted',
					'insert_url' => 'yes',
				],
				'ai'           => [
					'active' => false,
				],
			]
		);

		$widget_video->add_control(
			'start',
			[
				'label'              => esc_html__( 'Start Time', 'elementor' ),
				'type'               => \Elementor\Controls_Manager::NUMBER,
				'description'        => esc_html__( 'Specify a start time (in seconds)', 'elementor' ),
				'frontend_available' => true,
				'condition'          => [
					'video_type!' => 'aparat',
				],
			]
		);

		$widget_video->add_control(
			'end',
			[
				'label'              => esc_html__( 'End Time', 'elementor' ),
				'type'               => \Elementor\Controls_Manager::NUMBER,
				'description'        => esc_html__( 'Specify an end time (in seconds)', 'elementor' ),
				'condition'          => [
					'video_type' => [ 'youtube', 'hosted' ],
				],
				'frontend_available' => true,
			]
		);

		$widget_video->add_control(
			'video_options',
			[
				'label'     => esc_html__( 'Video Options', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'video_type!' => 'aparat',
				],
			]
		);

		$widget_video->add_control(
			'autoplay',
			[
				'label'              => esc_html__( 'Autoplay', 'elementor' ),
				'type'               => \Elementor\Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'conditions'         => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'  => 'show_image_overlay',
							'value' => '',
						],
						[
							'name'  => 'image_overlay[url]',
							'value' => '',
						],
					],
				],
			]
		);

		$widget_video->add_control(
			'play_on_mobile',
			[
				'label'              => esc_html__( 'Play On Mobile', 'elementor' ),
				'type'               => \Elementor\Controls_Manager::SWITCHER,
				'condition'          => [
					'autoplay'    => 'yes',
					'video_type!' => 'aparat',
				],
				'frontend_available' => true,
			]
		);

		$widget_video->add_control(
			'mute',
			[
				'label'              => esc_html__( 'Mute', 'elementor' ),
				'type'               => \Elementor\Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'condition'          => [
					'video_type!' => 'aparat',
				],
			]
		);

		$widget_video->add_control(
			'loop',
			[
				'label'     => esc_html__( 'Loop', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'condition' => [
					'video_type!' => [ 'dailymotion', 'aparat' ],
				],

				'frontend_available' => true,
			]
		);

		$widget_video->add_control(
			'controls',
			[
				'label'              => esc_html__( 'Player Controls', 'elementor' ),
				'type'               => \Elementor\Controls_Manager::SWITCHER,
				'label_off'          => esc_html__( 'Hide', 'elementor' ),
				'label_on'           => esc_html__( 'Show', 'elementor' ),
				'default'            => 'yes',
				'condition'          => [
					'video_type!' => [ 'vimeo', 'aparat' ],
				],
				'frontend_available' => true,
			]
		);

		$widget_video->add_control(
			'showinfo',
			[
				'label'     => esc_html__( 'Video Info', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'elementor' ),
				'label_on'  => esc_html__( 'Show', 'elementor' ),
				'default'   => 'yes',
				'condition' => [
					'video_type' => [ 'dailymotion' ],
				],
			]
		);

		$widget_video->add_control(
			'modestbranding',
			[
				'label'              => esc_html__( 'Modest Branding', 'elementor' ),
				'type'               => \Elementor\Controls_Manager::SWITCHER,
				'condition'          => [
					'video_type' => [ 'youtube' ],
					'controls'   => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$widget_video->add_control(
			'logo',
			[
				'label'     => esc_html__( 'Logo', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'elementor' ),
				'label_on'  => esc_html__( 'Show', 'elementor' ),
				'default'   => 'yes',
				'condition' => [
					'video_type' => [ 'dailymotion' ],
				],
			]
		);

		// YouTube.
		$widget_video->add_control(
			'yt_privacy',
			[
				'label'              => esc_html__( 'Privacy Mode', 'elementor' ),
				'type'               => \Elementor\Controls_Manager::SWITCHER,
				'description'        => esc_html__( 'When you turn on privacy mode, YouTube/Vimeo won\'t store information about visitors on your website unless they play the video.', 'elementor' ),
				'condition'          => [
					'video_type' => [ 'youtube', 'vimeo' ],
				],
				'frontend_available' => true,
			]
		);

		$widget_video->add_control(
			'lazy_load',
			[
				'label'              => esc_html__( 'Lazy Load', 'elementor' ),
				'type'               => \Elementor\Controls_Manager::SWITCHER,
				'conditions'         => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'video_type',
							'operator' => '===',
							'value'    => 'youtube',
						],
						[
							'relation' => 'and',
							'terms'    => [
								[
									'name'     => 'show_image_overlay',
									'operator' => '===',
									'value'    => 'yes',
								],
								[
									'name'     => 'video_type',
									'operator' => '!==',
									'value'    => [ 'hosted', 'aparat' ],
								],

							],
						],
					],
				],
				'frontend_available' => true,
			]
		);

		$widget_video->add_control(
			'rel',
			[
				'label'     => esc_html__( 'Suggested Videos', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => [
					''    => esc_html__( 'Current Video Channel', 'elementor' ),
					'yes' => esc_html__( 'Any Video', 'elementor' ),
				],
				'condition' => [
					'video_type' => 'youtube',
				],
			]
		);

		// Vimeo.
		$widget_video->add_control(
			'vimeo_title',
			[
				'label'     => esc_html__( 'Intro Title', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'elementor' ),
				'label_on'  => esc_html__( 'Show', 'elementor' ),
				'default'   => 'yes',
				'condition' => [
					'video_type' => 'vimeo',
				],
			]
		);

		$widget_video->add_control(
			'vimeo_portrait',
			[
				'label'     => esc_html__( 'Intro Portrait', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'elementor' ),
				'label_on'  => esc_html__( 'Show', 'elementor' ),
				'default'   => 'yes',
				'condition' => [
					'video_type' => 'vimeo',
				],
			]
		);

		$widget_video->add_control(
			'vimeo_byline',
			[
				'label'     => esc_html__( 'Intro Byline', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'elementor' ),
				'label_on'  => esc_html__( 'Show', 'elementor' ),
				'default'   => 'yes',
				'condition' => [
					'video_type' => 'vimeo',
				],
			]
		);

		$widget_video->add_control(
			'color',
			[
				'label'     => esc_html__( 'Controls Color', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'condition' => [
					'video_type' => [ 'vimeo', 'dailymotion' ],
				],
			]
		);

		$widget_video->add_control(
			'download_button',
			[
				'label'     => esc_html__( 'Download Button', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'elementor' ),
				'label_on'  => esc_html__( 'Show', 'elementor' ),
				'condition' => [
					'video_type' => 'hosted',
				],
			]
		);

		$widget_video->add_control(
			'preload',
			[
				'label'       => esc_html__( 'Preload', 'elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => [
					'metadata' => esc_html__( 'Metadata', 'elementor' ),
					'auto'     => esc_html__( 'Auto', 'elementor' ),
					'none'     => esc_html__( 'None', 'elementor' ),
				],
				'description' => sprintf(
					// Translators: %1$s and %2$s are placeholders for opening and closing HTML link tags respectively.
					esc_html__( 'Preload attribute lets you specify how the video should be loaded when the page loads. %1$sLearn More%2$s', 'elementor' ),
					'<a target="_blank" href="https://go.elementor.com/preload-video/">',
					'</a>'
				),
				'default'     => 'metadata',
				'condition'   => [
					'video_type' => 'hosted',
					'autoplay'   => '',
				],
			]
		);

		$widget_video->add_control(
			'poster',
			[
				'label'     => esc_html__( 'Poster', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::MEDIA,
				'dynamic'   => [
					'active' => true,
				],
				'condition' => [
					'video_type' => 'hosted',
				],
			]
		);

		$widget_video->add_control(
			'view',
			[
				'label'   => esc_html__( 'View', 'elementor' ),
				'type'    => \Elementor\Controls_Manager::HIDDEN,
				'default' => 'youtube',
			]
		);

		$widget_video->end_controls_section();

		$widget_video->start_controls_section(
			'section_image_overlay',
			[
				'label'     => esc_html__( 'Image Overlay', 'elementor' ),
				'condition' => [
					'video_type!' => 'aparat',
				],
			]
		);

		$widget_video->add_control(
			'show_image_overlay',
			[
				'label'              => esc_html__( 'Image Overlay', 'elementor' ),
				'type'               => \Elementor\Controls_Manager::SWITCHER,
				'label_off'          => esc_html__( 'Hide', 'elementor' ),
				'label_on'           => esc_html__( 'Show', 'elementor' ),
				'frontend_available' => true,
			]
		);

		$widget_video->add_control(
			'image_overlay',
			[
				'label'              => esc_html__( 'Choose Image', 'elementor' ),
				'type'               => \Elementor\Controls_Manager::MEDIA,
				'default'            => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'dynamic'            => [
					'active' => true,
				],
				'condition'          => [
					'show_image_overlay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$widget_video->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image_overlay',
				// Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_overlay_size` and `image_overlay_custom_dimension`.
				'default'   => 'full',
				'separator' => 'none',
				'condition' => [
					'show_image_overlay' => 'yes',
				],
			]
		);

		$widget_video->add_control(
			'show_play_icon',
			[
				'label'     => esc_html__( 'Play Icon', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [
					'show_image_overlay'  => 'yes',
					'image_overlay[url]!' => '',
				],
			]
		);

		$widget_video->add_control(
			'play_icon',
			[
				'label'            => esc_html__( 'Icon', 'elementor' ),
				'type'             => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'skin_settings'    => [
					'inline' => [
						'none' => [
							'label' => 'Default',
							'icon'  => 'eicon-play',
						],
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended'      => [
					'fa-regular' => [
						'play-circle',
					],
					'fa-solid'   => [
						'play',
						'play-circle',
					],
				],
				'condition'        => [
					'show_image_overlay' => 'yes',
					'show_play_icon!'    => '',
				],
			]
		);

		$widget_video->add_control(
			'lightbox',
			[
				'label'              => esc_html__( 'Lightbox', 'elementor' ),
				'type'               => \Elementor\Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'label_off'          => esc_html__( 'Off', 'elementor' ),
				'label_on'           => esc_html__( 'On', 'elementor' ),
				'condition'          => [
					'show_image_overlay'  => 'yes',
					'image_overlay[url]!' => '',
				],
				'separator'          => 'before',
			]
		);

		$widget_video->end_controls_section();

		$widget_video->start_controls_section(
			'section_video_style',
			[
				'label' => esc_html__( 'Video', 'elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$widget_video->add_control(
			'aspect_ratio',
			[
				'label'                => esc_html__( 'Aspect Ratio', 'elementor' ),
				'type'                 => \Elementor\Controls_Manager::SELECT,
				'options'              => [
					'169' => '16:9',
					'219' => '21:9',
					'43'  => '4:3',
					'32'  => '3:2',
					'11'  => '1:1',
					'916' => '9:16',
				],
				'selectors_dictionary' => [
					'169' => '1.77777', // 16 / 9
					'219' => '2.33333', // 21 / 9
					'43'  => '1.33333', // 4 / 3
					'32'  => '1.5', // 3 / 2
					'11'  => '1', // 1 / 1
					'916' => '0.5625', // 9 / 16
				],
				'default'              => '169',
				'selectors'            => [
					'{{WRAPPER}} .elementor-wrapper' => '--video-aspect-ratio: {{VALUE}}',
				],
			]
		);

		$widget_video->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .elementor-wrapper',
			]
		);

		$widget_video->add_control(
			'play_icon_title',
			[
				'label'     => esc_html__( 'Play Icon', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon'     => 'yes',
				],
				'separator' => 'before',
			]
		);

		$widget_video->add_control(
			'play_icon_color',
			[
				'label'     => esc_html__( 'Color', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-custom-embed-play i'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .elementor-custom-embed-play svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon'     => 'yes',
				],
			]
		);

		$widget_video->add_responsive_control(
			'play_icon_size',
			[
				'label'     => esc_html__( 'Size', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 300,
					],
				],
				'selectors' => [
					// Not using a CSS vars because the default size value is coming from a global scss file.
					'{{WRAPPER}} .elementor-custom-embed-play i'   => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-custom-embed-play svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon'     => 'yes',
				],
			]
		);

		$widget_video->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name'           => 'play_icon_text_shadow',
				'selector'       => '{{WRAPPER}} .elementor-custom-embed-play i',
				'fields_options' => [
					'text_shadow_type' => [
						'label' => esc_html_x( 'Shadow', 'Text Shadow Control', 'elementor' ),
					],
				],
				'condition'      => [
					'show_image_overlay'  => 'yes',
					'show_play_icon'      => 'yes',
					'play_icon[library]!' => 'svg',
				],
			]
		);

		$widget_video->end_controls_section();

		$widget_video->start_controls_section(
			'section_lightbox_style',
			[
				'label'     => esc_html__( 'Lightbox', 'elementor' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_image_overlay'  => 'yes',
					'image_overlay[url]!' => '',
					'lightbox'            => 'yes',
				],
			]
		);

		$widget_video->add_control(
			'lightbox_color',
			[
				'label'     => esc_html__( 'Background Color', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-{{ID}}' => 'background-color: {{VALUE}};',
				],
			]
		);

		$widget_video->add_control(
			'lightbox_ui_color',
			[
				'label'     => esc_html__( 'UI Color', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-{{ID}} .dialog-lightbox-close-button'     => 'color: {{VALUE}}',
					'#elementor-lightbox-{{ID}} .dialog-lightbox-close-button svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$widget_video->add_control(
			'lightbox_ui_color_hover',
			[
				'label'     => esc_html__( 'UI Hover Color', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-{{ID}} .dialog-lightbox-close-button:hover'     => 'color: {{VALUE}}',
					'#elementor-lightbox-{{ID}} .dialog-lightbox-close-button:hover svg' => 'fill: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$widget_video->add_control(
			'lightbox_video_width',
			[
				'label'     => esc_html__( 'Content Width', 'elementor' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'default'   => [
					'unit' => '%',
				],
				'range'     => [
					'%' => [
						'min' => 30,
					],
				],
				'selectors' => [
					'(desktop+)#elementor-lightbox-{{ID}} .elementor-video-container' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget_video->add_control(
			'lightbox_content_position',
			[
				'label'                => esc_html__( 'Content Position', 'elementor' ),
				'type'                 => \Elementor\Controls_Manager::SELECT,
				'frontend_available'   => true,
				'options'              => [
					''    => esc_html__( 'Center', 'elementor' ),
					'top' => esc_html__( 'Top', 'elementor' ),
				],
				'selectors'            => [
					'#elementor-lightbox-{{ID}} .elementor-video-container' => '{{VALUE}}; transform: translateX(-50%);',
				],
				'selectors_dictionary' => [
					'top' => 'top: 60px',
				],
			]
		);

		$widget_video->add_responsive_control(
			'lightbox_content_animation',
			[
				'label'              => esc_html__( 'Entrance Animation', 'elementor' ),
				'type'               => \Elementor\Controls_Manager::ANIMATION,
				'frontend_available' => true,
			]
		);

		$widget_video->end_controls_section();
	}

protected function render() {
    $settings = $this->get_settings_for_display();
    if ( $settings['video_type'] === 'aparat' ) {
        // Sanitize and validate inputs
        $video_url = sanitize_text_field( $settings['aparat_url'] );
        $video_id = explode( "/", $video_url );
        $video_id = end( $video_id );
        
        $mute = isset( $settings['mute_vid'] ) && $settings['mute_vid'] ? 'true' : 'false';
        $autoplay = isset( $settings['autoplay'] ) && $settings['autoplay'] ? 'true' : 'false';
        $show_title = isset( $settings['title_show'] ) && $settings['title_show'] ? 'true' : 'false';
        
        $start_h = isset( $settings['start_h'] ) ? absint( $settings['start_h'] ) : 0;
        $start_m = isset( $settings['start_m'] ) ? absint( $settings['start_m'] ) : 0;
        $start_s = isset( $settings['start_s'] ) ? absint( $settings['start_s'] ) : 0;
        $start_time = ( $start_h * 60 * 60 ) + ( $start_m * 60 ) + $start_s;
        
        $min_height = isset( $settings['aparat_height'] ) ? absint( $settings['aparat_height'] ) : 400;

        $response = wp_remote_get( 'https://www.aparat.com/etc/api/video/videohash/' . urlencode( $video_id ) );

        if ( is_wp_error( $response ) ) {
            echo esc_html__( 'ارتباط با آپارات برقرار نشد.', 'your-text-domain' );
            return;
        }

        $data = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( isset( $data['error'] ) ) {
            echo esc_html__( 'ارتباط با آپارات برقرار نشد.', 'your-text-domain' );
            return;
        }

        $iframe_src = add_query_arg(
            array(
                't' => $start_time,
                'titleShow' => $show_title,
                'muted' => $mute,
                'autoplay' => $autoplay,
            ),
            'https://www.aparat.com/video/video/embed/videohash/' . esc_attr( $video_id ) . '/vt/frame'
        );

        printf(
            '<iframe src="%s" height="%d" allow="autoplay" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe>',
            esc_url( $iframe_src ),
            esc_attr( $min_height )
        );

        return;
    }

    parent::render();
}
}


