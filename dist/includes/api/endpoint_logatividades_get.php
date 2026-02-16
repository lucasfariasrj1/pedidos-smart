<?php

function endpointLogatividadesGet(string $token): array
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.assistenciasmarthard.com.br/auth/logatividades',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS => '',
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    $statusCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($err) {
        return ['ok' => false, 'status' => 500, 'data' => ['error' => 'cURL Error #: ' . $err]];
    }

    $json = json_decode((string) $response, true);
    return ['ok' => $statusCode >= 200 && $statusCode < 300, 'status' => $statusCode, 'data' => is_array($json) ? $json : ['raw' => $response]];
}
