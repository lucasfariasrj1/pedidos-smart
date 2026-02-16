<?php
include_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/api/logout.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$token = $_COOKIE['jwt_token'] ?? '';
if ($token !== '') {
    logoutEndpoint($token);
}

$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
setcookie('jwt_token', '', [
    'expires' => time() - 3600,
    'path' => '/',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax',
]);

$_SESSION = [];
session_destroy();

header('Location: ' . rtrim(BASE_URL, '/') . '/login.php?reason=missing_token');
exit;
