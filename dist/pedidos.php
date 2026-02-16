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

$fornecedoresResponse = callApi('GET', '/auth/fornecedores');
if (($fornecedoresResponse['status'] ?? 0) === 401) {
    redirectToLogin();
}
$fornecedores = apiData($fornecedoresResponse);
if (!is_array($fornecedores)) {
    $fornecedores = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create_order') {
    $peca = trim((string) ($_POST['peca'] ?? ''));
    $observacao = trim((string) ($_POST['observacao'] ?? ''));
    $fornecedorId = (int) ($_POST['fornecedor_id'] ?? 0);
    $lojaId = $isAdmin ? (int) ($_POST['loja_id'] ?? 0) : $userLojaId;

    if ($peca === '' || $fornecedorId <= 0 || $lojaId <= 0) {
        $alert = ['type' => 'danger', 'message' => 'Preencha todos os campos obrigatórios para criar o pedido.'];
    } else {
        $payload = [
            'peca' => $peca,
            'fornecedor_id' => $fornecedorId,
            'observacao' => $observacao,
            'loja_id' => $lojaId,
        ];

        $createResponse = callApi('POST', '/auth/orders', $payload);

        if (($createResponse['status'] ?? 0) === 401) {
            redirectToLogin();
        }

        if ($createResponse['ok']) {
            $alert = ['type' => 'success', 'message' => 'Pedido criado com sucesso.'];
        } else {
            $alert = ['type' => 'danger', 'message' => apiMessage($createResponse, 'Erro ao criar pedido.')];
        }
    }
}

include_once __DIR__ . '/includes/header.php';
?>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
<div class="app-wrapper">
    <?php include_once __DIR__ . '/includes/sidebar.php'; ?>

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-8">
                        <h3 class="mb-0">Novo Pedido</h3>
                        <p class="text-secondary small mb-0">
                            <?php if ($isAdmin): ?>
                                Você pode registrar pedidos para qualquer loja.
                            <?php else: ?>
                                Pedido vinculado automaticamente à loja #<?= htmlspecialchars((string) $userLojaId, ENT_QUOTES, 'UTF-8') ?>.
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
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

                <div class="card card-primary card-outline shadow-sm">
                    <form method="POST">
                        <input type="hidden" name="action" value="create_order">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Peça</label>
                                <input type="text" name="peca" class="form-control" placeholder="Ex: Tela iPhone 14" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Fornecedor</label>
                                <select name="fornecedor_id" class="form-select" required>
                                    <option value="">Selecione um fornecedor</option>
                                    <?php foreach ($fornecedores as $fornecedor): ?>
                                        <option value="<?= (int) ($fornecedor['id'] ?? 0) ?>">
                                            <?= htmlspecialchars((string) ($fornecedor['nome'] ?? 'Fornecedor sem nome'), ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Loja ID</label>
                                <input
                                    type="number"
                                    name="loja_id"
                                    class="form-control"
                                    value="<?= $isAdmin ? '' : (int) $userLojaId ?>"
                                    <?= $isAdmin ? 'required' : 'readonly' ?>
                                >
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Observação</label>
                                <textarea name="observacao" class="form-control" rows="3" placeholder="Detalhes adicionais (opcional)"></textarea>
                            </div>
                        </div>

                        <div class="card-footer d-grid">
                            <button type="submit" class="btn btn-primary fw-bold">
                                <i class="bi bi-plus-lg me-2"></i>Criar Pedido
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include_once __DIR__ . '/includes/footer.php'; ?>
</div>
