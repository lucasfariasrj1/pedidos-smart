<?php

require_once __DIR__ . '/client.php';

function endpointAuthUsersListAll(string $token): array
{
    return apiRequest('GET', '/auth/users/listall', null, $token);
}
