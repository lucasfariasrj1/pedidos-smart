<?php
require_once __DIR__ . '/includes/api/logatividades.php';

$token = $_COOKIE['jwt_token'] ?? '';
$feedbackType = null;
$feedbackMessage = null;
$logDetalhe = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'view_log') {
    $logId = (int) ($_POST['log_id'] ?? 0);
    if ($logId > 0) {
        $detailResponse = logatividadesDetailEndpoint($token, $logId);
        if ($detailResponse['ok']) {
            $logDetalhe = $detailResponse['data'];
        } else {
            $feedbackType = 'error';
            $feedbackMessage = (string) ($detailResponse['data']['error'] ?? 'Não foi possível carregar o detalhe do log.');
        }
    }
}

$logsResponse = logatividadesListEndpoint($token);
$logs = $logsResponse['ok'] && is_array($logsResponse['data']) ? $logsResponse['data'] : [];

if (!$logsResponse['ok'] && $feedbackMessage === null) {
    $feedbackType = 'error';
    $feedbackMessage = (string) ($logsResponse['data']['error'] ?? 'Erro ao carregar logs da API.');
}

function logValue(array $log, array $keys, string $default = '-'): string
{
    foreach ($keys as $key) {
        if (isset($log[$key]) && $log[$key] !== '') {
            return (string) $log[$key];
        }
    }
    return $default;
}
?>
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0">Logs de Atividade</h3>
                <p class="text-secondary small">Integração via GET /logatividades e GET /logatividades/:id</p>
            </div>
            <div class="col-sm-6 text-end">
                <button class="btn btn-outline-danger btn-sm" type="button" onclick="showSystemAlert('warning','Limpeza de logs','Ação de limpeza não implementada pela API atual.')">
                    <i class="bi bi-trash3 me-1"></i> Limpar Logs Antigos
                </button>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>ID</th>
                                <th>Data/Hora</th>
                                <th>Usuário</th>
                                <th>Ação</th>
                                <th>Descrição</th>
                                <th>IP</th>
                                <th class="text-center">Detalhes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logs)): ?>
                                <tr><td colspan="7" class="text-center small text-muted">Nenhum log retornado pela API.</td></tr>
                            <?php else: ?>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td>#<?= (int) ($log['id'] ?? 0); ?></td>
                                        <td class="small text-muted"><?= htmlspecialchars(logValue($log, ['created_at', 'data_hora', 'dataHora']), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= htmlspecialchars(logValue($log, ['usuario_nome', 'usuario', 'user', 'email']), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><span class="badge text-bg-primary"><?= htmlspecialchars(logValue($log, ['acao', 'action', 'tipo']), ENT_QUOTES, 'UTF-8'); ?></span></td>
                                        <td><?= htmlspecialchars(logValue($log, ['descricao', 'description', 'mensagem']), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="small text-muted text-nowrap"><?= htmlspecialchars(logValue($log, ['ip', 'ip_address']), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="text-center">
                                            <form method="POST">
                                                <input type="hidden" name="action" value="view_log">
                                                <input type="hidden" name="log_id" value="<?= (int) ($log['id'] ?? 0); ?>">
                                                <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bi bi-search"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($feedbackMessage): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    showSystemAlert('<?= $feedbackType === 'error' ? 'error' : 'warning'; ?>', 'Logs', <?= json_encode($feedbackMessage, JSON_UNESCAPED_UNICODE); ?>);
});
</script>
<?php endif; ?>

<?php if (is_array($logDetalhe)): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    showSystemAlert('info', 'Detalhes do log #<?= (int) ($logDetalhe['id'] ?? 0); ?>', <?= json_encode(json_encode($logDetalhe, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), JSON_UNESCAPED_UNICODE); ?>);
});
</script>
<?php endif; ?>
