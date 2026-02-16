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
                        <span class="nav-link text-primary fw-bold"><i class="bi bi-gear-fill me-1"></i> Painel de Controle</span>
                    </li>
                </ul>
            </div>
        </nav>
        
        <?php include_once __DIR__ . '/includes/sidebar.php'; ?>
        
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Configurações do Sistema</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h3 class="card-title fw-bold text-primary">Informações da Empresa</h3>
                                </div>
                                <form action="processar_settings.php" method="POST">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Nome do Sistema/Empresa</label>
                                                <input type="text" name="app_name" class="form-control" value="Sistema de Pedidos Peças">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">E-mail de Suporte</label>
                                                <input type="email" name="app_email" class="form-control" value="admin@empresa.com">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Mensagem Padrão do WhatsApp</label>
                                            <textarea name="wa_template" class="form-control" rows="4">Olá! Segue novo pedido realizado via sistema:
{LISTA_DE_ITENS}
Aguardamos confirmação.</textarea>
                                            <small class="text-muted">Use <strong>{LISTA_DE_ITENS}</strong> para onde os produtos devem aparecer.</small>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white text-end">
                                        <button type="submit" class="btn btn-primary px-4">Salvar Alterações</button>
                                    </div>
                                </form>
                            </div>

                            <div class="card shadow-sm">
                                <div class="card-header border-0">
                                    <h3 class="card-title fw-bold">Lojas Cadastradas</h3>
                                    <div class="card-tools">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalNovaLoja">
                                            <i class="bi bi-house-add me-1"></i> Adicionar Loja
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nome da Unidade</th>
                                                <th>Localização</th>
                                                <th>Status</th>
                                                <th class="text-end px-4">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td><strong>Loja 1 - São Judas</strong></td>
                                                <td>Av. Principal, 1000</td>
                                                <td><span class="badge text-bg-success">Ativa</span></td>
                                                <td class="text-end px-4">
                                                    <button class="btn btn-sm btn-light border"><i class="bi bi-pencil"></i></button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td><strong>Loja 2 - Centro</strong></td>
                                                <td>Rua das Flores, 50</td>
                                                <td><span class="badge text-bg-success">Ativa</span></td>
                                                <td class="text-end px-4">
                                                    <button class="btn btn-sm btn-light border"><i class="bi bi-pencil"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card shadow-sm bg-primary text-white mb-4">
                                <div class="card-body">
                                    <h5 class="fw-bold"><i class="bi bi-info-circle me-2"></i> Status do Sistema</h5>
                                    <p class="mb-1"><strong>PHP:</strong> 8.2.0</p>
                                    <p class="mb-1"><strong>Banco de Dados:</strong> Conectado</p>
                                    <p class="mb-1"><strong>Espaço em Disco:</strong> 85% Livre</p>
                                    <hr class="bg-white opacity-25">
                                    <p class="mb-0 small italic">Último backup: Hoje, 04:00 AM</p>
                                </div>
                            </div>

                            <div class="card shadow-sm border-0">
                                <div class="card-body text-center py-4">
                                    <i class="bi bi-shield-check text-success display-4 mb-3 d-block"></i>
                                    <h5 class="fw-bold">Manutenção</h5>
                                    <p class="text-muted small">Ative o modo de manutenção para realizar atualizações de banco de dados.</p>
                                    <button class="btn btn-outline-danger w-100 mt-2">Ativar Modo Manutenção</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <div class="modal fade" id="modalNovaLoja" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content shadow-lg border-0">
                    <form action="processar_loja.php" method="POST">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Cadastrar Nova Unidade</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nome da Loja</label>
                                <input type="text" name="nome_loja" class="form-control" placeholder="Ex: Loja 3 - Shopping" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Endereço/Referência</label>
                                <input type="text" name="endereco" class="form-control" placeholder="Ex: Av. Brasil, s/n">
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="submit" class="btn btn-primary">Salvar Loja</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<?php include_once __DIR__ . '/includes/footer.php'; ?>