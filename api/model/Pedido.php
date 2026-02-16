<?php

class Pedido
{
    private PDO $conn;
    private string $table_name = 'pedidos';

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function read(?int $loja_id = null): array
    {
        $query = "SELECT p.*, f.nome AS fornecedor_nome
                  FROM {$this->table_name} p
                  LEFT JOIN fornecedores f ON p.fornecedor_id = f.id";

        $params = [];
        if ($loja_id !== null) {
            $query .= ' WHERE p.loja_id = ?';
            $params[] = $loja_id;
        }

        $query .= ' ORDER BY p.created_at DESC';

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id, ?int $loja_id = null): ?array
    {
        $query = "SELECT p.*, f.nome AS fornecedor_nome
                  FROM {$this->table_name} p
                  LEFT JOIN fornecedores f ON p.fornecedor_id = f.id
                  WHERE p.id = ?";

        $params = [$id];
        if ($loja_id !== null) {
            $query .= ' AND p.loja_id = ?';
            $params[] = $loja_id;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        return $pedido ?: null;
    }

    public function create(array $data): bool
    {
        $query = "INSERT INTO {$this->table_name} (
                    loja_id, usuario_id, fornecedor_id, modelo_celular,
                    nome_peca, quantidade, data_pedido, preco_fornecedor, observacoes
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        return $this->conn->prepare($query)->execute([
            $data['loja_id'],
            $data['usuario_id'],
            $data['fornecedor_id'],
            $data['modelo_celular'],
            $data['nome_peca'],
            $data['quantidade'],
            $data['data_pedido'],
            $data['preco_fornecedor'],
            $data['observacoes'] ?? null
        ]);
    }

    public function update(int $id, array $data, ?int $loja_id = null): bool
    {
        $query = "UPDATE {$this->table_name}
                  SET fornecedor_id = ?, modelo_celular = ?, nome_peca = ?, quantidade = ?,
                      data_pedido = ?, preco_fornecedor = ?, observacoes = ?
                  WHERE id = ?";

        $params = [
            $data['fornecedor_id'],
            $data['modelo_celular'],
            $data['nome_peca'],
            $data['quantidade'],
            $data['data_pedido'],
            $data['preco_fornecedor'],
            $data['observacoes'] ?? null,
            $id
        ];

        if ($loja_id !== null) {
            $query .= ' AND loja_id = ?';
            $params[] = $loja_id;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    public function delete(int $id, ?int $loja_id = null): bool
    {
        $query = "DELETE FROM {$this->table_name} WHERE id = ?";
        $params = [$id];

        if ($loja_id !== null) {
            $query .= ' AND loja_id = ?';
            $params[] = $loja_id;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }
}
