<?php

require_once __DIR__ . '/client.php';

function endpointAuthFornecedoresGet(string $token): array
{
    return apiRequest('GET', '/auth/fornecedores', null, $token);
}
