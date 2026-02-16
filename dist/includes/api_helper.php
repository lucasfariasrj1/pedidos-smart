<?php

require_once __DIR__ . '/../config.php';

const API_BASE_URL = 'https://api.assistenciasmarthard.com.br';

function clearAuthCookie(): void
{
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    setcookie('jwt_token', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function redirectToLogin(): void
{
    clearAuthCookie();
    header('Location: login.php');
    exit;
}

function callApi(string $method, string $endpoint, ?array $data = null): array
{
    $token = $_COOKIE['jwt_token'] ?? null;
    $url = rtrim(API_BASE_URL, '/') . '/' . ltrim($endpoint, '/');

    $curl = curl_init();

    $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
    ];

    if (!empty($token)) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }

    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => strtoupper($method),
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => $headers,
    ];

    if ($data !== null && in_array(strtoupper($method), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
        $options[CURLOPT_POSTFIELDS] = json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    curl_setopt_array($curl, $options);

    $response = curl_exec($curl);
    $error = curl_error($curl);
    $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($error) {
        return [
            'ok' => false,
            'status' => 500,
            'data' => null,
            'error' => 'Erro de conexão com API: ' . $error,
        ];
    }

    $decoded = null;
    if ($response !== false && $response !== '') {
        $decoded = json_decode($response, true);
    }

    if (json_last_error() !== JSON_ERROR_NONE) {
        $decoded = ['message' => $response];
    }

    return [
        'ok' => $status >= 200 && $status < 300,
        'status' => $status,
        'data' => $decoded,
        'error' => null,
    ];
}

function apiData(array $response)
{
    $payload = $response['data'] ?? null;

    if (!is_array($payload)) {
        return $payload;
    }

    if (array_key_exists('data', $payload)) {
        return $payload['data'];
    }

    return $payload;
}

function apiMessage(array $response, string $fallback = 'Operação não pôde ser concluída.'): string
{
    $payload = $response['data'] ?? null;

    if (is_array($payload)) {
        if (!empty($payload['message']) && is_string($payload['message'])) {
            return $payload['message'];
        }
        if (!empty($payload['error']) && is_string($payload['error'])) {
            return $payload['error'];
        }
    }

    if (!empty($response['error']) && is_string($response['error'])) {
        return $response['error'];
    }

    return $fallback;
}
