<?php

require_once __DIR__ . '/../model/Usuario.php';

class UsuarioController
{
    private Usuario $model;
    private LogController $log;

    public function __construct(PDO $db, LogController $log)
    {
        $this->model = new Usuario($db);
        $this->log = $log;
    }

    public function handle(string $metodo, ?int $id, array $user): void
    {
        if (($user['role'] ?? 'usuario') !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Acesso negado']);
            return;
        }

        switch ($metodo) {
            case 'GET':
                if ($id) {
                    $usuario = $this->model->findById($id);
                    if (!$usuario) {
                        http_response_code(404);
                        echo json_encode(['error' => 'Usuário não encontrado']);
                        return;
                    }
                    echo json_encode($usuario);
                    return;
                }

                echo json_encode($this->model->all());
                return;

            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true) ?? [];
                if (empty($data['nome']) || empty($data['email']) || empty($data['senha'])) {
                    http_response_code(422);
                    echo json_encode(['error' => 'nome, email e senha são obrigatórios']);
                    return;
                }

                if ($this->model->findByEmail($data['email'])) {
                    http_response_code(409);
                    echo json_encode(['error' => 'E-mail já cadastrado']);
                    return;
                }

                if ($this->model->create($data)) {
                    $this->log->registrar($user['id'], 'USUARIO_CREATE', 'Usuário criado: ' . $data['email']);
                    http_response_code(201);
                    echo json_encode(['message' => 'Usuário criado com sucesso']);
                    return;
                }
                break;

            case 'PUT':
                if (!$id) {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID é obrigatório']);
                    return;
                }

                $data = json_decode(file_get_contents('php://input'), true) ?? [];
                if ($this->model->update($id, $data)) {
                    $this->log->registrar($user['id'], 'USUARIO_UPDATE', 'Usuário atualizado ID: ' . $id);
                    echo json_encode(['message' => 'Usuário atualizado']);
                    return;
                }
                break;

            case 'DELETE':
                if (!$id) {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID é obrigatório']);
                    return;
                }

                if ($this->model->delete($id)) {
                    $this->log->registrar($user['id'], 'USUARIO_DELETE', 'Usuário removido ID: ' . $id);
                    echo json_encode(['message' => 'Usuário removido']);
                    return;
                }
                break;

            default:
                http_response_code(405);
                echo json_encode(['error' => 'Método não permitido']);
                return;
        }

        http_response_code(500);
        echo json_encode(['error' => 'Operação não concluída']);
    }
}
