<?php
class LogController {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function registrar($user_id, $acao, $descricao) {
        $query = "INSERT INTO logs_atividade (usuario_id, acao, descricao, ip_address) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id, $acao, $descricao, $_SERVER['REMOTE_ADDR']]);
    }

    public function handle($metodo) {
        if ($metodo === 'GET') {
            $stmt = $this->db->query("SELECT l.*, u.nome FROM logs_atividade l JOIN usuarios u ON l.usuario_id = u.id ORDER BY created_at DESC");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    }
}