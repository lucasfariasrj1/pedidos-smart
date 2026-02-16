<?php
// config.php
define('BASE_URL', 'https://pedidos.assistenciasmarthard.com.br/');
define('API_BASE_URL', 'http://localhost:3000');

if (!defined('BASE_URL')) {
    // Detecta se é HTTP ou HTTPS
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    
    // Define o caminho do projeto (ajuste se estiver em subpasta, ex: /meu-sistema)
    // Se estiver na raiz, deixe apenas '/'
    $project_path = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
    
    define('BASE_URL', $protocol . "://" . $host . $project_path);
}
?>