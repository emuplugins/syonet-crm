<?php

// Função para enviar dados para a API usando Bearer Token
function sendDataToApi($url, $username, $password, $jsonData) {
    // Codifica as credenciais em Base64
    $credentials = base64_encode("$username:$password");

    // Inicializando a requisição cURL
    $ch = curl_init($url);

    // Configurando as opções do cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Basic ' . $credentials // Usando a autenticação básica
    ]);

    // Executando a requisição e capturando a resposta
    $response = curl_exec($ch);

    // Verificando se houve erro na requisição
    if (curl_errno($ch)) {
        // Caso ocorra erro, captura e exibe o erro
        $error_msg = curl_error($ch);
        curl_close($ch);
        return "cURL Error: " . $error_msg;  // Retorna o erro
    }

    // Fechando a conexão cURL
    curl_close($ch);
    if ( ! $response) {
        return "Erro ao enviar dados para a API";
    }
    // Retorna a resposta da API
    return $response;
}