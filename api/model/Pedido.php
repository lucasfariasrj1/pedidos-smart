<?php
class Pedido {
    private $conn;
    private $table_name = "pedidos";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($loja_id = null) {
        $query = "SELECT p.*, f.nome as fornecedor_nome FROM " . $this->table_name . " p 
                  LEFT JOIN fornecedores f ON p.fornecedor_id = f.id";
        
        if ($loja_id) {
            $query .= " WHERE p.loja_id = :loja_id";
        }
        
        $query .= " ORDER BY p.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        if ($loja_id) $stmt->bindParam(":loja_id", $loja_id);
        
        $stmt->execute();
        return $stmt;
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET loja_id=:loja_id, usuario_id=:usuario_id, fornecedor_id=:fornecedor_id, 
                  modelo_celular=:modelo, nome_peca=:peca, quantidade=:qtd, data_pedido=:data, 
                  preco_fornecedor=:preco, observacoes=:obs";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }
}