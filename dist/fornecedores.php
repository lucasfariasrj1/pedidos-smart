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
                                    <tbody id="fornecedoresTbody">
                                        <tr><td colspan="5" class="text-center small text-muted">Carregando fornecedores...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
