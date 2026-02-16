<?php
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/includes/api_helper.php';

$meResponse = callApi('GET', '/auth/users/me');
if (($meResponse['status'] ?? 0) === 401) {
    redirectToLogin();
}
$currentUser = apiData($meResponse);
if (!is_array($currentUser)) {
    $currentUser = [];
}
$isAdmin = ($currentUser['role'] ?? '') === 'admin';

$alert = null;
$usuarios = [];

if (!$isAdmin) {
    $alert = ['type' => 'warning', 'message' => 'Acesso restrito: somente administradores podem listar usuários.'];
} else {
    $usersResponse = callApi('GET', '/auth/users/listall');
    if (($usersResponse['status'] ?? 0) === 401) {
        redirectToLogin();
    }

    if ($usersResponse['ok']) {
        $usuarios = apiData($usersResponse);
        if (!is_array($usuarios)) {
            $usuarios = [];
        }
    } else {
        $alert = ['type' => 'danger', 'message' => apiMessage($usersResponse, 'Erro ao carregar usuários.')];
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
                <h3 class="mb-0">Usuários e Lojas</h3>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <?php if ($alert): ?>
                    <div class="alert alert-<?= $alert['type'] ?>" role="alert">
                        <?= htmlspecialchars($alert['message'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <?php if ($isAdmin): ?>
                    <div class="card shadow-sm">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>E-mail</th>
                                    <th>Role</th>
                                    <th>Loja ID</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (empty($usuarios)): ?>
                                    <tr><td colspan="4" class="text-center text-muted py-4">Nenhum usuário encontrado.</td></tr>
                                <?php endif; ?>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td><?= (int) ($usuario['id'] ?? 0) ?></td>
                                        <td><?= htmlspecialchars((string) ($usuario['email'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>
                                            <span class="badge <?= ($usuario['role'] ?? '') === 'admin' ? 'text-bg-danger' : 'text-bg-primary' ?>">
                                                <?= htmlspecialchars((string) ($usuario['role'] ?? 'user'), ENT_QUOTES, 'UTF-8') ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars((string) ($usuario['loja_id'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include_once __DIR__ . '/includes/footer.php'; ?>
</div>
