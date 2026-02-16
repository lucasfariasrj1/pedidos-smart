<?php include_once __DIR__ . '/includes/auth_check.php'; ?>
<?php include_once __DIR__ . '/includes/header.php'; ?>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
  <div id="feedback-container"></div>
  <div class="app-wrapper">
    <?php include_once __DIR__ . '/includes/sidebar.php'; ?>
    <main class="app-main p-3">
      <div class="container-fluid">
        <h3>Fornecedores</h3>
        <div class="card mb-3">
          <div class="card-body">
            <form id="fornecedor-form" class="row g-2">
              <div class="col-md-4"><input name="nome" class="form-control" placeholder="Nome" required></div>
              <div class="col-md-3"><input name="whatsapp" class="form-control" placeholder="WhatsApp" required></div>
              <div class="col-md-4"><input name="email" type="email" class="form-control" placeholder="E-mail"></div>
              <div class="col-md-1 d-grid"><button class="btn btn-primary">Salvar</button></div>
            </form>
          </div>
        </div>

        <div class="card">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead><tr><th>ID</th><th>Nome</th><th>WhatsApp</th><th>Email</th><th class="text-end">Ações</th></tr></thead>
              <tbody id="fornecedores-table-body"></tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
<?php include_once __DIR__ . '/includes/footer.php'; ?>
