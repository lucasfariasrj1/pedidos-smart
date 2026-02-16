<?php include_once __DIR__ . '/includes/auth_check.php'; ?>
<?php include_once __DIR__ . '/includes/header.php'; ?>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
  <div id="feedback-container"></div>
  <div class="app-wrapper">
    <?php include_once __DIR__ . '/includes/sidebar.php'; ?>
    <main class="app-main p-3">
      <div class="container-fluid">
        <h3>Usuários</h3>
        <p class="text-secondary small">Loja: <strong data-store-name>-</strong></p>

        <div class="card mb-3">
          <div class="card-body">
            <form id="usuario-form" class="row g-2">
              <div class="col-md-3"><input name="nome" class="form-control" placeholder="Nome" required></div>
              <div class="col-md-3"><input name="email" type="email" class="form-control" placeholder="E-mail" required></div>
              <div class="col-md-2"><input name="senha" type="password" class="form-control" placeholder="Senha"></div>
              <div class="col-md-2">
                <select name="role" class="form-select">
                  <option value="usuario">Usuário</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
              <div class="col-md-1"><input name="loja_id" type="number" class="form-control" placeholder="Loja"></div>
              <div class="col-md-1 d-grid"><button class="btn btn-primary">Salvar</button></div>
            </form>
          </div>
        </div>

        <div class="card">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead><tr><th>ID</th><th>Nome</th><th>Email</th><th>Role</th><th>Loja</th><th class="text-end">Ações</th></tr></thead>
              <tbody id="usuarios-table-body"></tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
<?php include_once __DIR__ . '/includes/footer.php'; ?>
