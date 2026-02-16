<?php
// dist/index.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/auth_check.php';

// Obtendo a URL da variável de consulta (configurada no .htaccess)
// Se não usar .htaccess, a URL seria index.php?url=pedidos
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Dados da sessão para controle de acesso
$userRole = $_SESSION['role'] ?? 'user';

// Início do Buffer de saída para definir o Title dinamicamente
ob_start();

$pageContent = '';
$pageTitle = 'Pedidos SmartHard';

// Roteamento baseado na URL
switch ($url) {
    case '':
    case 'dashboard':
        $pageTitle = 'Dashboard | Pedidos SmartHard';
        $pageContent = 'dashboard_home.php';
        break;

    case 'pedidos':
        $pageTitle = 'Gerenciar Pedidos | Pedidos SmartHard';
        $pageContent = 'pedidos.php';
        break;

    case 'history-pedidos':
        $pageTitle = 'Histórico de Pedidos | Pedidos SmartHard';
        $pageContent = 'historicoPedidos.php';
        break;

    case 'usuarios':
        if ($userRole === 'admin') {
            $pageTitle = 'Gestão de Usuários | Pedidos SmartHard';
            $pageContent = 'usuarios.php';
        } else {
            $pageContent = '403.php'; // Ou uma mensagem de erro
        }
        break;

    case 'fornecedores':
        if ($userRole === 'admin') {
            $pageTitle = 'Fornecedores | Pedidos SmartHard';
            $pageContent = 'fornecedores.php';
        } else {
            $pageContent = '403.php';
        }
        break;

    case 'settings':
        $pageTitle = 'Minhas Configurações | Pedidos SmartHard';
        $pageContent = 'settings.php';
        break;

    default:
        $pageTitle = 'Página Não Encontrada';
        $pageContent = '404.php';
        break;
}

// Renderização da estrutura AdminLTE
include_once __DIR__ . '/includes/header.php'; // O header deve usar a variável $pageTitle
include_once __DIR__ . '/includes/sidebar.php';

echo '<main class="app-main">';
echo '  <div class="app-content-header">';
echo '    <div class="container-fluid">';

if (file_exists(__DIR__ . '/' . $pageContent)) {
    include_once __DIR__ . '/' . $pageContent;
} else {
    echo "<h1>Erro: Arquivo $pageContent não encontrado.</h1>";
}

echo '    </div>';
echo '  </div>';
echo '</main>';

include_once __DIR__ . '/includes/footer.php';

ob_end_flush();
?>