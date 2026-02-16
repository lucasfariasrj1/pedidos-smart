<?php
class Fornecedor {
    private $conn;
    private $table_name = "fornecedores";

    public function __construct($db) { $this->conn = $db; }

    public function all() {
        return $this->conn->query("SELECT * FROM " . $this->table_name . " ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " (nome, whatsapp, email) VALUES (?, ?, ?)";
        return $this->conn->prepare($query)->execute([$data['nome'], $data['whatsapp'], $data['email']]);
    }

    public function delete($id) {
        return $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id = ?")->execute([$id]);
    }
}