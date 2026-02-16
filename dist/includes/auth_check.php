<?php
require_once __DIR__ . '/../config.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function base64UrlDecode(string $data): string
{
    $remainder = strlen($data) % 4;
    if ($remainder) {
        $data .= str_repeat('=', 4 - $remainder);
    }

    return base64_decode(strtr($data, '-_', '+/')) ?: '';
}

function decodeJwtPayload(string $jwt): ?array
{
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) {
        return null;
    }

    $payload = json_decode(base64UrlDecode($parts[1]), true);
    return is_array($payload) ? $payload : null;
}

$token = $_SESSION['jwt_token'] ?? $_COOKIE['jwt_token'] ?? null;

if (!$token) {
    header('Location: login.php');
    exit;
}

$payload = decodeJwtPayload($token);
if (!$payload || (!empty($payload['exp']) && (int)$payload['exp'] < time())) {
    setcookie('jwt_token', '', time() - 3600, '/');
    unset($_SESSION['jwt_token'], $_SESSION['user_payload']);
    header('Location: login.php');
    exit;
}

$_SESSION['jwt_token'] = $token;
$_SESSION['user_payload'] = $payload;

$role = $payload['role'] ?? 'usuario';
if (isMaintenanceMode() && $role !== 'admin') {
    header('Location: manutencao.php');
    exit;
}
$currentPage = basename($_SERVER['PHP_SELF']);
$adminOnlyPages = ['usuarios.php', 'fornecedores.php', 'settings.php', 'logs.php'];
$userAllowedPages = ['pedidos.php', 'historicoPedidos.php', 'index.php'];

if ($role !== 'admin' && in_array($currentPage, $adminOnlyPages, true)) {
    http_response_code(403);
    header('Location: pedidos.php');
    exit;
}

if ($role !== 'admin' && !in_array($currentPage, $userAllowedPages, true)) {
    http_response_code(403);
    header('Location: pedidos.php');
    exit;
}
