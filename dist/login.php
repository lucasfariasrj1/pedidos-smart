<?php include_once __DIR__ . '/config.php'; ?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login | Pedidos SmartHard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="<?= BASE_URL ?>css/adminlte.css" />
  </head>
  <body class="login-page bg-body-secondary">
    <div class="login-box">
      <div class="login-logo mb-4">
        <a href="<?= BASE_URL ?>" class="text-decoration-none text-dark"><b>Pedidos</b> SmartHard</a>
      </div>
      <div class="card card-outline card-primary shadow-lg">
        <div class="card-body login-card-body rounded">
          <p class="login-box-msg fw-bold">Acesse sua conta</p>
          <form id="login-form">
            <div class="input-group mb-3">
              <input type="email" name="email" class="form-control" placeholder="E-mail" required autofocus />
            </div>
            <div class="input-group mb-3">
              <input type="password" name="senha" class="form-control" placeholder="Senha" required />
            </div>
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary fw-bold">Entrar</button>
            </div>
          </form>
          <div id="login-message" class="mt-3 small"></div>
        </div>
      </div>
    </div>

    <script>
      const apiLoginUrl = '../api/login';

      function decodeJwt(token) {
        try {
          return JSON.parse(atob(token.split('.')[1].replace(/-/g, '+').replace(/_/g, '/')));
        } catch (error) {
          return null;
        }
      }

      document.getElementById('login-form').addEventListener('submit', async (event) => {
        event.preventDefault();
        const message = document.getElementById('login-message');
        message.textContent = 'Autenticando...';

        const payload = Object.fromEntries(new FormData(event.currentTarget).entries());

        try {
          const response = await fetch(apiLoginUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
          });

          const data = await response.json();
          if (!response.ok || !data.token) {
            throw new Error(data.error || 'Falha no login');
          }

          const jwt = data.token;
          const jwtPayload = decodeJwt(jwt);
          localStorage.setItem('jwt_token', jwt);
          localStorage.setItem('user_payload', JSON.stringify(jwtPayload || {}));
          document.cookie = `jwt_token=${jwt}; path=/; max-age=28800; SameSite=Lax`;

          message.className = 'mt-3 small text-success';
          message.textContent = 'Login realizado com sucesso. Redirecionando...';
          window.location.href = 'index';
        } catch (error) {
          message.className = 'mt-3 small text-danger';
          message.textContent = error.message;
        }
      });
    </script>
  </body>
</html>
