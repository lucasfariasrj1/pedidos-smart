<?php
$baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
$currentRoute = $url ?? '';
?>
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    <a href="<?= $baseUrl; ?>/dashboard" class="brand-link">
      <span class="brand-text fw-light">Sistema de Pedidos</span>
    </a>
  </div>
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" data-accordion="false">
        <li class="nav-item">
          <a href="<?= $baseUrl; ?>/dashboard" class="nav-link <?= ($currentRoute === '' || $currentRoute === 'dashboard') ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-speedometer2"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-header">OPERAÇÃO</li>

        <li class="nav-item">
          <a href="<?= $baseUrl; ?>/pedidos" class="nav-link <?= $currentRoute === 'pedidos' ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-plus-circle-fill"></i>
            <p>Fazer Pedido</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= $baseUrl; ?>/history-pedidos" class="nav-link <?= $currentRoute === 'history-pedidos' ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-clock-history"></i>
            <p>Histórico de Pedidos</p>
          </a>
        </li>

        <li class="nav-header">GERENCIAMENTO</li>

        <li class="nav-item">
          <a href="<?= $baseUrl; ?>/fornecedores" class="nav-link <?= $currentRoute === 'fornecedores' ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-truck"></i>
            <p>Fornecedores</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= $baseUrl; ?>/usuarios" class="nav-link <?= $currentRoute === 'usuarios' ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-people"></i>
            <p>Usuários e Lojas</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= $baseUrl; ?>/logs" class="nav-link <?= $currentRoute === 'logs' ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-journal-text"></i>
            <p>Logs de Atividade</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= $baseUrl; ?>/settings" class="nav-link <?= $currentRoute === 'settings' ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-gear"></i>
            <p>Configurações</p>
          </a>
        </li>


                <li class="nav-header">CONTA</li>

        <li class="nav-item">
          <a href="<?= $baseUrl; ?>/logout.php" class="nav-link text-danger">
            <i class="nav-icon bi bi-box-arrow-right"></i>
            <p>Sair do Sistema</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>
