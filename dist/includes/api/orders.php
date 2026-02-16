<?php

require_once __DIR__ . '/client.php';

function ordersListEndpoint(string $token): array
{
    return apiRequest('GET', '/orders', null, $token);
}

function ordersCreateEndpoint(string $token, string $peca, int $fornecedorId, string $observacao): array
{
    return apiRequest('POST', '/orders', [
        'peca' => $peca,
        'fornecedor_id' => $fornecedorId,
        'observacao' => $observacao,
    ], $token);
}

function ordersUpdateEndpoint(string $token, int $id, string $status): array
{
    return apiRequest('PUT', '/orders/' . $id, [
        'status' => $status,
    ], $token);
}

function ordersDeleteEndpoint(string $token, int $id): array
{
    return apiRequest('DELETE', '/orders/' . $id, null, $token);
}
