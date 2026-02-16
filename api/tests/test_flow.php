<?php
/**
 * Teste CLI básico que exercita o fluxo de autenticação e chamadas protegidas
 * Ajuste BASE_URL, EMAIL, SENHA conforme seu ambiente.
 * Uso: php api/tests/test_flow.php
 */

$BASE_URL = getenv('ORCAFACIL_BASE') ?: 'http://localhost/orcafacil';
$EMAIL = getenv('ORCAFACIL_EMAIL') ?: 'admin@example.com';
$SENHA = getenv('ORCAFACIL_SENHA') ?: 'password';

function call($method, $url, $body = null, $token = null) {
    $ch = curl_init();
    $headers = ['Accept: application/json'];
    if ($body !== null) { $headers[] = 'Content-Type: application/json'; curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body)); }
    if ($token) $headers[] = 'Authorization: Bearer ' . $token;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($resp === false) { echo "CURL ERROR: " . curl_error($ch) . PHP_EOL; curl_close($ch); return [0,null]; }
    curl_close($ch);
    $json = json_decode($resp, true);
    return [$code, $json];
}

echo "Base: $BASE_URL" . PHP_EOL;

// 1) Login
[$code, $json] = call('POST', $BASE_URL . '/api/auth/login.php', ['email'=>$EMAIL,'senha'=>$SENHA]);
echo "Login: HTTP $code\n";
print_r($json);

$token = null;
if (is_array($json) && isset($json['token'])) $token = $json['token'];
elseif (is_array($json) && isset($json['user']) && isset($json['user']['token'])) $token = $json['user']['token'];

if (!$token) { echo "Token não obtido — verifique credenciais e proxy. Abortando.\n"; exit(1); }
echo "Token obtido: " . substr($token,0,20) . "..." . PHP_EOL;

// 2) List users
[$code,$json] = call('GET', $BASE_URL . '/api/auth/users/listall.php', null, $token);
echo "List Users: HTTP $code\n"; print_r(is_array($json) ? array_slice($json,0,5) : $json);

// 3) List orders
[$code,$json] = call('GET', $BASE_URL . '/api/orders/index.php', null, $token);
echo "List Orders: HTTP $code\n"; print_r(is_array($json) ? array_slice($json,0,5) : $json);

// 4) List fornecedores
[$code,$json] = call('GET', $BASE_URL . '/api/fornecedores/index.php', null, $token);
echo "List Fornecedores: HTTP $code\n"; print_r(is_array($json) ? array_slice($json,0,5) : $json);

echo "Teste finalizado." . PHP_EOL;
