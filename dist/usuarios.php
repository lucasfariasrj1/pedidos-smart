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
                                    <tbody id="usuariosTbody">
                                        <tr><td colspan="5" class="text-center small text-muted">Carregando usuários...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
