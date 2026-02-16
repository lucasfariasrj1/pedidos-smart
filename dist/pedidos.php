<?php
require_once __DIR__ . '/includes/api/orders.php';
require_once __DIR__ . '/includes/api/fornecedores.php';

$token = $_COOKIE['jwt_token'] ?? '';
$feedbackType = null;
$feedbackMessage = null;

$phoneModels = [
    'Apple' => ['iPhone SE (2020)','iPhone SE (2022)','iPhone 11','iPhone 11 Pro','iPhone 11 Pro Max','iPhone 12 mini','iPhone 12','iPhone 12 Pro','iPhone 12 Pro Max','iPhone 13 mini','iPhone 13','iPhone 13 Pro','iPhone 13 Pro Max','iPhone 14','iPhone 14 Plus','iPhone 14 Pro','iPhone 14 Pro Max','iPhone 15','iPhone 15 Plus','iPhone 15 Pro','iPhone 15 Pro Max','iPhone 16','iPhone 16 Plus','iPhone 16 Pro','iPhone 16 Pro Max'],
    'Samsung' => ['Galaxy S20','Galaxy S20 FE','Galaxy S21','Galaxy S21 FE','Galaxy S22','Galaxy S23','Galaxy S24','Galaxy S25','Galaxy S20+','Galaxy S21+','Galaxy S22+','Galaxy S23+','Galaxy S24+','Galaxy S25+','Galaxy S20 Ultra','Galaxy S21 Ultra','Galaxy S22 Ultra','Galaxy S23 Ultra','Galaxy S24 Ultra','Galaxy S25 Ultra','Galaxy A12','Galaxy A13','Galaxy A14','Galaxy A15','Galaxy A21s','Galaxy A22','Galaxy A23','Galaxy A24','Galaxy A25','Galaxy A31','Galaxy A32','Galaxy A33','Galaxy A34','Galaxy A35','Galaxy A51','Galaxy A52','Galaxy A53','Galaxy A54','Galaxy A55','Galaxy M12','Galaxy M13','Galaxy M14','Galaxy M15','Galaxy Z Flip 3','Galaxy Z Flip 4','Galaxy Z Flip 5','Galaxy Z Fold 3','Galaxy Z Fold 4','Galaxy Z Fold 5'],
    'Xiaomi' => ['Redmi 9','Redmi 10','Redmi 12','Redmi 13','Redmi Note 9','Redmi Note 10','Redmi Note 11','Redmi Note 12','Redmi Note 13','Poco X3','Poco X4','Poco X5','Poco X6','Poco F3','Poco F4','Poco F5','Mi 11','Mi 11 Lite','Mi 12','Mi 12T','Xiaomi 13','Xiaomi 13 Lite','Xiaomi 14'],
    'Motorola' => ['Moto E6','Moto E7','Moto E13','Moto E20','Moto E22','Moto E32','Moto G8','Moto G9','Moto G10','Moto G20','Moto G22','Moto G23','Moto G24','Moto G30','Moto G31','Moto G32','Moto G41','Moto G42','Moto G52','Moto G53','Moto G54','Moto G60','Moto G62','Moto G72','Moto G73','Moto G84','Moto G85','Edge 20','Edge 30','Edge 40','Edge 50'],
    'Realme' => ['Realme C11','Realme C21','Realme C25','Realme C33','Realme C35','Realme C55','Realme 8','Realme 9','Realme 10','Realme 11','Realme Narzo 30','Realme Narzo 50'],
    'OPPO' => ['OPPO A15','OPPO A16','OPPO A17','OPPO A54','OPPO A57','OPPO A58','OPPO Reno 6','OPPO Reno 7','OPPO Reno 8','OPPO Reno 11'],
    'Vivo' => ['Vivo Y15','Vivo Y20','Vivo Y22','Vivo Y27','Vivo Y36','Vivo V21','Vivo V23','Vivo V25','Vivo V27'],
    'ASUS' => ['Zenfone 7','Zenfone 8','Zenfone 9','Zenfone 10','ROG Phone 5','ROG Phone 6','ROG Phone 7'],
    'Nokia' => ['Nokia 2.4','Nokia 3.4','Nokia 5.4','Nokia G10','Nokia G20','Nokia G22','Nokia X20'],
    'Huawei' => ['P30','P40','P50','Mate 30','Mate 40','Nova 8','Nova 9','Nova 10'],
    'LG' => ['K22','K40s','K51s','K61','K62','Velvet'],
    'Sony' => ['Xperia 1 II','Xperia 1 III','Xperia 1 IV','Xperia 5 II','Xperia 5 III','Xperia 10 IV'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create_order') {
    $modelo = trim((string) ($_POST['modelo_celular'] ?? ''));
    $peca = trim((string) ($_POST['nome_peca'] ?? ''));
    $observacoes = trim((string) ($_POST['observacoes'] ?? ''));
    $fornecedorId = (int) ($_POST['fornecedor_id'] ?? 0);

    if ($modelo === '' || $peca === '' || $fornecedorId <= 0) {
        $feedbackType = 'error';
        $feedbackMessage = 'Preencha modelo, peça e fornecedor.';
    } else {
        $descPeca = $modelo . ' - ' . $peca;
        $createOrder = ordersCreateEndpoint($token, $descPeca, $fornecedorId, $observacoes);
        if ($createOrder['ok']) {
            $feedbackType = 'success';
            $feedbackMessage = (string) ($createOrder['data']['message'] ?? 'Pedido criado com sucesso.');
        } else {
            $feedbackType = 'error';
            $feedbackMessage = (string) ($createOrder['data']['message'] ?? $createOrder['data']['error'] ?? 'Não foi possível criar o pedido.');
        }
    }
}

$fornecedoresResponse = fornecedoresListEndpoint($token);
$fornecedores = $fornecedoresResponse['ok'] && is_array($fornecedoresResponse['data']) ? $fornecedoresResponse['data'] : [];
if (!$fornecedoresResponse['ok'] && $feedbackMessage === null) {
    $feedbackType = 'error';
    $feedbackMessage = (string) ($fornecedoresResponse['data']['error'] ?? 'Falha ao carregar fornecedores pela API.');
}
?>
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row"><div class="col-sm-6"><h3 class="mb-0">Novo Pedido</h3></div></div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-8">
                <div class="card card-primary card-outline shadow-sm">
                    <form id="pedido-form" method="POST">
                        <input type="hidden" name="action" value="create_order">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Modelo do Celular</label>
                                <select name="modelo_celular" class="form-select" required>
                                    <option value="">Selecione o modelo...</option>
                                    <?php foreach ($phoneModels as $brand => $models): ?>
                                        <optgroup label="<?= htmlspecialchars($brand, ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php foreach ($models as $model): ?>
                                                <option value="<?= htmlspecialchars($model, ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($model, ENT_QUOTES, 'UTF-8'); ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nome da Peça</label>
                                <input type="text" name="nome_peca" class="form-control" placeholder="Ex: Tela, Bateria, Conector..." required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Fornecedor</label>
                                <select id="fornecedorSelect" name="fornecedor_id" class="form-select" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($fornecedores as $fornecedor): ?>
                                        <option value="<?= (int) ($fornecedor['id'] ?? 0); ?>"><?= htmlspecialchars((string) ($fornecedor['nome'] ?? 'Sem nome'), ENT_QUOTES, 'UTF-8'); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Observações</label>
                                <textarea name="observacoes" class="form-control" rows="3" placeholder="Cor, especificação, etc..."></textarea>
                            </div>

                           
                        </div>

                        <div class="card-footer d-grid gap-2">
                            <button id="btnSubmitPedido" type="submit" class="btn btn-primary py-2 fw-bold">
                                <i class="bi bi-plus-lg me-2"></i> Adicionar Peça
                            </button>
                        </div>
                        <div class="card-footer d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-bold"><i class="bi bi-plus-lg me-2"></i> Criar Pedido</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($feedbackMessage): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    showSystemAlert('<?= $feedbackType === 'error' ? 'error' : 'success'; ?>', 'Pedidos', <?= json_encode($feedbackMessage, JSON_UNESCAPED_UNICODE); ?>);
});
</script>
<?php endif; ?>
