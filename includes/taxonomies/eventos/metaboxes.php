<?php

// Adicionar campo de relacionamento ao adicionar termo
function add_event_type_relation_field($taxonomy) {
    $event_types = get_terms(array('taxonomy' => 'event_type', 'hide_empty' => false));
    
    // Inicializa a variável $selected_event_types como um array vazio
    $selected_event_types = array();

    // Verifica se estamos editando um termo existente
    if (isset($_GET['tag_ID'])) {
        $term_id = intval($_GET['tag_ID']);
        $selected_event_types = get_term_meta($term_id, 'related_event_types', true);
    }
}

function delete_event_type() {
    if (!isset($_POST['term_id']) || !is_numeric($_POST['term_id'])) {
        wp_send_json_error(array('message' => 'ID do Tipo de Evento inválido.'));
    }

    $term_id = intval($_POST['term_id']);
    $deleted = wp_delete_term($term_id, 'event_type');

    if (is_wp_error($deleted)) {
        wp_send_json_error(array('message' => 'Erro ao excluir o Tipo de Evento.'));
    }

    wp_send_json_success(array('message' => 'Tipo de Evento removido com sucesso.'));
}
add_action('wp_ajax_delete_event_type', 'delete_event_type');






function edit_event_type_relation_field($term, $taxonomy) {
    $selected_event_types = get_term_meta($term->term_id, 'related_event_types', true);
    $event_types = get_terms(array('taxonomy' => 'event_type', 'hide_empty' => false));

    // Obtém apenas os eventos relacionados ao termo atual
    if (!empty($selected_event_types) && is_array($selected_event_types)) {
        $event_types = get_terms(array(
            'taxonomy'   => 'event_type',
            'hide_empty' => false,
            'include'    => $selected_event_types,
        ));
    }
    ?>

    <tr class="form-field" style="display: flex; width: 100%;">
        
        <td class="form-field-wrapper-td" style="width: 100%; display: flex;  padding: 0px!important; ">
            <style>
                .term-description-wrap{
                    display: none!important;
                }
                tbody {
                    display: grid!important; /* Garante que o tbody se comporte como um grupo de linhas */
                }
            </style>
            <div class="syonet-form-wrapper form-wrapper" style="width: 100%;">
                <label for="related_event_types" class="syonet-form-field-label">Adicionar novo tipo de evento:</label>
                
                <div class="form-group" style="margin-bottom: 0px!important; grid-template-columns: 2.8fr 1fr!important;">

                    <div class="form-group-item ">
                        <input type="text" id="new_event_type" placeholder="Nome do tipo de evento">
                        <input type="hidden" id="event_group_id" value="<?php echo esc_attr($term->term_id); ?>">
                    </div>

                    <div class="form-group-item">
                        <button type="button" id="add_event_type" class="button syonet-button syonet-button-default"><?php _e('Adicionar Novo', 'my_domain'); ?></button>
                    </div>
                    
                    <p id="event_type_status" class="success-message form-group-item max-width" style="display: none; grid-column: span 2; width:auto; max-width:100%"><?php _e('Tipo de Evento adicionado!', 'my_domain'); ?></p>

                    
                    
                </div>
                
                <label for="related_event_types" class="syonet-form-field-label">Tipos de evento cadastrados:</label>
                <?php if(empty($event_types)) { ?> 

                    <style>
                        #related_event_types{
                            display: none;
                        }
                        #wpseo_meta{
                            display: none!important;
                        }
                    </style>

                <?php } ?>
                
                <div id="related_event_types" class="form-group" style="margin-top: 0px!important; margin-bottom: 0px!important;">
                    <?php foreach ($event_types as $event) : ?>
                    
                        <div id="event_wrapper_<?php echo esc_attr($event->term_id); ?>" class="form-group-item max-width" style="grid-template-columns: 2.8fr 1fr; align-items: center;">
                            <input type="text" name="related_event_types[]" id="event_<?php echo esc_attr($event->term_id); ?>" value="<?php echo esc_attr($event->name); ?>" readonly>
                            <button type="button" class="remove_event_type button syonet-button syonet-button-danger" data-term-id="<?php echo esc_attr($event->term_id); ?>">Remover</button>
                        
                        </div>
                    <?php endforeach; ?>
                    
                </div>
                <p class="info-message" style="width: auto; max-width: 100%;"> Insira exatamente como está no CRM.</p>
                
            </div>
        </td>
    </tr>

   

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var relatedEventTypesDiv = document.getElementById("related_event_types");

            // Adicionar novo evento
            document.getElementById("add_event_type").addEventListener("click", function() {
                var newEventType = document.getElementById("new_event_type").value.trim();
                if (newEventType === '') {
                    alert('Por favor, insira um nome para o Tipo de Evento.');
                    return;
                }

                var eventGroupId = document.getElementById("event_group_id").value;
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "<?php echo admin_url('admin-ajax.php'); ?>", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            var newEventDiv = document.createElement("div");
                            var relatedEventTypesDiv = document.getElementById("related_event_types");
                            newEventDiv.id = "event_wrapper_" + response.data.term_id;
                            newEventDiv.className = "form-group-item max-width";
                            newEventDiv.style.gridTemplateColumns = "2.8fr 1fr";
                            newEventDiv.style.alignItems = "center";
                            relatedEventTypesDiv.style.display = "grid";
                            // Substituindo as variáveis PHP com os valores de 'response.data.term_id' e 'response.data.term_name'
                            newEventDiv.innerHTML = '<input type="text" name="related_event_types[]" id="event_' + response.data.term_id + '" value="' + response.data.term_name + '" readonly> <button type="button" class="remove_event_type button syonet-button syonet-button-danger" data-term-id="' + response.data.term_id + '">Remover</button>';
                            
                            relatedEventTypesDiv.appendChild(newEventDiv);
                            document.getElementById("event_type_status").textContent = response.data.message;
                            document.getElementById("event_type_status").style.display = "grid";
                            document.getElementById("new_event_type").value = "";

                            attachRemoveEventHandlers();
                        } else {
                            alert(response.data.message);
                        }
                    }
                };
                xhr.send("action=add_event_type&term_name=" + encodeURIComponent(newEventType) + "&event_group_id=" + eventGroupId);
            });

            // Remover evento
            function attachRemoveEventHandlers() {
                document.querySelectorAll(".remove_event_type").forEach(function(button) {
                    button.addEventListener("click", function() {
                        var termId = this.getAttribute("data-term-id");
                        if (!confirm("Tem certeza que deseja excluir este Tipo de Evento?")) {
                            return;
                        }

                        var relatedEventTypesDiv = document.getElementById("related_event_types");
                        relatedEventTypesDiv.style.display = "none";

                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "<?php echo admin_url('admin-ajax.php'); ?>", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                var response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    document.getElementById("event_wrapper_" + termId).remove();
                                } else {
                                    alert(response.data.message);
                                }
                            }
                        };
                        xhr.send("action=delete_event_type&term_id=" + termId);
                    });
                });
            }

            attachRemoveEventHandlers();
        });
    </script>

    <?php
}
add_action('event_group_edit_form_fields', 'edit_event_type_relation_field', 10, 2);

