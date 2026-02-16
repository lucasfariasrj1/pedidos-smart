<?php
include_once __DIR__ . '/includes/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destrói todos os dados da sessão
$_SESSION = [];
session_destroy();

// Redireciona para login
header('Location: /login.php');
exit;
