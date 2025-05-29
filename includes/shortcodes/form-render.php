<?php

if ( ! defined('ABSPATH')) exit;

function syonet_form_shortcode($atts) {
    
    $atts = shortcode_atts(array(
        'id' => '',
        'logo' => '',
        'title' => '',
        'submit_text' => '',
        'subtitle' => ''
        'veiculo' => ''
    ), $atts);

    if (empty($atts['id'])) {
        return 'ID do formulário não fornecido.';
    }

    $veiculo = $atts['veiculo'];
    
    $form_post = get_post($atts['id']);

    if (!$form_post || $form_post->post_type !== 'syonet_form') {
        return 'Formulário não encontrado.';
    }

    ob_start();
    ?>
    <?php require_once FORMULARIO_SYONET_DIR . 'includes/shortcodes/templates/form.php'; ?>
    <?php
    return ob_get_clean();
}

add_shortcode('syonet_form', 'syonet_form_shortcode');
