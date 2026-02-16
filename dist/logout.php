<?php
include_once __DIR__ . '/includes/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
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

header('Location: ' . rtrim(BASE_URL, '/') . '/login.php');
exit;
