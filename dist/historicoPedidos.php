<?php
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/api_helper.php';

$alert = null;

$meResponse = callApi('GET', '/auth/users/me');
if (($meResponse['status'] ?? 0) === 401) {
    redirectToLogin();
}
$currentUser = apiData($meResponse);
if (!is_array($currentUser)) {
    $currentUser = [];
}
$isAdmin = ($currentUser['role'] ?? '') === 'admin';
$userLojaId = (int) ($currentUser['loja_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $pedidoId = (int) ($_POST['pedido_id'] ?? 0);

    if (!$isAdmin) {
        $alert = ['type' => 'danger', 'message' => 'Seu perfil não possui permissão para alterar pedidos.'];
    } elseif ($action === 'update_status') {
        $status = trim((string) ($_POST['status'] ?? ''));
        $response = callApi('PUT', '/auth/orders/' . $pedidoId, ['status' => $status]);
        if (($response['status'] ?? 0) === 401) {
            redirectToLogin();
        }
        $alert = $response['ok']
            ? ['type' => 'success', 'message' => 'Status atualizado com sucesso.']
            : ['type' => 'danger', 'message' => apiMessage($response, 'Erro ao atualizar status.')];
    } elseif ($action === 'delete_order') {
        $response = callApi('DELETE', '/auth/orders/' . $pedidoId);
        if (($response['status'] ?? 0) === 401) {
            redirectToLogin();
        }
        $alert = $response['ok']
            ? ['type' => 'success', 'message' => 'Pedido excluído com sucesso.']
            : ['type' => 'danger', 'message' => apiMessage($response, 'Erro ao excluir pedido.')];
    }
}

$ordersResponse = callApi('GET', '/auth/orders');
if (($ordersResponse['status'] ?? 0) === 401) {
    redirectToLogin();
}
$pedidos = apiData($ordersResponse);
if (!is_array($pedidos)) {
    $pedidos = [];
}

if (!$isAdmin) {
    $pedidos = array_values(array_filter($pedidos, static function ($pedido) use ($userLojaId) {
        return (int) ($pedido['loja_id'] ?? 0) === $userLojaId;
    }));
}

include_once __DIR__ . '/includes/header.php';
?>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
<div class="app-wrapper">
    <?php include_once __DIR__ . '/includes/sidebar.php'; ?>

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <h3 class="mb-0">Histórico de Pedidos</h3>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <?php if ($alert): ?>
                    <div class="alert alert-<?= $alert['type'] ?> alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($alert['message'], ENT_QUOTES, 'UTF-8') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Peça</th>
                                <th>Loja</th>
                                <th>Fornecedor</th>
                                <th>Status</th>
                                <th class="text-end">Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($pedidos)): ?>
                                <tr><td colspan="6" class="text-center text-muted py-4">Nenhum pedido encontrado.</td></tr>
                            <?php endif; ?>
                            <?php foreach ($pedidos as $pedido): ?>
                                <tr>
                                    <td><?= (int) ($pedido['id'] ?? 0) ?></td>
                                    <td><?= htmlspecialchars((string) ($pedido['peca'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>#<?= (int) ($pedido['loja_id'] ?? 0) ?></td>
                                    <td>#<?= (int) ($pedido['fornecedor_id'] ?? 0) ?></td>
                                    <td>
                                        <?php if ($isAdmin): ?>
                                            <form method="POST" class="d-flex gap-2">
                                                <input type="hidden" name="action" value="update_status">
                                                <input type="hidden" name="pedido_id" value="<?= (int) ($pedido['id'] ?? 0) ?>">
                                                <select name="status" class="form-select form-select-sm">
                                                    <?php
                                                    $statusAtual = (string) ($pedido['status'] ?? 'pendente');
                                                    $statusOptions = ['pendente', 'comprado', 'entregue', 'cancelado'];
                                                    foreach ($statusOptions as $statusOption):
                                                    ?>
                                                        <option value="<?= $statusOption ?>" <?= $statusAtual === $statusOption ? 'selected' : '' ?>>
                                                            <?= ucfirst($statusOption) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button class="btn btn-sm btn-warning">Salvar</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="badge text-bg-secondary"><?= htmlspecialchars((string) ($pedido['status'] ?? 'pendente'), ENT_QUOTES, 'UTF-8') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php if ($isAdmin): ?>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="delete_order">
                                                <input type="hidden" name="pedido_id" value="<?= (int) ($pedido['id'] ?? 0) ?>">
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Deseja excluir este pedido?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="badge text-bg-secondary">Sem permissão</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once __DIR__ . '/includes/footer.php'; ?>
</div>
