<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// SHORTCODE ATTRIBUTES
$atts = shortcode_atts(array(
    'id' => '',
    'logo' => '',
    'title' => '',
    'submit_text' => '',
    'subtitle' => ''
), $atts);


// ELEMENTOR 

$post_id = $atts['id'];
$logo = $atts['logo'] ? $atts['logo'] : FORMULARIO_SYONET_URL . 'assets/images/syonet-logo.png';
$title = $atts['title'] ? $atts['title'] : '';
$submit_text = $atts['submit_text'] ? $atts['submit_text'] : 'Enviar';
$subtitle = $atts['subtitle'] ? $atts['subtitle'] : '';

// DEFAULT
$event_types = wp_get_post_terms($post_id, 'event_type');
$event_groups = wp_get_post_terms($post_id, 'event_group');
$empresas = wp_get_post_terms($post_id, 'empresa');
$document_types = get_option('syonet_document_type');
$person_types = get_option('syonet_person_type');
$phone_types = get_option('syonet_phone_type');
$source = get_option('syonet_source');
$media = get_option('syonet_media');
$contact_preference = get_option('syonet_contact_preference');

?>

<form class="form-wizard" action="<?php echo admin_url('admin-ajax.php'); ?>" method="POST" style="width: 100%;">
    
    <input type="hidden" name="action" value="mpf_save_form">
    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('mpf_save_form_nonce'); ?>">
    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">

    <h3 class="title">
            <?php if (!empty($logo)) echo '<img class="syonet-logo-form" src="'. $logo . '" alt="SyoNet" style="height: auto;">';
            if (!empty($title)) echo '<span class="syonet-form-title">' . $title . '</span>';
            if (!empty($subtitle)) echo '<span class="syonet-form-subtitle">' . $subtitle . '</span>'; ?>
        </h3>

        <div class="completed" hidden>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.746 3.746 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
            </svg>
            <h3>Formulário enviado com sucesso!</h3>
            <p>Obrigado por entrar em contato conosco.</p>
        </div>

        <div class="progress-container">
            <div class="progress"></div>
            <ol>
                <li class="current">Contato</li>
                <li>Documento</li>
                <li>Endereço</li>
                <li>Conclusão</li>
            </ol>
        </div>

        <div class="steps-container">

            <div class="step" style="position: relative;">
                <div class="form-group">

                    <div class="form-control-syonet">
                        <label for="name">Primeiro nome</label>
                        <input type="text" id="name" name="name" placeholder="João Fernando" required />
                    </div>

                    <div class="form-control-syonet">
                    <label for="email">Melhor email</label>
                        <input type="email" id="email" name="email" placeholder="suaconta@email.com" required />
                    </div>

                </div>

                <div class="phone form-group">
                  
                    <div class="form-control-syonet">
                    <label for="phone" style="width: 100%;">Número de telefone</label>
                    <div id="search-country-body" style="display: none;">
                            <input type="text" id="search_country" class="form-control search-input"
                                placeholder="Buscar país..." onkeyup="filterCountries()" onclick="event.stopPropagation()">
                            <div class="dropdown" id="dropdown">
                                <div id="country_list"></div>
                            </div>
                        </div>
                    
                        
                        <input type="text" id="ddi" value="+55" name="ddi" hidden placeholder="DDI" required />
                        <input type="text" id="ddd" value="11" name="ddd" hidden placeholder="DDD" required />
                        <div class="phone-input" style="display: flex;align-items: center; flex-wrap: wrap;">
                       
                        <div class="custom-select">
                            <div class="selected-flag" onclick="toggleDropdown()" tabindex="0" onkeydown="if(event.key === 'Enter') { toggleDropdown(); selectCountry(selectedCountryName, selectedCountryFlag, selectedCountryDdi); }">
                                <img id="selected_flag" src="https://cdnjs.cloudflare.com/ajax/libs/flag-icons/7.2.3/flags/4x3/br.svg" alt="" tabindex="-1">
                            </div>
                            
                        </div>
                        <input type="text" id="phone" name="phone" placeholder="(99) 99999-9999" required style="width: 70%; flex-grow: 1;" oninput="phoneNumberFormatter()" onblur="phoneNumberFormatter()">
                    </div>
                    </div>

                    <div class="form-control-syonet">
                        <label for="phone_type">Tipo</label>
                        <select name="phone_type" id="phone_type">
                            <?php foreach ($phone_types as $phone_type): ?>
                                <option value="<?php echo $phone_type['value']; ?>"><?php echo $phone_type['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                <div class="form-control-syonet">
                    <label for="source">Onde nos conheceu:</label>
                    <select name="source" id="source">
                        <?php foreach ($source as $source): ?>
                            <option value="<?php echo $source['value']; ?>"><?php echo $source['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-control-syonet">
                    <label for="media">Onde preenche:</label>
                    <select name="media" id="media">
                        <?php foreach ($media as $media): ?>
                            <option value="<?php echo $media['value']; ?>"><?php echo $media['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
           
            </div>
            <div class="step">
                <div class="form-group">
                    <div class="form-control-syonet">
                        <label for="document">Número do documento</label>
                        <input type="text" id="document" name="document" placeholder="999.999.999-99" required />
                    </div>
                    <div class="form-control-syonet">
                        <label for="document_type">Tipo de documento</label>
                        <select name="document_type" id="document_type" required>
                            <?php foreach ($document_types as $document_type): ?>
                                <option value="<?php echo $document_type['value']; ?>"><?php echo $document_type['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-control-syonet">
                    <label for="person_type">Tipo de pessoa</label>
                    <select name="person_type" id="person_type" required>
                        <?php foreach ($person_types as $person_type): ?>
                            <option value="<?php echo $person_type['value']; ?>"><?php echo $person_type['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="step">

                <div class="form-group">
                    <div class="form-control-syonet">
                        <label for="country">País</label>
                        <input type="text" id="country" name="country" placeholder="Brasil" required value="Brasil"/>
                    </div>
                    <div class="form-control-syonet">
                        <label for="postcode">CEP</label>
                        <input type="text" id="postcode" name="postcode" placeholder="00000-000" required onblur="buscaCep(this.value)" />
                    </div>
                </div>

                <div class="form-group show-address">
                    <div class="form-control-syonet">
                        <label for="address_number">Número</label>
                        <input type="text" id="address_number" name="address_number" placeholder="144" required />
                    </div>
                    <div class="form-control-syonet">
                        <label for="state">Estado</label>
                        <input type="text" id="state" name="state" placeholder="SP" required />
                    </div>
                </div>

                <div class="form-group show-address">

                    <div class="form-control-syonet">
                        <label for="city">Cidade</label>
                        <input type="text" id="city" name="city" placeholder="São Paulo" required />
                    </div>

                    <div class="form-control-syonet">
                        <label for="neighborhood">Bairro</label>
                        <input type="text" id="neighborhood" name="neighborhood" placeholder="Novo Paraíso" required />
                    </div>
                </div>
                <div class="form-group show-address">
                    <div class="form-control-syonet">
                        <label for="address">Endereço</label>
                        <input type="text" id="address" name="address" placeholder="Rua das Flores" required />
                    </div>

                </div>

            </div>

            <div class="step">
                <div class="form-control-syonet">
                    <label for="company">Consercionária</label>
                    <select name="company" id="company">
                        <?php foreach ($empresas as $empresa): ?>
                            <option value="<?php echo get_term_meta($empresa->term_id, 'empresa_id', true) .', ' . $empresa->name; ?>"><?php echo $empresa->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
               <div class="form-group">
                <div class="form-control-syonet">
                    <label for="model">Modelo/Marca do veículo</label>
                    <input type="text" id="model" name="model" placeholder="YAMAHA" required />
                </div>
                <div class="form-control-syonet">
                    <label for="contact_preference">Preferência de contato</label>
                    <select name="contact_preference" id="contact_preference">
                        <?php foreach ($contact_preference as $contact_preference): ?>
                            <option value="<?php echo $contact_preference['value']; ?>"><?php echo $contact_preference['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                </div>
                <div class="form-control-syonet">
                    <label for="update_data">Atualizar dados? (Se já é cliente)</label>
                    <select name="update_data" id="update_data">
                        <option value="TRUE">Sim</option>
                        <option value="FALSE">Não</option>
                    </select>
                </div>
                <div class="form-control-syonet">
                    <label for="comment">Comentário</label>
                    <div style="display:grid;">
                        <textarea id="comment" name="comment" placeholder="Descreva seu interesse." required></textarea>
                    </div>
                </div>
                
            </div>

        </div>

        <div class="controls">
            <button type="button" class="syonet-prev-btn">Anterior</button>
            <button type="button" class="syonet-next-btn">Próximo</button>
            <button type="submit" class="syonet-submit-btn"><?php if (!empty($submit_text)) echo '<span>' . $submit_text . '</span>'; ?></button>
        </div>

    </form>