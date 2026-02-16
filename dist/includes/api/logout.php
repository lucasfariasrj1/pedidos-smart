<?php

require_once __DIR__ . '/endpoint_auth_logout.php';

function logoutEndpoint(string $token): array
{
    return endpointAuthLogout($token);
}
