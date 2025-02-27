<?php

if ( ! defined('ABSPATH')) exit;

// Adiciona o menu principal e submenu
function syonet_add_options_page() {
    // Menu principal "Formulário Syonet"
    add_menu_page(
        'Configurações do Formulário', // Título da página
        'Formulário Syonet', // Nome no menu
        'manage_options', // Permissão necessária
        'syonet-options', // Slug do menu
        'syonet_render_options_page', // Função de callback para renderizar a página
        'dashicons-forms', // Ícone do menu
        25 // Posição no menu
    );

    // Submenu "Configurações" (para manter o clique no menu principal funcional)
    add_submenu_page(
        'syonet-options', // Slug do menu pai
        'Configurações do Formulário', // Título da página
        'Configurações', // Nome no submenu
        'manage_options', // Permissão necessária
        'syonet-options', // Slug do submenu (igual ao menu principal para evitar link duplicado)
        'syonet_render_options_page' // Função de callback
    );

    add_submenu_page(
        'syonet-options', // Slug do menu pai
        'Envios', // Título da página
        'Envios', // Nome no submenu
        'manage_options', // Capacidade necessária
        'edit.php?post_type=syonet_submissions', // Slug do post type
        '' // Função de callback (vazia)
    );
    add_submenu_page(
        'syonet-options', // Slug do menu pai
        'Formulários', // Título da página
        'Formulários', // Nome no submenu
        'manage_options', // Capacidade necessária
        'edit.php?post_type=syonet_form', // Slug do post type
        '' // Função de callback (vazia)
    );
    add_submenu_page(
        'syonet-options', // Slug do menu pai
        'Eventos', // Título da página
        'Eventos', // Nome no submenu
        'manage_options', // Capacidade necessária
        'edit-tags.php?taxonomy=event_group&post_type=syonet_form', // Slug do post type
        '' // Função de callback (vazia)
    );
    add_submenu_page(
        'syonet-options', // Slug do menu pai
        'Empresas', // Título da página
        'Empresas', // Nome no submenu
        'manage_options', // Capacidade necessária
        'edit-tags.php?taxonomy=empresa&post_type=syonet_form', // Slug do post type
        '' // Função de callback (vazia)
    );
    
}


