<?php

require_once __DIR__ . '/client.php';

function endpointAuthOrdersPost(string $token, string $peca, int $fornecedorId, string $observacao): array
{
    return apiRequest('POST', '/auth/orders', [
        'peca' => $peca,
        'fornecedor_id' => $fornecedorId,
        'observacao' => $observacao,
    ], $token);
}
