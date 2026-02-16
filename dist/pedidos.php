<?php
require_once __DIR__ . '/includes/api/orders.php';
require_once __DIR__ . '/includes/api/fornecedores.php';

$token = (string) ($_COOKIE['jwt_token'] ?? '');
$userRole = strtolower((string) ($_SESSION['role'] ?? 'user'));
$userLojaId = isset($_SESSION['loja_id']) ? (int) $_SESSION['loja_id'] : 0;
$feedbackType = null;
$feedbackMessage = null;

$phoneModels = [
    'Apple' => ['iPhone SE (2020)', 'iPhone SE (2022)', 'iPhone 11', 'iPhone 11 Pro', 'iPhone 11 Pro Max', 'iPhone 12 mini', 'iPhone 12', 'iPhone 12 Pro', 'iPhone 12 Pro Max', 'iPhone 13 mini', 'iPhone 13', 'iPhone 13 Pro', 'iPhone 13 Pro Max', 'iPhone 14', 'iPhone 14 Plus', 'iPhone 14 Pro', 'iPhone 14 Pro Max', 'iPhone 15', 'iPhone 15 Plus', 'iPhone 15 Pro', 'iPhone 15 Pro Max', 'iPhone 16', 'iPhone 16 Plus', 'iPhone 16 Pro', 'iPhone 16 Pro Max'],
    'Samsung' => ['Galaxy S20', 'Galaxy S20 FE', 'Galaxy S21', 'Galaxy S21 FE', 'Galaxy S22', 'Galaxy S23', 'Galaxy S24', 'Galaxy S25', 'Galaxy S20+', 'Galaxy S21+', 'Galaxy S22+', 'Galaxy S23+', 'Galaxy S24+', 'Galaxy S25+', 'Galaxy S20 Ultra', 'Galaxy S21 Ultra', 'Galaxy S22 Ultra', 'Galaxy S23 Ultra', 'Galaxy S24 Ultra', 'Galaxy S25 Ultra', 'Galaxy A12', 'Galaxy A13', 'Galaxy A14', 'Galaxy A15', 'Galaxy A21s', 'Galaxy A22', 'Galaxy A23', 'Galaxy A24', 'Galaxy A25', 'Galaxy A31', 'Galaxy A32', 'Galaxy A33', 'Galaxy A34', 'Galaxy A35', 'Galaxy A51', 'Galaxy A52', 'Galaxy A53', 'Galaxy A54', 'Galaxy A55', 'Galaxy M12', 'Galaxy M13', 'Galaxy M14', 'Galaxy M15', 'Galaxy Z Flip 3', 'Galaxy Z Flip 4', 'Galaxy Z Flip 5', 'Galaxy Z Fold 3', 'Galaxy Z Fold 4', 'Galaxy Z Fold 5'],
    'Xiaomi' => ['Redmi 9', 'Redmi 10', 'Redmi 12', 'Redmi 13', 'Redmi Note 9', 'Redmi Note 10', 'Redmi Note 11', 'Redmi Note 12', 'Redmi Note 13', 'Poco X3', 'Poco X4', 'Poco X5', 'Poco X6', 'Poco F3', 'Poco F4', 'Poco F5', 'Mi 11', 'Mi 11 Lite', 'Mi 12', 'Mi 12T', 'Xiaomi 13', 'Xiaomi 13 Lite', 'Xiaomi 14'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create_order') {
    $modelo = trim((string) ($_POST['modelo_celular'] ?? ''));
    $nomePeca = trim((string) ($_POST['nome_peca'] ?? ''));
    $observacoes = trim((string) ($_POST['observacoes'] ?? ''));
    $fornecedorId = (int) ($_POST['fornecedor_id'] ?? 0);

    if ($modelo === '' || $nomePeca === '' || $fornecedorId <= 0) {
        $feedbackType = 'error';
        $feedbackMessage = 'Preencha modelo, peça e fornecedor.';
    } else {
        $peca = $modelo . ' - ' . $nomePeca;
        $createOrder = ordersCreateEndpoint($token, $peca, $fornecedorId, $observacoes);
        $feedbackType = $createOrder['ok'] ? 'success' : 'error';
        $feedbackMessage = (string) ($createOrder['data']['message'] ?? $createOrder['data']['error'] ?? 'Não foi possível criar o pedido.');
    }
}

$fornecedoresResponse = fornecedoresListEndpoint($token);
$fornecedores = $fornecedoresResponse['ok'] && is_array($fornecedoresResponse['data']) ? $fornecedoresResponse['data'] : [];

$ordersResponse = ordersListEndpoint($token);
$ordersRaw = $ordersResponse['ok'] && is_array($ordersResponse['data']) ? $ordersResponse['data'] : [];
$orders = array_values(array_filter($ordersRaw, static function (array $order) use ($userRole, $userLojaId): bool {
    if ($userRole !== 'user') {
        return true;
    }

    if (!isset($order['loja_id'])) {
        return false;
    }

    return (int) $order['loja_id'] === $userLojaId;
}));
?>
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row"><div class="col-sm-6"><h3 class="mb-0">Novo Pedido</h3></div></div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-8">
                <div class="card card-primary card-outline shadow-sm mb-3">
                    <form id="pedido-form" method="POST">
                        <input type="hidden" name="action" value="create_order">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Modelo do Celular</label>
                                <select name="modelo_celular" class="form-select" required>
                                    <option value="">Selecione o modelo...</option>
                                    <?php foreach ($phoneModels as $brand => $models): ?>
                                        <optgroup label="<?= htmlspecialchars($brand, ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php foreach ($models as $model): ?>
                                                <option value="<?= htmlspecialchars($model, ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($model, ENT_QUOTES, 'UTF-8'); ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach; ?>
                                </select>
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

                        <div class="card-footer d-grid">
                            <button type="submit" class="btn btn-primary py-2 fw-bold"><i class="bi bi-plus-lg me-2"></i> Criar Pedido</button>
                        </div>
                    </form>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header"><strong>Pedidos recentes</strong></div>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead><tr><th>ID</th><th>Peça</th><th>Status</th><th>Loja</th></tr></thead>
                            <tbody>
                            <?php if (!$orders): ?>
                                <tr><td colspan="4" class="text-center text-muted">Nenhum pedido encontrado.</td></tr>
                            <?php else: foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?= (int) ($order['id'] ?? 0); ?></td>
                                    <td><?= htmlspecialchars((string) ($order['peca'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars((string) ($order['status'] ?? 'Pendente'), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= (int) ($order['loja_id'] ?? 0); ?></td>
                                </tr>
                            <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($feedbackMessage): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    showSystemAlert('<?= $feedbackType === 'error' ? 'error' : 'success'; ?>', 'Pedidos', <?= json_encode($feedbackMessage, JSON_UNESCAPED_UNICODE); ?>);
});
</script>
<?php endif; ?>
