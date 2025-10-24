-- Script de configuração rápida do banco DressCode
-- Execute este arquivo no MySQL/phpMyAdmin

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS dresscode CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dresscode;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    nome VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Inserir usuário de teste (email: teste@dresscode.com, senha: 123456)
INSERT IGNORE INTO usuarios (email, senha, nome) VALUES 
('teste@dresscode.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Usuário Teste');

-- Verificar se foi criado
SELECT 'Banco configurado com sucesso!' as status;
SELECT * FROM usuarios;