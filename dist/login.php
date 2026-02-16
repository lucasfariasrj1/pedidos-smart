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
    
    <link rel="stylesheet" href="<?= BASE_URL ?>css/adminlte.css" />
  </head>

  <body class="login-page bg-body-secondary">
    <div class="login-box">
      <div class="login-logo mb-4">
        <a href="<?= BASE_URL ?>" class="text-decoration-none text-dark">
            <i class="bi bi-box-seam-fill text-primary"></i> <b>Pedidos</b> SmartHard
        </a>
      </div>
      
      <div class="card card-outline card-primary shadow-lg">
        <div class="card-body login-card-body rounded">
          <p class="login-box-msg fw-bold">Acesse sua conta para gerenciar pedidos</p>
          
          <form action="<?= BASE_URL ?>auth/login_process.php" method="post">
            
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
                  <button type="submit" class="btn btn-primary fw-bold">Entrar <i class="bi bi-box-arrow-in-right ms-1"></i></button>
                </div>
              </div>
            </div>
          </form>

          <div class="mt-4 text-center">
             <p class="mb-0 small text-secondary">Acesso restrito a colaboradores.</p>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="<?= BASE_URL ?>js/adminlte.js"></script>
  </body>
</html>