function add_event_type_ajax() {
    // Verifica a permissão do usuário
    if (!current_user_can('manage_categories')) {
        wp_send_json_error(array('message' => __('Permissão negada.', 'my_domain')));
        return;
    }

    // Verifica se o nome do termo foi enviado
    if (!isset($_POST['term_name']) || empty($_POST['term_name'])) {
        wp_send_json_error(array('message' => __('O nome do tipo de evento é obrigatório.', 'my_domain')));
        return;
    }

    // Verifica se o ID do event_group foi enviado
    if (!isset($_POST['event_group_id']) || empty($_POST['event_group_id'])) {
        wp_send_json_error(array('message' => __('ID do grupo de evento não fornecido.', 'my_domain')));
        return;
    }

    $term_name = sanitize_text_field($_POST['term_name']);
    $event_group_id = intval($_POST['event_group_id']);

    // Tenta adicionar o termo
    $result = wp_insert_term($term_name, 'event_type');

    if (is_wp_error($result)) {
        wp_send_json_error(array('message' => $result->get_error_message()));
    } else {
        // Associa o novo event_type ao event_group atual
        $related_event_types = get_term_meta($event_group_id, 'related_event_types', true);
        if (!is_array($related_event_types)) {
            $related_event_types = array();
        }
        $related_event_types[] = $result['term_id'];
        update_term_meta($event_group_id, 'related_event_types', $related_event_types);

        wp_send_json_success(array(
            'message' => __('Tipo de Evento adicionado com sucesso!', 'my_domain'),
            'term_id' => $result['term_id'],
            'term_name' => $term_name
        ));
    }
}
add_action('wp_ajax_add_event_type', 'add_event_type_ajax');

