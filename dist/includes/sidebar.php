<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    <a href="index.php" class="brand-link">
      <span class="brand-text fw-light">Sistema de Pedidos</span>
    </a>
  </div>
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" role="navigation" id="navigation">
        <li class="nav-item">
          <a href="index.php" class="nav-link">
            <i class="nav-icon bi bi-speedometer2"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-header">OPERAÇÃO</li>

        <li class="nav-item">
          <a href="pedidos.php" class="nav-link">
            <i class="nav-icon bi bi-plus-circle-fill"></i>
            <p>Fazer Pedido</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="historicoPedidos.php" class="nav-link">
            <i class="nav-icon bi bi-clock-history"></i>
            <p>Histórico de Pedidos</p>
          </a>
        </li>

        <li class="nav-header">GERENCIAMENTO</li>

        <li class="nav-item">
          <a href="fornecedores.php" class="nav-link">
            <i class="nav-icon bi bi-truck"></i>
            <p>Fornecedores</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="usuarios.php" class="nav-link">
            <i class="nav-icon bi bi-people"></i>
            <p>Usuários</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="settings.php" class="nav-link">
            <i class="nav-icon bi bi-shop"></i>
            <p>Lojas</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="logs.php" class="nav-link">
            <i class="nav-icon bi bi-journal-text"></i>
            <p>Logs de Atividade</p>
          </a>
        </li>

        <li class="nav-item mt-3">
          <button id="logout-button" type="button" class="nav-link border-0 bg-transparent w-100 text-start">
            <i class="nav-icon bi bi-box-arrow-right text-danger"></i>
            <p class="text-danger">Sair do Sistema</p>
          </button>
        </li>
      </ul>
    </nav>
  </div>
</aside>
