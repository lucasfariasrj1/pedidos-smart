<?php
include_once __DIR__ . '/auth_check.php';


$baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
$currentTitle = $pageTitle ?? 'Pedidos SmartHard';
?>
<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#0d6efd" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />
    <meta name="apple-mobile-web-app-title" content="Pedidos SmartHard" />
    <link rel="manifest" href="<?= $baseUrl; ?>/manifest.webmanifest" />
    <link rel="apple-touch-icon" href="<?= $baseUrl; ?>/assets/img/AdminLTELogo.png" />
    <title><?= htmlspecialchars($currentTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="<?= $baseUrl; ?>/css/adminlte.css" />
  </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
      <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
          <ul class="navbar-nav">
            <li class="nav-item d-none d-md-block">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Alternar menu lateral">
                <i class="bi bi-list"></i>
              </a>
            </li>
          </ul>
        </div>
      </nav>
