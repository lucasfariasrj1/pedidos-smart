<?php
require_once __DIR__ . '/includes/api/profile.php';
require_once __DIR__ . '/includes/api/lojas.php';

$token = $_COOKIE['jwt_token'] ?? '';
$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $response = profileUpdateEndpoint(
            $token,
            trim((string) ($_POST['nome'] ?? '')),
            trim((string) ($_POST['email'] ?? '')),
        );
        if ($response['ok']) {
            $message = $response['data']['message'] ?? 'Perfil atualizado!';
        } else {
            $error = $response['data']['message'] ?? 'Erro ao atualizar perfil.';
        }
    }

    if ($action === 'create_loja') {
        $response = lojasCreateEndpoint(
            $token,
            trim((string) ($_POST['nome'] ?? '')),
            trim((string) ($_POST['endereco'] ?? '')),
        );
        if ($response['ok']) {
            $message = $response['data']['message'] ?? 'Loja cadastrada!';
        } else {
            $error = $response['data']['message'] ?? 'Erro ao cadastrar loja.';
        }
    }

    if ($action === 'update_loja') {
        $response = lojasUpdateEndpoint(
            $token,
            (int) ($_POST['id'] ?? 0),
            trim((string) ($_POST['nome'] ?? '')),
            trim((string) ($_POST['endereco'] ?? '')),
        );
        if ($response['ok']) {
            $message = $response['data']['message'] ?? 'Loja atualizada!';
        } else {
            $error = $response['data']['message'] ?? 'Erro ao atualizar loja.';
        }
    }

    if ($action === 'delete_loja') {
        $response = lojasDeleteEndpoint($token, (int) ($_POST['id'] ?? 0));
        if ($response['ok']) {
            $message = $response['data']['message'] ?? 'Loja deletada!';
        } else {
            $error = $response['data']['message'] ?? 'Erro ao deletar loja.';
        }
    }
}

$profileResponse = profileGetEndpoint($token);
$profile = $profileResponse['ok'] && is_array($profileResponse['data']) ? $profileResponse['data'] : [];

$lojasResponse = lojasListAllEndpoint($token);
$lojas = $lojasResponse['ok'] && is_array($lojasResponse['data']) ? $lojasResponse['data'] : [];
?>
<div class="app-content-header">
    <div class="container-fluid">
        <h3 class="mb-0">Configurações</h3>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>

        <div class="card shadow-sm mb-4">
            <div class="card-header"><h3 class="card-title">Meu Perfil (API /profile)</h3></div>
            <div class="card-body">
                <form method="POST" class="row g-2">
                    <input type="hidden" name="action" value="update_profile">
                    <div class="col-md-5"><input class="form-control" name="nome" value="<?= htmlspecialchars((string) ($profile['nome'] ?? $profile['name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required></div>
                    <div class="col-md-5"><input class="form-control" name="email" type="email" value="<?= htmlspecialchars((string) ($profile['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required></div>
                    <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Salvar</button></div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header"><h3 class="card-title">Lojas (API /lojas)</h3></div>
            <div class="card-body">
                <form method="POST" class="row g-2 mb-3">
                    <input type="hidden" name="action" value="create_loja">
                    <div class="col-md-4"><input class="form-control" name="nome" placeholder="Nome da loja" required></div>
                    <div class="col-md-6"><input class="form-control" name="endereco" placeholder="Endereço" required></div>
                    <div class="col-md-2"><button class="btn btn-outline-primary w-100" type="submit">Adicionar</button></div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>ID</th><th>Nome</th><th>Endereço</th><th>Ações</th></tr></thead>
                        <tbody>
                            <?php if (!$lojas): ?>
                                <tr><td colspan="4" class="text-center text-muted">Nenhuma loja retornada.</td></tr>
                            <?php else: foreach ($lojas as $loja): ?>
                                <tr>
                                    <td><?= (int) ($loja['id'] ?? 0); ?></td>
                                    <td><?= htmlspecialchars((string) ($loja['nome'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars((string) ($loja['endereco'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <details>
                                            <summary class="btn btn-sm btn-outline-secondary">Editar</summary>
                                            <form method="POST" class="d-flex gap-2 mt-2">
                                                <input type="hidden" name="action" value="update_loja">
                                                <input type="hidden" name="id" value="<?= (int) ($loja['id'] ?? 0); ?>">
                                                <input class="form-control form-control-sm" name="nome" value="<?= htmlspecialchars((string) ($loja['nome'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                                                <input class="form-control form-control-sm" name="endereco" value="<?= htmlspecialchars((string) ($loja['endereco'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                                                <button class="btn btn-sm btn-outline-primary" type="submit">Salvar</button>
                                            </form>
                                        </details>
                                        <form method="POST" class="mt-1" onsubmit="return confirm('Excluir loja?');">
                                            <input type="hidden" name="action" value="delete_loja">
                                            <input type="hidden" name="id" value="<?= (int) ($loja['id'] ?? 0); ?>">
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
</div>
