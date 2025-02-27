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
		// ABA PRINCIPAL - CONTEÚDO
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
				'default' => esc_html__( '', 'textdomain' ),
				'placeholder' => esc_html__( 'Digite seu título aqui', 'textdomain' ),
			]
		);
	
		$this->add_control(
			'form_subtitle',
			[
				'label' => esc_html__( 'Subtítulo do Formulário', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '', 'textdomain' ),
				'placeholder' => esc_html__( 'Digite seu subtítulo aqui', 'textdomain' ),
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
	
		// ABA SECUNDÁRIA - ESTILOS


		// 1. SUB-ABA: CABEÇALHO
		$this->start_controls_section(
			'form_styles',
			[
				'label' => __('Formulário', 'custom-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
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
		


		
		// 1. SUB-ABA: CABEÇALHO
		$this->start_controls_section(
			'section_header_styles',
			[
				'label' => __('Cabeçalho', 'custom-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
	
		$this->start_controls_tabs('tabs_logo_styles');
	
		$this->start_controls_tab(
			'tab_logo_size',
			[
				'label' => __('Tamanho', 'custom-elementor'),
			]
		);
		
		$this->add_responsive_control(
			'logo-size',
			[
				'label' => esc_html__('Tamanho do Logo', 'textdomain'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem', 'custom'],
				'range' => [
					'px' => ['min' => 70, 'max' => 300, 'step' => 1],
					'%' => ['min' => 0, 'max' => 100],
				],
				'default' => ['unit' => 'px', 'size' => 200],
				'selectors' => [
					'{{WRAPPER}} .form-wizard .syonet-logo-form' => 'width: {{SIZE}}{{UNIT}}!important;',
				],
				
			]
		);
		
		$this->end_controls_tab();
	
		$this->start_controls_tab(
			'tab_logo_margin',
			[
				'label' => __('Espaçamento', 'custom-elementor'),
			]
		);
		
		$this->add_responsive_control(
			'logo_margin_bottom',
			[
				'label' => esc_html__('Logo Margin Bottom', 'textdomain'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem', 'custom'],
				'range' => ['px' => ['min' => 0, 'max' => 30, 'step' => 1]],
				'default' => ['unit' => 'px', 'size' => 20],
				'selectors' => [
					'{{WRAPPER}} .form-wizard .syonet-logo-form' => 'margin-bottom: {{SIZE}}{{UNIT}}!important;',
				],
				
			]
		);

		$this->add_responsive_control(
			'title_margin_bottom',
			[
				'label' => esc_html__( 'Title Margin Bottom', 'textdomain' ),
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
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .form-wizard .syonet-form-title' => 'margin-bottom: {{SIZE}}{{UNIT}}!important;',
				],
				
			]
		);

		$this->add_responsive_control(
			'subtitle_margin_bottom',
			[
				'label' => esc_html__( 'Subtitle Margin Bottom', 'textdomain' ),
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
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .form-wizard .syonet-form-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}}!important;',
				],
				
			]
		);
		
		$this->end_controls_tab();
	
		$this->end_controls_tabs();
		
		$this->end_controls_section();
	
		// 2. SUB-ABA: CAMPOS
		$this->start_controls_section(
			'form_fields_styles',
			[
				'label' => __('Campos do Formulário', 'custom-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
	
		$this->start_controls_tabs('tabs_fields_styles');
	
		$this->start_controls_tab(
			'tab_typography',
			[
				'label' => __('Tipografia', 'custom-elementor'),
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
	
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'label' => __('Tamanho da Fonte dos Labels', 'custom-elementor'),
				'name' => 'label_font_size',
				'selector' => '{{WRAPPER}} .form-wizard label',
				
			]
		);
		
		$this->end_controls_tab();
	
		$this->start_controls_tab(
			'tab_spacing',
			[
				'label' => __('Espaçamento', 'custom-elementor'),
			]
		);
		
		$this->add_responsive_control(
			'input_padding',
			[
				'label' => esc_html__('Input Padding', 'textdomain'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em', 'rem', 'custom'],
				'default' => ['top' => 14, 'right' => 14, 'bottom' => 14, 'left' => 14, 'unit' => 'px', 'isLinked' => true],
				'selectors' => [
					'{{WRAPPER}} .form-wizard input, {{WRAPPER}} .form-wizard textarea, {{WRAPPER}} .form-wizard select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				],
				
			]
		);
		
		$this->add_responsive_control(
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
					'{{WRAPPER}} .form-wizard input, {{WRAPPER}} .form-wizard textarea, {{WRAPPER}} .form-wizard select, {{WRAPPER}} .form-wizard .phone-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
					'{{WRAPPER}} .form-wizard .phone-input input' => 'border-radius:0px!important;',
				],
				
			]
		);

		$this->add_responsive_control(
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
					'{{WRAPPER}} .form-wizard input, {{WRAPPER}} .form-wizard textarea, {{WRAPPER}} .form-wizard select, {{WRAPPER}} .form-wizard .phone-input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
					'{{WRAPPER}} .form-wizard .phone-input input' => 'border-width: 0px!important;',
				],
				
			]
		);

		$this->add_responsive_control(
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

		
		
		$this->end_controls_tab();
	
		$this->start_controls_tab(
			'tab_colors',
			[
				'label' => __('Cores', 'custom-elementor'),
			]
		);
		
		$this->add_control(
			'input_background_color',
			[
				'label' => esc_html__('Cor de Fundo', 'textdomain'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .form-wizard input, {{WRAPPER}} .form-wizard textarea, {{WRAPPER}} .form-wizard select' => 'background-color: {{VALUE}} !important;',
				],
				'default' => '#ffffff',
			]
		);
		
		$this->add_control(
			'input_border_color',
			[
				'label' => esc_html__('Cor da Borda', 'textdomain'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .form-wizard input, {{WRAPPER}} .form-wizard textarea, {{WRAPPER}} .form-wizard select' => 'border-color: {{VALUE}}!important;',
				],
				'default' => '#D9E9F0',
			]
		);
		
		$this->end_controls_tab();
	
		$this->end_controls_tabs();
		
		$this->end_controls_section();
	
		// 3. SUB-ABA: PASSOS
		$this->start_controls_section(
			'form_steps_styles',
			[
				'label' => __('Passos do Formulário', 'custom-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
	
		$this->start_controls_tabs('tabs_steps_styles');
	
		$this->start_controls_tab(
			'tab_steps_colors',
			[
				'label' => __('Cores', 'custom-elementor'),
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
			'step-focus-color',
			[
				'label' => esc_html__('Cor de Foco do Passo', 'textdomain'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .form-wizard' => '--focus-color: {{VALUE}};',
				],
				'default' => '#266cee',
			]
		);
		
		$this->end_controls_tab();
	
		$this->start_controls_tab(
			'tab_steps_size',
			[
				'label' => __('Tamanho', 'custom-elementor'),
			]
		);
		
		$this->add_responsive_control(
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
				'label' => __('Tamanho da Fonte do ícone do passo', 'custom-elementor'),
				'name' => 'step_number_font_size',
				'selector' => '{{WRAPPER}} .form-wizard .progress-container li::before',
			]
		);
		
		$this->end_controls_tab();
	
		$this->end_controls_tabs();
		
		$this->end_controls_section();
	
		// 4. SUB-ABA: BOTÕES
		$this->start_controls_section(
			'form_buttons_styles',
			[
				'label' => __('Botões', 'custom-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_buttons_styles');

		$this->start_controls_tab(
			'button_spacing',
			[
				'label' => __('Espaçamento', 'custom-elementor'),
			]
		);

		$this->add_responsive_control(
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

		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'button_colors',
			[
				'label' => __('Cores', 'custom-elementor'),
			]
		);
		
			  $this->add_control(
				  'button_background_color',
				  [
					  'label' => esc_html__( 'Cor de Fundo do Botão', 'textdomain' ),
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
					  'label' => esc_html__( 'Cor do Botão', 'textdomain' ),
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
					  'label' => esc_html__( 'Cor de Fundo do Botão de Envio', 'textdomain' ),
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
					  'label' => esc_html__( 'Cor do Botão de Envio', 'textdomain' ),
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

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_border',
			[
				'label' => __('Borda', 'custom-elementor'),
			]
		);

		$this->add_control(
			'submit_button_border_color',
			[
				'label' => esc_html__( 'Cor da Borda do Botão de Envio', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .form-wizard button.syonet-submit-btn' => 'border-color: {{VALUE}}!important;',
				],
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'default' => '',
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => esc_html__( 'Cor da Borda do Botão', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .form-wizard button.syonet-next-btn, {{WRAPPER}} .form-wizard button.syonet-prev-btn' => 'border-color: {{VALUE}}!important;',
				],
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'default' => '',
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Borda do Botão', 'textdomain' ),
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
					'{{WRAPPER}} .form-wizard button.syonet-next-btn, {{WRAPPER}} .form-wizard button.syonet-prev-btn, {{WRAPPER}} .form-wizard button.syonet-submit-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
						  '{{WRAPPER}} .form-wizard .phone-input input' => 'border-radius:0px!important;',
				],
				
			]
		);
		
		
			  $this->add_responsive_control(
			'button_border_width',
			[
				'label' => esc_html__( 'Borda do Botão', 'textdomain' ),
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
					'{{WRAPPER}} .form-wizard button.syonet-next-btn, {{WRAPPER}} .form-wizard button.syonet-prev-btn, {{WRAPPER}} .form-wizard button.syonet-submit-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				],
			]
		);
		
		


		$this->end_controls_tab();

		$this->end_controls_tabs();
		


		// Como não havia controles específicos para botões no código original,
		// estou criando uma seção vazia para que você possa adicionar os controles de botões
		// que desejar no futuro.
		
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
            echo __('<div style="width: 700px; height:300px; display:flex; flex-direction:column; justify-content:center; align-items:center; background-color: #f0f0f0; border-radius: 10px; padding: 20px; font-family: sans-serif; text-align: center;">
			
			<h2 style="font-size: 20px; font-weight: bold; color: #171717; ">Selecione um formulário.</h2>
			<p class="form-select-description"> No lado esquerdo, você pode selecionar um formulário já criado, e personalizá-lo com os estilos que desejar.<br><br> Caso queira alterar opções de empresas, eventos, etc, você pode fazer isso no painel do site. Clique abaixo para acessar o painel de opções.</p><br>
			<a onclick="window.open(\''.admin_url('admin.php?page=syonet-options').'\', \'_blank\');" style="background-color: #2563EB; color: #ffffff; padding: 10px 20px; border-radius: 5px; text-decoration: none; cursor: pointer;">Criar ou Editar opções do formulário</a>
			</div>', 'custom-elementor');
            return;
        }

       
        echo do_shortcode('[syonet_form id="' . esc_attr($settings['form_id']) . '" logo="' . esc_attr($settings['logo']['url']) . '" title="' . esc_attr($settings['form_title']) . '" submit_text="' . esc_attr($settings['form_submit_text']) . '" subtitle="' . esc_attr($settings['form_subtitle']) . '"]');
        
    }
}