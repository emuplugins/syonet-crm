<?php

if (! defined('ABSPATH')) exit;

require_once('includes/functions/api_consumer.php');
require_once FORMULARIO_SYONET_DIR . 'includes/classes/form-fields.php';


function generateE164Number($ddi, $ddd, $phone)
{
    $phone = preg_replace('/\D/', '', $phone);

    if (!empty($ddi) && !empty($ddd) && !empty($phone)) {
        // Retorna o número no formato E.164
        return '+' . $ddi . $phone; // O DDI já inclui o código do país
    }
    return null; // Retorna null se os dados não forem válidos
}

function mpf_save_form()
{
    global $wpdb;
    $username = get_option('syonet_username');
    $password = get_option('syonet_password');
    $api_link = get_option('syonet_api_link');

    // Verifica nonce 
    check_ajax_referer('mpf_save_form_nonce', 'nonce');

    $post_id = $_POST['post_id'] ?? NULL;

    $contact_preference = isset($_POST['contact_preference']) ? sanitize_text_field($_POST['contact_preference']) : 'Nenhum';
    $days_to_update = isset($_POST['days_to_update']) ? intval($_POST['days_to_update']) : 0;
    $rules = isset($_POST['rules']) ? filter_var($_POST['rules'], FILTER_VALIDATE_BOOLEAN) : false;
    $additional_fields = isset($_POST['additional_fields']) ? array_map('sanitize_text_field', (array) $_POST['additional_fields']) : null;
    $update_data = isset($_POST['update_data']) ? sanitize_textarea_field($_POST['update_data']) : null;

    $company = isset($_POST['company']) ? sanitize_text_field($_POST['company']) : null;
    $company = explode(',', $company);
    $companyID = isset($company[0]) ? intval($company[0]) : null;
    $companyName = isset($company[1]) ? sanitize_text_field($company[1]) : null;

    $event_type = wp_get_post_terms($post_id, 'event_type') ?? '';
    $event_group = wp_get_post_terms($post_id, 'event_group') ?? '';

    $originalEvent = null;

    // filtrando termos
    $event_type = wp_list_pluck($event_type, 'name'); // tipos de evento
    $event_group = wp_list_pluck($event_group, 'name'); // grupos de evento

    $event_type = !empty($event_type) ? $event_type[0] : '';
    $event_group = !empty($event_group) ? $event_group[0] : '';






    $grecaptcha = $_POST['g-recaptcha-response'] ?? null;

    if (!$grecaptcha) {
        wp_send_json_error('Recaptcha inválido');
    }

    $secretKey = '6LfdJP0qAAAAALKnl2m_II3PahoyZw3Jq_rqstvl';

    $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $grecaptcha);
    $responseData = json_decode($response, true);

    if (!$responseData['success']) {
       wp_send_json_error('Recaptcha inválido');
    }



    $e164Number = generateE164Number(intval($_POST['ddi'] ?? 0), intval($_POST['ddd'] ?? 0), preg_replace('/\D/', '', $_POST['phone'] ?? ''));

    $data = [
        'customer' => [
            'name' => sanitize_text_field($_POST['name'] ?? ''),
            'emails' => [
                sanitize_email($_POST['email'] ?? '')
            ],
            'phones' => [
                [
                    'ddi' => intval($_POST['ddi'] ?? 0),
                    'ddd' => intval($_POST['ddd'] ?? 0),
                    'numero' => preg_replace('/\D/', '', $_POST['phone'] ?? ''),
                    'tipo' => sanitize_text_field($_POST['phone_type'] ?? ''),
                    'e164Number' => $e164Number
                ]
            ],
            'document' => sanitize_text_field($_POST['document'] ?? ''),
            'documentType' => sanitize_text_field($_POST['document_type'] ?? ''),
            'personType' => sanitize_text_field($_POST['person_type'] ?? ''),
            'contactPreferenceType' => $contact_preference,
            'addresses' => [
                [
                    'country' => sanitize_text_field($_POST['country'] ?? ''),
                    'state' => sanitize_text_field($_POST['state'] ?? ''),
                    'city' => sanitize_text_field($_POST['city'] ?? ''),
                    'postcode' => sanitize_text_field($_POST['postcode'] ?? ''),
                    'neighborhood' => sanitize_text_field($_POST['neighborhood'] ?? ''),
                    'number' => sanitize_text_field($_POST['address_number'] ?? ''),
                    'address' => sanitize_text_field($_POST['address'] ?? '')
                ]
            ]
        ],
        'event' => [
            'companyId' => $companyID,
            'eventGroup' => $event_group,
            'eventType' => $event_type,
            'source' => sanitize_text_field($_POST['source'] ?? 'INTERNET'),
            'media' => sanitize_text_field($_POST['media'] ?? 'SITE CONCESSIONARIA'),
            'comment' => sanitize_textarea_field($_POST['comment'] ?? ''),
            'userId' => null,
            'originalEvent' => $originalEvent,
            'leadInfo' => new stdClass()
        ],
        'daysToUpdateOpenEvent' => $days_to_update ?? NULL,
        'rules' => [
            'updateMainEmailPhone' => $update_data ?? NULL
        ],
        'additionalFields' => [
            [
                'kind' => 'Brand/Model',
                'value' => [
                    'brand' => sanitize_text_field($_POST['model']) ?? NULL
                ]
            ]
        ]
    ];

    $jsonData = json_encode($data);

    try {
        $customer = new Customer(
            $data['customer']['name'],
            $data['customer']['emails'],
            $data['customer']['phones'],
            $data['customer']['document'],
            $data['customer']['documentType'],
            $data['customer']['personType'],
            $data['customer']['addresses']
        );

        $event = new Event(
            [$data['event']['companyId']],
            $data['event']['eventGroup'],
            $data['event']['eventType'],
            $data['event']['source'],
            $data['event']['media'],
            $data['event']['comment'],
            $data['event']['originalEvent'],
            $data['event']['leadInfo']
        );

        $additionalFields = [
            new AdditionalFields('Brand/Model', ['brand' => $_POST['model'] ?? NULL])
        ];

        $payload = new RequestPayload(
            $customer,
            $event,
            $data['daysToUpdateOpenEvent'] ?? NULL,
            $data['rules']['updateMainEmailPhone'] ?? NULL,
            $additionalFields
        );

        $jsonData = json_encode($payload->toArray());
        $jsonData = json_encode($data);
        $apiResponse = sendDataToApi($api_link, $username, $password, $jsonData);

        $data_to_insert = [
            'name' => sanitize_text_field($_POST['name']),
            'email' => sanitize_email($_POST['email']),
            'ddi' => intval($_POST['ddi']),
            'ddd' => intval($_POST['ddd']),
            'phone' => preg_replace('/\D/', '', $_POST['phone']),
            'phone_type' => sanitize_text_field($_POST['phone_type']),
            'document' => sanitize_text_field($_POST['document']),
            'document_type' => sanitize_text_field($_POST['document_type']),
            'person_type' => sanitize_text_field($_POST['person_type']),
            'contact_preference_type' => $contact_preference,
            'country' => sanitize_text_field($_POST['country']),
            'state' => sanitize_text_field($_POST['state']),
            'city' => sanitize_text_field($_POST['city']),
            'postcode' => sanitize_text_field($_POST['postcode']),
            'neighborhood' => sanitize_text_field($_POST['neighborhood']),
            'address_number' => sanitize_text_field($_POST['address_number']),
            'address' => sanitize_text_field($_POST['address']),
            'company_id' => intval($companyID),
            'company' => sanitize_text_field($companyName),
            'model' => sanitize_text_field($_POST['model']),
            'source' => sanitize_text_field($_POST['source']),
            'media' => sanitize_text_field($_POST['media']),
            'comment' => sanitize_textarea_field($_POST['comment']),
            'days_to_update' => intval($days_to_update) ?? NULL,
            'rules' => json_encode($rules) ?? NULL,
            'additional_fields' => json_encode($additional_fields) ?? NULL,
            'json_data' => json_encode($data),
            'api_response' => json_encode($apiResponse) ?? NULL,
            'created_at' => current_time('mysql')
        ];

        $post_data = [
            'post_title'   => sanitize_text_field($_POST['name']),
            'post_content' => '',
            'post_status'  => 'publish',
            'post_type'    => 'syonet_submissions',
        ];
        $post_id = wp_insert_post($post_data);

        if (is_wp_error($post_id)) {
            wp_send_json_error('Erro ao criar o post: ' . $post_id->get_error_message());
        }

        foreach ($data_to_insert as $meta_key => $meta_value) {
            update_post_meta($post_id, $meta_key, $meta_value);
        }

        wp_send_json_success([
            'message' => 'Dados salvos e enviados com sucesso!',
            'api_data' => $jsonData
        ]);
    } catch (Exception $e) {
        wp_send_json_error('Erro no processamento: ' . $e->getMessage());
    }
}

add_action('wp_ajax_mpf_save_form', 'mpf_save_form');
add_action('wp_ajax_nopriv_mpf_save_form', 'mpf_save_form');
