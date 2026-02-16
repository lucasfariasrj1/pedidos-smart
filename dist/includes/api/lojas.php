<?php

require_once __DIR__ . '/client.php';

function lojasListAllEndpoint(string $token): array
{
    return apiRequest('GET', '/lojas/listall', null, $token);
}

function lojasCreateEndpoint(string $token, string $nome, string $endereco): array
{
    return apiRequest('POST', '/lojas', [
        'nome' => $nome,
        'endereco' => $endereco,
    ], $token);
}

function lojasUpdateEndpoint(string $token, int $id, string $nome, string $endereco): array
{
    return apiRequest('PUT', '/lojas/' . $id, [
        'nome' => $nome,
        'endereco' => $endereco,
    ], $token);
}

function lojasDeleteEndpoint(string $token, int $id): array
{
    return apiRequest('DELETE', '/lojas/' . $id, null, $token);
}
