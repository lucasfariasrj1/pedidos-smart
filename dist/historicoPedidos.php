<?php
require_once __DIR__ . '/includes/api/orders.php';

$token = $_COOKIE['jwt_token'] ?? '';
$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $orderId = (int) ($_POST['order_id'] ?? 0);

    if ($action === 'delete_order' && $orderId > 0) {
        $response = ordersDeleteEndpoint($token, $orderId);
        $message = $response['ok'] ? ($response['data']['message'] ?? 'Pedido removido!') : ($response['data']['message'] ?? 'Erro ao remover pedido.');
        if (!$response['ok']) {
            $error = $message;
            $message = null;
        }
    }

    if ($action === 'update_status' && $orderId > 0) {
        $status = trim((string) ($_POST['status'] ?? 'Pendente'));
        $response = ordersUpdateEndpoint($token, $orderId, $status);
        $message = $response['ok'] ? ($response['data']['message'] ?? 'Status atualizado!') : ($response['data']['message'] ?? 'Erro ao atualizar status.');
        if (!$response['ok']) {
            $error = $message;
            $message = null;
        }
    }
}

$ordersResponse = ordersListEndpoint($token);
$orders = $ordersResponse['ok'] && is_array($ordersResponse['data']) ? $ordersResponse['data'] : [];
?>
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Histórico de Pedidos</h3>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body table-responsive p-0">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Peça</th>
                            <th>Fornecedor</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr><td colspan="5" class="text-center text-muted">Nenhum pedido encontrado na API.</td></tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?= (int) ($order['id'] ?? 0); ?></td>
                                    <td><?= htmlspecialchars((string) ($order['peca'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= (int) ($order['fornecedor_id'] ?? 0); ?></td>
                                    <td>
                                        <form method="POST" class="d-flex gap-2">
                                            <input type="hidden" name="action" value="update_status">
                                            <input type="hidden" name="order_id" value="<?= (int) ($order['id'] ?? 0); ?>">
                                            <select name="status" class="form-select form-select-sm">
                                                <?php foreach (['Pendente', 'Aprovado', 'Recusado'] as $status): ?>
                                                    <option value="<?= $status; ?>" <?= (($order['status'] ?? '') === $status) ? 'selected' : ''; ?>><?= $status; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button class="btn btn-outline-primary btn-sm" type="submit">Salvar</button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <form method="POST" class="js-confirm-form" data-confirm-title="Remover pedido" data-confirm-message="Tem certeza que deseja remover este pedido?">
                                            <input type="hidden" name="action" value="delete_order">
                                            <input type="hidden" name="order_id" value="<?= (int) ($order['id'] ?? 0); ?>">
                                            <button class="btn btn-outline-danger btn-sm" type="submit"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if ($message): ?>
<script>document.addEventListener('DOMContentLoaded', function () { showSystemAlert('success', 'Pedidos', <?= json_encode($message, JSON_UNESCAPED_UNICODE); ?>); });</script>
<?php endif; ?>
<?php if ($error): ?>
<script>document.addEventListener('DOMContentLoaded', function () { showSystemAlert('error', 'Pedidos', <?= json_encode($error, JSON_UNESCAPED_UNICODE); ?>); });</script>
<?php endif; ?>