function syonet_render_options_page() {
    ?>
    <div class="wrap">
        <br>
        <form method="post" action="options.php">
            <?php
            settings_fields('syonet_options_group');
            do_settings_sections('syonet-options');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Registre as configurações
add_action('admin_init', 'syonet_register_settings');

function syonet_register_settings() {

    
    // Registra o campo para o username
    register_setting('syonet_options_group', 'syonet_username');
    add_settings_section('syonet_section_id', '', null, 'syonet-options');
    add_settings_field('syonet_username_field_id', 'Username', 'syonet_render_username_field', 'syonet-options', 'syonet_section_id');

    // Registra o campo para o password
    register_setting('syonet_options_group', 'syonet_password');
    add_settings_field('syonet_password_field_id', 'Password', 'syonet_render_password_field', 'syonet-options', 'syonet_section_id');

    // Registra o campo para o api link
    register_setting('syonet_options_group', 'syonet_api_link');
    add_settings_field('syonet_api_link_field_id', 'API Link', 'syonet_render_api_link_field', 'syonet-options', 'syonet_section_id');

    // Registra o campo para a logotipo
    register_setting('syonet_options_group', 'syonet_logo');
    add_settings_field('syonet_logo_field_id', 'Logotipo do formulário', 'syonet_render_logo_field', 'syonet-options', 'syonet_section_id');


    // Registra os campos de múltiplos valores
    register_setting('syonet_options_group', 'syonet_document_type');
    register_setting('syonet_options_group', 'syonet_person_type');
    register_setting('syonet_options_group', 'syonet_phone_type');
    register_setting('syonet_options_group', 'syonet_source');
    register_setting('syonet_options_group', 'syonet_media');
    register_setting('syonet_options_group', 'syonet_contact_preference');

    // Adiciona os campos de múltiplos valores
    add_settings_field('syonet_document_type_field_id', '', 'syonet_render_document_type_field', 'syonet-options', 'syonet_section_id');
    add_settings_field('syonet_person_type_field_id', '', 'syonet_render_person_type_field', 'syonet-options', 'syonet_section_id');
    add_settings_field('syonet_phone_type_field_id', '', 'syonet_render_phone_type_field', 'syonet-options', 'syonet_section_id');
    add_settings_field('syonet_source_field_id', '', 'syonet_render_source_field', 'syonet-options', 'syonet_section_id');
    add_settings_field('syonet_media_field_id', '', 'syonet_render_media_field', 'syonet-options', 'syonet_section_id');
    add_settings_field('syonet_contact_preference_field_id', '', 'syonet_render_contact_preference_field', 'syonet-options', 'syonet_section_id');
}

function syonet_sanitize_password($value) {
    $current_value = get_option('syonet_password', '');
    return empty($value) ? $current_value : $value;
}

// Registra o campo para o password com sanitização personalizada
register_setting('syonet_options_group', 'syonet_password', [
    'sanitize_callback' => 'syonet_sanitize_password'
]);



function syonet_render_username_field() {
    $value = get_option('syonet_username', '');
    ?>

<tr class="form-field" style="display: flex; width: 100%; grid-column: span 2;">
    <td class="form-field-wrapper-td" style="width: 100%; display: flex; padding: 0px!important;">
        <div class="syonet-multi-value-field  syonet-form-wrapper form-wrapper" style="width: 100%;">

            <div class="syonet-values-container" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 0px!important;">
                <h1 style="font-weight: bold; font-size: 20px;">Bem vindo, ao sistema de integração com o CRM Syonet!</h1>
                <p>Aqui você pode configurar os tipos de documento que serão exibidos no formulário.<br> O <strong>primeiro campo</strong> é o valor do CRM e o <strong>segundo campo</strong> é o valor que aparecerá no formulário.</p>

                

                <p class="info-message"> Após fazer as alterações, clique em <strong>Salvar</strong> (No fim da página) para atualizar no site.</p>
            </div>

        </div>
    </td>
</tr>


    <tr class="form-field" style="display: flex; width: 100%;">
    <td class="form-field-wrapper-td" style="width: min(100%, 700px); display: flex; padding: 0px!important;">

    

    <div class="syonet-multi-value-field  syonet-form-wrapper form-wrapper" style="width: 100%;">
            <label for="syonet_username">Username</label>
            <div class="syonet-values-container" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 0px!important;">
                
                    <div class="syonet-value-row form-group" style="grid-template-columns: 1fr!important; margin-bottom: 0px!important; gap: 10px!important;">
                        <input type="text" name="syonet_username" value="<?php echo esc_attr($value); ?>" placeholder="" />
                        
                       
                    </div>
                
            </div>

          
        </div>

    </td>
</tr>

    <?php
}

function syonet_render_password_field() {
    $value = get_option('syonet_password', '');

    ?>
    <tr class="form-field" style="display: flex; width: 100%;">
    <td class="form-field-wrapper-td" style="width: min(100%, 700px); display: flex; padding: 0px!important;">

    

    <div class="syonet-multi-value-field  syonet-form-wrapper form-wrapper" style="width: 100%;">
            <label for="syonet_password">Password</label>
            <div class="syonet-values-container" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 0px!important;">
                
                    <div class="syonet-value-row form-group" style="grid-template-columns: 1fr!important; margin-bottom: 0px!important; gap: 10px!important;">

                         <?php if($value) {
                            $passwordcroppedstart = substr($value, 0, 2) . '*';
                            $passwordcroppedend = '**'. substr($value, -1);
                            $passwordplaceholder = mb_strlen($value) > 0 ? 'Senha cadastrada ('. $passwordcroppedstart . $passwordcroppedend .'). Clique e digite para alterar.' : '';
                            
                            
                            ?>
                            <input type="password" name="syonet_password" placeholder="<?php echo $passwordplaceholder; ?>" />
                         <?php } else { ?>
                            <input type="password" name="syonet_password" placeholder="Insira a senha do sistema" />
                         <?php } ?>
                        
                       
                    </div>
                
            </div>

          
        </div>

    </td>
</tr>

    <?php

}

function syonet_render_api_link_field() {
    $value = get_option('syonet_api_link', '');
    ?>
    <tr class="form-field" style="display: flex; width: 100%;">
    <td class="form-field-wrapper-td" style="width: 100%; display: flex; padding: 0px!important;">

    

    <div class="syonet-multi-value-field  syonet-form-wrapper form-wrapper" style="width: 100%;">
            <label for="syonet_api_link">Endpoint da API</label>
            <div class="syonet-values-container" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 0px!important;">
                
                    <div class="syonet-value-row form-group" style="grid-template-columns: 1fr!important; margin-bottom: 0px!important; gap: 10px!important;">
                        <input type="text" name="syonet_api_link" value="<?php echo esc_attr($value); ?>" placeholder="" />
                        
                       
                    </div>
                
            </div>

          
        </div>

    </td>
</tr>

    <?php
}

function syonet_render_logo_field() {
    $value = get_option('syonet_logo', '');
    wp_enqueue_media();
    ?>

<tr class="form-field" style="display: flex; width: 100%;">
    <td class="form-field-wrapper-td" style="width: min(100%, 700px); display: flex; padding: 0px!important;">

    

    <div class="syonet-multi-value-field  syonet-form-wrapper form-wrapper" style="width: 100%;">
            <label for="syonet_logo">Logotipo do formulário</label>
            <div class="syonet-values-container" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 0px!important;">
                
                    <div class="syonet-value-row form-group" style="grid-template-columns: 1fr 1fr!important; margin-bottom: 0px!important; gap: 10px!important;">
                        <img id="logo_preview" src="<?php 
                        
                        if(!$value){
                            echo FORMULARIO_SYONET_URL . 'assets/images/syonet-logo.webp';
                        }else{
                            echo esc_attr($value);
                        }
                        
                        ?>" style=" 
                        height: auto; 
                        display: block; 
                        object-fit: contain; 
                        object-position: center;
                        border-radius: 4px;
                        padding: 7px 14px;
                        background-color: #d8e0f3;
                        max-height: 30px;
                        width: -webkit-fill-available;
                        max-width: 100%;" />

                        <input type="text" id="syonet_logo" name="syonet_logo" value="<?php echo esc_attr($value); ?>" placeholder="" hidden />
                        <div style="flex-direction: column; display: flex; gap: 10px;">

                            <button type="button" class="button syonet-button syonet-button-default" id="upload_logo_button" style="display: <?php echo $value ? 'none' : 'inline-block'; ?>;">Alterar Logo</button>

                        <button type="button" class="button syonet-button syonet-button-danger" id="remove_logo_button" style="display: <?php echo $value ? 'inline-block' : 'none'; ?>;">Excluir Logo</button>
                        </div>
                        
                    </div>
                
            </div>

          
        </div>

    </td>
</tr>

<script>
    jQuery(document).ready(function($) {
        var mediaUploader;

        // Verifica se já existe uma imagem definida e atualiza a visualização
        var logoUrl = $('#syonet_logo').val();
        if (logoUrl) {
            $('#logo_preview').attr('src', logoUrl).show();
            $('#remove_logo_button').show(); // Mostra o botão de excluir
            $('#upload_logo_button').hide(); // Esconde o botão de adicionar
        } else {
            $('#logo_preview').attr('src', '<?php echo FORMULARIO_SYONET_URL . 'assets/images/syonet-logo.webp'; ?>').show();
            $('#remove_logo_button').hide(); // Esconde o botão de excluir
            $('#upload_logo_button').show(); // Mostra o botão de adicionar
        }

        $('#upload_logo_button').click(function(e) {
            e.preventDefault();
            // Se o uploader já estiver definido, então apenas abri-lo
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            // Se não, crie um novo uploader
            mediaUploader = wp.media({
                title: 'Selecionar Logo',
                button: {
                    text: 'Usar esta imagem'
                },
                multiple: false // Se você quiser permitir múltiplas seleções, defina como true
            });
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#syonet_logo').val(attachment.url); // Define o valor do input com a URL da imagem
                $('#logo_preview').attr('src', attachment.url).show(); // Atualiza o src da imagem e a exibe
                $('#remove_logo_button').show(); // Mostra o botão de excluir
                $('#upload_logo_button').hide(); // Esconde o botão de adicionar
            });
            mediaUploader.open();
        });

        // Função para excluir a logo
        $('#remove_logo_button').click(function() {
            $('#syonet_logo').val(''); // Limpa o valor do input
            $('#logo_preview').attr('src', '<?php echo site_url(); ?>/wp-content/plugins/formulario-syonet/assets/images/syonet-logo.webp').show();
            $(this).hide(); // Oculta o botão de excluir
            $('#upload_logo_button').show();
            
        });
    });
