<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/permissions.php';

$url = isset($_GET['url']) ? trim($_GET['url'], '/') : '';
$userRole = $_SESSION['role'] ?? 'user';
$currentPage = $url === '' ? 'dashboard' : $url;

$pageTitle = 'Dashboard | SmartHard';
$pageFile = 'dashboard_home.php';

switch ($url) {
    case '':
    case 'dashboard':
        $pageTitle = 'Dashboard | SmartHard';
        $pageFile = 'dashboard_home.php';
        break;

    case 'pedidos':
        $pageTitle = 'Pedidos | SmartHard';
        $pageFile = 'pedidos.php';
        break;

    case 'history-pedidos':
        $pageTitle = 'Histórico de Pedidos | SmartHard';
        $pageFile = 'historicoPedidos.php';
        break;

    case 'usuarios':
        $pageTitle = 'Usuários | SmartHard';
        $pageFile = isAdmin() ? 'usuarios.php' : '401.php';
        break;

    case 'fornecedores':
        $pageTitle = 'Fornecedores | SmartHard';
        $pageFile = isAdmin() ? 'fornecedores.php' : '401.php';
        break;

    case 'logs':
        $pageTitle = 'Logs | SmartHard';
        $pageFile = isAdmin() ? 'logs.php' : '401.php';
        break;

    case 'settings':
        $pageTitle = 'Configurações | SmartHard';
        $pageFile = 'settings.php';
        break;

    case '401':
        $pageTitle = '401 | SmartHard';
        $pageFile = '401.php';
        break;

    case '500':
        $pageTitle = '500 | SmartHard';
        $pageFile = '500.php';
        break;

    default:
        $pageTitle = '404 | SmartHard';
        $pageFile = '404.php';
        break;
}

include_once __DIR__ . '/includes/header.php';
include_once __DIR__ . '/includes/sidebar.php';
?>

<main class="app-main">
<?php
$fullPath = __DIR__ . '/' . $pageFile;
if (file_exists($fullPath)) {
    include $fullPath;
} else {
    include __DIR__ . '/500.php';
}
?>
</main>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
