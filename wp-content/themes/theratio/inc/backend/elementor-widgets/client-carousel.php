<?php 
namespace Elementor; // Custom widgets must be defined in the Elementor namespace
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly (security measure)

/**
 * Widget Name: Client Slider
 */
class Theratio_Client_Carousel extends Widget_Base{

 	// The get_name() method is a simple one, you just need to return a widget name that will be used in the code.
	public function get_name() {
		return 'theratio-image-carousel';
	}

	// The get_title() method, which again, is a very simple one, you need to return the widget title that will be displayed as the widget label.
	public function get_title() {
		return __( 'Theratio Client Carousel', 'theratio' );
	}

	// The get_icon() method, is an optional but recommended method, it lets you set the widget icon. you can use any of the eicon or font-awesome icons, simply return the class name as a string.
	public function get_icon() {
		return 'eicon-slider-push';
	}

	// The get_categories method, lets you set the category of the widget, return the category name as a string.
	public function get_categories() {
		return [ 'category_theratio' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Client Carousel', 'theratio' ),
			]
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'title',
			[
				'label' => __( 'Name', 'theratio' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '', 'theratio' ),
			]
		);
		$repeater->add_control(
			'image_partner',
			[
				'label' => __( 'Image', 'theratio' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$repeater->add_control(
			'image_link',
			[
				'label' => __( 'Link', 'theratio' ),
				'type' => Controls_Manager::URL,
				'default' => [],
			]
		);
		$this->add_control(
		    'image_carousel',
		    [
		        'label'       => '',
		        'type'        => Controls_Manager::REPEATER,
		        'show_label'  => false,
		        'prevent_empty' => false,
		        'default'     => [],
		        'fields'      => $repeater->get_controls(),
		        'title_field' => '{{{title}}}',
		    ]
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_partner_size', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'exclude' => ['1536x1536', '2048x2048'],
				'include' => [],
				'default' => 'full',
			]
		);

		$slides_show = range( 1, 10 );
		$slides_show = array_combine( $slides_show, $slides_show );

		$this->add_responsive_control(
			'slides_show',
			[
				'label' => __( 'Slides To Show', 'theratio' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'theratio' ),
				] + $slides_show,
				'default' => ''
			]
		);

		$this->add_responsive_control(
			'tscroll',
			[
				'label' => __( 'Slides to Scroll', 'theratio' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'Default', 'theratio' ),
				] + $slides_show,
			]
		);

		$this->add_control(
			'navigation',
			[
				'label' => __( 'Navigation', 'theratio' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'both' => __( 'Arrows and Dots', 'theratio' ),
					'arrows' => __( 'Arrows', 'theratio' ),
					'dots' => __( 'Dots', 'theratio' ),
					'none' => __( 'None', 'theratio' ),
				],
			]
		);

		$this->end_controls_section();

