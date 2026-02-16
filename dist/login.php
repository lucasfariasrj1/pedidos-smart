<?php include_once __DIR__ . '/config.php'; ?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login | Pedidos SmartHard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous" />
    
    <link rel="stylesheet" href="<?= BASE_URL ?>dist/css/adminlte.css" />
  </head>

  <body class="login-page bg-body-secondary">
    <div class="login-box">
      <div class="login-logo mb-4">
        <a href="index.php?page=dashboard" class="text-decoration-none text-dark">
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
                  <button type="submit" id="btn-entrar" class="btn btn-primary fw-bold">
                      <span id="btn-text">Entrar</span>
                  </button>
                </div>
              </div>
            </div>
          </form>

          <div id="login-message" class="mt-3 text-center small"></div>

          <div class="mt-4 text-center border-top pt-3">
              <p class="mb-0 small text-secondary">Acesso restrito a colaboradores.</p>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?= BASE_URL ?>dist/js/adminlte.js"></script>

    <script>
      // Rota da API ajustada para o formato sem URL amigável
      const apiLoginUrl = '<?= BASE_URL ?>api/index.php?url=login';

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
            throw new Error(data.error || 'Falha na autenticação. Verifique seus dados.');
          }

          // Salva o Token e Payload para o Frontend
          const jwt = data.token;
          const jwtPayload = decodeJwt(jwt);
          localStorage.setItem('jwt_token', jwt);
          localStorage.setItem('user_payload', JSON.stringify(jwtPayload || {}));

          // Define o Cookie para o PHP (auth_check.php) reconhecer a sessão
          document.cookie = `jwt_token=${jwt}; path=/; max-age=28800; SameSite=Lax`;

          message.className = 'mt-3 text-center small text-success';
          message.textContent = 'Login realizado com sucesso! Redirecionando...';

          // Redireciona para o roteador central do painel
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