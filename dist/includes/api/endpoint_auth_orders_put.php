<?php

require_once __DIR__ . '/client.php';

function endpointAuthOrdersPut(string $token, int $id, string $status): array
{
    return apiRequest('PUT', '/auth/orders/' . $id, [
        'status' => $status,
    ], $token);
}
