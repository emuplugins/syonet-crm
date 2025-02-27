<?php
namespace Custom_Elementor;

if (!defined('ABSPATH')) {
    exit; // Segurança
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography as Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography as Scheme_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors as Global_Colors;
use Elementor\Utils as Utils;

class Syonet_Form_Widget extends Widget_Base {

    public function get_name() {
        return 'syonet_form_widget';
    }

    public function get_title() {
        return __('Formulário Syonet', 'custom-elementor');
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Configurações do Formulário', 'custom-elementor'),
            ]
        );


		$this->add_control(
			'form_title',
			[
				'label' => esc_html__( 'Título do Formulário', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Formulário de Contato', 'textdomain' ),
				'placeholder' => esc_html__( 'Digite seu título aqui', 'textdomain' ),
			]
		);

		$this->add_control(
			'form_submit_text',
			[
				'label' => esc_html__( 'Texto do Botão de Envio', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Enviar', 'textdomain' ),
				'placeholder' => esc_html__( 'Digite seu texto aqui', 'textdomain' ),
			]
		);

		$this->add_control(
			'form_subtitle',
			[
				'label' => esc_html__( 'Subtítulo do Formulário', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Formulário de Contato', 'textdomain' ),
				'placeholder' => esc_html__( 'Digite seu subtítulo aqui', 'textdomain' ),
			]
		);


        $this->add_control(
            'form_id',
            [
                'label' => __('Escolha o Formulário', 'custom-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_syonet_forms(),
                'default' => '',
            ]
        );

        $this->add_control(
			'logo',
			[
				'label' => esc_html__( 'Logotipo', 'textdomain' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => FORMULARIO_SYONET_URL . 'assets/images/syonet-logo.webp',
				],
			]
		);


        $this->end_controls_section();

        // Adicionando a seção de estilos
        $this->start_controls_section(
            'section_styles',
            [
                'label' => __('Estilos', 'custom-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
                'label' => __('Tamanho da Fonte dos Labels', 'custom-elementor'),
				'name' => 'label_font_size',
				'selector' => '{{WRAPPER}} .form-wizard label',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
                'label' => __('Tamanho da Fonte do ícone do passo', 'custom-elementor'),
				'name' => 'step_number_font_size',
				'selector' => '{{WRAPPER}} .form-wizard .progress-container li::before',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
                'label' => __('Tamanho da Fonte do Passo', 'custom-elementor'),
				'name' => 'step_font_size',
				'selector' => '{{WRAPPER}} .form-wizard .progress-container li',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
                'label' => __('Tamanho da Fonte dos Inputs', 'custom-elementor'),
				'name' => 'input_typography',
				'selector' => '{{WRAPPER}} .form-wizard input, {{WRAPPER}} .form-wizard textarea, {{WRAPPER}} .form-wizard select',
                
			]
		);
        
        


        $this->add_control(
			'form_margin',
			[
				'label' => esc_html__( 'Form Margin', 'textdomain' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .form-wizard' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


        $this->add_control(
			'form_padding',
			[
				'label' => esc_html__( 'Form Padding', 'textdomain' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 25,
					'right' => 25,
					'bottom' => 25,
					'left' => 25,
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .form-wizard' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'input_padding',
			[
				'label' => esc_html__( 'Input Padding', 'textdomain' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 14,
					'right' => 14,
					'bottom' => 14,
					'left' => 14,
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .form-wizard input, {{WRAPPER}} .form-wizard textarea, {{WRAPPER}} .form-wizard select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
                    '{{WRAPPER}} .form-wizard div.phone-input > div' => 'padding: 
                        calc({{TOP}}{{UNIT}} * 0) 
                        calc({{RIGHT}}{{UNIT}} * 0.55) 
                        calc({{BOTTOM}}{{UNIT}} * 0) 
                        calc({{LEFT}}{{UNIT}} * 0.55)!important;'
                        
				],
			]
		);

        $this->add_control(
			'input_border_radius',
			[
				'label' => esc_html__( 'Input Border Radius', 'textdomain' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 7,
					'right' => 7,
					'bottom' => 7,
					'left' => 7,
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .form-wizard input, {{WRAPPER}} .form-wizard textarea, {{WRAPPER}} .form-wizard select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
                    '{{WRAPPER}} .form-wizard .phone-input input' => 'border-radius:0px!important;',
				],
			]
		);


        $this->add_control(
			'input_border_width',
			[
				'label' => esc_html__( 'Input Border Width', 'textdomain' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .form-wizard input, {{WRAPPER}} .form-wizard textarea, {{WRAPPER}} .form-wizard select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				],
			]
		);


        $this->add_control(
			'label_margin_bottom',
			[
				'label' => esc_html__( 'Label Margin Bottom', 'textdomain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .form-wizard label' => 'margin-bottom: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_control(
			'logo_margin_bottom',
			[
				'label' => esc_html__( 'Logo Margin Bottom', 'textdomain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .form-wizard .syonet-logo-form' => 'margin-bottom: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

        $this->add_control(
			'step-size',
			[
				'label' => esc_html__( 'Tamanho do Passo', 'textdomain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 60,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .form-wizard' => '--step-size: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_control(
			'logo-size',
			[
				'label' => esc_html__( 'Tamanho do Logo', 'textdomain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 70,
						'max' => 300,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 170,
				],
				'selectors' => [
					'{{WRAPPER}} .form-wizard .syonet-logo-form' => 'width: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

        $this->add_control(
            'step-background-color',
            [
                'label' => esc_html__( 'Cor de Fundo do Passo', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .form-wizard .progress-container li.done::before, {{WRAPPER}} .form-wizard .progress-container li.current::before' => 'background-color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'default' => '#cc0927',
            ]
        );


        $this->add_control(
            'input_border_color',
            [
                'label' => esc_html__( 'Input Border Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .form-wizard input, {{WRAPPER}} .form-wizard textarea, {{WRAPPER}} .form-wizard select' => 'border-color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'default' => '#D9E9F0',
            ]
        );

        $this->add_control(
            'input_background_color',
            [
                'label' => esc_html__( 'InputBackground Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .form-wizard input, {{WRAPPER}} .form-wizard textarea, {{WRAPPER}} .form-wizard select, {{WRAPPER}} .form-wizard .phone-input' => 'background-color: {{VALUE}} !important;',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'default' => '#ffffff',
            ]
        );
        
        $this->add_control(
			'button_padding',
			[
				'label' => esc_html__( 'Button Padding', 'textdomain' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 10,
					'right' => 20,
					'bottom' => 10,
					'left' => 20,
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .form-wizard button.syonet-next-btn, {{WRAPPER}} .form-wizard button.syonet-prev-btn, {{WRAPPER}} .form-wizard button.syonet-submit-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',                        
				],
			]
		);

        

        $this->add_control(
            'button_background_color',
            [
                'label' => esc_html__( 'Button Background Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .form-wizard button.syonet-next-btn, {{WRAPPER}} .form-wizard button.syonet-prev-btn' => 'background-color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'default' => '#cc0927',
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => esc_html__( 'Button Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .form-wizard button.syonet-next-btn, {{WRAPPER}} .form-wizard button.syonet-prev-btn' => 'color: {{VALUE}};',
                ],
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'submit_button_background_color',
            [
                'label' => esc_html__( 'Submit Button Background Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .form-wizard button.syonet-submit-btn' => 'background-color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'default' => '#2563EB',
            ]
        );

        $this->add_control(
            'submit_button_color',
            [
                'label' => esc_html__( 'Submit Button Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .form-wizard button.syonet-submit-btn' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'default' => '#ffffff',
            ]
        );

		$this->add_control(
            'form_backgroud_color',
            [
                'label' => esc_html__( 'Form Background Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .form-wizard' => 'background-color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'default' => '#ffffff',
            ]
        );


        


        $this->end_controls_section();
    }

    protected function get_syonet_forms() {
        $forms = get_posts([
            'post_type'      => 'syonet_form',
            'posts_per_page' => -1,
            'post_status'    => 'any', // Agora inclui rascunhos e publicações
			'default' => [0],
        ]);
    
        $options = ['' => __('Selecione um formulário', 'custom-elementor')];
    
        if (!empty($forms)) {
            foreach ($forms as $form) {
                $options[$form->ID] = $form->post_title;
            }
        }
    
        return $options;
    }    

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (empty($settings['form_id'])) {
            echo __('Selecione um formulário.', 'custom-elementor');
            return;
        }

        echo do_shortcode('[syonet_form id="' . esc_attr($settings['form_id']) . '" logo="' . esc_attr($settings['logo']['url']) . '" title="' . esc_attr($settings['form_title']) . '" submit_text="' . esc_attr($settings['form_submit_text']) . '" subtitle="' . esc_attr($settings['form_subtitle']) . '"]');
    }
}