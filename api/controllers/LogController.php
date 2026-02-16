<?php

class LogController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function registrar(?int $user_id, string $acao, string $descricao): void
    {
        $query = 'INSERT INTO logs_atividade (usuario_id, acao, descricao, ip_address) VALUES (?, ?, ?, ?)';
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            $user_id,
            $acao,
            $descricao,
            $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
        ]);
    }

    public function handle(string $metodo): void
    {
        if ($metodo !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Método não permitido']);
            return;
        }

        $stmt = $this->db->query(
            'SELECT l.*, u.nome FROM logs_atividade l LEFT JOIN usuarios u ON l.usuario_id = u.id ORDER BY created_at DESC'
        );
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
