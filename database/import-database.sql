-- Execute este SQL no phpMyAdmin (http://localhost/phpmyadmin)
-- Ou copie e cole no HeidiSQL

CREATE DATABASE IF NOT EXISTS dresscode CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dresscode;

-- Tabela de categorias
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL,
    descricao TEXT,
    ativo BOOLEAN DEFAULT TRUE
);

-- Inserir categorias
INSERT IGNORE INTO categorias (nome, slug, descricao) VALUES 
('Feminino', 'feminino', 'Roupas femininas'),
('Masculino', 'masculino', 'Roupas masculinas'),
('Infantil', 'infantil', 'Roupas infantis'),
('Acessórios', 'acessorios', 'Bolsas, sapatos, joias, etc.');

-- Tabela de produtos
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
    imagem VARCHAR(255),
    status ENUM('Ativo', 'Vendido', 'Inativo') DEFAULT 'Ativo',
    visualizacoes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- Inserir produtos de exemplo
INSERT IGNORE INTO produtos (nome, descricao, preco, tamanho, cor, marca, condicao, categoria_id, imagem) VALUES 
('Blusa Floral Vintage', 'Linda blusa com estampa floral, perfeita para o verão', 45.90, 'M', 'Floral', 'Vintage', 'Seminovo', 1, 'blusa-floral.jpg'),
('Calça Jeans Skinny', 'Calça jeans azul escuro, modelo skinny', 89.90, 'G', 'Azul', 'Levis', 'Usado', 1, 'calca-jeans.jpg'),
('Vestido Midi Bege', 'Vestido midi em tom bege, muito elegante', 120.00, 'P', 'Bege', 'Zara', 'Seminovo', 1, 'vestido-midi.jpg'),
('Camisa Social Branca', 'Camisa social masculina branca', 35.00, 'M', 'Branco', 'Renner', 'Seminovo', 2, 'camisa-social.jpg'),
('Tênis Esportivo', 'Tênis esportivo preto, muito confortável', 75.50, 'G', 'Preto', 'Nike', 'Usado', 4, 'tenis-esportivo.jpg'),
('Saia Plissada Rosa', 'Saia plissada em tom rosa claro', 55.00, 'M', 'Rosa', 'C&A', 'Novo', 1, 'saia-plissada.jpg'),
('Jaqueta Jeans', 'Jaqueta jeans clássica, muito versátil', 95.00, 'M', 'Azul', 'Wrangler', 'Seminovo', 1, 'jaqueta-jeans.jpg'),
('Bolsa de Couro', 'Bolsa de couro marrom, muito elegante', 80.00, 'Único', 'Marrom', 'Arezzo', 'Usado', 4, 'bolsa-couro.jpg');

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    nome VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserir usuário de teste (senha: 123456)
INSERT IGNORE INTO usuarios (email, senha, nome) VALUES 
('teste@dresscode.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Usuário Teste');