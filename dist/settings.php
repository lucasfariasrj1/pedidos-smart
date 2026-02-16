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

include_once __DIR__ . '/includes/header.php';
?>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
<div class="app-wrapper">
    <?php include_once __DIR__ . '/includes/sidebar.php'; ?>

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <h3 class="mb-0">Configurações da Conta</h3>
                <p class="text-muted mb-0">Dados carregados de <code>GET /auth/users/me</code>.</p>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ID do usuário</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars((string) ($currentUser['id'] ?? '-'), ENT_QUOTES, 'UTF-8') ?>" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">E-mail</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars((string) ($currentUser['email'] ?? '-'), ENT_QUOTES, 'UTF-8') ?>" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Role</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars((string) ($currentUser['role'] ?? 'user'), ENT_QUOTES, 'UTF-8') ?>" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Loja ID</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars((string) ($currentUser['loja_id'] ?? '-'), ENT_QUOTES, 'UTF-8') ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include_once __DIR__ . '/includes/footer.php'; ?>
</div>
