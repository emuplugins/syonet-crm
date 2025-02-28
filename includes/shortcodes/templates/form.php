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
$event_types = wp_get_post_terms($post_id, 'syonet_event_type');
$event_groups = wp_get_post_terms($post_id, 'syonet_event_group');
$empresas = wp_get_post_terms($post_id, 'empresa');
$document_types = get_option('syonet_document_type');
$person_types = get_option('syonet_person_type');
$phone_types = get_option('syonet_phone_type');
$source = get_option('syonet_source');
$media = get_option('syonet_media');
$contact_preference = get_option('syonet_contact_preference');


// Gerador de passos e campos

// COMO FUNCIONA? Cada STEP tem um array com os campos que serão exibidos.
// Cada campo tem um array com as informações do campo, como label, required, type, placeholder, options, etc.
// O type pode ser text, email, tel, select, boolean, textarea.
// O select pode ter options que são arrays com value e name.
// O boolean pode ter options que são arrays com TRUE e FALSE.

// Antes de usar um term_object, você precisa converter para um array com get_object_vars.

$empresas = array_map(function($empresa) {
    return [
        'value' => get_term_meta($empresa->term_id, 'empresa_id', true),
        'name'  => $empresa->name
    ];
}, $empresas);

$veiculos = [

    'CG 160 START' => [
        'value' => 'CG 160 START',
        'brand' => 'Honda',
        'name'  => 'CG 160 START'
    ],
    'CG 160 FAN' => [
        'value' => 'CG 160 FAN',
        'brand' => 'Honda',
        'name'  => 'CG 160 FAN'
    ],
    'CG 160 TITAN' => [
        'value' => 'CG 160 TITAN',
        'brand' => 'Honda',
        'name'  => 'CG 160 TITAN'
    ],
    'CG 160 CARGO' => [
        'value' => 'CG 160 CARGO',
        'brand' => 'Honda',
        'name'  => 'CG 160 CARGO'
    ],
    'POP 110' => [
        'value' => 'POP 110',
        'brand' => 'Honda',
        'name'  => 'POP 110'
    ],
    'BIZ 125' => [
        'value' => 'BIZ 125',
        'brand' => 'Honda',
        'name'  => 'BIZ 125'
    ],
    'ELITE 125' => [
        'value' => 'ELITE 125',
        'brand' => 'Honda',
        'name'  => 'ELITE 125'
    ],
    'PCX' => [
        'value' => 'PCX',
        'brand' => 'Honda',
        'name'  => 'PCX'
    ],
    'ADV' => [
        'value' => 'ADV',
        'brand' => 'Honda',
        'name'  => 'ADV'
    ],
    'X-ADV' => [
        'value' => 'X-ADV',
        'brand' => 'Honda',
        'name'  => 'X-ADV'
    ],
    'CB 300F TWISTER' => [
        'value' => 'CB 300F TWISTER',
        'brand' => 'Honda',
        'name'  => 'CB 300F TWISTER'
    ],
    'CB 500F' => [
        'value' => 'CB 500F',
        'brand' => 'Honda',
        'name'  => 'CB 500F'
    ],
    'CB 650R' => [
        'value' => 'CB 650R',
        'brand' => 'Honda',
        'name'  => 'CB 650R'
    ],
    'CB 1000R' => [
        'value' => 'CB 1000R',
        'brand' => 'Honda',
        'name'  => 'CB 1000R'
    ],
    'CB 1000R BLACK EDITION' => [
        'value' => 'CB 1000R BLACK EDITION',
        'brand' => 'Honda',
        'name'  => 'CB 1000R BLACK EDITION'
    ],
    'XR 300L TORNADO' => [
        'value' => 'XR 300L TORNADO',
        'brand' => 'Honda',
        'name'  => 'XR 300L TORNADO'
    ],
    'NXR 160 BROS' => [
        'value' => 'NXR 160 BROS',
        'brand' => 'Honda',
        'name'  => 'NXR 160 BROS'
    ],
    'SAHARA 300' => [
        'value' => 'SAHARA 300',
        'brand' => 'Honda',
        'name'  => 'SAHARA 300'
    ],
    'XRE 190' => [
        'value' => 'XRE 190',
        'brand' => 'Honda',
        'name'  => 'XRE 190'
    ],
    'CB 500X' => [
        'value' => 'CB 500X',
        'brand' => 'Honda',
        'name'  => 'CB 500X'
    ],
    'NC 750X' => [
        'value' => 'NC 750X',
        'brand' => 'Honda',
        'name'  => 'NC 750X'
    ],
    'CRF 1100L AFRICA TWIN' => [
        'value' => 'CRF 1100L AFRICA TWIN',
        'brand' => 'Honda',
        'name'  => 'CRF 1100L AFRICA TWIN'
    ],
    'CRF 1100L AFRICA TWIN ADVENTURE SPORTS' => [
        'value' => 'CRF 1100L AFRICA TWIN ADVENTURE SPORTS',
        'brand' => 'Honda',
        'name'  => 'CRF 1100L AFRICA TWIN ADVENTURE SPORTS'
    ],
    'CRF 250F' => [
        'value' => 'CRF 250F',
        'brand' => 'Honda',
        'name'  => 'CRF 250F'
    ],
    'CRF 450' => [
        'value' => 'CRF 450',
        'brand' => 'Honda',
        'name'  => 'CRF 450'
    ],
    'TRX 420 FOURTRAX' => [
        'value' => 'TRX 420 FOURTRAX',
        'brand' => 'Honda',
        'name'  => 'TRX 420 FOURTRAX'
    ],
    'CBR 650R' => [
        'value' => 'CBR 650R',
        'brand' => 'Honda',
        'name'  => 'CBR 650R'
    ],
    'CBR 1000RR-R FIREBLADE SP' => [
        'value' => 'CBR 1000RR-R FIREBLADE SP',
        'brand' => 'Honda',
        'name'  => 'CBR 1000RR-R FIREBLADE SP'
    ],
    'GL 1800 GOLDWING' => [
        'value' => 'GL 1800 GOLDWING',
        'brand' => 'Honda',
        'name'  => 'GL 1800 GOLDWING'
    ]
];


