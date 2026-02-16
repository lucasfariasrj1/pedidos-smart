<?php
// dist/includes/auth_check.php
session_start();

if (!isset($_COOKIE['jwt_token']) && basename($_SERVER['PHP_SELF']) !== 'login.php') {
    header("Location: login.php");
    exit;
}

// Opcional: Decodificar JWT aqui para validar expiração no servidor
?>