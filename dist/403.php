<?php $baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : ''; ?>
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h3 class="mb-0">Erro 403</h3>
      </div>
    </div>
  </div>
</div>
<div class="app-content">
  <div class="container-fluid">
    <div class="error-page my-4">
      <h2 class="headline text-warning"><i class="bi bi-shield-exclamation"></i></h2>
      <div class="error-content">
        <h3>Acesso negado.</h3>
        <p>Você não possui permissão para executar esta ação.</p>
        <a href="<?= $baseUrl; ?>/dashboard" class="btn btn-outline-primary">
          Voltar ao Dashboard
        </a>
      </div>
    </div>
  </div>
</div>
