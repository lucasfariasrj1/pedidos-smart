<?php

require_once __DIR__ . '/endpoint_logatividades_get.php';
require_once __DIR__ . '/endpoint_logatividades_get_by_id.php';

function logatividadesListEndpoint(string $token): array
{
    return endpointLogatividadesGet($token);
}

function logatividadesDetailEndpoint(string $token, int $id): array
{
    return endpointLogatividadesGetById($token, $id);
}
