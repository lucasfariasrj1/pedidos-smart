<?php $baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : ''; ?>
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
</script>
</body>
</html>
