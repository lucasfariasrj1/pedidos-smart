<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/auth_check.php';

$page = isset($_GET['page']) ? trim((string)$_GET['page']) : '';

if ($page === '') {
    header('Location: index.php?page=dashboard');
    exit;
}

$role = $_SESSION['user_payload']['role'] ?? 'usuario';

switch ($page) {
    case 'dashboard':
        include __DIR__ . '/dashboard.php';
        break;

    case 'pedidos':
        include __DIR__ . '/pedidos.php';
        break;

    case 'history-pedidos':
    case 'historico-pedidos':
        include __DIR__ . '/historicoPedidos.php';
        break;

    case 'fornecedores':
        if ($role !== 'admin') {
            header('Location: index.php?page=dashboard');
            exit;
        }
        include __DIR__ . '/fornecedores.php';
        break;

    case 'usuarios':
        if ($role !== 'admin') {
            header('Location: index.php?page=dashboard');
            exit;
        }
        include __DIR__ . '/usuarios.php';
        break;

    case 'settings':
        if ($role !== 'admin') {
            header('Location: index.php?page=dashboard');
            exit;
        }
        include __DIR__ . '/settings.php';
        break;

    case 'logs':
        if ($role !== 'admin') {
            header('Location: index.php?page=dashboard');
            exit;
        }
        include __DIR__ . '/logs.php';
        break;

    default:
        http_response_code(404);
        echo 'Página não encontrada.';
        break;
}
