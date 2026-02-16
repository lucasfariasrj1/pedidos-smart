<?php
require_once __DIR__ . '/includes/api/users.php';

$token = $_COOKIE['jwt_token'] ?? '';
$userMeResponse = usersMeEndpoint($token);
$userMe = $userMeResponse['ok'] && is_array($userMeResponse['data']) ? $userMeResponse['data'] : [];
?>
<div class="app-content-header">
    <div class="container-fluid">
        <h3 class="mb-0">Configurações</h3>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title">Dados do usuário autenticado (GET /auth/users/me)</h3>
            </div>
            <div class="card-body">
                <?php if ($userMeResponse['ok']): ?>
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label fw-bold">ID</label><input class="form-control" value="<?= (int) ($userMe['id'] ?? 0); ?>" readonly></div>
                        <div class="col-md-4"><label class="form-label fw-bold">E-mail</label><input class="form-control" value="<?= htmlspecialchars((string) ($userMe['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" readonly></div>
                        <div class="col-md-4"><label class="form-label fw-bold">Role</label><input class="form-control" value="<?= htmlspecialchars((string) ($userMe['role'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" readonly></div>
                        <div class="col-md-4"><label class="form-label fw-bold">Loja ID</label><input class="form-control" value="<?= (int) ($userMe['loja_id'] ?? 0); ?>" readonly></div>
                        <div class="col-md-4"><label class="form-label fw-bold">Último login</label><input class="form-control" value="<?= htmlspecialchars((string) ($userMe['last_login'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>" readonly></div>
                        <div class="col-md-4"><label class="form-label fw-bold">Atualizado em</label><input class="form-control" value="<?= htmlspecialchars((string) ($userMe['updated_at'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>" readonly></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (!$userMeResponse['ok']): ?>
<script>document.addEventListener('DOMContentLoaded', function () { showSystemAlert('error', 'Configurações', <?= json_encode((string) ($userMeResponse['data']['error'] ?? 'Falha ao carregar dados do usuário.'), JSON_UNESCAPED_UNICODE); ?>); });</script>
<?php endif; ?>
