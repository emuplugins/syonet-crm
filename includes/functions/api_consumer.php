<?php

if ( ! defined('ABSPATH')) exit;

function sendDataToApi($url, $username, $password, $jsonData) {
    
    $credentials = base64_encode("$username:$password");

    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Basic ' . $credentials
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return "cURL Error: " . $error_msg;
    }

    curl_close($ch);
    if ( ! $response) {
        return "Erro ao enviar dados para a API";
    }
    return $response;
}
