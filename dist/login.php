<?php
include_once __DIR__ . '/config.php';

$loginError = null;

function callLoginApi(string $email, string $senha): array {
  $payload = json_encode([
    "email" => $email,
    "senha" => $senha
  ], JSON_UNESCAPED_UNICODE);

  $curl = curl_init();

  curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.assistenciasmarthard.com.br/auth/login",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => [
      "Content-Type: application/json",
      "Accept: application/json"
    ],
  ]);

  $response = curl_exec($curl);
  $err = curl_error($curl);
  $statusCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);

  curl_close($curl);

  if ($err) {
    return [
      "ok" => false,
      "status" => 500,
      "data" => [
        "message" => "Erro ao conectar na API",
        "details" => $err
      ]
    ];
  }

  $json = json_decode($response, true);

  // Se a API não retornar JSON, devolve como texto
  if (!is_array($json)) {
    return [
      "ok" => ($statusCode >= 200 && $statusCode < 300),
      "status" => $statusCode,
      "data" => [
        "message" => "Resposta inválida (não é JSON).",
        "raw" => $response
      ]
    ];
  }

  return [
    "ok" => ($statusCode >= 200 && $statusCode < 300),
    "status" => $statusCode,
    "data" => $json
  ];
}

// Processa POST do formulário (sem JS)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST["email"] ?? "");
  $senha = (string)($_POST["senha"] ?? "");

  if ($email === "" || $senha === "") {
    $loginError = "E-mail e senha são obrigatórios.";
  } else {
    $result = callLoginApi($email, $senha);

    // Ajuste aqui se sua API retornar token em outro lugar:
    $token = $result["data"]["token"] ?? ($result["data"]["data"]["token"] ?? null);

    if (!$result["ok"] || !$token) {
      $loginError = $result["data"]["message"] ?? $result["data"]["error"] ?? "Falha na autenticação.";
    } else {
      // Cookie por 8 horas
      $secure = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off");
      setcookie("jwt_token", $token, [
        "expires" => time() + 28800,
        "path" => "/",
        "secure" => $secure,
        "httponly" => true,
        "samesite" => "Lax",
      ]);

      // Redireciona pro dashboard
      header("Location: " . rtrim(BASE_URL, "/") . "/dist/index.php?page=dashboard");
      exit;
    }
  }
}
?>
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

    <link rel="stylesheet" href="<?= rtrim(BASE_URL, '/') ?>/css/adminlte.css" />
  </head>

  <body class="login-page bg-body-secondary">
    <div class="login-box">
      <div class="login-logo mb-4">
        <a href="<?= rtrim(BASE_URL, '/') ?>/dist/index.php?page=dashboard" class="text-decoration-none text-dark">
          <i class="bi bi-box-seam-fill text-primary"></i> <b>Pedidos</b> SmartHard
        </a>
      </div>

      <div class="card card-outline card-primary shadow-lg">
        <div class="card-body login-card-body rounded">
          <p class="login-box-msg fw-bold">Acesse sua conta para gerenciar pedidos</p>

          <?php if (!empty($loginError)): ?>
            <div class="alert alert-danger py-2 small mb-3" role="alert">
              <?= htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="">
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

          <div class="mt-4 text-center">
            <p class="mb-0 small text-secondary">Acesso restrito a colaboradores.</p>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="<?= rtrim(BASE_URL, '/') ?>/dist/js/adminlte.js"></script>
  </body>
</html>
