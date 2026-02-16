<?php

require_once __DIR__ . '/client.php';

function endpointAuthFornecedoresPut(string $token, int $id, string $nome, string $whatsapp, string $email): array
{
    return apiRequest('PUT', '/auth/fornecedores/' . $id, [
        'nome' => $nome,
        'whatsapp' => $whatsapp,
        'email' => $email,
    ], $token);
}
