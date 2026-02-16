<?php

require_once __DIR__ . '/endpoint_auth_users_me.php';
require_once __DIR__ . '/endpoint_auth_users_listall.php';

function usersListAllEndpoint(string $token): array
{
    return endpointAuthUsersListAll($token);
}

function usersMeEndpoint(string $token): array
{
    return endpointAuthUsersMe($token);
}
