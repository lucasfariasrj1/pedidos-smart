<?php

require_once __DIR__ . '/client.php';

function endpointAuthOrdersDelete(string $token, int $id): array
{
    return apiRequest('DELETE', '/auth/orders/' . $id, null, $token);
}