// Salvar o campo personalizado "Tipo de Evento"
function save_event_type_field($term_id) {
    // Verifica o nonce para criação de termos
    if (isset($_POST['event_type_meta_new_nonce']) && !wp_verify_nonce($_POST['event_type_meta_new_nonce'], 'event_type_meta_new')) {
        return;
    }

    // Verifica o nonce para edição de termos
    if (isset($_POST['event_type_meta_edit_nonce']) && !wp_verify_nonce($_POST['event_type_meta_edit_nonce'], 'event_type_meta_edit')) {
        return;
    }

    // Salva o campo "Tipo de Evento"
    if (isset($_POST['event_type'])) {
        update_term_meta($term_id, 'event_type', sanitize_text_field($_POST['event_type']));
    }
}
add_action('edited_event_group', 'save_event_type_field', 10, 1);
add_action('created_event_group', 'save_event_type_field', 10, 1);

// Salvar relação entre event_group e event_type
function save_event_type_relation_field($term_id) {
    $current_event_types = get_term_meta($term_id, 'related_event_types', true);
    $current_event_types = is_array($current_event_types) ? $current_event_types : array();

    if (isset($_POST['related_event_types'])) {
        $new_event_types = array_map('intval', $_POST['related_event_types']);
        $event_types_to_remove = array_diff($current_event_types, $new_event_types);

        // Remove os tipos de evento que foram desmarcados
        foreach ($event_types_to_remove as $event_type_id) {
            wp_delete_term($event_type_id, 'event_type');
        }

        update_term_meta($term_id, 'related_event_types', $new_event_types);
    } else {
        // Se nenhum tipo de evento estiver selecionado, remove todos
        foreach ($current_event_types as $event_type_id) {
            wp_delete_term($event_type_id, 'event_type');
        }
        delete_term_meta($term_id, 'related_event_types');
    }
}
add_action('created_event_group', 'save_event_type_relation_field', 10, 1);
add_action('edited_event_group', 'save_event_type_relation_field', 10, 1);



function delete_related_event_types_on_slug_change($term_id, $tt_id, $taxonomy) {
    // Verifica se a taxonomia é 'event_group'
    if ($taxonomy !== 'event_group') {
        return;
    }

    // Obtém o termo atualizado
    $updated_term = get_term($term_id, $taxonomy);

    // Obtém o slug antigo do termo antes da atualização
    $old_slug = get_term_meta($term_id, '_old_slug', true);

    // Verifica se o slug foi alterado
    if ($old_slug === $updated_term->slug) {
        return; // Se o slug não mudou, não faz nada
    }

    // Obtém os event_types relacionados ao event_group
    $related_event_types = get_term_meta($term_id, 'related_event_types', true);

    // Se houver event_types relacionados, exclui cada um deles
    if (!empty($related_event_types) && is_array($related_event_types)) {
        foreach ($related_event_types as $event_type_id) {
            wp_delete_term($event_type_id, 'event_type');
        }
    }

    // Limpa os meta dados relacionados
    delete_term_meta($term_id, 'related_event_types');

    // Atualiza o slug antigo para o novo slug
    update_term_meta($term_id, '_old_slug', $updated_term->slug);
}
add_action('edited_term', 'delete_related_event_types_on_slug_change', 10, 3);

// Salva o slug antigo antes da atualização
function save_old_slug_before_update($term_id, $tt_id, $taxonomy) {
    if ($taxonomy !== 'event_group') {
        return;
    }

    // Obtém o termo antes da atualização
    $term = get_term($term_id, $taxonomy);

    // Salva o slug antigo como meta dado
    update_term_meta($term_id, '_old_slug', $term->slug);
}
add_action('edit_term', 'save_old_slug_before_update', 10, 3);