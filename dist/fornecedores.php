<?php
require_once __DIR__ . '/includes/api/fornecedores.php';

$token = $_COOKIE['jwt_token'] ?? '';
$message = null;
$error = null;

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
        <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>

        <div class="card mb-3">
            <div class="card-body">
                <form method="POST" class="row g-2">
                    <input type="hidden" name="action" value="create">
                    <div class="col-md-4"><input class="form-control" name="nome" placeholder="Nome" required></div>
                    <div class="col-md-3"><input class="form-control" name="whatsapp" placeholder="WhatsApp" required></div>
                    <div class="col-md-3"><input class="form-control" name="email" type="email" placeholder="E-mail" required></div>
                    <div class="col-md-2"><button class="btn btn-primary w-100" type="submit">Novo</button></div>
                </form>
            </div>
        </div>

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
                                <details>
                                    <summary class="btn btn-sm btn-outline-secondary">Editar</summary>
                                    <form method="POST" class="mt-2 d-flex gap-2 flex-wrap">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="id" value="<?= (int) ($fornecedor['id'] ?? 0); ?>">
                                        <input class="form-control form-control-sm" name="nome" value="<?= htmlspecialchars((string) ($fornecedor['nome'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                                        <input class="form-control form-control-sm" name="whatsapp" value="<?= htmlspecialchars((string) ($fornecedor['whatsapp'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                                        <input class="form-control form-control-sm" name="email" type="email" value="<?= htmlspecialchars((string) ($fornecedor['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                                        <button class="btn btn-sm btn-outline-primary" type="submit">Salvar</button>
                                    </form>
                                </details>
                                <form method="POST" class="mt-1" onsubmit="return confirm('Remover fornecedor?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int) ($fornecedor['id'] ?? 0); ?>">
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
