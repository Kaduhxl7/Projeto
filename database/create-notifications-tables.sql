-- Tabela de notificações
CREATE TABLE IF NOT EXISTS notificacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    titulo VARCHAR(255) NOT NULL,
    mensagem TEXT NOT NULL,
    tipo ENUM('novo_produto', 'promocao') NOT NULL,
    produto_id INT NULL,
    data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    lida TINYINT(1) DEFAULT 0,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE SET NULL
);

-- Adicionar campo para controle de notificações por email na tabela usuarios
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS receber_notificacoes TINYINT(1) DEFAULT 1;