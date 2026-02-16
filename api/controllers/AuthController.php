<?php

require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class AuthController
{
    private Usuario $usuarioModel;
    private AuthMiddleware $authMiddleware;
    private LogController $logController;

    public function __construct(PDO $db, AuthMiddleware $authMiddleware, LogController $logController)
    {
        $this->usuarioModel = new Usuario($db);
        $this->authMiddleware = $authMiddleware;
        $this->logController = $logController;
    }

    public function register(): void
    {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        if (empty($data['nome']) || empty($data['email']) || empty($data['senha'])) {
            http_response_code(422);
            echo json_encode(['error' => 'nome, email e senha são obrigatórios']);
            return;
        }

        if ($this->usuarioModel->findByEmail($data['email'])) {
            http_response_code(409);
            echo json_encode(['error' => 'E-mail já cadastrado']);
            return;
        }

        $ok = $this->usuarioModel->create($data);
        if (!$ok) {
            http_response_code(500);
            echo json_encode(['error' => 'Não foi possível registrar usuário']);
            return;
        }

        $novo = $this->usuarioModel->findByEmail($data['email']);
        $this->logController->registrar($novo['id'] ?? null, 'USUARIO_REGISTER', 'Novo usuário registrado');

        http_response_code(201);
        echo json_encode(['message' => 'Usuário registrado com sucesso']);
    }

    public function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        if (empty($data['email']) || empty($data['senha'])) {
            http_response_code(422);
            echo json_encode(['error' => 'email e senha são obrigatórios']);
            return;
        }

        $usuario = $this->usuarioModel->findByEmail($data['email']);
        if (!$usuario || !password_verify($data['senha'], $usuario['senha'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Credenciais inválidas']);
            return;
        }

        $token = $this->authMiddleware->generateToken([
            'id' => (int)$usuario['id'],
            'loja_id' => isset($usuario['loja_id']) ? (int)$usuario['loja_id'] : null,
            'role' => $usuario['role'] ?? 'usuario'
        ]);

        $this->logController->registrar((int)$usuario['id'], 'USUARIO_LOGIN', 'Login realizado');

        echo json_encode([
            'token' => $token,
            'user' => [
                'id' => (int)$usuario['id'],
                'nome' => $usuario['nome'],
                'email' => $usuario['email'],
                'role' => $usuario['role'],
                'loja_id' => $usuario['loja_id']
            ]
        ]);
    }

    public function logout(?array $user): void
    {
        $this->logController->registrar($user['id'] ?? null, 'USUARIO_LOGOUT', 'Logout solicitado');
        echo json_encode(['message' => 'Logout efetuado (invalidação lógica no cliente).']);
    }
}
