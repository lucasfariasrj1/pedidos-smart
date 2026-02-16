<?php

require_once __DIR__ . '/endpoint_auth_fornecedores_get.php';
require_once __DIR__ . '/endpoint_auth_fornecedores_post.php';
require_once __DIR__ . '/endpoint_auth_fornecedores_put.php';
require_once __DIR__ . '/endpoint_auth_fornecedores_delete.php';

function fornecedoresListEndpoint(string $token): array
{
    return endpointAuthFornecedoresGet($token);
}

function fornecedoresCreateEndpoint(string $token, string $nome, string $whatsapp, string $email): array
{
    return endpointAuthFornecedoresPost($token, $nome, $whatsapp, $email);
}

function fornecedoresUpdateEndpoint(string $token, int $id, string $nome, string $whatsapp, string $email): array
{
    return endpointAuthFornecedoresPut($token, $id, $nome, $whatsapp, $email);
}

function fornecedoresDeleteEndpoint(string $token, int $id): array
{
    return endpointAuthFornecedoresDelete($token, $id);
}
