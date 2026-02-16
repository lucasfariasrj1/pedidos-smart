<?php
require_once 'model/Pedido.php';
require_once 'controllers/LogController.php';

class PedidoController {
    public function handle($metodo, $id, $user) {
        $db = (new Database())->getConnection();
        $model = new Pedido($db);
        $log = new LogController();

        switch ($metodo) {
            case 'GET':
                // Se não for admin, filtra pela loja do usuário
                $loja_id = ($user['role'] === 'admin') ? null : $user['loja_id'];
                $result = $id ? $model->find($id, $loja_id) : $model->all($loja_id);
                echo json_encode($result);
                break;

            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                $data['loja_id'] = $user['loja_id'];
                $data['usuario_id'] = $user['id'];
                
                if ($model->create($data)) {
                    $log->registrar($user['id'], "CRIAR", "Pedido de {$data['nome_peca']} adicionado.");
                    echo json_encode(["message" => "Pedido criado!"]);
                }
                break;

            case 'DELETE':
                if ($model->delete($id, $user['loja_id'])) {
                    $log->registrar($user['id'], "EXCLUIR", "Pedido ID $id removido.");
                    echo json_encode(["message" => "Pedido excluído!"]);
                }
                break;
        }
    }
}