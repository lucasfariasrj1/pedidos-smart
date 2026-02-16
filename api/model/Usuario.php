<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public function __construct($db) { $this->conn = $db; }

    public function all() {
        $query = "SELECT u.id, u.nome, u.email, u.role, l.nome as loja_nome 
                  FROM " . $this->table_name . " u 
                  LEFT JOIN lojas l ON u.loja_id = l.id";
        return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        // Senha criptografada com Bcrypt
        $hash = password_hash($data['senha'], PASSWORD_BCRYPT);
        $query = "INSERT INTO " . $this->table_name . " (loja_id, nome, email, senha, role) VALUES (?, ?, ?, ?, ?)";
        return $this->conn->prepare($query)->execute([$data['loja_id'], $data['nome'], $data['email'], $hash, $data['role']]);
    }

    public function delete($id) {
        return $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id = ?")->execute([$id]);
    }
}