</script>




            <?php
}


function syonet_render_document_type_field() {
    $values = get_option('syonet_document_type', array());

    // Se o valor for uma string, converte para array
    if (is_string($values)) {
        $values = array_filter(array_map('trim', explode(',', $values)));
    }

    // Garante que $values seja um array
    if (!is_array($values)) {
        $values = array();
    }
?>

<style>
    .term-description-wrap {
        display: none!important;
    }
    tbody {
        display: grid!important; /* Garante que o tbody se comporte como um grupo de linhas */
        grid-template-columns: 1fr 1fr!important;
        gap: 20px!important;
        width: min(100%, 1200px)!important;
        font-family: 'Poppins', sans-serif!important;
    }
    tr {
        display: none;

    }
    tr.form-field {
        display: flex!important;
    }
    .syonet-multi-value-field{
        margin:0!important;
    }
</style>

<tr class="form-field" style="display: flex; width: 100%;">
    
    <td class="form-field-wrapper-td" style="width: min(100%, 700px); display: flex; padding: 0px!important;">
        
        <div class="syonet-multi-value-field  syonet-form-wrapper form-wrapper" style="width: 100%;">
            <label for="syonet_document_type">Tipos de Documento</label>
            <div class="syonet-values-container" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 0px!important;">
                <?php foreach ($values as $index => $value) { ?>
                    <div class="syonet-value-row form-group" style="grid-template-columns: 1.4fr 1fr 0.5fr!important; margin-bottom: 0px!important; gap: 10px!important;">
                        <input type="text" name="syonet_document_type[<?php echo $index; ?>][value]" value="<?php echo esc_attr($value['value']); ?>" placeholder="Valor do CRM" />
                        <input type="text" name="syonet_document_type[<?php echo $index; ?>][name]" value="<?php echo esc_attr($value['name']); ?>" placeholder="Aparece no formulário" />
                        <button type="button" class="button syonet-remove-value syonet-button syonet-button-danger">Remover</button>
                    </div>
                <?php } ?>
            </div>

            <a class="button syonet-add-value syonet-button syonet-button-default" data-field="syonet_document_type" >Adicionar</a>
        </div>

    </td>
</tr>

<?php



    
}

