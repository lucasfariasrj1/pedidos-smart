<?php

require_once __DIR__ . '/../model/Pedido.php';

class PedidoController
{
    private Pedido $model;
    private LogController $log;

    public function __construct(PDO $db, LogController $log)
    {
        $this->model = new Pedido($db);
        $this->log = $log;
    }

    public function handle(string $metodo, ?int $id, array $user): void
    {
        $loja_id = ($user['role'] ?? 'usuario') === 'admin' ? null : (int)($user['loja_id'] ?? 0);

        switch ($metodo) {
            case 'GET':
                $result = $id ? $this->model->find($id, $loja_id) : $this->model->read($loja_id);
                if ($id && !$result) {
                    http_response_code(404);
                    echo json_encode(['error' => 'Pedido não encontrado']);
                    return;
                }
                echo json_encode($result);
                return;

            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true) ?? [];
                $data['loja_id'] = $loja_id ?? (int)($data['loja_id'] ?? 0);
                $data['usuario_id'] = (int)$user['id'];
                if ($this->model->create($data)) {
                    $this->log->registrar($user['id'], 'PEDIDO_CREATE', 'Pedido criado: ' . ($data['nome_peca'] ?? '')); 
                    http_response_code(201);
                    echo json_encode(['message' => 'Pedido criado']);
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
                if ($this->model->update($id, $data, $loja_id)) {
                    $this->log->registrar($user['id'], 'PEDIDO_UPDATE', 'Pedido atualizado ID: ' . $id);
                    echo json_encode(['message' => 'Pedido atualizado']);
                    return;
                }

                http_response_code(404);
                echo json_encode(['error' => 'Pedido não encontrado ou sem permissão']);
                return;

            case 'DELETE':
                if (!$id) {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID é obrigatório']);
                    return;
                }

                if ($this->model->delete($id, $loja_id)) {
                    $this->log->registrar($user['id'], 'PEDIDO_DELETE', 'Pedido removido ID: ' . $id);
                    echo json_encode(['message' => 'Pedido removido']);
                    return;
                }

                http_response_code(404);
                echo json_encode(['error' => 'Pedido não encontrado ou sem permissão']);
                return;

            default:
                http_response_code(405);
                echo json_encode(['error' => 'Método não permitido']);
                return;
        }

        http_response_code(500);
        echo json_encode(['error' => 'Operação não concluída']);
    }
}
