<?php

// Adicionar campo personalizado "ID da Empresa" ao adicionar termo
function add_empresa_id_field($taxonomy) {
    wp_nonce_field('empresa_meta_new', 'empresa_meta_new_nonce');
    ?>
    <div class="form-field">
        <label for="empresa_id"><?php _e('ID da Empresa', 'my_domain'); ?></label>
        <input name="empresa_id" id="empresa_id" type="text" value="" />
        <p class="description"><?php _e('Insira o ID da empresa.', 'my_domain'); ?></p>
    </div>
    <?php
}
add_action('empresa_add_form_fields', 'add_empresa_id_field', 10, 1);

// Adicionar campo personalizado "ID da Empresa" ao editar termo
function edit_empresa_id_field($term, $taxonomy) {
    $empresa_id = get_term_meta($term->term_id, 'empresa_id', true);
    wp_nonce_field('empresa_meta_edit', 'empresa_meta_edit_nonce');
    ?>
    <tr class="form-field">
        <th><label for="empresa_id"><?php _e('ID da Empresa', 'my_domain'); ?></label></th>
        <td>
            <input name="empresa_id" id="empresa_id" type="text" value="<?php echo esc_attr($empresa_id); ?>" />
            <p class="description"><?php _e('Insira o ID da empresa.', 'my_domain'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('empresa_edit_form_fields', 'edit_empresa_id_field', 10, 2);

// Salvar o campo personalizado "ID da Empresa"
function save_empresa_id_field($term_id) {
    // Verifica o nonce para criação de termos
    if (isset($_POST['empresa_meta_new_nonce']) && !wp_verify_nonce($_POST['empresa_meta_new_nonce'], 'empresa_meta_new')) {
        return;
    }

    // Verifica o nonce para edição de termos
    if (isset($_POST['empresa_meta_edit_nonce']) && !wp_verify_nonce($_POST['empresa_meta_edit_nonce'], 'empresa_meta_edit')) {
        return;
    }

    // Salva o campo "ID da Empresa"
    if (isset($_POST['empresa_id'])) {
        update_term_meta($term_id, 'empresa_id', sanitize_text_field($_POST['empresa_id']));
    }
}
add_action('edited_empresa', 'save_empresa_id_field', 10, 1);
add_action('created_empresa', 'save_empresa_id_field', 10, 1);

// Adicionar coluna "ID da Empresa" na listagem de termos
function add_empresa_id_column($columns) {
    $columns['empresa_id'] = __('ID da Empresa', 'my_domain');
    return $columns;
}
add_filter('manage_edit-empresa_columns', 'add_empresa_id_column', 10, 1);

// Exibir o valor do campo "ID da Empresa" na coluna
function display_empresa_id_column($content, $column_name, $term_id) {
    if ($column_name === 'empresa_id') {
        $empresa_id = get_term_meta($term_id, 'empresa_id', true);
        echo esc_attr($empresa_id);
    }
}
add_action('manage_empresa_custom_column', 'display_empresa_id_column', 10, 3);

// Tornar a coluna "ID da Empresa" ordenável
function make_empresa_id_column_sortable($columns) {
    $columns['empresa_id'] = 'empresa_id';
    return $columns;
}
add_filter('manage_edit-empresa_sortable_columns', 'make_empresa_id_column_sortable', 10, 1);

// Adicionar campo "ID da Empresa" ao formulário rápido de adição de termos
function add_empresa_id_field_to_quick_add() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Adiciona o campo "ID da Empresa" no início do formulário rápido
            $('#empresa-add').prepend(`
                    <label for="newempresa_id"></label>
                    <input type="number" name="newempresa_id" id="newempresa_id" placeholder="ID da Empresa" class="form-required" value="" aria-required="true" style="max-width: 10%!important;">
                    <style>
                        #empresa-add input {
                            vertical-align: middle!important;
                        }
                            #wpseo_meta{
                            display: none!important;
                        }
                        
                    </style>
            `);

            // Adiciona o placeholder ao campo "Nome da empresa"
            $('#newempresa').attr('placeholder', 'Nome da empresa');

            // Intercepta o envio do formulário rápido
            $(document).on('click', '#empresa-add-submit', function(e) {
                e.preventDefault();

                var empresaId = $('#newempresa_id').val();
                var termName = $('#newempresa').val();
                var termParent = $('#newempresa_parent').val();
                var nonce = '<?php echo wp_create_nonce('add_empresa_term_nonce'); ?>';

                // Envia os dados via AJAX
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'add_empresa_term', // Ação personalizada
                        term_name: termName,
                        term_parent: termParent,
                        empresa_id: empresaId,
                        _ajax_nonce: nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Recarrega a lista de termos
                            $('#empresachecklist').html(response.data);
                            // Limpa o formulário
                            $('#newempresa').val('');
                            $('#newempresa_id').val('');
                            $('#newempresa_parent').val('-1');
                        } else {
                            alert('Erro ao adicionar o termo: ' + response.data);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Erro na requisição AJAX: ' + error);
                    }
                });
            });
        });
    </script>
    <?php
}
add_action('admin_footer', 'add_empresa_id_field_to_quick_add');

// Processar o envio do formulário rápido via AJAX
function handle_add_empresa_term() {
    // Verifica a nonce
    if (!isset($_POST['_ajax_nonce']) || !wp_verify_nonce($_POST['_ajax_nonce'], 'add_empresa_term_nonce')) {
        wp_send_json_error('Nonce inválido.');
    }

    // Verifica permissões
    if (!current_user_can('manage_categories')) {
        wp_send_json_error('Você não tem permissão para realizar esta ação.');
    }

    // Valida os dados
    if (empty($_POST['term_name'])) {
        wp_send_json_error('O nome do termo é obrigatório.');
    }

    $term_name = sanitize_text_field($_POST['term_name']);
    $term_parent = intval($_POST['term_parent']);
    $empresa_id = sanitize_text_field($_POST['empresa_id']);

    // Adiciona o termo
    $term = wp_insert_term($term_name, 'empresa', ['parent' => $term_parent]);

    if (!is_wp_error($term)) {
        // Salva o "ID da Empresa" no termo criado
        update_term_meta($term['term_id'], 'empresa_id', $empresa_id);
        wp_send_json_success('Termo adicionado com sucesso.');
    } else {
        wp_send_json_error('Erro ao adicionar o termo: ' . $term->get_error_message());
    }
}
add_action('wp_ajax_add_empresa_term', 'handle_add_empresa_term');


function inline_taxonomy_styles() {
    if (is_tax('empresa', 'event_type', 'event_group')) {
        $custom_css = "
            .fixed .column-posts {
                width: 180px!important;
            }
        ";
        wp_add_inline_style('custom-taxonomy-style', $custom_css);
    }
}
add_action('wp_enqueue_scripts', 'inline_taxonomy_styles');