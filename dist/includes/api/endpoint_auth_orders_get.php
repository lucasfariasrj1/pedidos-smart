<?php

require_once __DIR__ . '/client.php';

function endpointAuthOrdersGet(string $token): array
{
    return apiRequest('GET', '/auth/orders', null, $token);
}
