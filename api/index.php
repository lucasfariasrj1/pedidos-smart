<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/model/Database.php';
require_once __DIR__ . '/model/Setting.php';
require_once __DIR__ . '/controllers/LogController.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/PedidoController.php';
require_once __DIR__ . '/controllers/FornecedorController.php';
require_once __DIR__ . '/controllers/UsuarioController.php';
require_once __DIR__ . '/controllers/LojaController.php';
require_once __DIR__ . '/controllers/SettingController.php';

$recurso = $url[0] ?? null;
$id = isset($url[1]) ? (int)$url[1] : null;
$subrecurso = $url[1] ?? null;
$metodo = $_SERVER['REQUEST_METHOD'];

$db = (new Database())->getConnection();
if (!($db instanceof PDO)) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro de conexão com banco de dados']);
    exit;
}
$settingModel = new Setting($db);
$logController = new LogController($db);
$authMiddleware = new AuthMiddleware('smart-hard-super-secret');

$publicRoutes = [
    'register' => ['POST'],
    'login' => ['POST']
];

$user_session = null;
if (!isset($publicRoutes[$recurso]) || !in_array($metodo, $publicRoutes[$recurso], true)) {
    try {
        $token = $authMiddleware->getBearerToken();
        $payload = $authMiddleware->validateToken($token);
        $user_session = [
            'id' => (int)$payload['id'],
            'loja_id' => isset($payload['loja_id']) ? (int)$payload['loja_id'] : null,
            'role' => $payload['role'] ?? 'usuario'
        ];
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['error' => 'Não autorizado: ' . $e->getMessage()]);
        exit;
    }
}

$maintenance = $settingModel->getByKey('modo_manutencao');
$isMaintenance = isset($maintenance['valor']) && ((string)$maintenance['valor'] === '1' || strtolower((string)$maintenance['valor']) === 'true');
if ($isMaintenance && (($user_session['role'] ?? 'usuario') !== 'admin')) {
    http_response_code(503);
    echo json_encode(['error' => 'Sistema em manutenção']);
    exit;
}

switch ($recurso) {
    case 'register':
        (new AuthController($db, $authMiddleware, $logController))->register();
        break;

    case 'login':
        (new AuthController($db, $authMiddleware, $logController))->login();
        break;

    case 'logout':
        if ($metodo !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método não permitido']);
            break;
        }
        (new AuthController($db, $authMiddleware, $logController))->logout($user_session);
        break;

    case 'pedidos':
        (new PedidoController($db, $logController))->handle($metodo, $id, $user_session);
        break;

    case 'fornecedores':
        (new FornecedorController($db, $logController))->handle($metodo, $id, $user_session);
        break;

    case 'usuarios':
        (new UsuarioController($db, $logController))->handle($metodo, $id, $user_session);
        break;

    case 'lojas':
        if ($metodo === 'POST') {
            (new SettingController($db, $logController))->handle($metodo, 'lojas', $user_session);
            break;
        }
        (new LojaController($db, $logController))->handle($metodo, $id, $user_session);
        break;

    case 'settings':
        (new SettingController($db, $logController))->handle($metodo, $subrecurso, $user_session);
        break;

    case 'logs':
        if (($user_session['role'] ?? 'usuario') !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Acesso negado']);
            break;
        }
        $logController->handle($metodo);
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Rota não encontrada']);
        break;
}
