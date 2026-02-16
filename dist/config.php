<?php
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    define('BASE_URL', $protocol . '://' . $host . '/');
}

if (!defined('DB_HOST')) {
    define('DB_HOST', '151.243.236.234');
    define('DB_NAME', 'pedidos_db');
    define('DB_USER', 'pedidos_user');
    define('DB_PASS', 'A!suptry@123');
}

function getGlobalSetting(string $chave, ?string $default = null): ?string
{
    static $cache = [];

    if (array_key_exists($chave, $cache)) {
        return $cache[$chave];
    }

    try {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare('SELECT valor FROM settings WHERE chave = ? LIMIT 1');
        $stmt->execute([$chave]);
        $valor = $stmt->fetchColumn();
        $cache[$chave] = $valor !== false ? (string)$valor : $default;
    } catch (Exception $e) {
        $cache[$chave] = $default;
    }

    return $cache[$chave];
}

function isMaintenanceMode(): bool
{
    $valor = getGlobalSetting('modo_manutencao', '0');
    return $valor === '1' || strtolower((string)$valor) === 'true';
}
?>
