<?php
define('API_BASE_URL', 'https://api.assistenciasmarthard.com.br');

if (!function_exists('apiBaseUrl')) {
    function apiBaseUrl(): string
    {
        if (defined('API_BASE_URL')) {
            return rtrim((string) API_BASE_URL, '/');
        }

        $envUrl = getenv('API_BASE_URL');
        if (is_string($envUrl) && $envUrl !== '') {
            return rtrim($envUrl, '/');
        }

        return 'https://api.assistenciasmarthard.com.br';
    }
}

if (!function_exists('apiAuthToken')) {
    function apiAuthToken(): ?string
    {
        $token = $_COOKIE['jwt_token'] ?? null;
        return is_string($token) && $token !== '' ? $token : null;
    }
}

if (!function_exists('apiRequest')) {
    function apiRequest(string $method, string $path, ?array $body = null, ?string $token = null): array
    {
        $url = apiBaseUrl() . '/' . ltrim($path, '/');
        $headers = [
            'Accept: application/json',
        ];

        if ($body !== null) {
            $headers[] = 'Content-Type: application/json';
            $payload = json_encode($body, JSON_UNESCAPED_UNICODE);
        } else {
            $payload = null;
        }

        if ($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => $headers,
        ]);

        if ($payload !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            return [
                'ok' => false,
                'status' => 500,
                'data' => [
                    'message' => 'Erro ao conectar com a API externa.',
                    'details' => $error,
                ],
            ];
        }

        $decoded = json_decode((string) $response, true);
        $data = is_array($decoded) ? $decoded : ['raw' => $response];

        return [
            'ok' => $status >= 200 && $status < 300,
            'status' => $status,
            'data' => $data,
        ];
    }
}
