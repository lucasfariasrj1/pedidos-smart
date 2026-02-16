<?php include_once __DIR__ . '/config.php'; ?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login | Pedidos SmartHard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/adminlte.css" />
  </head>

  <body class="login-page bg-body-secondary">
    <div class="login-box">
      <div class="login-logo mb-4">
        <a href="<?= BASE_URL ?>dist/index.php?page=dashboard" class="text-decoration-none text-dark">
            <i class="bi bi-box-seam-fill text-primary"></i> <b>Pedidos</b> SmartHard
        </a>
      </div>
      
      <div class="card card-outline card-primary shadow-lg">
        <div class="card-body login-card-body rounded">
          <p class="login-box-msg fw-bold">Acesse sua conta para gerenciar pedidos</p>
          
        <form id="login-form">
          <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="E-mail" required autofocus />
            <div class="input-group-text"><span class="bi bi-envelope"></span></div>
          </div>
          
          <div class="input-group mb-3">
            <input type="password" name="senha" class="form-control" placeholder="Senha" required />
            <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
          </div>

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary fw-bold">Entrar</button>
          </div>
        </form>
        <div id="login-message" class="mt-3 text-center small"></div>
          <div class="mt-4 text-center">
             <p class="mb-0 small text-secondary">Acesso restrito a colaboradores.</p>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="<?= BASE_URL ?>dist/js/adminlte.js"></script>
    <script>
      // Faz login usando o proxy local /api/auth/login.php
      document.getElementById('login-form').addEventListener('submit', async (event) => {
        event.preventDefault();
        const message = document.getElementById('login-message');
        message.className = 'mt-3 text-center small text-secondary';
        message.textContent = 'Autenticando...';

        const payload = Object.fromEntries(new FormData(event.currentTarget).entries());

        try {
          const res = await fetch('/api/auth/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
            credentials: 'same-origin'
          });

          const data = await res.json();
          if (!res.ok || !data.token) {
            message.className = 'mt-3 text-center small text-danger';
            message.textContent = data.error || data.message || 'Falha na autenticação.';
            return;
          }

          // Armazena cookie para que o PHP do painel aceite a sessão
          // Tempo de vida: 8 horas
          document.cookie = `jwt_token=${data.token}; path=/; max-age=28800; SameSite=Lax`;
          // Guarde também em localStorage para chamadas AJAX do front-end
          localStorage.setItem('jwt_token', data.token);

          message.className = 'mt-3 text-center small text-success';
          message.textContent = 'Login realizado! Redirecionando...';
          setTimeout(() => window.location.href = 'index.php?page=dashboard', 700);

        } catch (err) {
          message.className = 'mt-3 text-center small text-danger';
          message.textContent = 'Erro ao conectar com o servidor.';
        }
      });
    </script>
  </body>
</html>