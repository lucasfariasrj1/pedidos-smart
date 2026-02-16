<?php
require_once __DIR__ . '/includes/api/users.php';
require_once __DIR__ . '/includes/api/auth.php';

$token = $_COOKIE['jwt_token'] ?? '';
$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create_user') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $senha = trim((string) ($_POST['senha'] ?? ''));
    $role = trim((string) ($_POST['role'] ?? 'user'));
    $lojaId = (int) ($_POST['loja_id'] ?? 1);

    $response = registerEndpoint($email, $senha, $role, $lojaId);
    if ($response['ok']) {
        $message = $response['data']['message'] ?? 'Usuário registrado!';
    } else {
        $error = $response['data']['message'] ?? $response['data']['error'] ?? 'Erro ao registrar usuário.';
    }
}

$usersResponse = usersListAllEndpoint($token);
$users = $usersResponse['ok'] && is_array($usersResponse['data']) ? $usersResponse['data'] : [];
?>
<div class="app-content-header">
    <div class="container-fluid">
        <h3 class="mb-0">Usuários do Sistema</h3>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>

        <div class="card mb-3">
            <div class="card-body">
                <form method="POST" class="row g-2">
                    <input type="hidden" name="action" value="create_user">
                    <div class="col-md-4"><input type="email" class="form-control" name="email" placeholder="E-mail" required></div>
                    <div class="col-md-3"><input type="password" class="form-control" name="senha" placeholder="Senha" required></div>
                    <div class="col-md-2">
                        <select name="role" class="form-select" required>
                            <option value="user">user</option>
                            <option value="admin">admin</option>
                        </select>
                    </div>
                    <div class="col-md-2"><input type="number" name="loja_id" class="form-control" value="1" min="1" required></div>
                    <div class="col-md-1"><button type="submit" class="btn btn-primary w-100">+</button></div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr><th>ID</th><th>E-mail</th><th>Loja</th><th>Nível</th><th>Último login</th></tr>
                    </thead>
                    <tbody>
                        <?php if (!$users): ?>
                            <tr><td colspan="5" class="text-center text-muted">Nenhum usuário retornado pela API.</td></tr>
                        <?php else: foreach ($users as $user): ?>
                            <tr>
                                <td><?= (int) ($user['id'] ?? 0); ?></td>
                                <td><?= htmlspecialchars((string) ($user['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= (int) ($user['loja_id'] ?? 0); ?></td>
                                <td><?= htmlspecialchars((string) ($user['role'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars((string) ($user['last_login'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
