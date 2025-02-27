<?php 

// Adiciona o Custom Post Type
add_action('init', 'mpf_register_syonet_form_post_type');
add_action('init', 'mpf_register_syonet_submissions');

function mpf_register_syonet_form_post_type() {
    $labels = array(
        'name'               => 'Formulários',
        'singular_name'      => 'Formulário',
        'menu_name'          => 'Formulários',
        'name_admin_bar'     => 'Formulário',
        'add_new'            => 'Adicionar Novo',
        'add_new_item'       => 'Adicionar Novo Formulário',
        'new_item'           => 'Novo Formulário',
        'edit_item'          => 'Editar Formulário',
        'view_item'          => 'Ver Formulário',
        'all_items'          => 'Todos os Formulários',
        'search_items'       => 'Buscar Formulários',
        'not_found'          => 'Nenhum formulário encontrado.',
        'not_found_in_trash' => 'Nenhum formulário encontrado na lixeira.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest' => false,
        'exclude_from_search' => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'syonet_form'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title')
    );

    register_post_type('syonet_form', $args);
}


function mpf_register_syonet_submissions() {
    $labels = array(
        'name'               => 'Envios',
        'singular_name'      => 'Envio',
        'menu_name'          => 'Envios',
        'name_admin_bar'     => 'Envio',
        'add_new'            => 'Adicionar Novo',
        'add_new_item'       => 'Adicionar Novo Envio',
        'new_item'           => 'Novo Envio',
        'edit_item'          => 'Editar Envio',
        'view_item'          => 'Ver Envio',
        'all_items'          => 'Todos os Envios',
        'search_items'       => 'Buscar Envios',
        'not_found'          => 'Nenhum envio encontrado.',
        'not_found_in_trash' => 'Nenhum envio encontrado na lixeira.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest' => false,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'syonet_submissions'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title')
    );

    register_post_type('syonet_submissions', $args);
}