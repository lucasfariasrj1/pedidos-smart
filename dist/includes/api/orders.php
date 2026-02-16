<?php

require_once __DIR__ . '/endpoint_auth_orders_get.php';
require_once __DIR__ . '/endpoint_auth_orders_post.php';
require_once __DIR__ . '/endpoint_auth_orders_put.php';
require_once __DIR__ . '/endpoint_auth_orders_delete.php';

function ordersListEndpoint(string $token): array
{
    return endpointAuthOrdersGet($token);
}

function ordersCreateEndpoint(string $token, string $peca, int $fornecedorId, string $observacao): array
{
    return endpointAuthOrdersPost($token, $peca, $fornecedorId, $observacao);
}

function ordersUpdateEndpoint(string $token, int $id, string $status): array
{
    return endpointAuthOrdersPut($token, $id, $status);
}

function ordersDeleteEndpoint(string $token, int $id): array
{
    return endpointAuthOrdersDelete($token, $id);
}
