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
                    <li class="nav-item">
                        <span class="nav-link badge text-bg-primary me-3">Painel Admin</span>
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
                            <h3 class="mb-0">Usuários do Sistema</h3>
                        </div>
                        <div class="col-sm-6 text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoUsuario">
                                <i class="bi bi-person-plus-fill me-2"></i> Criar Usuário
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Nome</th>
                                            <th>E-mail</th>
                                            <th>Vínculo / Loja</th>
                                            <th>Nível de Acesso</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">MP</div>
                                                    <span class="fw-bold">Marcos Paulo</span>
                                                </div>
                                            </td>
                                            <td>marcos@admin.com</td>
                                            <td><span class="text-muted italic">Acesso Global</span></td>
                                            <td><span class="badge rounded-pill text-bg-danger">Administrador</span></td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-light border" title="Editar"><i class="bi bi-pencil-square"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">JS</div>
                                                    <span class="fw-bold">João Silva</span>
                                                </div>
                                            </td>
                                            <td>joao@loja1.com</td>
                                            <td><span class="badge text-bg-info">LOJA 1 - SÃO JUDAS</span></td>
                                            <td><span class="badge rounded-pill text-bg-secondary">Usuário Loja</span></td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-light border"><i class="bi bi-pencil-square"></i></button>
                                                <button class="btn btn-sm btn-light border text-danger"><i class="bi bi-trash"></i></button>
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

        <div class="modal fade" id="modalNovoUsuario" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content shadow-lg border-0">
                    <form action="processar_usuario.php" method="POST">
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title">Novo Usuário do Sistema</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nome Completo</label>
                                <input type="text" name="name" class="form-control" placeholder="Ex: João Silva" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">E-mail (Login)</label>
                                <input type="email" name="email" class="form-control" placeholder="joao@empresa.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Senha Inicial</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tipo de Perfil</label>
                                <select name="role" class="form-select" id="roleSelect" onchange="toggleLojaSelect()" required>
                                    <option value="usuario">Usuário de Loja (Restrito)</option>
                                    <option value="admin">Administrador (Acesso Total)</option>
                                </select>
                            </div>
                            <div class="mb-3" id="lojaContainer">
                                <label class="form-label fw-bold">Vincular à Loja</label>
                                <select name="loja_id" class="form-select">
                                    <option value="">Selecione a Loja...</option>
                                    <option value="1">Loja 1 - São Judas</option>
                                    <option value="2">Loja 2 - Centro</option>
                                    <option value="3">Loja 3 - Shopping</option>
                                </select>
                                <small class="text-muted italic">O usuário verá apenas pedidos desta loja.</small>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Criar Usuário</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Lógica para esconder o select de loja se for Admin
            function toggleLojaSelect() {
                const role = document.getElementById('roleSelect').value;
                const container = document.getElementById('lojaContainer');
                if (role === 'admin') {
                    container.style.display = 'none';
                } else {
                    container.style.display = 'block';
                }
            }
        </script>

<?php include_once __DIR__ . '/includes/footer.php'; ?>