-- ==========================================================
-- SCRIPT UNIFICADO DE BANCO DE DADOS - DRESSCODE
-- VERSÃO OTIMIZADA PARA PRODUÇÃO
-- ==========================================================

SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;
SET NAMES utf8mb4;
SET character_set_client = utf8mb4;
SET character_set_connection = utf8mb4;
SET character_set_results = utf8mb4;
START TRANSACTION;

-- ==========================================================
-- CRIAÇÃO DO BANCO
-- ==========================================================
CREATE DATABASE IF NOT EXISTS dresscode CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dresscode;

-- ==========================================================
-- TABELAS PRINCIPAIS
-- ==========================================================

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    nome VARCHAR(100),
    telefone VARCHAR(20),
    data_nascimento DATE,
    genero ENUM('M', 'F', 'Outro'),
    foto_perfil VARCHAR(255),
    tipo ENUM('cliente', 'vendedor', 'admin') DEFAULT 'cliente',
    email_verificado BOOLEAN DEFAULT FALSE,
    ultimo_login DATETIME NULL,
    receber_notificacoes TINYINT(1) DEFAULT 1,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    slug VARCHAR(100) NOT NULL,  -- Aumentado para 100
    descricao TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

CREATE TABLE IF NOT EXISTS brechos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    endereco TEXT,
    cidade VARCHAR(100),
    estado VARCHAR(50),
    cep VARCHAR(10),
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    telefone VARCHAR(20),
    email VARCHAR(255),
    usuario_id INT,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_brecho_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    tamanho ENUM('PP', 'P', 'M', 'G', 'GG', 'XG', 'Único'),
    cor VARCHAR(50),
    marca VARCHAR(50),
    condicao ENUM('Novo', 'Seminovo', 'Usado') DEFAULT 'Seminovo',
    categoria_id INT,
    brecho_id INT,
    vendedor_id INT,
    imagem VARCHAR(255),
    status_pagamento ENUM('pendente', 'pago') DEFAULT 'pendente',
    id_pagamento INT NULL,
    status ENUM('Ativo', 'Vendido', 'Inativo') DEFAULT 'Ativo',
    visualizacoes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_produto_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    CONSTRAINT fk_produto_brecho FOREIGN KEY (brecho_id) REFERENCES brechos(id) ON DELETE CASCADE,
    CONSTRAINT fk_produto_vendedor FOREIGN KEY (vendedor_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- ==========================================================
-- TABELAS RELACIONADAS
-- ==========================================================

CREATE TABLE IF NOT EXISTS produto_imagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT,
    caminho VARCHAR(255) NOT NULL,
    principal BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_img_produto FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    produto_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_fav_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_fav_produto FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorito (usuario_id, produto_id)
);

