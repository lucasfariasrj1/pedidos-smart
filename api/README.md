# API proxy / Test & integração

Esta pasta contém proxies PHP que encaminham requisições do painel para a API externa `https://api.assistenciasmarthard.com.br/`.

Arquivos principais

- `client.php` — helper que encaminha requisições (cURL) e repassa headers, body e status.
- `auth/*` — endpoints: `login.php`, `register.php`, `logout.php`, `users/me.php`, `users/listall.php`.
- `orders/*` — `index.php` (GET/POST), `item.php?id=...` (PUT/DELETE).
- `fornecedores/*` — `index.php`, `item.php?id=...`.

Como usar

1. Coloque o projeto em um servidor que rode PHP (XAMPP já atende). Supondo que o projeto esteja disponível em `http://localhost/orcafacil/`:

  - Login: `POST http://localhost/orcafacil/api/auth/login.php` (body JSON `{ "email": "...", "senha": "..." }`)
  - Listar pedidos: `GET http://localhost/orcafacil/api/orders/index.php` (inclua header `Authorization: Bearer <token>` quando necessário)
  - Listar fornecedores: `GET http://localhost/orcafacil/api/fornecedores/index.php`

2. Para operações sobre um ID use `?id=123`, por exemplo:

  - Atualizar pedido: `PUT http://localhost/orcafacil/api/orders/item.php?id=123`
  - Deletar fornecedor: `DELETE http://localhost/orcafacil/api/fornecedores/item.php?id=45`

Teste rápido com `curl`

  - Login:

```bash
curl -X POST "http://localhost/orcafacil/api/auth/login.php" \
  -H "Content-Type: application/json" \
  -d '{"email":"seu@email.com","senha":"sua_senha"}'
```

  - Listar pedidos (após obter token):

```bash
curl -X GET "http://localhost/orcafacil/api/orders/index.php" \
  -H "Authorization: Bearer <token>"
```

Página de demonstração

- `panel_demo.html` — demo simples com formulário de login e botões para listar usuários/pedidos/fornecedores.

Scripts de teste

- `api/tests/test_flow.php` — script CLI que realiza login e algumas chamadas de verificação (edite as variáveis no início com o `BASE_URL` e credenciais).

Observações

- O proxy repassa o header `Authorization` recebido. A lógica de armazenamento do token fica no cliente (navegador ou aplicação do painel).
- Se preferir URLs amigáveis sem `.php`, configure regras do servidor (Apache `mod_rewrite`) para mapear caminhos para estes arquivos.
