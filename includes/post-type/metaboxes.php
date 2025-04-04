<?php

if ( ! defined('ABSPATH')) exit;

// Adiciona o conteúdo após o título do post
add_action('edit_form_after_title', 'add_event_group_after_title');

function add_event_group_after_title($post) {
    // Verifica se o post type é 'formulario'
    if ($post->post_type !== 'syonet_form') {
        return;
    }

    // Recupera os grupos de eventos (event_group)
    $event_groups = get_terms(array(
        'taxonomy' => 'event_group',
        'hide_empty' => false,
    ));

    // Recupera o event_group relacionado ao post
    $selected_event_group = wp_get_object_terms($post->ID, 'event_group', array('fields' => 'ids'));
    $selected_event_group = !empty($selected_event_group) ? $selected_event_group[0] : '';

    // Recupera os event_types relacionados ao event_group selecionado
    $related_event_types = array();
    if ($selected_event_group) {
        $related_event_types = get_term_meta($selected_event_group, 'related_event_types', true);
        if (!is_array($related_event_types)) {
            $related_event_types = array();
        }
    }

    // Recupera o event_type relacionado ao post
    $selected_event_type = wp_get_object_terms($post->ID, 'event_type', array('fields' => 'ids'));
    $selected_event_type = !empty($selected_event_type) ? $selected_event_type[0] : '';

    // Verifica se o event_type selecionado está entre os relacionados
    if (!in_array($selected_event_type, $related_event_types)) {
        $selected_event_type = ''; // Reseta se não estiver relacionado
    }

    // Recupera a taxonomia de empresas (empresa_taxonomy)
    $empresa_taxonomies = get_terms(array(
        'taxonomy' => 'empresa',
        'hide_empty' => false,
    ));

    // Recupera as empresas relacionadas ao post
    $selected_empresas = wp_get_object_terms($post->ID, 'empresa', array('fields' => 'ids'));

    // Nonce para segurança
    wp_nonce_field('save_event_group_meta_box', 'event_group_meta_box_nonce');

    ?><div class="event-group-metabox">
    
    <div class="submitbox" id="submitpost">

<div id="minor-publishing">
    
    <div id="minor-publishing-actions"  style="margin-bottom:10px; padding:0!important; position:relative;">
        <div id="save-action"></div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.copy-event-code').addEventListener('click', function() {
                // Copiar o texto do evento para a área de transferência
                const shortcodeText = document.querySelector('.shortcode-text');
                navigator.clipboard.writeText(shortcodeText.textContent).then(function() {
                // Exibir o emoji de check
                const checkMark = document.getElementById('check-mark');
                checkMark.style.opacity = '1';
                
                // Ocultar o emoji após 1 segundo
                setTimeout(function() {
                    checkMark.style.opacity = '0';
                }, 2000);
                }).catch(function(err) {
                console.error('Erro ao copiar: ', err);
                });
            });
            });

        </script>


        <style> 
        .copy-event-code{
            cursor: pointer;
        }
        .copy-event-code{
        transition:background-color 0.1s linear;
        }
        .copy-event-code:hover{
            background-color:#2271b1!important;
            color:white!important;
            
            border-color:#2271b1!important;
        }
        
        
        </style>
        
        <div style="display:flex; flex-direction:row; gap:10px; align-items:center; width:fit-content; position:absolute; right:0px; top:0px;" class="syonet-form-wrapper">
        
        <span class="info-message copy-event-code" style="width:fit-content; position:relative; font-size:0.9em!important; padding: 0.55em 0.8em!important;">
        
        COPIAR SHORTCODE<b></b></span>
        
        <span id="check-mark" style="opacity:0; transition:opacity 0.3s ease; color: green; font-size: 20px; position:absolute!important; left:-40px; bottom:10px;">✔️</span> 
        
        </b>


        <span class="shortcode-text" style="display:none;">[formulario_shortcode id="<?php echo $post->ID; ?>"]</span>
        
        </div>
    

        <div class="empresa-container fade-in" style="display:flex; flex-direction:column; gap:10px; margin-bottom:20px; justify-content:flex-start; align-items:flex-start;">
                <label style="font-weight:bold;">Empresas:</label>
                <?php foreach ($empresa_taxonomies as $empresa) : ?>
                    <div>
                        <input type="checkbox" id="empresa_<?php echo esc_attr($empresa->term_id); ?>" name="empresa[]" value="<?php echo esc_attr($empresa->term_id); ?>" <?php checked(in_array($empresa->term_id, (array) $selected_empresas)); ?>>
                        <label for="empresa_<?php echo esc_attr($empresa->term_id); ?>"><?php echo esc_html($empresa->name); ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        <div class="event-group-container fade-in " style="display:grid; grid-template-columns: 0.5fr 1fr; gap:10px; align-items:center;">
            <label for="event_group" style="font-weight:bold;">Grupo de Evento:</label>
            <select id="event_group" name="event_group" style="flex-grow: 1;">
                <option value="">Selecione um grupo de evento</option>
                <?php foreach ($event_groups as $group) : ?>
                    <option value="<?php echo esc_attr($group->term_id); ?>" <?php selected($selected_event_group, $group->term_id); ?>>
                        <?php echo esc_html($group->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="event_type_wrapper" style="display:<?php echo ($selected_event_group && !empty($related_event_types)) ? 'grid' : 'none'; ?>; grid-template-columns: 0.5fr 1fr; gap:10px; align-items:center; margin-top:10px;" class="fade-in">
            <label for="event_type" style="font-weight:bold;">Tipo de Evento:</label>
            <select id="event_type" name="event_type" style="flex-grow: 1;">
                <?php if ($selected_event_group && !empty($related_event_types)) : ?>
                    <?php foreach ($related_event_types as $event_type_id) : ?>
                        <?php $event_type = get_term($event_type_id, 'event_type'); ?>
                        <?php if ($event_type && !is_wp_error($event_type)) : ?>
                            <option value="<?php echo esc_attr($event_type->term_id); ?>" <?php selected($selected_event_type, $event_type->term_id); ?>>
                                <?php echo esc_html($event_type->name); ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="thankYouPage-wrapper" style="display:grid; grid-template-columns: 0.5fr 1fr; gap:10px; align-items:center; margin-top:10px;">

            <label for="thankYouPage" style="font-weight:bold;">Página de obrigado</label>

            <input type="text" name="thankYouPage" id="thankYouPage" placeholder="Insira o link aqui" style="text-align: left;" value="<?php echo get_post_meta( $post->ID, 'thankYouPage', true ) ?? ''; ?>">
            <div style="grid-column:span 2; text-align:left">Para corresponder ao URL do site, comece a partir do caminho de navegação <br><br>
            (https://site.com/minha-pagina == /minha-pagina)</div>

        </div>

        <p id="event_type_message" style="color: red; display: none; text-align:left;"></p>

        
    </div>

    <div id="major-publishing-actions">
        <div id="delete-action">
            <a class="submitdelete deletion" href="http://localhost/projetos/wordpress/wp-admin/post.php?post=<?php echo $post->ID; ?>&amp;action=trash&amp;_wpnonce=<?php echo wp_create_nonce('trash-post_' . $post->ID); ?>">Mover para lixeira</a>
        </div>

        <div id="publishing-action">
            <span class="spinner"></span>
            <input name="original_publish" type="hidden" id="original_publish" value="<?php echo $post->post_status == 'publish' ? 'Atualizar' : 'Publicar'; ?>">
            <input type="submit" name="<?php echo $post->post_status == 'publish' ? 'update' : 'publish'; ?>" id="publish" class="button button-primary button-large" value="<?php echo $post->post_status == 'publish' ? 'Atualizar' : 'Publicar'; ?>">
        </div>
        <div class="clear"></div>
    </div>
</div>
</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var eventGroupSelect = document.getElementById('event_group');
        var eventTypeWrapper = document.getElementById('event_type_wrapper');
        var eventTypeSelect = document.getElementById('event_type');
        var eventTypeMessage = document.getElementById('event_type_message');

        // Função para carregar os event_types relacionados
        function loadRelatedEventTypes(eventGroupId, selectedEventType) {
            if (eventGroupId) {
                eventTypeSelect.disabled = true;
                eventTypeSelect.innerHTML = '<option value="">Carregando...</option>';
               
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '<?php echo admin_url('admin-ajax.php'); ?>', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            eventTypeSelect.innerHTML = '';
                            eventTypeSelect.disabled = false;

                            if (response.data.event_types.length > 0) {
                                response.data.event_types.forEach(function(eventType) {
                                    var option = document.createElement('option');
                                    option.value = eventType.term_id;
                                    option.textContent = eventType.name;
                                    // Define o event_type selecionado, se houver
                                    if (selectedEventType && eventType.term_id == selectedEventType) {
                                        option.selected = true;
                                    }
                                    eventTypeSelect.appendChild(option);
                                });

                                eventTypeMessage.style.display = 'none';
                                eventTypeWrapper.style.display = 'grid';
                                eventTypeSelect.disabled = false;
                            } else {
                                eventTypeMessage.textContent = response.data.message;
                                eventTypeMessage.style.display = 'block';
                                eventTypeWrapper.style.display = 'none';
                                eventTypeSelect.disabled = false;
                            }

                            if (response.data.hide_event_type_wrapper) {
                                eventTypeWrapper.style.display = 'none';
                                eventTypeSelect.disabled = false;
                            }
                        } else {
                            alert('Erro ao carregar os tipos de evento.');
                        }
                    }
                };
                xhr.send('action=get_related_event_types&event_group_id=' + eventGroupId);
            } else {
                eventTypeWrapper.style.display = 'none';
                eventTypeMessage.style.display = 'none';
            }
        }

        // Verifica o event_group selecionado ao carregar a página
        var initialEventGroupId = eventGroupSelect.value;
        var initialEventType = '<?php echo esc_js($selected_event_type); ?>'; // Passa o event_type selecionado
        if (initialEventGroupId) {
            loadRelatedEventTypes(initialEventGroupId, initialEventType);
        }

        // Adiciona o listener para mudanças no select de event_group
        eventGroupSelect.addEventListener('change', function() {
            loadRelatedEventTypes(this.value, ''); // Reseta o event_type ao mudar o event_group
        });
    });
