<?php 

add_action('edit_form_after_title', 'mpf_render_syonet_submissions_metabox');

// Renderiza o metabox
function mpf_render_syonet_submissions_metabox($post) {

    // Verifica se o post type é 'syonet_submissions'
    if ($post->post_type !== 'syonet_submissions') {
        return;
    }

    // Obtém os valores atuais dos campos
    $fields = [
        'name', 'email', 'ddi', 'ddd', 'phone', 'phone_type', 'document',
        'document_type', 'person_type', 'contact_preference_type', 'country',
        'state', 'city', 'postcode', 'neighborhood', 'address_number',
        'address', 'company_id', 'model', 'source', 'media', 'comment',
        'days_to_update', 'rules', 'additional_fields', 'json_data', 'api_response', 'company'
    ];

    // Obtém os valores salvos
    $values = [];
    foreach ($fields as $field) {
        $values[$field] = get_post_meta($post->ID, $field, true);
    }

    wp_nonce_field('mpf_save_syonet_submissions_metabox', 'mpf_syonet_submissions_metabox_nonce');

    // Renderiza os campos
    ?>
    <div class="syonet-form-wrapper form-wrapper">
    <div class="form-group" style="margin-bottom: 0px!important;">
        <div class="form-group-item">

            <?php

            $api_response = $values['api_response'] ?? '';
            $api_response = trim($api_response, '""');
            $api_response = json_decode($api_response);

            if (isset($api_response->ok) && $api_response->ok == 1) {
                echo '<span class="success-message">Código do evento gerado com sucesso!</span>';
            } else {
                echo '<span class="error-message">Ocorreu um erro ao gerar o evento.</span>';
            }
            ?>

        </div>
        <div class="form-group-item">

            <?php

            $api_response = $values['api_response'] ?? '';
            $api_response = trim($api_response, '""');
            $api_response = json_decode($api_response);

            if (isset($api_response->ok) && $api_response->ok == 1) {
                echo '<style> 
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
                
                <div style="display:flex; flex-direction:row; gap:10px; align-items:center;">
                
                <span class="info-message copy-event-code" style="width:fit-content; position:relative;">
                
                COPIAR:<b style="font-family:Helvetica!important; font-weight:800!important;">  ' . esc_html($api_response->msg) . '</b></span>
                
                <span id="check-mark" style="opacity:0; transition:opacity 0.3s ease; color: green; font-size: 24px; postion:absolute!important; right:-15px">✔️</span> 
                
                </b>
                
                </div>';
            } else {
                echo '<span class="info-message">Mensagem do sistema: ' . esc_html($api_response->msg) . '</span>';
            }
            ?>

        </div>
        <script>
  document.querySelector('.copy-event-code').addEventListener('click', function() {
    // Copiar o texto do evento para a área de transferência
    const text = this.textContent || this.innerText;
    const textreplace = text.replace('EVENTO:', '');
    navigator.clipboard.writeText(textreplace).then(function() {
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
</script>
    </div>
    </div>
    
    
    <div class="syonet-form-wrapper form-wrapper">

        <div class="form-group">
            <div class="form-group-item">
                <label for="name">Nome do Contato:</label>
                <input type="text" id="name" name="name" value="<?php echo esc_attr($values['name']); ?>" readonly style="width: 100%;" />
            </div>
            <div class="form-group-item">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" value="<?php echo esc_attr($values['email']); ?>" readonly style="width: 100%;" />
            </div>
        
            <div class="form-group-item">
                <label for="ddi">DDI:</label>
                <input type="text" id="ddi" name="ddi" value="<?php echo esc_attr($values['ddi']); ?>" readonly style="width: 100%;" />
            </div>
            <div class="form-group-item">
                <label for="ddd">DDD:</label>
                <input type="text" id="ddd" name="ddd" value="<?php echo esc_attr($values['ddd']); ?>" readonly style="width: 100%;" />
            </div>
        
    
            <div class="form-group-item">
                <label for="phone">Telefone:</label>
                <input type="text" id="phone" name="phone" value="<?php echo esc_attr($values['phone']); ?>" readonly style="width: 100%;" />
            </div>
            <div class="form-group-item">
                <label for="phone_type">Tipo de Telefone:</label>
                <input type="text" id="phone_type" name="phone_type" value="<?php echo esc_attr($values['phone_type']); ?>" readonly style="width: 100%;" />
            </div>
        
        
            <div class="form-group-item">
                <label for="document">Documento:</label>
                <input type="text" id="document" name="document" value="<?php echo esc_attr($values['document']); ?>" readonly style="width: 100%;" />
            </div>
            <div class="form-group-item">
                <label for="document_type">Tipo de Documento:</label>
                <input type="text" id="document_type" name="document_type" value="<?php echo esc_attr($values['document_type']); ?>" readonly style="width: 100%;" />
            </div>
        
            <div class="form-group-item">
                <label for="person_type">Tipo de Pessoa:</label>
                <input type="text" id="person_type" name="person_type" value="<?php echo esc_attr($values['person_type']); ?>" readonly style="width: 100%;" />
            </div>
            <div class="form-group-item">
                <label for="contact_preference_type">Tipo de Preferência de Contato:</label>
                <input type="text" id="contact_preference_type" name="contact_preference_type" value="<?php echo esc_attr($values['contact_preference_type']); ?>" readonly style="width: 100%;" />
            </div>
        
            
            <div class="form-group-item">
                <label for="company_id">ID da Empresa:</label>
                <input type="text" id="company_id" name="company_id" value="<?php echo esc_attr($values['company_id']); ?>, <?php echo esc_attr($values['company']); ?>" readonly style="width: 100%;" />
            </div>
    
            <div class="form-group-item">
                <label for="model">Modelo/Marca do veículo:</label>
                <input type="text" id="model" name="model" readonly value="<?php echo esc_attr($values['model']); ?>" style="width: 100%;" />
            </div>
            <div class="form-group-item">
                <label for="source">Origem:</label>
                <input type="text" id="source" name="source" readonly value="<?php echo esc_attr($values['source']); ?>" style="width: 100%;" />
            </div>
    
            <div class="form-group-item">
                <label for="media">Mídia:</label>
                <input type="text" id="media" name="media" readonly value="<?php echo esc_attr($values['media']); ?>" style="width: 100%;" />
            </div>
            
        
            <div class="form-group-item">
                <label for="days_to_update">Atraso em dias para atualizar no CRM:</label>
                <input type="text" id="days_to_update" name="days_to_update" readonly value="<?php echo esc_attr($values['days_to_update']); ?>" style="width: 100%;" />
            </div>
            <div class="form-group-item">
                <label for="rules">Atualizar contato:</label>
                <textarea id="rules" name="rules" rows="3" style="width: 100%;" readonly><?php
                if ($values['rules'] == TRUE) {
                    echo 'Sim';
                } else {
                    echo 'Não';
                }
                ?></textarea>
            </div>
            <div class="form-group-item max-width">
                <label for="comment">Comentário:</label>
                <textarea id="comment" name="comment" rows="3" style="width: 100%;" readonly><?php echo esc_textarea($values['comment']); ?></textarea>
            </div>    
        </div>
    </div>

    <div class="syonet-form-wrapper form-wrapper">
        <div class="form-group">
        <div class="form-group-item">
                <label for="country">País:</label>
                <input type="text" id="country" name="country" value="<?php echo esc_attr($values['country']); ?>" readonly style="width: 100%;" />
            </div>
            <div class="form-group-item">
                <label for="state">Estado:</label>
                <input type="text" id="state" name="state" value="<?php echo esc_attr($values['state']); ?>" readonly style="width: 100%;" />
            </div>
        
            <div class="form-group-item">
                <label for="city">Cidade:</label>
                <input type="text" id="city" name="city" value="<?php echo esc_attr($values['city']); ?>" readonly style="width: 100%;" />
            </div>
            <div class="form-group-item">
                <label for="postcode">CEP:</label>
                <input type="text" id="postcode" name="postcode" value="<?php echo esc_attr($values['postcode']); ?>" readonly style="width: 100%;" />
            </div>
        
            <div class="form-group-item">
                <label for="neighborhood">Bairro:</label>
                <input type="text" id="neighborhood" name="neighborhood" value="<?php echo esc_attr($values['neighborhood']); ?>" readonly style="width: 100%;" />
            </div>
            <div class="form-group-item">
                <label for="address_number">Número:</label>
                <input type="text" id="address_number" name="address_number" value="<?php echo esc_attr($values['address_number']); ?>" readonly style="width: 100%;" />
            </div>
    
            <div class="form-group-item">
                <label for="address">Endereço:</label>
                <input type="text" id="address" name="address" value="<?php echo esc_attr($values['address']); ?>" readonly style="width: 100%;" />
            </div>
        </div>
    </div>

    
    <div class="syonet-form-wrapper form-wrapper">
        <div class="form-group">
        <div class="form-group-item max-width">
            <label for="api_response">Resposta da API:</label>
            <input readonly type="text" id="api_response" name="api_response" value="<?php echo esc_attr($values['api_response']); ?>" style="width: 100%;" />
        </div>

        <div class="form-group-item max-width">
            <label for="json_data">Formato JSON:</label>
            <textarea id="json_data" name="json_data" style="width: 100%;" readonly><?php 
                // Decodifica o JSON e o re-encoda de forma legível
                $json_data = json_decode($values['json_data'], true);
                echo esc_textarea(json_encode($json_data, JSON_PRETTY_PRINT));
            ?></textarea>
        </div>
    </div>

    </div>

    <?php
}

// Salva os dados do metabox
add_action('save_post', 'mpf_save_syonet_submissions_metabox');

function mpf_save_syonet_submissions_metabox($post_id) {
    // Verifica o nonce
    if (!isset($_POST['mpf_syonet_submissions_metabox_nonce']) || !wp_verify_nonce($_POST['mpf_syonet_submissions_metabox_nonce'], 'mpf_save_syonet_submissions_metabox')) {
        return;
    }

    // Evita salvamento automático
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Verifica permissões do usuário
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Lista de campos
    $fields = [
        'name', 'email', 'ddi', 'ddd', 'phone', 'phone_type', 'document',
        'document_type', 'person_type', 'contact_preference_type', 'country',
        'state', 'city', 'postcode', 'neighborhood', 'address_number',
        'address', 'company_id', 'model', 'source', 'media', 'comment',
        'days_to_update', 'rules', 'additional_fields', 'json_data', 'api_response'
    ];

    // Salva os dados
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = $_POST[$field];

            

            update_post_meta($post_id, $field, $value);
        }
    }
}
