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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if (!$isAdmin) {
        $alert = ['type' => 'danger', 'message' => 'Apenas administradores podem alterar fornecedores.'];
    } elseif ($action === 'create') {
        $payload = [
            'nome' => trim((string) ($_POST['nome'] ?? '')),
            'whatsapp' => trim((string) ($_POST['whatsapp'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
        ];
        $response = callApi('POST', '/auth/fornecedores', $payload);
        if (($response['status'] ?? 0) === 401) {
            redirectToLogin();
        }
        $alert = $response['ok']
            ? ['type' => 'success', 'message' => 'Fornecedor criado com sucesso.']
            : ['type' => 'danger', 'message' => apiMessage($response, 'Erro ao criar fornecedor.')];
    } elseif ($action === 'update') {
        $id = (int) ($_POST['id'] ?? 0);
        $payload = [
            'nome' => trim((string) ($_POST['nome'] ?? '')),
            'whatsapp' => trim((string) ($_POST['whatsapp'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
        ];
        $response = callApi('PUT', '/auth/fornecedores/' . $id, $payload);
        if (($response['status'] ?? 0) === 401) {
            redirectToLogin();
        }
        $alert = $response['ok']
            ? ['type' => 'success', 'message' => 'Fornecedor atualizado com sucesso.']
            : ['type' => 'danger', 'message' => apiMessage($response, 'Erro ao atualizar fornecedor.')];
    } elseif ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $response = callApi('DELETE', '/auth/fornecedores/' . $id);
        if (($response['status'] ?? 0) === 401) {
            redirectToLogin();
        }
        $alert = $response['ok']
            ? ['type' => 'success', 'message' => 'Fornecedor removido com sucesso.']
            : ['type' => 'danger', 'message' => apiMessage($response, 'Erro ao remover fornecedor.')];
    }
}

$listResponse = callApi('GET', '/auth/fornecedores');
if (($listResponse['status'] ?? 0) === 401) {
    redirectToLogin();
}
$fornecedores = apiData($listResponse);
if (!is_array($fornecedores)) {
    $fornecedores = [];
}

include_once __DIR__ . '/includes/header.php';
?>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
<div class="app-wrapper">
    <?php include_once __DIR__ . '/includes/sidebar.php'; ?>

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Fornecedores</h3>
                <?php if ($isAdmin): ?>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoFornecedor">
                        <i class="bi bi-plus-circle me-2"></i>Novo Fornecedor
                    </button>
                <?php endif; ?>
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
                                <th>Nome</th>
                                <th>WhatsApp</th>
                                <th>E-mail</th>
                                <th class="text-end">Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($fornecedores)): ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">Nenhum fornecedor encontrado.</td></tr>
                            <?php endif; ?>
                            <?php foreach ($fornecedores as $fornecedor): ?>
                                <tr>
                                    <td><?= (int) ($fornecedor['id'] ?? 0) ?></td>
                                    <td><?= htmlspecialchars((string) ($fornecedor['nome'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars((string) ($fornecedor['whatsapp'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars((string) ($fornecedor['email'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-end">
                                        <?php if ($isAdmin): ?>
                                            <button
                                                class="btn btn-sm btn-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditarFornecedor"
                                                data-id="<?= (int) ($fornecedor['id'] ?? 0) ?>"
                                                data-nome="<?= htmlspecialchars((string) ($fornecedor['nome'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                                data-whatsapp="<?= htmlspecialchars((string) ($fornecedor['whatsapp'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                                data-email="<?= htmlspecialchars((string) ($fornecedor['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= (int) ($fornecedor['id'] ?? 0) ?>">
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Deseja remover este fornecedor?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="badge text-bg-secondary">Somente leitura</span>
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

    <?php if ($isAdmin): ?>
        <div class="modal fade" id="modalNovoFornecedor" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <input type="hidden" name="action" value="create">
                        <div class="modal-header">
                            <h5 class="modal-title">Novo Fornecedor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nome</label>
                                <input type="text" name="nome" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">WhatsApp</label>
                                <input type="text" name="whatsapp" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">E-mail</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalEditarFornecedor" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="editFornecedorId">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title text-dark">Editar Fornecedor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nome</label>
                                <input type="text" name="nome" id="editFornecedorNome" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">WhatsApp</label>
                                <input type="text" name="whatsapp" id="editFornecedorWhatsapp" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">E-mail</label>
                                <input type="email" name="email" id="editFornecedorEmail" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-warning">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            const editModal = document.getElementById('modalEditarFornecedor');
            editModal?.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                document.getElementById('editFornecedorId').value = button.getAttribute('data-id') || '';
                document.getElementById('editFornecedorNome').value = button.getAttribute('data-nome') || '';
                document.getElementById('editFornecedorWhatsapp').value = button.getAttribute('data-whatsapp') || '';
                document.getElementById('editFornecedorEmail').value = button.getAttribute('data-email') || '';
            });
        </script>
    <?php endif; ?>

    <?php include_once __DIR__ . '/includes/footer.php'; ?>
</div>
