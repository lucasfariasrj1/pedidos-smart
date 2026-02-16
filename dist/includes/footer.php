<?php
$baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
$isAdminUser = function_exists('isAdmin') ? isAdmin() : false;
?>
<footer class="app-footer">
  <div class="float-end d-none d-sm-inline">
    Desenvolvido por <a href="https://lf.dev.br/" target="_blank" class="text-decoration-none" rel="noopener noreferrer">LF DEVELOPER</a>.
  </div>
  <strong>
    Copyright &copy; 2014-2025
    <a href="#" class="text-decoration-none">Assistencia SmartHard</a>.
  </strong>
  Todos os Direitos Reservados.
</footer>

<div class="d-md-none border-top fixed-bottom d-flex justify-content-around align-items-center py-2 shadow-lg bottom-nav bg-body">
  <a href="<?= $baseUrl; ?>/dashboard" class="text-center text-decoration-none bottom-nav-link <?= ($currentPage === 'dashboard') ? 'active' : ''; ?>" aria-label="Início">
    <i class="bi bi-house-door fs-4"></i>
    <span>Início</span>
  </a>
  <a href="<?= $baseUrl; ?>/pedidos" class="text-center text-decoration-none bottom-nav-link <?= ($currentPage === 'pedidos') ? 'active' : ''; ?>" aria-label="Pedidos">
    <i class="bi bi-cart fs-4"></i>
    <span>Pedidos</span>
  </a>
  <?php if ($isAdminUser): ?>
    <a href="<?= $baseUrl; ?>/fornecedores" class="text-center text-decoration-none bottom-nav-link <?= ($currentPage === 'fornecedores') ? 'active' : ''; ?>" aria-label="Fornecedores">
      <i class="bi bi-truck fs-4"></i>
      <span>Fornecedores</span>
    </a>
  <?php endif; ?>
  <a href="<?= $baseUrl; ?>/settings" class="text-center text-decoration-none bottom-nav-link <?= ($currentPage === 'settings') ? 'active' : ''; ?>" aria-label="Perfil">
    <i class="bi bi-person fs-4"></i>
    <span>Perfil</span>
  </a>
</div>

<div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutConfirmLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutConfirmLabel">Confirmar saída</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        Tem certeza que deseja sair do sistema?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <a href="<?= $baseUrl; ?>/logout.php" class="btn btn-danger">Sair</a>
      </div>
    </div>
  </div>
</div>

<style>
  .bottom-nav {
    z-index: 1040;
  }

  .bottom-nav-link {
    min-width: 70px;
    color: var(--bs-secondary-color);
    transition: transform 0.2s ease, color 0.2s ease;
  }

  .bottom-nav-link span {
    display: block;
    font-size: 0.7rem;
    line-height: 1;
    margin-top: 0.1rem;
  }

  .bottom-nav-link.active {
    color: var(--bs-primary);
  }

  .bottom-nav-link:active {
    transform: scale(0.92);
  }

  @media (max-width: 767.98px) {
    .app-sidebar {
      display: none !important;
    }

    .app-main {
      padding-bottom: 78px !important;
    }

    .app-wrapper .app-main {
      margin-left: 0 !important;
    }
  }
</style>
</div>
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="<?= $baseUrl; ?>/js/adminlte.js"></script>
<script>
  const sidebarWrapper = document.querySelector('.sidebar-wrapper');
  if (sidebarWrapper && window.OverlayScrollbarsGlobal?.OverlayScrollbars) {
    window.OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
      scrollbars: { theme: 'os-theme-light', autoHide: 'leave', clickScroll: true },
    });
  }

  const logoutModalEl = document.getElementById('logoutConfirmModal');
  const logoutLinks = document.querySelectorAll('[data-logout-link="true"]');
  if (logoutModalEl && logoutLinks.length > 0 && window.bootstrap?.Modal) {
    const logoutModal = new bootstrap.Modal(logoutModalEl);
    logoutLinks.forEach((link) => {
      link.addEventListener('click', (event) => {
        event.preventDefault();
        logoutModal.show();
      });
    });
  }

  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('<?= $baseUrl; ?>/sw.js').catch(() => {
      });
    });
  }
</script>
</body>
</html>
