<?php
require_once 'model/Usuario.php';
require_once 'controllers/LogController.php';

class UsuarioController {
    public function handle($metodo, $id) {
        $db = (new Database())->getConnection();
        $model = new Usuario($db);
        $log = new LogController();
        $admin_id = 1; // Pegar da sessão real

        switch ($metodo) {
            case 'GET':
                echo json_encode($model->all());
                break;
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                if ($model->create($data)) {
                    $log->registrar($admin_id, "USUARIO", "Criou usuário: " . $data['nome']);
                    echo json_encode(["message" => "Usuário criado com sucesso"]);
                }
                break;
            case 'DELETE':
                if ($model->delete($id)) {
                    $log->registrar($admin_id, "USUARIO", "Excluiu usuário ID: " . $id);
                    echo json_encode(["message" => "Usuário removido"]);
                }
                break;
        }
    }
}