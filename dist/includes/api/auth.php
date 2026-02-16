<?php

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

      if (function_exists('decodeJwtPayload')) {
        $payload = decodeJwtPayload($token);
        if (is_array($payload)) {
          $_SESSION['jwt_payload'] = $payload;
          $_SESSION['role'] = strtolower((string)($payload['role'] ?? 'user'));
          $_SESSION['name'] = $payload['name'] ?? 'Usuário';
          $_SESSION['email'] = $payload['email'] ?? $email;
        }
      }

      // Redireciona pro dashboard
      header("Location: " . rtrim(BASE_URL, "/") . "/index.php?url=dashboard");
      exit;
    }
  }
}
?>