		//Style
		$this->start_controls_section(
			'image_style_section',
			[
				'label' => __( 'Image', 'theratio' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Vertical Align', 'theratio' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start'    => [
						'title' => __( 'Top', 'theratio' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Center', 'theratio' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => __( 'Bottom', 'theratio' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .image-carousel .swiper-wrapper' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'img_spacing',
			[
				'label' => __( 'Image Spacing', 'theratio' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 90,
				],
			]
		);

		$this->start_controls_tabs( 'img_effects' );

		$this->start_controls_tab( 'normal',
			[
				'label' => __( 'Normal', 'theratio' ),
			]
		);

		$this->add_control(
			'opacity',
			[
				'label' => __( 'Opacity', 'theratio' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'img_css_filters',
				'selector' => '{{WRAPPER}} .swiper-slide img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'img_hover_effects',
			[
				'label' => __( 'Hover', 'theratio' ),
			]
		);

		$this->add_control(
			'opacity_hover',
			[
				'label' => __( 'Opacity', 'theratio' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide img:hover' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'img_css_filters_hover',
				'selector' => '{{WRAPPER}} .swiper-slide img:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		//Dots
		$this->start_controls_section(
			'dots_section',
			[
				'label' => __( 'Dots', 'theratio' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

        $this->add_control(
			'dot_style',
			[
				'label' => __( 'Dot', 'theratio' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
            'dots_bgcolor',
            [
                'label' => __( 'Color', 'theratio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .octf-swiper-pagination .swiper-pagination-bullet-active' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .octf-swiper-pagination .swiper-pagination-bullet:before' => 'background: {{VALUE}};',
				],
            ]
        );

        $this->add_control(
            'dots_active_bgcolor',
            [
                'label' => __( 'Color Active', 'theratio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .octf-swiper-pagination .swiper-pagination-bullet-active' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .octf-swiper-pagination .swiper-pagination-bullet-active:before' => 'background: {{VALUE}};',
				],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
			'arrows_section',
			[
				'label' => __( 'Arrow', 'theratio' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				]
			]
		);
		$this->add_control(
			'arrow_bg_color',
			[
				'label' => __( 'Background', 'theratio' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .octf-swiper-button-next, {{WRAPPER}} .octf-swiper-button-prev' => 'background: {{VALUE}};',
				]
			]
		);
		$this->add_control(
			'arrow_bg_hcolor',
			[
				'label' => __( 'Background Hover', 'theratio' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .octf-swiper-button-next:not(.swiper-button-disabled):hover, {{WRAPPER}} .octf-swiper-button-prev:not(.swiper-button-disabled):hover' => 'background: {{VALUE}};',
				]
			]
		);
		$this->add_control(
			'arrow_color',
			[
				'label' => __( 'Color', 'theratio' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .octf-swiper-button-next, {{WRAPPER}} .octf-swiper-button-prev' => 'color: {{VALUE}};',
				]
			]
		);
		$this->add_control(
			'arrow_hcolor',
			[
				'label' => __( 'Color Hover', 'theratio' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .octf-swiper-button-next:not(.swiper-button-disabled):hover, {{WRAPPER}} .octf-swiper-button-prev:not(.swiper-button-disabled):hover' => 'color: {{VALUE}};',
				]
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$dots = ( in_array( $settings['navigation'], [ 'dots', 'both' ] ) );
		$arrows = ( in_array( $settings['navigation'], [ 'arrows', 'both' ] ) );

		if ( empty( $settings['image_carousel'] ) ) {
			return;
		}

		$slides = [];

		foreach ( $settings['image_carousel'] as $key => $attachment ) {
			$title = $attachment['title'];
            $image_url = Group_Control_Image_Size::get_attachment_image_src( $attachment['image_partner']['id'], 'image_partner_size', $settings );
            $image_html = '<img src="' . esc_attr( $image_url ) . '" alt="' . esc_attr( $title ) . '">';
            $link_tag = '';
            if ( ! empty( $attachment['image_link']['url'] ) ) {
				$this->add_render_attribute( 'link' . $key, 'href', $attachment['image_link']['url'] );

				if ( $attachment['image_link']['is_external'] ) {
					$this->add_render_attribute( 'link' . $key, 'target', '_blank' );
				}

				if ( $attachment['image_link']['nofollow'] ) {
					$this->add_render_attribute( 'link' . $key, 'rel', 'nofollow' );
				}
				$link_tag = '<a '.$this->get_render_attribute_string('link' . $key).'>';
			}
            
			$slide_html = '<div class="swiper-slide"><div class="img-item">' . $link_tag . '<figure>' . $image_html . '</figure>';

			if( $link_tag ){
				$slide_html .= '</a>';
			}
			$slide_html .= '</div></div>';
			if( $image_url ){
				$slides[] = $slide_html;
			}
		}
		if ( empty( $slides ) ) {
			return;
		}
		$showDesktop   = !empty( $settings['slides_show'] ) ? $settings['slides_show'] : 3;
		$showTablet    = !empty( $settings['slides_show_tablet'] ) ? $settings['slides_show_tablet'] : $showDesktop;
		$showMobile    = !empty( $settings['slides_show_mobile'] ) ? $settings['slides_show_mobile'] : $showTablet;

		$scrollDesktop   = !empty( $settings['tscroll'] ) ? $settings['tscroll'] : 1;
		$scrollTablet    = !empty( $settings['tscroll_tablet'] ) ? $settings['tscroll_tablet'] : $scrollDesktop;
		$scrollMobile    = !empty( $settings['tscroll_mobile'] ) ? $settings['tscroll_mobile'] : $scrollTablet;

		$gapDesktop      = isset( $settings['img_spacing']['size'] ) && is_numeric( $settings['img_spacing']['size'] ) ? $settings['img_spacing']['size'] : 30;
		$gapTablet  = isset( $settings['img_spacing_tablet']['size'] ) && is_numeric( $settings['img_spacing_tablet']['size'] ) ? $settings['img_spacing_tablet']['size'] : $gapDesktop;
		$gapMobile  = isset( $settings['img_spacing_mobile']['size'] ) && is_numeric( $settings['img_spacing_mobile']['size'] ) ? $settings['img_spacing_mobile']['size'] : $gapTablet;
		
		$owl_options = [
			'slides_show_desktop'  		 => absint( $showDesktop ),
			'slides_show_tablet'  		 => absint( $showTablet ),
			'slides_show_mobile'   		 => absint( $showMobile ),
			'slides_scroll_desktop'  	 => absint( $scrollDesktop ),
			'slides_scroll_tablet'  	 => absint( $scrollTablet ),
			'slides_scroll_mobile'   	 => absint( $scrollMobile ),
			'margin_desktop'   	   		 => absint( $gapDesktop ),
			'margin_tablet'   	   		 => absint( $gapTablet ),
			'margin_mobile'  		 	 => absint( $gapMobile ),
			'arrows'        	   		 => $arrows,
			'dots'          	   		 => $dots,
		];

		$this->add_render_attribute(
			'slides', [
				'class'               => ['image-carousel', 'swiper swiper-container'],
				'data-slider_options' => wp_json_encode( $owl_options ),
			]
		);
		?>

		<div <?php echo $this->get_render_attribute_string( 'slides' ); ?> <?php if( is_rtl() ){ echo'dir="rtl"'; }?> >			
			<div class="swiper-wrapper">
		        <?php echo implode( '', $slides ); ?>
		    </div>

			<?php if( $dots ){ ?>
			<!-- Add Dots -->
			<div class="octf-swiper-pagination"></div>
			<?php } ?>
			<?php if( $arrows  ){ ?>
			<!-- Add Arrows -->
			<div class="octf-swiper-button-next"><i class="ot-flaticon-right-arrow"></i></div>
			<div class="octf-swiper-button-prev"><i class="ot-flaticon-left-arrow"></i></div>
			<?php } ?>
	    </div>
		<?php 
		
	}

}
// After the Theratio_Client_Carousel class is defined, I must register the new widget class with Elementor:
Plugin::instance()->widgets_manager->register( new Theratio_Client_Carousel() );