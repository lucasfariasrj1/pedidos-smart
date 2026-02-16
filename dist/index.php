<?php
// dist/index.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/auth_check.php';

// No Nginx, a variável $uri no try_files costuma vir com a barra inicial.
// O trim($url, '/') é fundamental para que o switch('pedidos') funcione.
$url = isset($_GET['url']) ? trim($_GET['url'], '/') : '';

$userRole = $_SESSION['role'] ?? 'user';
$lojaId   = $_SESSION['loja_id'] ?? 0;

$pageTitle = "Pedidos SmartHard";
$pageFile = "dashboard_home.php";

switch ($url) {
    case '':
    case 'dashboard':
        $pageTitle = "Dashboard | SmartHard";
        $pageFile = "dashboard_home.php";
        break;

    case 'pedidos':
        $pageTitle = "Gerenciar Pedidos | SmartHard";
        $pageFile = "pedidos.php";
        break;

    case 'history-pedidos':
        $pageTitle = "Histórico | SmartHard";
        $pageFile = "historicoPedidos.php";
        break;

    case 'usuarios':
        if ($userRole === 'admin') {
            $pageTitle = "Usuários | SmartHard";
            $pageFile = "usuarios.php";
        } else {
            $pageFile = "403.php"; 
        }
        break;

    case 'fornecedores':
        if ($userRole === 'admin') {
            $pageTitle = "Fornecedores | SmartHard";
            $pageFile = "fornecedores.php";
        } else {
            $pageFile = "403.php";
        }
        break;

    case 'settings':
        $pageTitle = "Configurações | SmartHard";
        $pageFile = "settings.php";
        break;

    default:
        $pageTitle = "404 - Não Encontrado";
        $pageFile = "404.php";
        break;
}

// Inclusão da estrutura
include_once __DIR__ . '/includes/header.php';
include_once __DIR__ . '/includes/sidebar.php';

echo '<main class="app-main"><div class="app-content-header"><div class="container-fluid">';

$fullPath = __DIR__ . '/' . $pageFile;

if (file_exists($fullPath)) {
    include_once $fullPath;
} else {
    echo "<h1>Erro: Arquivo {$pageFile} não encontrado.</h1>";
}

echo '</div></div></main>';

include_once __DIR__ . '/includes/footer.php';
?>