</script>

    <style>
        #postbox-container-1 {
            display: none;
        }
         #poststuff #post-body.columns-2 {
            margin-right: 0px;
            overflow: hidden;
        }
        .event-group-metabox {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color:rgb(255, 255, 255);
            max-width: 400px;
        }

                /* Define a animação fade-in */
        @keyframes fadeIn {
            from {
                opacity: 0; /* Começa invisível */
            }
            to {
                opacity: 1; /* Termina visível */
            }
        }

        /* Aplica a animação a um elemento */
        .fade-in {
            animation: fadeIn 0.2s ease-in; /* Duração de 1 segundo e efeito de easing */
        }
        label {
            text-align:left;
        } 
    </style>
    <?php
}

// Função AJAX para carregar os event_types relacionados
add_action('wp_ajax_get_related_event_types', 'get_related_event_types_ajax');

function get_related_event_types_ajax() {
    if (!isset($_POST['event_group_id']) || empty($_POST['event_group_id'])) {
        wp_send_json_error(array('message' => 'ID do grupo de evento não fornecido.'));
    }

    $event_group_id = intval($_POST['event_group_id']);
    $related_event_types = get_term_meta($event_group_id, 'related_event_types', true);

    // Verifica se há event_types relacionados
    if (empty($related_event_types) || !is_array($related_event_types)) {
        wp_send_json_success(array(
            'message' => 'Adicione tipos de evento a esse grupo.',
            'event_types' => array(), // Retorna um array vazio
            'hide_event_type_wrapper' => true, // Adiciona um sinalizador para ocultar o wrapper
        ));
    }

    $event_types = array();
    foreach ($related_event_types as $event_type_id) {
        $event_type = get_term($event_type_id, 'event_type');
        if ($event_type && !is_wp_error($event_type)) {
            $event_types[] = array(
                'term_id' => $event_type->term_id,
                'name' => $event_type->name,
            );
        }
    }

    wp_send_json_success(array(
        'message' => '',
        'event_types' => $event_types
    ));
}