function syonet_render_person_type_field() {
    $values = get_option('syonet_person_type', array());

    // Se o valor for uma string, converte para array
    if (is_string($values)) {
        $values = array_filter(array_map('trim', explode(',', $values)));
    }

    // Garante que $values seja um array
    if (!is_array($values)) {
        $values = array();
    }

    ?>


<tr class="form-field" style="display: flex; width: 100%;">    
    <td class="form-field-wrapper-td" style="width: min(100%, 700px); display: flex; padding: 0px!important;">
        
        <div class="syonet-multi-value-field syonet-form-wrapper form-wrapper" style="width: 100%;">
            <label for="syonet_person_type">Tipos de Pessoa</label>
            <div class="syonet-values-container" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 0px!important;">
                <?php foreach ($values as $index => $value) { ?>
                    <div class="syonet-value-row form-group" style="grid-template-columns: 1.4fr 1fr 0.5fr!important; margin-bottom: 0px!important; gap: 10px!important;">
                        <input type="text" name="syonet_person_type[<?php echo $index; ?>][value]" value="<?php echo esc_attr($value['value']); ?>" placeholder="Valor do CRM" />
                        <input type="text" name="syonet_person_type[<?php echo $index; ?>][name]" value="<?php echo esc_attr($value['name']); ?>" placeholder="Aparece no formulário" />
                        <button type="button" class="button syonet-remove-value syonet-button syonet-button-danger">Remover</button>
                    </div>
                <?php } ?>
            </div>

            <a class="button syonet-add-value syonet-button syonet-button-default" data-field="syonet_person_type" >Adicionar</a>
        </div>

    </td>
</tr>

<?php
}

