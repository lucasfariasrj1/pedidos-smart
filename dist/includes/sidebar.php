<?php
// Define a página atual para marcar o menu como ativo
$currentPage = $_GET['page'] ?? 'dashboard';
// Recupera o papel do usuário da sessão (ajuste conforme seu sistema de login)
$userRole = $_SESSION['role'] ?? 'usuario'; 
?>

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    <a href="index.php?page=dashboard" class="brand-link">
      <span class="brand-text fw-light">Sistema de Pedidos</span>
    </a>
  </div>

  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" data-accordion="false" id="navigation">
        
        <li class="nav-item">
          <a href="index.php?page=dashboard" class="nav-link <?= $currentPage == 'dashboard' ? 'active' : '' ?>">
            <i class="nav-icon bi bi-speedometer2"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-header">OPERAÇÃO</li>

        <li class="nav-item">
          <a href="index.php?page=pedidos" class="nav-link <?= $currentPage == 'pedidos' ? 'active' : '' ?>">
            <i class="nav-icon bi bi-plus-circle-fill"></i>
            <p>Fazer Pedido</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="index.php?page=history-pedidos" class="nav-link <?= $currentPage == 'history-pedidos' ? 'active' : '' ?>">
            <i class="nav-icon bi bi-clock-history"></i>
            <p>Histórico de Pedidos</p>
          </a>
        </li>

        <li class="nav-header">GERENCIAMENTO</li>

        <li class="nav-item">
          <a href="index.php?page=fornecedores" class="nav-link <?= $currentPage == 'fornecedores' ? 'active' : '' ?>">
            <i class="nav-icon bi bi-truck"></i>
            <p>Fornecedores</p>
          </a>
        </li>

        <?php if ($userRole === 'admin'): ?>
        <li class="nav-item <?= in_array($currentPage, ['usuarios', 'logs', 'settings']) ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= in_array($currentPage, ['usuarios', 'logs', 'settings']) ? 'active' : '' ?>">
            <i class="nav-icon bi bi-shield-lock-fill"></i>
            <p>
              Administração
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="index.php?page=usuarios" class="nav-link <?= $currentPage == 'usuarios' ? 'active' : '' ?>">
                <i class="nav-icon bi bi-people"></i>
                <p>Usuários e Lojas</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="index.php?page=logs" class="nav-link <?= $currentPage == 'logs' ? 'active' : '' ?>">
                <i class="nav-icon bi bi-journal-text"></i>
                <p>Logs de Atividade</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="index.php?page=settings" class="nav-link <?= $currentPage == 'settings' ? 'active' : '' ?>">
                <i class="nav-icon bi bi-gear"></i>
                <p>Configurações</p>
              </a>
            </li>
          </ul>
        </li>
        <?php endif; ?>

        <li class="nav-item mt-3">
          <a href="logout.php" class="nav-link text-danger">
            <i class="nav-icon bi bi-box-arrow-right"></i>
            <p>Sair do Sistema</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>