<?php include_once __DIR__ . '/includes/auth_check.php'; ?>
<?php include_once __DIR__ . '/includes/header.php'; ?>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
  <div id="feedback-container"></div>
  <div class="app-wrapper">
    <?php include_once __DIR__ . '/includes/sidebar.php'; ?>
    <main class="app-main p-3">
      <div class="container-fluid">
        <h3>Lojas</h3>
        <div class="card mb-3">
          <div class="card-body">
            <form id="loja-form" class="row g-2">
              <div class="col-md-3"><input name="nome" class="form-control" placeholder="Nome" required></div>
              <div class="col-md-3"><input name="cnpj" class="form-control" placeholder="CNPJ"></div>
              <div class="col-md-4"><input name="endereco" class="form-control" placeholder="Endereço"></div>
              <div class="col-md-1"><input name="ativo" type="number" class="form-control" value="1" min="0" max="1"></div>
              <div class="col-md-1 d-grid"><button class="btn btn-primary">Salvar</button></div>
            </form>
          </div>
        </div>

        <div class="card">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead><tr><th>ID</th><th>Nome</th><th>Endereço</th><th>Status</th><th class="text-end">Ações</th></tr></thead>
              <tbody id="lojas-table-body"></tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
<?php include_once __DIR__ . '/includes/footer.php'; ?>
