<?php

require_once __DIR__ . '/client.php';

function endpointAuthUsersPost(string $token, string $email, string $senha, string $role, int $lojaId): array
{
    return apiRequest('POST', '/auth/register', [
        'email' => $email,
        'senha' => $senha,
        'role' => $role,
        'loja_id' => $lojaId,
    ], $token);
}
