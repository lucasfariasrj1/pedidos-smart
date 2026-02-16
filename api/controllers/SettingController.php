<?php

require_once __DIR__ . '/../model/Setting.php';
require_once __DIR__ . '/../model/loja.php';

class SettingController
{
    private Setting $settingModel;
    private Loja $lojaModel;
    private LogController $log;

    public function __construct(PDO $db, LogController $log)
    {
        $this->settingModel = new Setting($db);
        $this->lojaModel = new Loja($db);
        $this->log = $log;
    }

    public function handle(string $metodo, ?string $subrecurso, array $user): void
    {
        if (($user['role'] ?? 'usuario') !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Acesso negado']);
            return;
        }

        if ($metodo === 'GET' && !$subrecurso) {
            echo json_encode($this->settingModel->all());
            return;
        }

        if ($metodo === 'PUT' && !$subrecurso) {
            $payload = json_decode(file_get_contents('php://input'), true);
            if (!is_array($payload) || empty($payload)) {
                http_response_code(400);
                echo json_encode(['error' => 'Payload inválido']);
                return;
            }

            $updated = [];
            foreach ($payload as $chave => $valor) {
                $valorNormalizado = is_scalar($valor) ? (string)$valor : json_encode($valor);
                if ($valorNormalizado === false) {
                    continue;
                }

                if ($this->settingModel->updateByKey((string)$chave, $valorNormalizado)) {
                    $updated[] = $chave;
                }
            }

            $this->log->registrar($user['id'], 'SETTING_UPDATE', 'Configurações alteradas: ' . implode(', ', $updated));
            echo json_encode(['message' => 'Configurações atualizadas', 'updated' => $updated]);
            return;
        }

        if ($metodo === 'POST' && $subrecurso === 'lojas') {
            $data = json_decode(file_get_contents('php://input'), true) ?? [];
            if (empty($data['nome'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Nome da loja é obrigatório']);
                return;
            }

            $data['cnpj'] = $data['cnpj'] ?? '';
            $data['endereco'] = $data['endereco'] ?? '';

            if ($this->lojaModel->create($data)) {
                $this->log->registrar($user['id'], 'LOJA_CREATE', 'Loja cadastrada via settings: ' . $data['nome']);
                http_response_code(201);
                echo json_encode(['message' => 'Loja criada']);
                return;
            }

            http_response_code(500);
            echo json_encode(['error' => 'Não foi possível criar a loja']);
            return;
        }

        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
    }
}
