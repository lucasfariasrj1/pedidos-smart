<?php
// dist/index.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/auth_check.php';

// Limpeza da URL para o Nginx
$url = isset($_GET['url']) ? trim($_GET['url'], '/') : '';

// Dados de Sessão
$userRole = $_SESSION['role'] ?? 'user';

// Definição de Título e Arquivo
$pageTitle = "Pedidos SmartHard";
$pageFile = "dashboard_home.php";

switch ($url) {
    case '':
    case 'dashboard':
        $pageTitle = "Dashboard | SmartHard";
        $pageFile = "dashboard_home.php";
        break;
    case 'pedidos':
        $pageTitle = "Pedidos | SmartHard";
        $pageFile = "pedidos.php";
        break;
    case 'history-pedidos':
        $pageTitle = "Histórico | SmartHard";
        $pageFile = "historicoPedidos.php";
        break;
    case 'usuarios':
        $pageFile = ($userRole === 'admin') ? "usuarios.php" : "403.php";
        break;
    case 'fornecedores':
        $pageFile = ($userRole === 'admin') ? "fornecedores.php" : "403.php";
        break;
    case 'settings':
        $pageFile = "settings.php";
        break;
    default:
        $pageFile = "404.php";
        break;
}

// 1. O Header deve vir primeiro, sem nenhum espaço ou "echo" antes dele
include_once __DIR__ . '/includes/header.php';

// 2. O Sidebar
include_once __DIR__ . '/includes/sidebar.php';

// 3. Estrutura Principal com classes do AdminLTE 4 (app-main)
?>
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><?php echo str_replace(' | SmartHard', '', $pageTitle); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <?php 
            $fullPath = __DIR__ . '/' . $pageFile;
            if (file_exists($fullPath)) {
                include_once $fullPath;
            } else {
                echo "<div class='alert alert-danger'>Página não encontrada.</div>";
            }
            ?>
        </div>
    </div>
</main>
<?php
// 4. Footer
include_once __DIR__ . '/includes/footer.php';
?>