CREATE TABLE IF NOT EXISTS avaliacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT,
    usuario_id INT,
    nota INT,  -- CHECK removido para compatibilidade MySQL
    comentario TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_av_produto FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    CONSTRAINT fk_av_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

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
    CONSTRAINT fk_pg_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_pg_produto FOREIGN KEY (id_produto) REFERENCES produtos(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS vendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    vendedor_id INT NOT NULL,
    comprador_id INT NULL,
    valor_venda DECIMAL(10,2) NOT NULL,
    comissao DECIMAL(10,2) DEFAULT 0.00,
    lucro_vendedor DECIMAL(10,2) NOT NULL,
    status ENUM('Pendente', 'Confirmada', 'Cancelada') DEFAULT 'Pendente',
    data_venda DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_venda_produto FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    CONSTRAINT fk_venda_vendedor FOREIGN KEY (vendedor_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_venda_comprador FOREIGN KEY (comprador_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS notificacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    titulo VARCHAR(255) NOT NULL,
    mensagem TEXT NOT NULL,
    tipo ENUM('novo_produto', 'promocao', 'venda', 'pagamento', 'sistema') NOT NULL,
    produto_id INT NULL,
    data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    lida TINYINT(1) DEFAULT 0,
    CONSTRAINT fk_notif_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_notif_produto FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NULL,
    reason VARCHAR(100) NOT NULL,
    description TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_report_produto FOREIGN KEY (product_id) REFERENCES produtos(id) ON DELETE CASCADE,
    CONSTRAINT fk_report_usuario FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- ==========================================================
-- SEGURANÇA / LOGS / CONFIGURAÇÕES
-- ==========================================================

CREATE TABLE IF NOT EXISTS tentativas_login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    sucesso BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS logs_seguranca (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    acao VARCHAR(100) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    dados_acessados TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_log_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS consentimentos_lgpd (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo_consentimento ENUM('marketing', 'analytics', 'cookies') NOT NULL,
    consentimento_dado BOOLEAN NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_lgpd_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_consentimento (usuario_id, tipo_consentimento)
);

CREATE TABLE IF NOT EXISTS configuracoes_pagamento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(100) NOT NULL UNIQUE,
    valor TEXT NOT NULL,
    descricao VARCHAR(255) NULL,
    ativo BOOLEAN DEFAULT TRUE,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS configuracoes_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tema ENUM('claro', 'escuro', 'auto') DEFAULT 'claro',
    notificacoes_email BOOLEAN DEFAULT TRUE,
    notificacoes_push BOOLEAN DEFAULT TRUE,
    idioma VARCHAR(5) DEFAULT 'pt-BR',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_config_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_config (usuario_id)
);

-- ==========================================================
-- TABELAS DE SUPORTE ADICIONAIS
-- ==========================================================

CREATE TABLE IF NOT EXISTS password_resets (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_password_resets_email (email),
    INDEX idx_password_resets_token (token)
);

CREATE TABLE IF NOT EXISTS aceite_termos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    termos_aceitos BOOLEAN DEFAULT FALSE,
    data_aceite DATETIME DEFAULT CURRENT_TIMESTAMP,
    assinatura TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    CONSTRAINT fk_aceite_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_aceite (id_usuario)
);

CREATE TABLE IF NOT EXISTS migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INT NOT NULL,
    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================================
-- ÍNDICES PARA PERFORMANCE
-- ==========================================================

-- Índices principais
CREATE INDEX idx_produtos_categoria ON produtos(categoria_id);
CREATE INDEX idx_produtos_brecho ON produtos(brecho_id);
CREATE INDEX idx_produtos_status ON produtos(status);
CREATE INDEX idx_usuarios_email ON usuarios(email);
CREATE INDEX idx_brechos_cidade ON brechos(cidade);
CREATE INDEX idx_brechos_estado ON brechos(estado);
CREATE INDEX idx_tentativas_email ON tentativas_login(email);

-- Índices adicionais para performance
CREATE INDEX idx_produtos_vendedor ON produtos(vendedor_id);
CREATE INDEX idx_produtos_status_pagamento ON produtos(status_pagamento);
CREATE INDEX idx_pagamentos_usuario ON pagamentos(id_usuario);
CREATE INDEX idx_pagamentos_status ON pagamentos(status_pagamento);
CREATE INDEX idx_vendas_produto ON vendas(produto_id);
CREATE INDEX idx_vendas_vendedor ON vendas(vendedor_id);
CREATE INDEX idx_vendas_status ON vendas(status);
CREATE INDEX idx_notificacoes_usuario ON notificacoes(id_usuario);
CREATE INDEX idx_notificacoes_lida ON notificacoes(lida);
CREATE INDEX idx_reports_product ON reports(product_id);
CREATE INDEX idx_avaliacoes_produto ON avaliacoes(produto_id);
CREATE INDEX idx_favoritos_usuario ON favoritos(usuario_id);

-- ==========================================================
-- DADOS INICIAIS
-- ==========================================================

INSERT IGNORE INTO categorias (id, nome, slug, descricao) VALUES
(1, 'Feminino', 'feminino', 'Roupas femininas'),
(2, 'Masculino', 'masculino', 'Roupas masculinas'),
(3, 'Infantil', 'infantil', 'Roupas infantis'),
(4, 'Acessórios', 'acessorios', 'Bolsas, sapatos, joias, etc.'),
(5, 'Outros', 'outros', 'Outros itens de moda');

INSERT IGNORE INTO usuarios (id, email, senha, nome, tipo, email_verificado) VALUES 
(1, 'teste@dresscode.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Usuário Teste', 'vendedor', TRUE),
(2, 'admin@dresscode.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'admin', TRUE);

INSERT IGNORE INTO configuracoes_pagamento (chave, valor, descricao) VALUES
('taxa_anuncio', '9.90', 'Taxa para publicação de anúncio'),
('mercadopago_access_token', '', 'Token de acesso do Mercado Pago'),
('mercadopago_public_key', '', 'Chave pública do Mercado Pago'),
('mercadopago_sandbox', 'true', 'Usar ambiente de teste (true/false)'),
('pagamento_ativo', 'true', 'Sistema de pagamento ativo (true/false)');

INSERT IGNORE INTO brechos (id, nome, descricao, endereco, cidade, estado, cep, latitude, longitude, usuario_id) VALUES
(1, 'Brechó Vintage SP', 'Peças vintage e retrô', 'Rua Augusta, 1234', 'São Paulo', 'SP', '01305-100', -23.5505, -46.6333, 1),
(2, 'Moda Consciente RJ', 'Roupas sustentáveis', 'Rua das Flores, 567', 'Rio de Janeiro', 'RJ', '22071-900', -22.9068, -43.1729, 1);

INSERT IGNORE INTO produtos (id, nome, descricao, preco, tamanho, cor, marca, condicao, categoria_id, vendedor_id, status_pagamento) VALUES
(1, 'Blusa Vintage Floral', 'Peça única com estilo vintage autêntico', 45.90, 'M', 'Floral', 'Vintage Co.', 'Seminovo', 1, 1, 'pago'),
(2, 'Vestido Boho Chic', 'Design moderno e elegante', 89.50, 'P', 'Bege', 'Boho Style', 'Usado', 1, 1, 'pago'),
(3, 'Calça Jeans Destroyed', 'Estilo casual e despojado', 65.00, 'G', 'Azul', 'Denim Brand', 'Seminovo', 1, 1, 'pago'),
(4, 'Camiseta Básica Algodão', 'Confortável e versátil', 29.90, 'M', 'Branco', 'Marca Básica', 'Novo', 2, 1, 'pago');

-- ==========================================================
-- MIGRAÇÃO INICIAL
-- ==========================================================

INSERT IGNORE INTO migrations (migration, batch) VALUES
('001_create_initial_tables', 1);

-- ==========================================================
-- FINALIZAÇÃO
-- ==========================================================

COMMIT;
SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;

SELECT '✅ Banco DressCode configurado com sucesso! Total de tabelas criadas: ' AS status;
SHOW TABLES;