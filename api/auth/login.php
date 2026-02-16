<?php
header("Content-Type: application/json; charset=utf-8");

// Recebe JSON do frontend
$input = json_decode(file_get_contents("php://input"), true);

// Validação básica
if (!isset($input["email"]) || !isset($input["senha"])) {
    http_response_code(400);
    echo json_encode([
        "error" => true,
        "message" => "Email e senha são obrigatórios."
    ]);
    exit;
}

$email = $input["email"];
$senha = $input["senha"];

// Monta payload para API
$payload = json_encode([
    "email" => $email,
    "senha" => $senha
]);

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.assistenciasmarthard.com.br/auth/login",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Accept: application/json"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);

// Tratamento de erro
if ($err) {
    http_response_code(500);
    echo json_encode([
        "error" => true,
        "message" => "Erro ao conectar na API",
        "details" => $err
    ]);
    exit;
}

// Retorna a resposta original da API Node
http_response_code($statusCode);
echo $response;
