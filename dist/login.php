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
              <input type="password" name="password" class="form-control" placeholder="Senha" required />
              <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
            </div>

            <div class="row align-items-center">
              <div class="col-7">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" />
                  <label class="form-check-label" for="rememberMe"> Lembrar-me </label>
                </div>
              </div>
              <div class="col-5">
                <div class="d-grid gap-2">
                  <button type="submit" id="btn-entrar" class="btn btn-primary fw-bold">Entrar <i class="bi bi-box-arrow-in-right ms-1"></i></button>
                </div>
              </div>
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
      // Endpoint ajustado para o formato sem URL amigável
      const apiLoginUrl = '/api/index.php?url=login';

      document.getElementById('login-form').addEventListener('submit', async (event) => {
        event.preventDefault();
        
        const message = document.getElementById('login-message');
        const btn = document.getElementById('btn-entrar');
        
        message.className = 'mt-3 text-center small text-secondary';
        message.textContent = 'Autenticando...';
        btn.disabled = true;

        const payload = Object.fromEntries(new FormData(event.currentTarget).entries());

        try {
          const response = await fetch(apiLoginUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
          });

          const data = await response.json();

          if (!response.ok || !data.token) {
            throw new Error(data.error || 'Falha na autenticação.');
          }

          // Armazenar JWT para sessões futuras
          localStorage.setItem('jwt_token', data.token);
          // Definir cookie para validação PHP no auth_check.php
          document.cookie = `jwt_token=${data.token}; path=/; max-age=28800; SameSite=Lax`;

          message.className = 'mt-3 text-center small text-success';
          message.textContent = 'Login realizado com sucesso! Redirecionando...';

          // Redirecionamento para o roteador central
          setTimeout(() => {
            window.location.href = 'index.php?page=dashboard';
          }, 800);

        } catch (error) {
          btn.disabled = false;
          message.className = 'mt-3 text-center small text-danger';
          message.textContent = error.message;
        }
      });
    </script>
  </body>
</html>