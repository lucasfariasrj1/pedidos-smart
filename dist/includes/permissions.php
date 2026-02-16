<?php
function getUserRole(): string
{
    return strtolower((string)($_SESSION['role'] ?? 'user'));
}

function isAdmin(): bool
{
    return getUserRole() === 'admin';
}

function hasRole(array $allowedRoles): bool
{
    return in_array(getUserRole(), array_map('strtolower', $allowedRoles), true);
}
?>
