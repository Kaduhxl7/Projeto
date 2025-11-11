-- ========================================
-- ADICIONAR TABELA DE ACEITE DE TERMOS
-- Execute este script se já tiver o banco criado
-- ========================================

USE dresscode;

-- Criar tabela de aceite de termos
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

-- Criar índice para performance
CREATE INDEX idx_aceite_usuario ON aceite_termos(id_usuario);
CREATE INDEX idx_aceite_data ON aceite_termos(data_aceite);

SELECT '✅ Tabela aceite_termos criada com sucesso!' AS status;
