<?php

require_once __DIR__ . '/../model/Fornecedor.php';

class FornecedorController
{
    private Fornecedor $model;
    private LogController $log;

    public function __construct(PDO $db, LogController $log)
    {
        $this->model = new Fornecedor($db);
        $this->log = $log;
    }

    public function handle(string $metodo, ?int $id, array $user): void
    {
        switch ($metodo) {
            case 'GET':
                echo json_encode($this->model->all());
                return;

            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true) ?? [];
                if ($this->model->create($data)) {
                    $this->log->registrar($user['id'] ?? null, 'FORNECEDOR_CREATE', 'Fornecedor criado: ' . ($data['nome'] ?? ''));
                    http_response_code(201);
                    echo json_encode(['message' => 'Fornecedor criado']);
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
                    $this->log->registrar($user['id'] ?? null, 'FORNECEDOR_UPDATE', 'Fornecedor atualizado ID: ' . $id);
                    echo json_encode(['message' => 'Fornecedor atualizado']);
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
                    $this->log->registrar($user['id'] ?? null, 'FORNECEDOR_DELETE', 'Fornecedor removido ID: ' . $id);
                    echo json_encode(['message' => 'Fornecedor removido']);
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
