-- Tabelas do Sistema de Pagamento - DressCode
-- Criado para gerenciar pagamentos de taxa de anúncio

USE dresscode;

-- Tabela de pagamentos
CREATE TABLE IF NOT EXISTS pagamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_produto INT NULL,
    valor DECIMAL(10,2) NOT NULL,
    metodo_pagamento ENUM('cartao', 'pix', 'boleto') NOT NULL,
    status_pagamento ENUM('pendente', 'pago', 'falhou', 'cancelado') DEFAULT 'pendente',
    codigo_transacao VARCHAR(255) NULL,
    gateway_id VARCHAR(255) NULL,
    gateway_response TEXT NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_pagamento DATETIME NULL,
    data_expiracao DATETIME NULL,
    INDEX idx_usuario (id_usuario),
    INDEX idx_produto (id_produto),
    INDEX idx_status (status_pagamento),
    INDEX idx_transacao (codigo_transacao),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produtos(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de configurações de pagamento
CREATE TABLE IF NOT EXISTS configuracoes_pagamento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(100) NOT NULL UNIQUE,
    valor TEXT NOT NULL,
    descricao VARCHAR(255) NULL,
    ativo BOOLEAN DEFAULT TRUE,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir configurações padrão
INSERT INTO configuracoes_pagamento (chave, valor, descricao) VALUES
('taxa_anuncio', '9.90', 'Taxa para publicação de anúncio'),
('mercadopago_access_token', '', 'Token de acesso do Mercado Pago'),
('mercadopago_public_key', '', 'Chave pública do Mercado Pago'),
('mercadopago_sandbox', 'true', 'Usar ambiente de teste (true/false)'),
('pagamento_ativo', 'true', 'Sistema de pagamento ativo (true/false)')
ON DUPLICATE KEY UPDATE valor = VALUES(valor);

-- Adicionar campo de status de pagamento na tabela produtos (se não existir)
ALTER TABLE produtos ADD COLUMN status_pagamento ENUM('pendente', 'pago') DEFAULT 'pendente';
ALTER TABLE produtos ADD COLUMN id_pagamento INT NULL;
ALTER TABLE produtos ADD INDEX idx_status_pagamento (status_pagamento);
ALTER TABLE produtos ADD CONSTRAINT fk_produto_pagamento FOREIGN KEY (id_pagamento) REFERENCES pagamentos(id) ON DELETE SET NULL;

-- Atualizar produtos existentes para status pago (compatibilidade)
UPDATE produtos SET status_pagamento = 'pago' WHERE status = 'Ativo';