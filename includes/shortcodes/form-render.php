<?php

if ( ! defined('ABSPATH')) exit;

function syonet_form_shortcode($atts) {
    // Extrai o ID do post a partir dos atributos do shortcode
    $atts = shortcode_atts(array(
        'id' => '',
        'logo' => '',
        'title' => '',
        'submit_text' => '',
        'subtitle' => ''
    ), $atts);

    // Verifica se o ID do post foi fornecido
    if (empty($atts['id'])) {
        return 'ID do formulário não fornecido.';
    }

    // Obtém o post do tipo 'formulario'
    $form_post = get_post($atts['id']);


    // Verifica se o post existe e é do tipo 'formulario'
    if (!$form_post || $form_post->post_type !== 'syonet_form') {
        return 'Formulário não encontrado.';
    }

    // Renderiza o formulário (você pode personalizar isso conforme necessário)
    ob_start(); // Inicia o buffer de saída
    ?>
    <?php require_once FORMULARIO_SYONET_DIR . 'includes/shortcodes/templates/form.php'; ?>
    <?php
    return ob_get_clean(); // Retorna o conteúdo do buffer
}

// Adiciona o shortcode
add_shortcode('syonet_form', 'syonet_form_shortcode');
