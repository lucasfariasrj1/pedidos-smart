<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

require_once 'model/Database.php';

// Captura a URL: ex: /api/pedidos/10
$url = isset($_GET['url']) ? explode('/', $_GET['url']) : [];
$recurso = $url[0] ?? null;
$id = $url[1] ?? null;
$metodo = $_SERVER['REQUEST_METHOD'];

// Mock de Usuário Logado (Em produção, extraia do Session ou JWT)
$user_session = ['id' => 1, 'loja_id' => 1, 'role' => 'usuario']; 

// Definição das Rotas
switch ($recurso) {
    case 'pedidos':
        require_once 'controllers/PedidoController.php';
        (new PedidoController())->handle($metodo, $id, $user_session);
        break;

    case 'fornecedores':
        require_once 'controllers/FornecedorController.php';
        (new FornecedorController())->handle($metodo, $id);
        break;

    case 'usuarios':
        require_once 'controllers/UsuarioController.php';
        (new UsuarioController())->handle($metodo, $id);
        break;

    case 'logs':
        require_once 'controllers/LogController.php';
        (new LogController())->handle($metodo);
        break;

    default:
        http_response_code(404);
        echo json_encode(["error" => "Rota não encontrada"]);
        break;
}