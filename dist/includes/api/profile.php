<?php

require_once __DIR__ . '/client.php';

function profileGetEndpoint(string $token): array
{
    return apiRequest('GET', '/profile', null, $token);
}

function profileUpdateEndpoint(string $token, string $nome, string $email): array
{
    return apiRequest('PUT', '/profile', [
        'nome' => $nome,
        'email' => $email,
    ], $token);
}
