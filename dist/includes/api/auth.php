<?php

require_once __DIR__ . '/client.php';

$loginError = null;

function loginEndpoint(string $email, string $senha): array
{
    return apiRequest('POST', '/login', [
        'email' => $email,
        'senha' => $senha,
    ]);
}

function registerEndpoint(string $email, string $senha, string $role, int $lojaId): array
{
    return apiRequest('POST', '/register', [
        'email' => $email,
        'senha' => $senha,
        'role' => $role,
        'loja_id' => $lojaId,
    ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && basename($_SERVER['PHP_SELF']) === 'login.php') {
    $email = trim($_POST['email'] ?? '');
    $senha = (string) ($_POST['senha'] ?? '');

    if ($email === '' || $senha === '') {
        $loginError = 'E-mail e senha são obrigatórios.';
    } else {
        $result = loginEndpoint($email, $senha);
        $token = $result['data']['token'] ?? null;

        if (!$result['ok'] || !$token) {
            $loginError = $result['data']['message'] ?? $result['data']['error'] ?? 'Falha na autenticação.';
        } else {
            $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
            setcookie('jwt_token', $token, [
                'expires' => time() + 28800,
                'path' => '/',
                'secure' => $secure,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);

            if (function_exists('decodeJwtPayload')) {
                $payload = decodeJwtPayload($token);
                if (is_array($payload)) {
                    $_SESSION['jwt_payload'] = $payload;
                    $_SESSION['role'] = strtolower((string) ($payload['role'] ?? 'user'));
                    $_SESSION['name'] = $payload['name'] ?? 'Usuário';
                    $_SESSION['email'] = $payload['email'] ?? $email;
                }
            }

            header('Location: ' . rtrim(BASE_URL, '/') . '/index.php?url=dashboard');
            exit;
        }
    }
}
