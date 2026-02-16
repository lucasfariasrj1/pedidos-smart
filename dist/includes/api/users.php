<?php

require_once __DIR__ . '/endpoint_auth_users_me.php';
require_once __DIR__ . '/endpoint_auth_users_listall.php';
require_once __DIR__ . '/endpoint_auth_users_post.php';

function usersListAllEndpoint(string $token): array
{
    return endpointAuthUsersListAll($token);
}

function usersCreateEndpoint(string $token, string $email, string $senha, string $role, int $lojaId): array
{
    return endpointAuthUsersPost($token, $email, $senha, $role, $lojaId);
}

function usersMeEndpoint(string $token): array
{
    return endpointAuthUsersMe($token);
}
