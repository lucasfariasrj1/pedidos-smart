<?php
// dist/index.php
require_once 'config.php';
require_once 'includes/auth_check.php'; // Protege as páginas

$page = $_GET['page'] ?? 'dashboard';

// Estrutura do AdminLTE
include 'includes/header.php';
include 'includes/sidebar.php';

echo '<main class="app-main"><div class="app-content-header"><div class="container-fluid">';

switch ($page) {
    case 'dashboard':
        include '/dashboard_home.php'; // Crie este ficheiro com o conteúdo da home
        break;
    case 'pedidos':
        include '/pedidos.php';
        break;
    case 'history-pedidos':
        include '/historicoPedidos.php';
        break;
    case 'usuarios':
        if ($_SESSION['role'] === 'admin') include 'usuarios.php';
        break;
    case 'fornecedores':
        include 'fornecedores.php';
        break;
    case 'settings':
        if ($_SESSION['role'] === 'admin') include 'settings.php';
        break;
    case 'logs':
        if ($_SESSION['role'] === 'admin') include 'logs.php';
        break;
    default:
        echo "<h1>Página não encontrada</h1>";
        break;
}

echo '</div></div></main>';
include 'includes/footer.php';
?>