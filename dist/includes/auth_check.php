<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('base64UrlDecode')) {
    function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($data, '-_', '+/')) ?: '';
    }
}

if (!function_exists('decodeJwtPayload')) {
    function decodeJwtPayload(string $jwt): ?array
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return null;
        }

        $payload = json_decode(base64UrlDecode($parts[1]), true);
        return is_array($payload) ? $payload : null;
    }
}

if (!function_exists('clearAuthData')) {
    function clearAuthData(): void
    {
        $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        setcookie('jwt_token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}

if (!function_exists('redirectToLogin')) {
    function redirectToLogin(string $reason = 'unauthorized'): void
    {
        $location = (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '') . '/login.php?reason=' . urlencode($reason);
        header('Location: ' . $location);
        exit;
    }
}

$currentScript = basename($_SERVER['PHP_SELF'] ?? '');
$isLoginPage = $currentScript === 'login.php';
$token = $_COOKIE['jwt_token'] ?? null;

if (!$token) {
    if (!$isLoginPage) {
        redirectToLogin('missing_token');
    }
    return;
}

$payload = decodeJwtPayload($token);
$exp = isset($payload['exp']) ? (int) $payload['exp'] : 0;

if (!$payload || ($exp > 0 && $exp <= time())) {
    clearAuthData();
    if (!$isLoginPage) {
        redirectToLogin('expired_token');
    }
    return;
}

$_SESSION['jwt_payload'] = $payload;
$_SESSION['user_id'] = $payload['sub'] ?? ($_SESSION['user_id'] ?? null);
$_SESSION['email'] = $payload['email'] ?? ($_SESSION['email'] ?? null);
$_SESSION['name'] = $payload['name'] ?? ($_SESSION['name'] ?? 'Usuário');
$_SESSION['role'] = strtolower((string) ($payload['role'] ?? ($_SESSION['role'] ?? 'user')));
$_SESSION['loja_id'] = isset($payload['loja_id']) ? (int) $payload['loja_id'] : ($_SESSION['loja_id'] ?? null);
$_SESSION['token_exp'] = $exp;

if ($isLoginPage) {
    $dashboardUrl = (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '') . '/index.php?url=dashboard';
    header('Location: ' . $dashboardUrl);
    exit;
}
