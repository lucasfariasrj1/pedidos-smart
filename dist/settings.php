<?php include_once __DIR__ . '/includes/auth_check.php'; ?>
<?php include_once __DIR__ . '/includes/header.php'; ?>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
  <div id="feedback-container"></div>
  <div class="app-wrapper">
    <?php include_once __DIR__ . '/includes/sidebar.php'; ?>
    <main class="app-main p-3">
      <div class="container-fluid">
        <h3>Configurações Globais</h3>

        <div class="card mb-3">
          <div class="card-header">Parâmetros do Sistema</div>
          <div class="card-body">
            <form id="global-settings-form" class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nome do Sistema</label>
                <input type="text" class="form-control" name="nome_sistema" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">URL da Logo</label>
                <input type="text" class="form-control" name="logo_url">
              </div>
              <div class="col-md-4">
                <label class="form-label">Limite de Pedidos por Loja</label>
                <input type="number" class="form-control" name="limite_pedidos_loja" min="0" step="1">
              </div>
              <div class="col-md-4 d-flex align-items-end">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="modo_manutencao" id="modo_manutencao">
                  <label class="form-check-label" for="modo_manutencao">Modo manutenção ativo</label>
                </div>
              </div>
              <div class="col-md-4 d-grid align-items-end">
                <button class="btn btn-primary" type="submit">Salvar Configurações</button>
              </div>
            </form>
          </div>
        </div>

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
