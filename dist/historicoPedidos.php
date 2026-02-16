            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Histórico de Pedidos</h3>
                            <p class="text-secondary small">Visualizando pedidos da: <strong>LOJA 1 - SÃO JUDAS</strong></p>
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
                                        <tbody>
                                            <tr>
                                                <td><span class="text-secondary">#1052</span></td>
                                                <td>15/02/2026</td>
                                                <td>
                                                    <span class="fw-bold">iPhone 14 Pro Max</span><br>
                                                    <small class="text-muted">Tela OLED Incell</small>
                                                </td>
                                                <td>1</td>
                                                <td>Leo Peças</td>
                                                <td>R$ 450,00</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button class="btn btn-outline-primary btn-sm" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#modalDetalhes1052" 
                                                                title="Ver Detalhes">
                                                            <i class="bi bi-search"></i>
                                                        </button>
                                                        
                                                        <?php 
                                                            $zap_fornecedor = "5511999999999"; 
                                                            $msg = urlencode("*Novo Pedido de Compra*\n\n*Pedido:* #1052\n*Loja:* São Judas\n*Item:* Tela OLED iPhone 14 Pro Max\n*Qtd:* 1\n*Preço:* R$ 450,00");
                                                        ?>
                                                        <a href="https://api.whatsapp.com/send?phone=<?= $zap_fornecedor ?>&text=<?= $msg ?>" 
                                                           target="_blank" class="btn btn-success btn-sm">
                                                            <i class="bi bi-whatsapp"></i> WhatsApp
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
