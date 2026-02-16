<?php include_once __DIR__ . '/includes/header.php'; ?>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="bi bi-list"></i></a>
                    </li>
                    <li class="nav-item d-none d-md-block"><a href="dashboard" class="nav-link">Home</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            <span class="d-none d-md-inline">Administrador</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        
        <?php include_once __DIR__ . '/includes/sidebar.php'; ?>
        
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Gestão de Fornecedores</h3>
                        </div>
                        <div class="col-sm-6 text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoFornecedor">
                                <i class="bi bi-plus-circle me-2"></i> Novo Fornecedor
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome do Fornecedor</th>
                                            <th>WhatsApp (Link Direto)</th>
                                            <th>E-mail</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td><span class="fw-bold">Leo Peças</span></td>
                                            <td>
                                                <a href="https://wa.me/5511999999999" target="_blank" class="text-success text-decoration-none">
                                                    <i class="bi bi-whatsapp me-1"></i> (11) 99999-9999
                                                </a>
                                            </td>
                                            <td>contato@leopecas.com</td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-warning" title="Editar" data-bs-toggle="modal" data-bs-target="#modalEditarFornecedor1">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Excluir" onclick="confirmarExclusao(1)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td><span class="fw-bold">Distribuidora Silva</span></td>
                                            <td>
                                                <a href="https://wa.me/5511888888888" target="_blank" class="text-success text-decoration-none">
                                                    <i class="bi bi-whatsapp me-1"></i> (11) 88888-8888
                                                </a>
                                            </td>
                                            <td>vendas@silvadist.com.br</td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></button>
                                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <div class="modal fade" id="modalNovoFornecedor" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="processar_fornecedor.php?acao=cadastrar" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Cadastrar Novo Fornecedor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nome da Empresa/Vendedor</label>
                                <input type="text" name="nome" class="form-control" placeholder="Ex: Leo Peças" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">WhatsApp (com DDD)</label>
                                <input type="text" name="whatsapp" class="form-control" placeholder="Ex: 11999999999 (apenas números)" required>
                                <small class="text-muted">Essencial para o envio automático de pedidos.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">E-mail (Opcional)</label>
                                <input type="email" name="email" class="form-control" placeholder="email@fornecedor.com">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar Fornecedor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalEditarFornecedor1" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="processar_fornecedor.php?acao=editar&id=1" method="POST">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title text-dark">Editar Fornecedor #1</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label text-dark">Nome do Fornecedor</label>
                                <input type="text" name="nome" class="form-control" value="Leo Peças" required>
                            </div>
                            <div class="mb-3 text-dark">
                                <label class="form-label">WhatsApp</label>
                                <input type="text" name="whatsapp" class="form-control" value="11999999999" required>
                            </div>
                        </div>
                        <div class="modal-footer text-dark">
                            <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Sair</button>
                            <button type="submit" class="btn btn-warning">Atualizar Dados</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function confirmarExclusao(id) {
                if(confirm('Tem certeza que deseja excluir este fornecedor? Todos os pedidos vinculados a ele serão afetados.')) {
                    window.location.href = 'processar_fornecedor.php?acao=excluir&id=' + id;
                }
            }
        </script>

<?php include_once __DIR__ . '/includes/footer.php'; ?>