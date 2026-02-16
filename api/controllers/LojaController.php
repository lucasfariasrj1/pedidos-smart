<?php
require_once 'model/loja.php';
require_once 'controllers/LogController.php';

class LojaController {
    public function handle($metodo, $id, $user) {
        if ($user['role'] !== 'admin') {
            http_response_code(403);
            exit(json_encode(["error" => "Acesso negado"]));
        }

        $db = (new Database())->getConnection();
        $model = new Loja($db);
        $log = new LogController();

        switch ($metodo) {
            case 'GET':
                echo json_encode($model->all());
                break;
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                if ($model->create($data)) {
                    $log->registrar($user['id'], "CRIAR", "Loja {$data['nome']} cadastrada.");
                    echo json_encode(["message" => "Loja criada"]);
                }
                break;
            case 'PUT':
                $data = json_decode(file_get_contents("php://input"), true);
                if ($model->update($id, $data)) {
                    $log->registrar($user['id'], "EDITAR", "Loja ID $id atualizada.");
                    echo json_encode(["message" => "Loja atualizada"]);
                }
                break;
            case 'DELETE':
                if ($model->delete($id)) {
                    $log->registrar($user['id'], "EXCLUIR", "Loja ID $id removida.");
                    echo json_encode(["message" => "Loja removida"]);
                }
                break;
        }
    }
}