<?php
require_once __DIR__ . '/includes/api/orders.php';
require_once __DIR__ . '/includes/api/fornecedores.php';

$token = $_COOKIE['jwt_token'] ?? '';
$pedidoMessage = null;
$pedidoError = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_order') {
    $peca = trim((string) ($_POST['nome_peca'] ?? ''));
    $modelo = trim((string) ($_POST['modelo_celular'] ?? ''));
    $observacoes = trim((string) ($_POST['observacoes'] ?? ''));
    $fornecedorId = (int) ($_POST['fornecedor_id'] ?? 0);

    if ($peca === '' || $fornecedorId <= 0) {
        $pedidoError = 'Preencha os campos obrigatórios para criar o pedido.';
    } else {
        $descPeca = $modelo !== '' ? ($modelo . ' - ' . $peca) : $peca;
        $createOrder = ordersCreateEndpoint($token, $descPeca, $fornecedorId, $observacoes);
        if ($createOrder['ok']) {
            $pedidoMessage = $createOrder['data']['message'] ?? 'Pedido criado com sucesso.';
        } else {
            $pedidoError = $createOrder['data']['message'] ?? 'Não foi possível criar o pedido.';
        }
    }
}

$fornecedoresResponse = fornecedoresListEndpoint($token);
$fornecedores = $fornecedoresResponse['ok'] && is_array($fornecedoresResponse['data']) ? $fornecedoresResponse['data'] : [];
?>
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Nova Peça</h3>
                <p class="text-secondary small">Criação de pedidos integrada com API externa</p>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card card-primary card-outline shadow-sm">
                    <form id="pedido-form" action="" method="POST">
                        <input type="hidden" name="action" value="create_order">
                        <div class="card-body">
                            <?php if ($pedidoMessage): ?>
                                <div class="alert alert-success"><?= htmlspecialchars($pedidoMessage, ENT_QUOTES, 'UTF-8'); ?></div>
                            <?php endif; ?>
                            <?php if ($pedidoError): ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($pedidoError, ENT_QUOTES, 'UTF-8'); ?></div>
                            <?php endif; ?>

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

                            <div class="mb-3">
                                <label class="form-label fw-bold">Fornecedor</label>
                                <select id="fornecedorSelect" name="fornecedor_id" class="form-select" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($fornecedores as $fornecedor): ?>
                                        <option value="<?= (int) ($fornecedor['id'] ?? 0); ?>"><?= htmlspecialchars((string) ($fornecedor['nome'] ?? 'Sem nome'), ENT_QUOTES, 'UTF-8'); ?></option>
                                    <?php endforeach; ?>
                                </select>
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
