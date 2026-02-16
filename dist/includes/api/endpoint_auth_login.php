<?php

require_once __DIR__ . '/client.php';

function endpointAuthLogin(string $email, string $senha): array
{
    return apiRequest('POST', '/auth/login', [
        'email' => $email,
        'senha' => $senha,
    ]);
}
