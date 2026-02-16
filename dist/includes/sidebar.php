<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    <a href="{{ route('dashboard') }}" class="brand-link">
      <!-- <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="brand-image opacity-75 shadow" /> -->
      <span class="brand-text fw-light">Sistema de Pedidos</span>
    </a>
  </div>
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" data-accordion="false" id="navigation">
        
        <li class="nav-item">
          <a href="index.php?page=dashboard" class="nav-link <?php echo ($_GET['page'] ?? '') === 'dashboard' ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-speedometer2"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-header">OPERAÇÃO</li>

        <li class="nav-item">
          <a href="index.php?page=pedidos" class="nav-link <?php echo ($_GET['page'] ?? '') === 'pedidos' ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-plus-circle-fill"></i>
            <p>Fazer Pedido</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="index.php?page=history-pedidos" class="nav-link <?php echo ($_GET['page'] ?? '') === 'history-pedidos' ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-clock-history"></i>
            <p>Histórico de Pedidos</p>
          </a>
        </li>

        <li class="nav-header">GERENCIAMENTO</li>

        <li class="nav-item">
          <a href="index.php?page=fornecedores" class="nav-link <?php echo ($_GET['page'] ?? '') === 'fornecedores' ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-truck"></i>
            <p>Fornecedores</p>
          </a>
        </li>

        <!-- @if(auth()->user()->role === 'admin') -->
        <li class="nav-item {{ request()->is('admin/*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-shield-lock-fill"></i>
            <p>
              Administração
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="index.php?page=usuarios" class="nav-link <?php echo ($_GET['page'] ?? '') === 'usuarios' ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-people"></i>
                <p>Usuários e Lojas</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="index.php?page=logs" class="nav-link <?php echo ($_GET['page'] ?? '') === 'logs' ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-journal-text"></i>
                <p>Logs de Atividade</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="index.php?page=settings" class="nav-link <?php echo ($_GET['page'] ?? '') === 'settings' ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-gear"></i>
                <p>Configurações</p>
              </a>
            </li>
          </ul>
        </li>
        <!-- @endif -->

        <li class="nav-item mt-3">
            <form method="POST" action="{{ route('logout') }}">
                <!-- @csrf -->
                <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                    <i class="nav-icon bi bi-box-arrow-right text-danger"></i>
                    <p class="text-danger">Sair do Sistema</p>
                </button>
            </form>
        </li>

      </ul>
    </nav>
  </div>
  </aside>