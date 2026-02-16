<?php
require_once __DIR__ . '/includes/api/fornecedores.php';

$token = (string) ($_COOKIE['jwt_token'] ?? '');
$userRole = strtolower((string) ($_SESSION['role'] ?? 'user'));
$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userRole !== 'admin') {
    include __DIR__ . '/403.php';
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $response = fornecedoresCreateEndpoint(
            $token,
            trim((string) ($_POST['nome'] ?? '')),
            trim((string) ($_POST['whatsapp'] ?? '')),
            trim((string) ($_POST['email'] ?? '')),
        );
        if ($response['ok']) {
            $message = $response['data']['message'] ?? 'Fornecedor cadastrado!';
        } else {
            $error = $response['data']['message'] ?? 'Erro ao cadastrar fornecedor.';
        }
    }

    if ($action === 'update') {
        $response = fornecedoresUpdateEndpoint(
            $token,
            (int) ($_POST['id'] ?? 0),
            trim((string) ($_POST['nome'] ?? '')),
            trim((string) ($_POST['whatsapp'] ?? '')),
            trim((string) ($_POST['email'] ?? '')),
        );
        if ($response['ok']) {
            $message = $response['data']['message'] ?? 'Fornecedor atualizado!';
        } else {
            $error = $response['data']['message'] ?? 'Erro ao atualizar fornecedor.';
        }
    }

    if ($action === 'delete') {
        $response = fornecedoresDeleteEndpoint($token, (int) ($_POST['id'] ?? 0));
        if ($response['ok']) {
            $message = $response['data']['message'] ?? 'Fornecedor deletado!';
        } else {
            $error = $response['data']['message'] ?? 'Erro ao deletar fornecedor.';
        }
    }
}

$listResponse = fornecedoresListEndpoint($token);
$fornecedores = $listResponse['ok'] && is_array($listResponse['data']) ? $listResponse['data'] : [];
?>
<div class="app-content-header">
    <div class="container-fluid">
        <h3 class="mb-0">Gestão de Fornecedores</h3>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <?php if ($userRole === 'admin'): ?>
        <div class="mb-3 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fornecedorCreateModal">Novo fornecedor</button>
        </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead><tr><th>ID</th><th>Nome</th><th>WhatsApp</th><th>E-mail</th><th>Ações</th></tr></thead>
                    <tbody>
                    <?php if (!$fornecedores): ?>
                        <tr><td colspan="5" class="text-center text-muted">Nenhum fornecedor encontrado.</td></tr>
                    <?php else: foreach ($fornecedores as $fornecedor): ?>
                        <tr>
                            <td><?= (int) ($fornecedor['id'] ?? 0); ?></td>
                            <td><?= htmlspecialchars((string) ($fornecedor['nome'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars((string) ($fornecedor['whatsapp'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars((string) ($fornecedor['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <?php if ($userRole === 'admin'): ?>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-secondary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#fornecedorEditModal"
                                    data-id="<?= (int) ($fornecedor['id'] ?? 0); ?>"
                                    data-nome="<?= htmlspecialchars((string) ($fornecedor['nome'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                                    data-whatsapp="<?= htmlspecialchars((string) ($fornecedor['whatsapp'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                                    data-email="<?= htmlspecialchars((string) ($fornecedor['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                                >Editar</button>
                                <form method="POST" class="d-inline js-confirm-form" data-confirm-title="Remover fornecedor" data-confirm-message="Tem certeza que deseja remover este fornecedor?">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int) ($fornecedor['id'] ?? 0); ?>">
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Excluir</button>
                                </form>
                                <?php else: ?>
                                    <span class="badge text-bg-secondary">Visualização</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if ($userRole === 'admin'): ?>
<div class="modal fade" id="fornecedorCreateModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Novo Fornecedor</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="create">
          <div class="mb-2"><input class="form-control" name="nome" placeholder="Nome" required></div>
          <div class="mb-2"><input class="form-control" name="whatsapp" placeholder="WhatsApp" required></div>
          <div class="mb-2"><input class="form-control" name="email" type="email" placeholder="E-mail" required></div>
        </div>
        <div class="modal-footer"><button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button><button class="btn btn-primary" type="submit">Salvar</button></div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="fornecedorEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Editar fornecedor</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="update">
          <input type="hidden" name="id" id="editFornecedorId">
          <div class="mb-2"><input class="form-control" name="nome" id="editFornecedorNome" required></div>
          <div class="mb-2"><input class="form-control" name="whatsapp" id="editFornecedorWhatsapp" required></div>
          <div class="mb-2"><input class="form-control" name="email" id="editFornecedorEmail" type="email" required></div>
        </div>
        <div class="modal-footer"><button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button><button class="btn btn-primary" type="submit">Atualizar</button></div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('fornecedorEditModal');
    if (!modal) {
        return;
    }

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) {
            return;
        }

        document.getElementById('editFornecedorId').value = button.getAttribute('data-id') || '';
        document.getElementById('editFornecedorNome').value = button.getAttribute('data-nome') || '';
        document.getElementById('editFornecedorWhatsapp').value = button.getAttribute('data-whatsapp') || '';
        document.getElementById('editFornecedorEmail').value = button.getAttribute('data-email') || '';
    });
});
</script>
<?php endif; ?>

<?php if ($message): ?>
<script>document.addEventListener('DOMContentLoaded', function () { showSystemAlert('success', 'Fornecedores', <?= json_encode($message, JSON_UNESCAPED_UNICODE); ?>); });</script>
<?php endif; ?>
<?php if ($error): ?>
<script>document.addEventListener('DOMContentLoaded', function () { showSystemAlert('error', 'Fornecedores', <?= json_encode($error, JSON_UNESCAPED_UNICODE); ?>); });</script>
<?php endif; ?>