// Salva os dados do metabox
add_action('save_post', 'save_event_group_meta_box');

function save_event_group_meta_box($post_id) {
    // Verifica o nonce para segurança
    if (!isset($_POST['event_group_meta_box_nonce']) || !wp_verify_nonce($_POST['event_group_meta_box_nonce'], 'save_event_group_meta_box')) {
        return;
    }

    // Verifica se o usuário tem permissão para editar o post
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Salva e relaciona o event_group selecionado ao post
    if (isset($_POST['event_group'])) {
        $selected_event_group = intval($_POST['event_group']); // Garante que seja um inteiro

        // Relaciona o novo event_group ao post (substitui os existentes)
        if ($selected_event_group > 0) { // Verifica se o ID é válido
            wp_set_object_terms($post_id, [$selected_event_group], 'event_group', false); // Passa um array de IDs
        } else {
            // Se nenhum event_group for selecionado, remove todos os termos
            wp_set_object_terms($post_id, array(), 'event_group');
        }
    }

    // Salva e relaciona o event_type selecionado ao post
    if (isset($_POST['event_type'])) {
        $selected_event_type = intval($_POST['event_type']); // Garante que seja um inteiro

        // Relaciona o novo event_type ao post (substitui os existentes)
        if ($selected_event_type > 0) { // Verifica se o ID é válido
            wp_set_object_terms($post_id, [$selected_event_type], 'event_type', false); // Passa um array de IDs
        } else {
            // Se nenhum event_type for selecionado, remove todos os termos
            wp_set_object_terms($post_id, array(), 'event_type');
        }
    }

    // Salva e relaciona a página de obrigado
    if (!empty($_POST['thankYouPage'])) {
        $thankYouPage = sanitize_text_field($_POST['thankYouPage']); // Garante que seja uma string segura
        update_post_meta($post_id, 'thankYouPage', $thankYouPage); // Salva como string no banco
    }


    // Salva e relaciona a empresa_taxonomy selecionada ao post
    if (isset($_POST['empresa'])) {
        $selected_empresas = array_map('intval', $_POST['empresa']); // Garante que seja um array de inteiros

        // Relaciona as empresas ao post (substitui os existentes)
        wp_set_object_terms($post_id, $selected_empresas, 'empresa', false); // Passa um array de IDs
    } else {
        // Se nenhuma empresa for selecionada, remove todos os termos
        wp_set_object_terms($post_id, array(), 'empresa');
    }
}
