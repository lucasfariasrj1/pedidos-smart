<?php

require_once __DIR__ . '/client.php';

function usersListAllEndpoint(string $token): array
{
    return apiRequest('GET', '/users/listall', null, $token);
}

function usersMeEndpoint(string $token): array
{
    return apiRequest('GET', '/users/me', null, $token);
}
