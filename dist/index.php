<?php
// dist/index.php
require_once 'config.php';
require_once 'includes/auth_check.php'; // Garante que $_SESSION['user'] existe ou redireciona

// O parâmetro 'page' vem da URL via GET (ex: index.php?page=pedidos)
$page = $_GET['page'] ?? 'dashboard';

// Pegamos o papel (role) do usuário da sessão para controle de acesso
$userRole = $_SESSION['role'] ?? 'user';

// Estrutura do Painel AdminLTE
include 'includes/header.php';
include 'includes/sidebar.php';

echo '<main class="app-main">';
echo '  <div class="app-content-header">';
echo '    <div class="container-fluid">';

switch ($page) {
    case 'dashboard':
        include 'dashboard_home.php';
        break;

    case 'pedidos':
        // Acesso para Admin (todos) e User (própria loja)
        include 'pedidos.php';
        break;

    case 'history-pedidos':
        include 'historicoPedidos.php';
        break;

    case 'fornecedores':
        // No Swagger, listar é permitido, mas criar/deletar exige admin.
        // Se quiser que apenas admin veja a página inteira:
        if ($userRole === 'admin') {
            include 'fornecedores.php';
        } else {
            echo '<div class="alert alert-warning">Acesso restrito a administradores.</div>';
        }
        break;

    case 'usuarios':
        // Rota exclusiva admin conforme Swagger (/auth/users/listall)
        if ($userRole === 'admin') {
            include 'usuarios.php';
        } else {
            echo '<div class="alert alert-danger">Acesso negado.</div>';
        }
        break;

    case 'settings':
        // Configurações do perfil (conforme endpoint /auth/users/me)
        include 'settings.php';
        break;

    case 'logs':
        if ($userRole === 'admin') {
            include 'logs.php';
        }
        break;

    default:
        echo '<div class="text-center"><h1>404</h1><p>Página não encontrada.</p></div>';
        break;
}

echo '    </div>';
echo '  </div>';
echo '</main>';

include 'includes/footer.php';
?>