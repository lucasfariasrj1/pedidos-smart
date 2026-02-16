<?php

class Setting
{
    private PDO $conn;
    private string $table_name = 'settings';

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function all(): array
    {
        $query = 'SELECT id, chave, valor, categoria, updated_at FROM ' . $this->table_name . ' ORDER BY categoria ASC, chave ASC';
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByKey(string $chave): ?array
    {
        $query = 'SELECT id, chave, valor, categoria, updated_at FROM ' . $this->table_name . ' WHERE chave = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$chave]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ?: null;
    }

    public function updateByKey(string $chave, string $valor): bool
    {
        $query = 'UPDATE ' . $this->table_name . ' SET valor = ?, updated_at = NOW() WHERE chave = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$valor, $chave]);

        return $stmt->rowCount() > 0;
    }
}
