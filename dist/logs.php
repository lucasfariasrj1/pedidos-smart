<?php include_once __DIR__ . '/includes/auth_check.php'; ?>
<?php include_once __DIR__ . '/includes/header.php'; ?>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="bi bi-list"></i></a>
                    </li>
                    <li class="nav-item d-none d-md-block"><a href="index.php?page=dashboard" class="nav-link">Home</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link text-secondary"><i class="bi bi-shield-lock-fill me-1"></i> Auditoria</span>
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
                            <h3 class="mb-0">Logs de Atividade</h3>
                            <p class="text-secondary small">Registo detalhado de todas as ações no sistema</p>
                        </div>
                        <div class="col-sm-6 text-end">
                            <button class="btn btn-outline-danger btn-sm" onclick="confirmarLimpeza()">
                                <i class="bi bi-trash3 me-1"></i> Limpar Logs Antigos
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <form class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Filtrar por Usuário</label>
                                    <select class="form-select form-select-sm">
                                        <option value="">Todos</option>
                                        <option value="1">Marcos Paulo (Admin)</option>
                                        <option value="2">João Silva</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Tipo de Ação</label>
                                    <select class="form-select form-select-sm">
                                        <option value="">Todas</option>
                                        <option value="create">Criação</option>
                                        <option value="update">Edição</option>
                                        <option value="delete">Exclusão</option>
                                        <option value="login">Login</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-dark btn-sm w-100">Filtrar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Data/Hora</th>
                                            <th>Usuário</th>
                                            <th>Ação</th>
                                            <th>Descrição</th>
                                            <th>IP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="small text-muted">15/02/2026 20:45</td>
                                            <td>
                                                <span class="fw-bold">João Silva</span><br>
                                                <small class="badge text-bg-info">Loja 1</small>
                                            </td>
                                            <td><span class="badge text-bg-success">CRIAÇÃO</span></td>
                                            <td>Criou um novo pedido: <strong>iPhone 14 Pro Max (#1052)</strong></td>
                                            <td class="small text-muted text-nowrap">192.168.1.45</td>
                                        </tr>
                                        <tr>
                                            <td class="small text-muted">15/02/2026 20:30</td>
                                            <td>
                                                <span class="fw-bold">Marcos Paulo</span><br>
                                                <small class="badge text-bg-danger">Admin</small>
                                            </td>
                                            <td><span class="badge text-bg-primary">LOGIN</span></td>
                                            <td>Entrou no sistema via Desktop</td>
                                            <td class="small text-muted text-nowrap">177.45.12.33</td>
                                        </tr>
                                        <tr>
                                            <td class="small text-muted">15/02/2026 19:15</td>
                                            <td>
                                                <span class="fw-bold">Marcos Paulo</span><br>
                                                <small class="badge text-bg-danger">Admin</small>
                                            </td>
                                            <td><span class="badge text-bg-warning text-dark">EDIÇÃO</span></td>
                                            <td>Alterou o WhatsApp do fornecedor: <strong>Leo Peças</strong></td>
                                            <td class="small text-muted text-nowrap">177.45.12.33</td>
                                        </tr>
                                        <tr>
                                            <td class="small text-muted">15/02/2026 18:00</td>
                                            <td>
                                                <span class="fw-bold">Marcos Paulo</span><br>
                                                <small class="badge text-bg-danger">Admin</small>
                                            </td>
                                            <td><span class="badge text-bg-danger">EXCLUSÃO</span></td>
                                            <td>Eliminou o utilizador: <strong>Teste Usuário (ID #14)</strong></td>
                                            <td class="small text-muted text-nowrap">177.45.12.33</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-muted">Mostrando 4 de 1.250 registos</span>
                                <nav>
                                    <ul class="pagination pagination-sm m-0">
                                        <li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">Seguinte</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script>
            function confirmarLimpeza() {
                if(confirm('Atenção: Deseja realmente remover todos os logs com mais de 30 dias? Esta ação não pode ser desfeita.')) {
                    // Lógica PHP aqui
                    alert('Logs antigos removidos com sucesso!');
                }
            }
        </script>

<?php include_once __DIR__ . '/includes/footer.php'; ?>