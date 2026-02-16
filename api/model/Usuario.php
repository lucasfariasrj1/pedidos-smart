<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public function __construct($db) { $this->conn = $db; }

    public function all() {
        $query = "SELECT u.id, u.nome, u.email, u.role, u.loja_id, l.nome as loja_nome 
                  FROM " . $this->table_name . " u 
                  LEFT JOIN lojas l ON u.loja_id = l.id ORDER BY u.nome ASC";
        return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $hash = password_hash($data['senha'], PASSWORD_BCRYPT);
        $query = "INSERT INTO " . $this->table_name . " (loja_id, nome, email, senha, role) VALUES (?, ?, ?, ?, ?)";
        return $this->conn->prepare($query)->execute([$data['loja_id'], $data['nome'], $data['email'], $hash, $data['role']]);
    }

    public function update($id, $data) {
        $sql = "UPDATE " . $this->table_name . " SET loja_id=?, nome=?, email=?, role=? WHERE id=?";
        return $this->conn->prepare($sql)->execute([$data['loja_id'], $data['nome'], $data['email'], $data['role'], $id]);
    }

    public function delete($id) {
        return $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id = ?")->execute([$id]);
    }
}