function syonet_render_phone_type_field() {
    $values = get_option('syonet_phone_type', array());

    // Se o valor for uma string, converte para array
    if (is_string($values)) {
        $values = array_filter(array_map('trim', explode(',', $values)));
    }

    // Garante que $values seja um array
    if (!is_array($values)) {
        $values = array();
    }

    ?>

<tr class="form-field" style="display: flex; width: 100%;">    
    <td class="form-field-wrapper-td" style="width: min(100%, 700px); display: flex; padding: 0px!important;">
        
        <div class="syonet-multi-value-field syonet-form-wrapper form-wrapper" style="width: 100%;">
            <label for="syonet_phone_type">Tipos de Telefone</label>
            
            <div class="syonet-values-container" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 0px!important;">
                <?php foreach ($values as $index => $value) { ?>
                    <div class="syonet-value-row form-group" style="grid-template-columns: 1.4fr 1fr 0.5fr!important; margin-bottom: 0px!important; gap: 10px!important;">
                        <input type="text" name="syonet_phone_type[<?php echo $index; ?>][value]" value="<?php echo esc_attr($value['value']); ?>" placeholder="Valor do CRM" />
                        <input type="text" name="syonet_phone_type[<?php echo $index; ?>][name]" value="<?php echo esc_attr($value['name']); ?>" placeholder="Aparece no formulário" />
                        <button type="button" class="button syonet-remove-value syonet-button syonet-button-danger">Remover</button>
                    </div>
                <?php } ?>
            </div>

            <a class="button syonet-add-value syonet-button syonet-button-default" data-field="syonet_phone_type" >Adicionar</a>
        </div>

    </td>
</tr>

<?php

}

function syonet_render_source_field() {
    $values = get_option('syonet_source', array());

    // Se o valor for uma string, converte para array
    if (is_string($values)) {
        $values = array_filter(array_map('trim', explode(',', $values)));
    }

    // Garante que $values seja um array
    if (!is_array($values)) {
        $values = array();
    }

    ?>

    <tr class="form-field" style="display: flex; width: 100%;">    
        <td class="form-field-wrapper-td" style="width: min(100%, 700px); display: flex; padding: 0px!important;">
            
            <div class="syonet-multi-value-field syonet-form-wrapper form-wrapper" style="width: 100%;">
                <label for="syonet_source">Tipos de Origem</label>
                
                <div class="syonet-values-container" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 0px!important;">
                    <?php foreach ($values as $index => $value) { ?>
                        <div class="syonet-value-row form-group" style="grid-template-columns: 1.4fr 1fr 0.5fr!important; margin-bottom: 0px!important; gap: 10px!important;">
                            <input type="text" name="syonet_source[<?php echo $index; ?>][value]" value="<?php echo esc_attr($value['value']); ?>" placeholder="Valor do CRM" />
                            <input type="text" name="syonet_source[<?php echo $index; ?>][name]" value="<?php echo esc_attr($value['name']); ?>" placeholder="Aparece no formulário" />
                            <button type="button" class="button syonet-remove-value syonet-button syonet-button-danger">Remover</button>
                        </div>
                    <?php } ?>
                </div>
    
                <a class="button syonet-add-value syonet-button syonet-button-default" data-field="syonet_source" >Adicionar</a>
            </div>
    
        </td>
    </tr>
    
    <?php
    
}

