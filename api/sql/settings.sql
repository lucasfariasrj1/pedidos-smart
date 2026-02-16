CREATE TABLE IF NOT EXISTS settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(120) NOT NULL UNIQUE,
    valor TEXT NULL,
    categoria VARCHAR(80) NOT NULL DEFAULT 'geral',
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings (chave, valor, categoria)
VALUES
    ('nome_sistema', 'Smart Hard', 'geral'),
    ('modo_manutencao', '0', 'sistema'),
    ('logo_url', '/dist/assets/img/AdminLTELogo.png', 'aparencia'),
    ('limite_pedidos_loja', '500', 'negocio')
ON DUPLICATE KEY UPDATE
    valor = VALUES(valor),
    categoria = VALUES(categoria),
    updated_at = CURRENT_TIMESTAMP;
