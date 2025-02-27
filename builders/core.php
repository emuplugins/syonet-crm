<?php

if ( ! defined('ABSPATH')) exit;

// Certifique-se de que Elementor está ativado
function custom_elementor_widget_load() {
    
    if (!did_action('elementor/loaded')) {
        return;
    }

    // Verifique se a classe Widget_Base existe
    if ( ! class_exists('Elementor\Widget_Base') ) {
        return; // Se não existir, não continue
    }

    require_once FORMULARIO_SYONET_DIR . '/builders/elementor/widgets/widget.php';
}
add_action('plugins_loaded', 'custom_elementor_widget_load');


// Registrar o widget no Elementor
function register_custom_elementor_widgets($widgets_manager) {
    require_once FORMULARIO_SYONET_DIR . '/builders/elementor/widgets/widget.php';

    $widgets_manager->register(new \Custom_Elementor\SyoNet_Form_Widget());
}
add_action('elementor/widgets/register', 'register_custom_elementor_widgets');






function syonet_form_register_editor_styles() {
    wp_enqueue_style( 'syonet-form-style-search-country', FORMULARIO_SYONET_URL . 'assets/css/search-country.css' );
    wp_enqueue_style( 'syonet-form-style', FORMULARIO_SYONET_URL . 'assets/css/style.css');
}
add_action( 'wp_enqueue_scripts', 'syonet_form_register_editor_styles' );

function syonet_form_enqueue_editor_styles() {
    
    wp_enqueue_style( 'syonet-form-style-search-country' );
    wp_enqueue_style( 'syonet-form-style' );

}
add_action( 'elementor/editor/after_enqueue_styles', 'syonet_form_enqueue_editor_styles' );







function syonet_form_elementor_scripts() {
    wp_enqueue_script('form-script', FORMULARIO_SYONET_URL . 'assets/js/script.js', array('jquery'), null, true);
    wp_enqueue_script('country-flags-script', FORMULARIO_SYONET_URL . 'assets/js/country-flags.js', array('jquery'), null, true);
    wp_enqueue_script('autopreenchimento-cep-script', FORMULARIO_SYONET_URL . 'assets/js/autopreenchimento-cep.js', array('jquery'), null, true);
    wp_enqueue_script('input-mask-script', FORMULARIO_SYONET_URL . 'assets/js/input-mask.js', array('jquery'), null, true);
}
add_action( 'wp_enqueue_scripts', 'syonet_form_elementor_scripts' );

function syonet_form_elementor_editor_scripts() {
    wp_enqueue_script( 'form-script' );
    wp_enqueue_script( 'country-flags-script' );
    wp_enqueue_script( 'autopreenchimento-cep-script' );
    wp_enqueue_script( 'input-mask-script' );
}
add_action( 'elementor/editor/after_enqueue_scripts', 'syonet_form_elementor_editor_scripts' );