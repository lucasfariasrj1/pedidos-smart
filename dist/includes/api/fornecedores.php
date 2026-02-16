<?php

require_once __DIR__ . '/client.php';

function fornecedoresListEndpoint(string $token): array
{
    return apiRequest('GET', '/fornecedores', null, $token);
}

function fornecedoresCreateEndpoint(string $token, string $nome, string $whatsapp, string $email): array
{
    return apiRequest('POST', '/fornecedores', [
        'nome' => $nome,
        'whatsapp' => $whatsapp,
        'email' => $email,
    ], $token);
}

function fornecedoresUpdateEndpoint(string $token, int $id, string $nome, string $whatsapp, string $email): array
{
    return apiRequest('PUT', '/fornecedores/' . $id, [
        'nome' => $nome,
        'whatsapp' => $whatsapp,
        'email' => $email,
    ], $token);
}

function fornecedoresDeleteEndpoint(string $token, int $id): array
{
    return apiRequest('DELETE', '/fornecedores/' . $id, null, $token);
}