$steps =  [

// STEP

    'Contato' => [
        'name' => [
            'label' => 'Primeiro nome',
            'required' => true,
            'type' => 'text',
            'placeholder' => 'João Fernando'
        ],
        'email' => [
            'label' => 'Melhor email',
            'required' => true,
            'type' => 'email',
            'placeholder' => 'suaconta@email.com'
        ],
        'phone' => [
            'label' => 'Número do Whatsapp',
            'required' => true,
            'type' => 'tel',
            'placeholder' => '(99) 99999-9999'
        ],
        'phone_type' => [
            'label' => 'Tipo',
            'required' => true,
            'type' => 'select',
            'options' => $phone_types,
            'placeholder' => FALSE
        ],
        // 'source' => [
        //     'label' => 'Onde nos conheceu:',
        //     'required' => true,
        //     'type' => 'select',
        //     'options' => $source
        // ],
        // 'media' => [
        //     'label' => 'Onde preenche:',
        //     'required' => true,
        //     'type' => 'select',
        //     'options' => $media
        // ]
    // ],

// // STEP

//     'Documentos' => [
//         'document' => [
//             'label' => 'Documento',
//             'required' => true,
//             'type' => 'text',
//             'placeholder' => '999.999.999-99'
//         ],
//         'document_type' => [
//             'label' => 'Tipo de documento',
//             'required' => true,
//             'type' => 'select',
//             'options' => $document_types
//         ],
//         'person_type' => [
//             'label' => 'Tipo de pessoa',
//             'required' => true,
//             'type' => 'select',
//             'options' => $person_types
//         ]
//     ],

// // STEP

//     'Endereço' => [
//         'country' => [
//             'label' => 'País',
//             'required' => true,
//             'type' => 'text',
//             'placeholder' => 'Brasil'
//         ],
//         'postcode' => [
//             'label' => 'CEP',
//             'required' => true,
//             'type' => 'text',
//             'placeholder' => '00000-000'
//         ],
//         'address_number' => [
//             'label' => 'Número',
//             'required' => true,
//             'type' => 'text',
//             'placeholder' => '144'
//         ],
//         'state' => [
//             'label' => 'Estado',
//             'required' => true,
//             'type' => 'text',
//             'placeholder' => 'SP'
//         ],
//         'city' => [
//             'label' => 'Cidade',
//             'required' => true,
//             'type' => 'text',
//             'placeholder' => 'São Paulo'
//         ],
//         'neighborhood' => [
//             'label' => 'Bairro',
//             'required' => true,
//             'type' => 'text',
//             'placeholder' => 'Novo Paraíso'
//         ],
//         'address' => [
//             'label' => 'Endereço',
//             'required' => true,
//             'type' => 'text',
//             'placeholder' => 'Rua das Flores'
//         ]
//     ],

// // STEP

//     'Conclusão' => [
        'company' => [
            'label' => 'Concessionária',
            'required' => true,
            'type' => 'select',
            'options' => $empresas,
            'placeholder' => 'Escolher opção'
        ],
        'model' => [
            'label' => 'Escolha sua moto',
            'required' => true,
            'type' => 'select',
            'options' => $veiculos,
            'placeholder' => 'Escolher opção'
        ],
//         'contact_preference' => [
//             'label' => 'Preferência de contato',
//             'required' => true,
//             'type' => 'select',
//             'options' => $contact_preference
//         ],
//         'update_data' => [
//             'label' => 'Atualizar dados? (Se já é cliente)',
//             'required' => true,
//             'type' => 'boolean',
//             'options' => [
//                 'Sim',
//                 'Não'
//             ]
//         ],
//         'comment' => [
//             'label' => 'Comentário',
//             'required' => true,
//             'type' => 'textarea',
//             'placeholder' => 'Descreva seu interesse.'
//         ]
//     ]
]
    ];
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

        <?php $step_keys = array_keys($steps);?>
        
        
            <div class="progress-container" style="<?php echo (count($steps) <= 1) ? 'display: none!important;' : ''; ?>">
                <div class="progress"></div>
                <ol>
                    <?php foreach (array_keys($steps) as $index => $step): ?>
                        <li class="<?php echo $index == 0 ? 'current' : ''; ?>"><?php echo $step; ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>
        

        
        <div class="steps-container">

        <?php 
        
        foreach ($steps as $step => $fields): ?>
            <div class="step <?php echo ($index == 0) ? 'first-step' : ''; ?>" style="<?php echo ($index == 0) ? 'position: relative;' : ''; ?>">
                <?php 
                $index = 0;
                
                foreach ($fields as $field => $details): ?>
                    <?php if ($index % 2 == 0): ?> <!-- Verificando se o índice é par -->
                        <div class="form-group">
                    <?php endif; ?>
        
                    <div class="form-control-syonet">
                        <?php if ($details['type'] == 'text'): ?>
                            <label for="<?php echo $field; ?>"><?php echo $details['label']; ?></label>
                            <input type="text" name="<?php echo $field; ?>" id="<?php echo $field; ?>" <?php echo $details['required'] ? 'required' : ''; ?> placeholder="<?php echo $details['label']; ?>">
                        <?php endif; ?>
        
                        <?php if ($details['type'] == 'email'): ?>
                            <label for="<?php echo $field; ?>"><?php echo $details['label']; ?></label>
                            <input type="email" name="<?php echo $field; ?>" id="<?php echo $field; ?>" <?php echo $details['required'] ? 'required' : ''; ?> placeholder="<?php echo $details['label']; ?>">
                        <?php endif; ?>
        
                        <?php if ($details['type'] == 'tel'): ?>
                            <div class="form-control-syonet">
                                <label for="phone" style="width: 100%;">Número de telefone</label>
        
                                <div id="search-country-body" style="display: none;">
                                    <input type="text" id="search_country" class="form-control search-input" placeholder="Buscar país..." onkeyup="filterCountries()" onclick="event.stopPropagation()">
                                    <div class="dropdown" id="dropdown">
                                        <div id="country_list"></div>
                                    </div>
                                </div>
        
                                <input type="text" id="ddi" value="+55" name="ddi" hidden placeholder="DDI" required />
                                <input type="text" id="ddd" value="11" name="ddd" hidden placeholder="DDD" required />
        
                                <div class="phone-input" style="display: flex; align-items: center; flex-wrap: wrap;">
                                    <div class="custom-select">
                                        <div class="selected-flag" onclick="toggleDropdown()" tabindex="0" onkeydown="if(event.key === 'Enter') { toggleDropdown(); selectCountry(selectedCountryName, selectedCountryFlag, selectedCountryDdi); }">
                                            <img id="selected_flag" src="https://cdnjs.cloudflare.com/ajax/libs/flag-icons/7.2.3/flags/4x3/br.svg" alt="" tabindex="-1">
                                        </div>
                                    </div>
        
                                    <input type="text" id="phone" name="phone" placeholder="(99) 99999-9999" <?php echo $details['required'] ? 'required' : ''; ?> style="width: 70%; flex-grow: 1;" oninput="phoneNumberFormatter()" onblur="phoneNumberFormatter()">
                                </div>
                            </div>
                        <?php endif; ?>
        
                        <?php if ($details['type'] == 'select'): ?>
                            <label for="<?php echo $field; ?>"><?php echo $details['label']; ?></label>
                            <select name="<?php echo $field; ?>" id="<?php echo $field; ?>" <?php echo $details['required'] ? 'required' : ''; ?>>

                                <?php if ($details['placeholder']): ?>
                                    <option value="">
                                        Escolha uma opção
                                    </option>
                                <?php endif; ?>
                                <?php foreach ($details['options'] as $option): ?>
                                    <?php 
                                    // Verifica se o $option é um objeto WP_Term
                                    if (is_object($option) && isset($option->term_id)) {
                                        // Converte o WP_Term object para um array
                                        $option = get_object_vars($option);
                                    }
                                    ?>
                                    <option value="<?php echo $option['value'] ?? $option['name'] ?? $option['post_name'] ?? ''; ?>">
                                        <?php echo $option['name'] ?? $option['post_name'] ?? $option['value'] ?? ''; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
        
                        <?php if ($details['type'] == 'boolean'): ?>
                            <label for="<?php echo $field; ?>"><?php echo $details['label']; ?></label>
                            <select name="<?php echo $field; ?>" id="<?php echo $field; ?>">
                                <option value="TRUE"><?php echo $details['options'][0]; ?></option>
                                <option value="FALSE"><?php echo $details['options'][1]; ?></option>
                            </select>
                        <?php endif; ?>
        
                        <?php if ($details['type'] == 'textarea'): ?>
                            <label for="<?php echo $field; ?>"><?php echo $details['label']; ?></label>
                            <div style="display:grid;">
                                <textarea id="<?php echo $field; ?>" name="<?php echo $field; ?>" placeholder="<?php echo $details['label']; ?>" required></textarea>
                            </div>
                        <?php endif; ?>
                    </div>
        
                    <?php if ($index % 2 == 1 || $index == count($fields) - 1): ?> <!-- Fechando a <div> para os índices ímpares ou o último índice -->
                        </div>
                    <?php endif; ?>
        
                    <?php $index++; ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        
        </div>
        
        <div class="controls">
            <?php if ($steps >= 0): ?>
                <button type="button" class="syonet-prev-btn">Anterior</button>
                <button type="button" class="syonet-next-btn">Próximo</button>
            <?php endif; ?>
            
            <button type="submit" class="syonet-submit-btn">
                <?php if (!empty($submit_text)) echo '<span>' . $submit_text . '</span>'; ?>
            </button>
        </div>
        </form>
        