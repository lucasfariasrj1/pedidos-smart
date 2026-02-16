<?php
// dist/index.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/auth_check.php';

// Define a página atual ou o dashboard por padrão
$page = $_GET['page'] ?? 'dashboard';

// Recupera dados da sessão protegidos pelo auth_check.php
$userRole = $_SESSION['role'] ?? 'user';
$lojaId   = $_SESSION['loja_id'] ?? 0;

// Inclui componentes estruturais do AdminLTE
include_once __DIR__ . '/includes/header.php';
include_once __DIR__ . '/includes/sidebar.php';

echo '<main class="app-main">';
echo '  <div class="app-content-header">';
echo '    <div class="container-fluid">';

// Caminho absoluto para evitar erros de inclusão
$pageDir = __DIR__ . '/';

switch ($page) {
    case 'dashboard':
        include_once $pageDir . 'dashboard_home.php';
        break;

    case 'pedidos':
        // Todos podem ver a página, o filtro de loja_id será feito dentro do pedidos.php via API
        include_once $pageDir . 'pedidos.php';
        break;

    case 'history-pedidos':
        include_once $pageDir . 'historicoPedidos.php';
        break;

    case 'usuarios':
        // Restrição de nível de acesso para Admin
        if ($userRole === 'admin') {
            include_once $pageDir . 'usuarios.php';
        } else {
            echo '<div class="alert alert-danger">Acesso restrito ao Administrador.</div>';
        }
        break;

    case 'fornecedores':
        // Conforme Swagger, admin gerencia fornecedores
        if ($userRole === 'admin') {
            include_once $pageDir . 'fornecedores.php';
        } else {
            echo '<div class="alert alert-warning">Acesso restrito a administradores.</div>';
        }
        break;

    case 'settings':
        include_once $pageDir . 'settings.php';
        break;

    case 'logs':
        if ($userRole === 'admin') {
            include_once $pageDir . 'logs.php';
        }
        break;

    default:
        echo '<div class="p-5 text-center"><h2>404</h2><p>Página não encontrada.</p></div>';
        break;
}

echo '    </div>';
echo '  </div>';
echo '</main>';

include_once __DIR__ . '/includes/footer.php';
?>