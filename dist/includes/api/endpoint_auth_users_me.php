<?php

require_once __DIR__ . '/client.php';

function endpointAuthUsersMe(string $token): array
{
    return apiRequest('GET', '/auth/users/me', null, $token);
}
