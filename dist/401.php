<?php $baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : ''; ?>
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h3 class="mb-0">Erro 401</h3>
      </div>
    </div>
  </div>
</div>
<div class="app-content">
  <div class="container-fluid">
    <div class="error-page my-4">
      <h2 class="headline text-danger"><i class="bi bi-shield-lock-fill"></i></h2>
      <div class="error-content">
        <h3>Não autorizado.</h3>
        <p>A sessão expirou ou você não tem permissão para acessar este conteúdo.</p>
        <a href="<?= $baseUrl; ?>/login.php" class="btn btn-outline-primary">
          Ir para o Login
        </a>
      </div>
    </div>
  </div>
</div>
