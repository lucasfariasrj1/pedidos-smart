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

    // Collect incoming headers but only forward Authorization explicitly
    $incoming = function_exists('getallheaders') ? getallheaders() : get_request_headers_fallback();
    $authHeader = null;
    foreach ($incoming as $k => $v) {
        if (strtolower($k) === 'authorization') { $authHeader = $v; break; }
    }

    $ch = curl_init();

    // Prepare default headers to avoid not-allowed issues
    $outHeaders = [
        'Content-Type: application/json',
        'User-Agent: orcafacil-proxy/1.0'
    ];
    if ($authHeader) $outHeaders[] = 'Authorization: ' . $authHeader;

    $body = null;
    if (in_array($method, ['POST','PUT','PATCH','DELETE'])) {
        $raw = file_get_contents('php://input');
        // Send raw body as-is (assume JSON)
        $body = $raw === false ? null : $raw;
    }

    $curlOptions = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HEADER => true,
        CURLOPT_HTTPHEADER => $outHeaders,
    ];

    if ($body !== null) $curlOptions[CURLOPT_POSTFIELDS] = $body;

    curl_setopt_array($ch, $curlOptions);

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
