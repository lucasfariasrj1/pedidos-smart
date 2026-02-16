            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Nova Peça</h3>
                            <p class="text-secondary small">LOJA NÃO VINCULADA</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6">
                            <div class="card card-primary card-outline shadow-sm">
                                <form id="pedido-form" action="#" method="POST">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Modelo do Celular</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                                <input type="text" name="modelo_celular" class="form-control" placeholder="Ex: iPhone 14 Pro, Samsung S23..." required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Nome da Peça</label>
                                            <input type="text" name="nome_peca" class="form-control" placeholder="Ex: Tela, Bateria, Conector..." required>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Quantidade</label>
                                                <input type="number" name="quantidade" class="form-control" value="1" min="1" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Data do Pedido</label>
                                                <input type="date" name="data_pedido" class="form-control" value="{{ date('Y-m-d') }}" required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Fornecedor</label>
                                            <select id="fornecedorSelect" name="fornecedor_id" class="form-select" required>
                                                <option value="">Carregando fornecedores...</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Preço do Fornecedor (R$)</label>
                                            <input type="number" step="0.01" name="preco_fornecedor" class="form-control" placeholder="Ex: 150.00" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Observações</label>
                                            <textarea name="observacoes" class="form-control" rows="3" placeholder="Cor, especificação, etc..."></textarea>
                                        </div>
                                    </div>

                                        <div class="card-footer d-grid gap-2">
                                        <button id="btnSubmitPedido" type="submit" class="btn btn-primary py-2 fw-bold">
                                            <i class="bi bi-plus-lg me-2"></i> Adicionar Peça
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
