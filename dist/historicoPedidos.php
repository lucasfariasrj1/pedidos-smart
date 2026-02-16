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
                    <li class="nav-item d-none d-md-block"><a href="pedidos" class="nav-link">Fazer Pedido</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            <span class="d-none d-md-inline" data-user-name>Usuário</span>
                        </a>
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
                            <h3 class="mb-0">Histórico de Pedidos</h3>
                            <p class="text-secondary small">Visualizando pedidos da: <strong data-store-name>-</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header border-0">
                                    <h3 class="card-title fw-bold">Pedidos Recentes</h3>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-striped align-middle">
                                        <thead>
                                            <tr>
                                                <th style="width: 80px">ID</th>
                                                <th>Data</th>
                                                <th>Modelo / Peça</th>
                                                <th>Qtd.</th>
                                                <th>Fornecedor</th>
                                                <th>Total (R$)</th>
                                                <th class="text-center">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pedidos-table-body"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <div class="modal fade" id="modalDetalhes1052" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="exampleModalLabel"> Detalhes do Pedido #1052</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="text-muted small d-block">Loja Requisitante</label>
                                <span class="fw-bold">Loja 1 - São Judas</span>
                            </div>
                            <div class="col-6 text-end">
                                <label class="text-muted small d-block">Data do Pedido</label>
                                <span class="fw-bold">15/02/2026</span>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label class="text-muted small d-block">Aparelho / Modelo</label>
                            <p class="mb-0 fw-bold text-primary">iPhone 14 Pro Max</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small d-block">Peça Solicitada</label>
                            <p class="mb-0 fw-bold">Tela OLED Incell (Preta)</p>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">
                                <label class="text-muted small d-block">Quantidade</label>
                                <span class="badge bg-secondary">1 unidade</span>
                            </div>
                            <div class="col-8 text-end">
                                <label class="text-muted small d-block">Fornecedor Selecionado</label>
                                <span class="fw-bold">Leo Peças</span>
                            </div>
                        </div>
                        <div class="p-3 bg-light rounded mb-3">
                            <label class="text-muted small d-block">Observações Técnicas</label>
                            <p class="mb-0 italic small text-secondary">"Cliente com pressa, verificar se a tela possui o True Tone ativo."</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-dark fw-bold">Preço Unitário:</h5>
                            <h4 class="mb-0 text-success fw-bold">R$ 450,00</h4>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <a href="https://api.whatsapp.com/send?phone=<?= $zap_fornecedor ?>&text=<?= $msg ?>" target="_blank" class="btn btn-success">
                            <i class="bi bi-whatsapp"></i> Reenviar WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once __DIR__ . '/includes/footer.php'; ?>