function syonet_render_media_field() {
    $values = get_option('syonet_media', array());

    // Se o valor for uma string, converte para array
    if (is_string($values)) {
        $values = array_filter(array_map('trim', explode(',', $values)));
    }

    // Garante que $values seja um array
    if (!is_array($values)) {
        $values = array();
    }

    ?>

<tr class="form-field" style="display: flex; width: 100%;">    
    <td class="form-field-wrapper-td" style="width: min(100%, 700px); display: flex; padding: 0px!important;">
        
        <div class="syonet-multi-value-field syonet-form-wrapper form-wrapper" style="width: 100%;">
            <label for="syonet_media">Tipos de Mídia</label>
           
            <div class="syonet-values-container" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 0px!important;">
                <?php foreach ($values as $index => $value) { ?>
                    <div class="syonet-value-row form-group" style="grid-template-columns: 1.4fr 1fr 0.5fr!important; margin-bottom: 0px!important; gap: 10px!important;">
                        <input type="text" name="syonet_media[<?php echo $index; ?>][value]" value="<?php echo esc_attr($value['value']); ?>" placeholder="Valor do CRM" />
                        <input type="text" name="syonet_media[<?php echo $index; ?>][name]" value="<?php echo esc_attr($value['name']); ?>" placeholder="Aparece no formulário" />
                        <button type="button" class="button syonet-remove-value syonet-button syonet-button-danger">Remover</button>
                    </div>
                <?php } ?>
            </div>

            <a class="button syonet-add-value syonet-button syonet-button-default" data-field="syonet_media" >Adicionar</a>
        </div>

    </td>
</tr>

<?php

}

function syonet_render_contact_preference_field() {
    $values = get_option('syonet_contact_preference', array());

    // Se o valor for uma string, converte para array
    if (is_string($values)) {
        $values = array_filter(array_map('trim', explode(',', $values)));
    }

    // Garante que $values seja um array
    if (!is_array($values)) {
        $values = array();
    }

    ?>

<tr class="form-field" style="display: flex; width: 100%;">    
    <td class="form-field-wrapper-td" style="width: min(100%, 700px); display: flex; padding: 0px!important;">
        
        <div class="syonet-multi-value-field syonet-form-wrapper form-wrapper" style="width: 100%;">
            <label for="syonet_contact_preference">Preferências de Contato</label>
            
            <div class="syonet-values-container" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 0px!important;">
                <?php foreach ($values as $index => $value) { ?>
                    <div class="syonet-value-row form-group" style="grid-template-columns: 1.4fr 1fr 0.5fr!important; margin-bottom: 0px!important; gap: 10px!important;">
                        <input type="text" name="syonet_contact_preference[<?php echo $index; ?>][value]" value="<?php echo esc_attr($value['value']); ?>" placeholder="Valor do CRM" />
                        <input type="text" name="syonet_contact_preference[<?php echo $index; ?>][name]" value="<?php echo esc_attr($value['name']); ?>" placeholder="Aparece no formulário" />
                        <button type="button" class="button syonet-remove-value syonet-button syonet-button-danger">Remover</button>
                    </div>
                <?php } ?>
            </div>

            <a class="button syonet-add-value syonet-button syonet-button-default" data-field="syonet_contact_preference" >Adicionar</a>
        </div>

    </td>
</tr>

<?php

}



function syonet_admin_enqueue_scripts($hook) {

    if ( ! is_admin()) return;

    wp_enqueue_script(
        'syonet-option-focus',
        plugins_url('assets/option-focus.js', __FILE__),
        array('jquery'),
        '1.0.0',
        true
    );
   
    wp_enqueue_script(
        'syonet-multi-value',
        plugins_url('assets/script.js', __FILE__),
        array('jquery'),
        '1.0.0',
        true
    );
    
    
}


add_action('admin_enqueue_scripts', 'syonet_admin_enqueue_scripts');

function syonet_enqueue_styles() {
    // Adiciona o CSS personalizado
    wp_enqueue_style(
        'syonet-css',
        plugins_url('assets/style.css', __FILE__)
    );
}

add_action('admin_init', 'syonet_enqueue_styles');