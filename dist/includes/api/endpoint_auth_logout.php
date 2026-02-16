<?php

require_once __DIR__ . '/client.php';

function endpointAuthLogout(string $token): array
{
    return apiRequest('POST', '/auth/logout', null, $token);
}
