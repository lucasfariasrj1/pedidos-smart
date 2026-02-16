<?php

require_once __DIR__ . '/client.php';

function endpointAuthFornecedoresPost(string $token, string $nome, string $whatsapp, string $email): array
{
    return apiRequest('POST', '/auth/fornecedores', [
        'nome' => $nome,
        'whatsapp' => $whatsapp,
        'email' => $email,
    ], $token);
}
