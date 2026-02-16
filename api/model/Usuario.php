<?php

class Usuario
{
    private PDO $conn;
    private string $table_name = 'usuarios';

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function all(): array
    {
        $query = "SELECT u.id, u.nome, u.email, u.role, u.loja_id, l.nome AS loja_nome
                  FROM {$this->table_name} u
                  LEFT JOIN lojas l ON u.loja_id = l.id
                  ORDER BY u.nome ASC";

        return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $query = "SELECT id, loja_id, nome, email, role FROM {$this->table_name} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $query = "SELECT * FROM {$this->table_name} WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function create(array $data): bool
    {
        $hash = password_hash($data['senha'], PASSWORD_BCRYPT);
        $query = "INSERT INTO {$this->table_name} (loja_id, nome, email, senha, role) VALUES (?, ?, ?, ?, ?)";

        return $this->conn->prepare($query)->execute([
            $data['loja_id'] ?? null,
            $data['nome'],
            $data['email'],
            $hash,
            $data['role'] ?? 'usuario'
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $query = "UPDATE {$this->table_name} SET loja_id = ?, nome = ?, email = ?, role = ? WHERE id = ?";

        return $this->conn->prepare($query)->execute([
            $data['loja_id'] ?? null,
            $data['nome'],
            $data['email'],
            $data['role'] ?? 'usuario',
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $query = "DELETE FROM {$this->table_name} WHERE id = ?";
        return $this->conn->prepare($query)->execute([$id]);
    }
}
