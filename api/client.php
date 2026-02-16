<?php
header('Content-Type: application/json; charset=utf-8');

function get_request_headers_fallback() {
    $headers = [];
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) === 'HTTP_') {
            $h = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
            $headers[$h] = $value;
        }
    }
    if (isset($_SERVER['CONTENT_TYPE'])) $headers['Content-Type'] = $_SERVER['CONTENT_TYPE'];
    return $headers;
}

function forward_request(string $path) {
    $base = 'https://api.assistenciasmarthard.com.br';
    $method = $_SERVER['REQUEST_METHOD'];

    $url = rtrim($base, '/') . $path;
    if ($method === 'GET' && !empty($_SERVER['QUERY_STRING'])) {
        $url .= '?' . $_SERVER['QUERY_STRING'];
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);

    $rawHeaders = function_exists('getallheaders') ? getallheaders() : get_request_headers_fallback();
    $outHeaders = [];
    foreach ($rawHeaders as $k => $v) {
        if (strtolower($k) === 'host') continue;
        $outHeaders[] = $k . ': ' . $v;
    }

    if (in_array($method, ['POST','PUT','PATCH'])) {
        $body = file_get_contents('php://input');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $hasCT = false;
        foreach ($outHeaders as $h) if (stripos($h, 'content-type:') === 0) $hasCT = true;
        if (!$hasCT) $outHeaders[] = 'Content-Type: application/json';
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $outHeaders);

    $resp = curl_exec($ch);
    if ($resp === false) {
        $err = curl_error($ch);
        curl_close($ch);
        http_response_code(502);
        echo json_encode(['error' => 'gateway_error', 'message' => $err]);
        return;
    }

    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $resp_header = substr($resp, 0, $header_size);
    $resp_body = substr($resp, $header_size);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // forward select headers
    $lines = preg_split('/\r?\n/', $resp_header);
    foreach ($lines as $line) {
        if (stripos($line, 'Content-Type:') === 0) header($line);
        if (stripos($line, 'Set-Cookie:') === 0) header($line, false);
    }

    http_response_code($http_code);
    echo $resp_body;
}
