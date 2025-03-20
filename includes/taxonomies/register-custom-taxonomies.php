<?php

if ( ! defined('ABSPATH')) exit;

// Registrar taxonomias personalizadas com labels dinâmicos
function register_custom_taxonomies() {
    $taxonomies = [
        'event_type'  => 'Tipo de Evento',
        'event_group' => 'Grupo de Evento',
        'empresa'     => 'Empresa',
    ];

    foreach ($taxonomies as $slug => $singular_label) {
        $plural_label = $singular_label . 's';

        $labels = [
            'name'              => $plural_label,
            'singular_name'     => $singular_label,
            'menu_name'         => $plural_label,
            'all_items'         => 'Todos ' . $plural_label,
            'edit_item'         => 'Editar ' . $singular_label,
            'view_item'         => 'Ver ' . $singular_label,
            'update_item'       => 'Atualizar ' . $singular_label,
            'add_new_item'      => 'Adicionar ' . $singular_label,
            'new_item_name'     => 'Novo Nome de ' . $singular_label,
            'parent_item'       => 'Grupo de ' . $plural_label,
            'parent_item_colon' => 'Grupo de ' . $plural_label . ':',
            'search_items'      => 'Buscar ' . $plural_label,
            'not_found'         => 'Nenhum ' . strtolower($singular_label) . ' encontrado',
            
        ];

        // Configurações padrão para todas as taxonomias
        $args = [
            'labels'            => $labels,
            'hierarchical'      => false,
            'public'            => true,
            'show_in_rest'      => true,
            'show_in_quick_edit' => true,
            'meta_box_cb'      => false, // Remove o metabox padrão
        ];

        // Configurações específicas para a taxonomia 'event_type'
        if ($slug === 'event_type') {
            $args['show_ui'] = false; // Oculta completamente a taxonomia no painel administrativo
        }

        // Registrar a taxonomia
        register_taxonomy($slug, 'formulario', $args);
    }
}
add_action('init', 'register_custom_taxonomies');
