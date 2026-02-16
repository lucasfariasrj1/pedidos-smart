<?php
class Loja {
    private $conn;
    private $table_name = "lojas";

    public function __construct($db) { $this->conn = $db; }

    public function all() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nome ASC";
        return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " (nome, cnpj, endereco) VALUES (?, ?, ?)";
        return $this->conn->prepare($query)->execute([$data['nome'], $data['cnpj'], $data['endereco']]);
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " SET nome=?, cnpj=?, endereco=?, ativo=? WHERE id=?";
        return $this->conn->prepare($query)->execute([$data['nome'], $data['cnpj'], $data['endereco'], $data['ativo'], $id]);
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        return $this->conn->prepare($query)->execute([$id]);
    }
}