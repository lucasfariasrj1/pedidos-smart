<?php

require_once __DIR__ . '/client.php';

function endpointAuthFornecedoresDelete(string $token, int $id): array
{
    return apiRequest('DELETE', '/auth/fornecedores/' . $id, null, $token);
}
