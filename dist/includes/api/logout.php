<?php

require_once __DIR__ . '/client.php';

function logoutEndpoint(string $token): array
{
    return apiRequest('POST', '/logout', [], $token);
}
