<?php $baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : ''; ?>
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h3 class="mb-0">Erro 500</h3>
      </div>
    </div>
  </div>
</div>
<div class="app-content">
  <div class="container-fluid">
    <div class="error-page my-4">
      <h2 class="headline text-warning"><i class="bi bi-exclamation-triangle-fill"></i></h2>
      <div class="error-content">
        <h3>Erro interno do servidor.</h3>
        <p>Ocorreu uma falha inesperada. Tente novamente em instantes ou contate o suporte técnico.</p>
        <a href="<?= $baseUrl; ?>/dashboard" class="btn btn-primary">
          Voltar ao Dashboard
        </a>
      </div>
    </div>
  </div>
</div>
